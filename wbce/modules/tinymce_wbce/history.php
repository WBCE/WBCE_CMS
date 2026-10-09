<?php
/**
 * tinymce_wbce — history.php
 *
 * Version-history core: table schema + rotating-snapshot logic for the
 * wbce_history plugin. One row per editor instance; `versions` holds a rotating
 * JSON array (newest first, max N) of { ts, user_id, user_name, content }.
 *
 * The instance is keyed by sha1 of a client-supplied context string
 * (admin URL path + query + DOM id) — see plugins/wbce_history/plugin.js. The
 * key is derived server-side here, never trusted verbatim from the client, and
 * the author (user_id/user_name) always comes from the server session.
 *
 * @license GNU GPL2
 */

defined('WB_PATH') or die();

if (!function_exists('tinymce_wbce_history_ensure_table')) {

    /** Self-healing schema creation (idempotent, runs once per request). */
    function tinymce_wbce_history_ensure_table(): void
    {
        global $database;
        static $done = false;
        if ($done) { return; }
        $done = true;
        $database->importSql(__DIR__ . '/install_struct.sql');
    }

    /** Stable per-instance key from the client context string. */
    function tinymce_wbce_history_key(string $context): string
    {
        return sha1($context);
    }

    /**
     * History settings for a given preset (they live per toolbar preset in
     * tinymce_cfg now). Falls back to the default preset when $presetId is null
     * or unknown, and to safe defaults if the config can't be read.
     *   enabled   : '1'|'0' — record new versions for editors using this preset
     *   max       : rotation cap, versions kept per field (1..100)
     *   keep      : min recent versions the automatic cleanup retains (<= max)
     *   autoclean : '1'|'0' — run the age-based cleanup (drop stale versions)
     *   ttl_days  : age threshold in days; versions older than NOW-ttl beyond the
     *               `keep` floor are pruned opportunistically (no cron needed)
     */
    function tinymce_wbce_history_settings(?string $presetId = null): array
    {
        static $cache = [];
        $ck = $presetId ?? '';
        if (isset($cache[$ck])) { return $cache[$ck]; }

        $defaults = ['enabled' => '1', 'max' => 10, 'keep' => 10, 'autoclean' => '0', 'ttl_days' => 90];

        if (!function_exists('tinymce_wbce_load_cfg')) {
            $p = __DIR__ . '/presets.php';
            if (is_file($p)) { require_once $p; }
        }
        if (!function_exists('tinymce_wbce_load_cfg')) { return $cache[$ck] = $defaults; }

        $cfg    = tinymce_wbce_load_cfg();
        $id     = ($presetId !== null && isset($cfg['presets'][$presetId]))
            ? $presetId : ($cfg['default_preset'] ?? '');
        $preset = $cfg['presets'][$id] ?? null;
        if (!is_array($preset)) { return $cache[$ck] = $defaults; }

        $max = max(1, min(100, (int) ($preset['history_max'] ?? 10)));
        return $cache[$ck] = [
            'enabled'   => (($preset['history_enabled'] ?? '1') === '0') ? '0' : '1',
            'max'       => $max,
            'keep'      => min($max, max(1, min(100, (int) ($preset['history_keep'] ?? 10)))),
            'autoclean' => (($preset['history_autoclean'] ?? '0') === '1') ? '1' : '0',
            'ttl_days'  => max(1, min(3650, (int) ($preset['history_ttl_days'] ?? 90))),
        ];
    }

    /**
     * Age-based cleanup (the "sentinel", no cron): keep the newest `keep`
     * versions unconditionally, then drop everything beyond that whose timestamp
     * is older than NOW - ttl_days. Versions are newest-first with strictly
     * decreasing ts, so once one is too old all following ones are too. Pure
     * function — returns the (possibly shorter) array; no-op when autoclean is
     * off or ttl is unset.
     */
    function tinymce_wbce_history_prune(array $versions, array $settings): array
    {
        if (($settings['autoclean'] ?? '0') !== '1') { return $versions; }
        $ttlDays = (int) ($settings['ttl_days'] ?? 0);
        if ($ttlDays <= 0) { return $versions; }
        $keep = max(1, (int) ($settings['keep'] ?? 1));
        if (count($versions) <= $keep) { return $versions; }

        $cutoff = time() - $ttlDays * 86400;
        $out    = [];
        foreach ($versions as $i => $v) {
            if ($i < $keep) { $out[] = $v; continue; }          // always keep newest N
            $ts = isset($v['ts']) ? strtotime((string) $v['ts']) : false;
            if ($ts === false || $ts >= $cutoff) { $out[] = $v; } // recent enough → keep
            // older than the cutoff → dropped
        }
        return $out;
    }

    /**
     * Persist a versions array for an instance. Writing an empty array removes
     * the row entirely (nothing left to keep). Used by the cleanup and by the
     * per-item delete action.
     */
    function tinymce_wbce_history_store(string $key, array $versions): bool
    {
        global $database;
        tinymce_wbce_history_ensure_table();
        if (!$versions) {
            $database->query("DELETE FROM `{TP}mod_tinymce_history` WHERE `instance_key` = ?", [$key]);
            return !$database->hasError();
        }
        $database->upsertRow('{TP}mod_tinymce_history', 'instance_key', [
            'instance_key' => $key,
            'versions'     => json_encode($versions, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);
        return !$database->hasError();
    }

    /**
     * Delete one stored version by its position in the newest-first array. The
     * `$ts` guard rejects a stale index (the list the client saw no longer
     * matches) so a concurrent change can't delete the wrong entry.
     */
    function tinymce_wbce_history_delete(string $key, int $index, string $ts = ''): bool
    {
        if ($index < 0) { return false; }
        $versions = tinymce_wbce_history_load($key);
        if (!isset($versions[$index])) { return false; }
        if ($ts !== '' && (string) ($versions[$index]['ts'] ?? '') !== $ts) { return false; }
        array_splice($versions, $index, 1);
        return tinymce_wbce_history_store($key, $versions);
    }

    /** Load the rotating versions array for an instance ([] if none). */
    function tinymce_wbce_history_load(string $key): array
    {
        global $database;
        $json = $database->fetchValue(
            "SELECT `versions` FROM `{TP}mod_tinymce_history` WHERE `instance_key` = ?",
            [$key]
        );
        if (!$json) { return []; }
        $arr = json_decode($json, true);
        return is_array($arr) ? $arr : [];
    }

    /**
     * Prepend a snapshot and rotate to $max. Deduplicated: if the newest stored
     * version already has identical content, nothing is written (returns false).
     */
    function tinymce_wbce_history_snapshot(
        string $key,
        string $context,
        string $content,
        int $userId,
        string $userName,
        string $userLogin = '',
        ?string $presetId = null
    ): bool {
        global $database;

        $settings = tinymce_wbce_history_settings($presetId);
        if ($settings['enabled'] !== '1') { return false; } // history off for this preset
        $max = $settings['max'];

        tinymce_wbce_history_ensure_table();

        $versions = tinymce_wbce_history_load($key);
        if ($versions && isset($versions[0]['content'])
            && sha1((string)$versions[0]['content']) === sha1($content)) {
            return false; // unchanged since the last version
        }

        array_unshift($versions, [
            'ts'         => date('Y-m-d H:i:s'),
            'user_id'    => $userId,
            'user_name'  => $userName,
            'user_login' => $userLogin,
            'content'    => $content,
        ]);
        $versions = array_slice($versions, 0, max(1, $max));
        // Opportunistic age-based cleanup on write (no cron): the sentinel runs
        // whenever a field is snapshotted or its history is opened (see list).
        $versions = tinymce_wbce_history_prune($versions, $settings);

        $database->upsertRow('{TP}mod_tinymce_history', 'instance_key', [
            'instance_key' => $key,
            'context'      => mb_substr($context, 0, 255),
            'versions'     => json_encode($versions, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);
        return !$database->hasError();
    }

    /**
     * Server-side snapshot on a module's REAL save (the "Sonderweg"): keyed by a
     * stable, URL-independent context "key:<module>-section-<id>". The client
     * plugin uses the same canonical key (declared via
     * window.WBCE_TINYMCE_HISTORY_KEYS) — so client dialog and server saves share
     * ONE timeline, and the client suppresses its own autosave for that field.
     * $admin supplies the author from the session. No-op if id/content invalid.
     */
    function tinymce_wbce_history_snapshot_section(
        int $sectionId,
        string $content,
        $admin = null,
        string $module = 'wysiwyg',
        ?string $presetId = null
    ): bool {
        if ($sectionId <= 0) { return false; }
        $context   = 'key:' . $module . '-section-' . $sectionId;
        $key       = tinymce_wbce_history_key($context);
        $userId    = ($admin && method_exists($admin, 'get_user_id'))      ? (int)$admin->get_user_id() : 0;
        $userName  = ($admin && method_exists($admin, 'get_display_name')) ? (string)$admin->get_display_name() : '';
        $userLogin = ($admin && method_exists($admin, 'get_username'))     ? (string)$admin->get_username() : '';
        // wysiwyg uses the DEFAULT preset (show_wysiwyg_editor without a preset)
        // → $presetId null resolves to the default preset's history settings.
        return tinymce_wbce_history_snapshot($key, $context, $content, $userId, $userName, $userLogin, $presetId);
    }
}
