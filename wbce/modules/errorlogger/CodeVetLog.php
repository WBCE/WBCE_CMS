<?php

declare(strict_types=1);

/**
 * Errorlog viewer — CodeVet audit-log reader.
 *
 * Reads framework/CodeVet.php's JSON-lines log
 * (var/code_vet/codevet.log, plus the rotated codevet-<ts>.log archives on
 * request) and decorates each entry for twig/codevet.twig.
 *
 * Read-only: no output, no writes. Uses $GLOBALS['database'] (guarded) for one
 * batched droplet/filter name lookup. tool.php owns the archive/rotate action.
 *
 * Log line shape written by CodeVet::logEvent():
 *   {time, action, profile, status, findings:[{rule,message,line,file}], context}
 * logEvent() only fires when there ARE findings, so every line is a
 * block-or-warn event ("passed" never actually occurs).
 *
 * Decorated row (one per collapsed group):
 *   ts        string  ISO timestamp of the most recent occurrence
 *   unix      int
 *   exact     string  'Y-m-d H:i:s'
 *   profile   string  droplet|outputfilter|code2|addon  (badge modifier + CV_ACT_ key)
 *   severity  string  'block' | 'warn'   (warn = an *_warned action)
 *   count     int     consecutive identical occurrences folded together
 *   is_new    bool    newer than the viewer's last visit
 *   findings  list<array{rule:string,message:string,line:int,file:string}>
 *   where     list<array{label:string,value:string,file:bool}>
 *
 * @author  Christian M. Stefan · www.wbEasy.de
 * @license GNU GPL2
 */
final class CodeVetLog
{
    private const DIR  = '/var/code_vet';
    private const FILE = 'codevet.log';
    private const CAP  = 500;

    /** context key → short label shown in the "Where" column. */
    private const CTX_LABELS = [
        'droplet_id' => 'Droplet',
        'filter_id'  => 'Filter',
        'section_id' => 'Section',
        'page_id'    => 'Page',
        'zip'        => 'ZIP',
        'name'       => 'Name',
        'id'         => 'ID',
    ];

    public static function dir(): string
    {
        return rtrim(WB_PATH, '/\\') . self::DIR;
    }

    public static function file(): string
    {
        return self::dir() . '/' . self::FILE;
    }

    /** Number of rotated archive logs (codevet-*.log) on disk. */
    public static function archiveCount(): int
    {
        return count(glob(self::dir() . '/codevet-*.log') ?: []);
    }

    /**
     * Raw JSON-lines entries, newest first, capped.
     *
     * @return list<array<string,mixed>>
     */
    public static function read(bool $withArchives = false): array
    {
        $files = [self::file()];
        if ($withArchives) {
            $files = array_merge($files, glob(self::dir() . '/codevet-*.log') ?: []);
        }

        $entries = [];
        foreach ($files as $path) {
            if (!is_file($path)) {
                continue;
            }
            foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
                $e = json_decode($line, true);
                if (is_array($e) && isset($e['action'], $e['profile'])) {
                    $entries[] = $e;
                }
            }
        }

        usort($entries, static fn ($a, $b) => strcmp((string) ($b['time'] ?? ''), (string) ($a['time'] ?? '')));
        return array_slice($entries, 0, self::CAP);
    }

    /**
     * Decorated, collapsed rows for the template.
     *
     * @param  int  $since  Unix time of the viewer's last visit
     * @return list<array<string,mixed>>
     */
    public static function rows(bool $withArchives, int $since): array
    {
        $out = [];

        foreach (self::read($withArchives) as $e) {
            $action   = (string) $e['action'];
            $profile  = strtolower((string) $e['profile']);
            $findings = self::normFindings($e['findings'] ?? []);
            $context  = is_array($e['context'] ?? null) ? $e['context'] : [];
            $unix     = strtotime((string) ($e['time'] ?? '')) ?: 0;

            $row = [
                'ts'       => (string) ($e['time'] ?? ''),
                'unix'     => $unix,
                'exact'    => $unix ? date('Y-m-d H:i:s', $unix) : '',
                'profile'  => in_array($profile, ['droplet', 'outputfilter', 'code2', 'addon'], true) ? $profile : 'other',
                'severity' => str_ends_with($action, '_warned') ? 'warn' : 'block',
                'count'    => 1,
                'is_new'   => $unix > $since,
                'findings' => $findings,
                '_ctx'     => $context,
                // Collapse key: the AJAX save and the full-page fallback of the
                // same edit fire two entries seconds apart — treat them as one.
                '_sig'     => $profile . '|' . str_replace('_ajax', '', $action)
                              . '|' . md5(json_encode($findings) . json_encode($context)),
            ];

            $i = count($out) - 1;
            if ($i >= 0 && $out[$i]['_sig'] === $row['_sig']) {
                $out[$i]['count']++;
                // keep the most recent occurrence on top (read() is newest-first,
                // so the first of a run already is the newest)
            } else {
                $out[] = $row;
            }
        }

        // One batched name lookup for all droplet_id / filter_id references,
        // then build the "where" column with the resolved names.
        $names = self::resolveNames($out);
        foreach ($out as &$r) {
            $r['where'] = self::where($r['_ctx'], $r['findings'], $names);
            unset($r['_ctx'], $r['_sig']);
        }
        unset($r);

        return $out;
    }

    /**
     * Resolve the numeric droplet_id / filter_id references in the rows to
     * their names, in one query per type. IN() values are cast to int first,
     * so the string interpolation is injection-safe.
     *
     * @param  list<array<string,mixed>> $rows  (each still carries '_ctx')
     * @return array{droplet:array<int,string>, filter:array<int,string>}
     */
    private static function resolveNames(array $rows): array
    {
        $map = ['droplet' => [], 'filter' => []];
        $db  = $GLOBALS['database'] ?? null;
        if (!is_object($db)) {
            return $map;
        }

        $dropletIds = $filterIds = [];
        foreach ($rows as $r) {
            $c = $r['_ctx'];
            if (isset($c['droplet_id'])) { $dropletIds[] = (int) $c['droplet_id']; }
            if (isset($c['filter_id']))  { $filterIds[]  = (int) $c['filter_id']; }
        }

        $lookup = static function (array $ids, string $table) use ($db): array {
            $ids = array_values(array_unique(array_filter($ids)));
            if (!$ids) {
                return [];
            }
            $out = [];
            try {
                $rows = $db->fetchAll("SELECT `id`, `name` FROM `{TP}{$table}` WHERE `id` IN (" . implode(',', $ids) . ')');
                foreach (($rows ?: []) as $row) {
                    $out[(int) $row['id']] = (string) $row['name'];
                }
            } catch (\Throwable) {
                // table missing (module not installed) → no names, no crash
            }
            return $out;
        };

        $map['droplet'] = $lookup($dropletIds, 'mod_droplets');
        $map['filter']  = $lookup($filterIds,  'mod_outputfilter_dashboard');
        return $map;
    }

    /**
     * @param  list<array<string,mixed>> $rows  output of rows()
     * @return array{total:int, per_profile:array<string,int>, last_unix:int, archives:int}
     */
    public static function summary(array $rows): array
    {
        $total = 0;
        $per   = [];
        foreach ($rows as $r) {
            $total += $r['count'];
            $per[$r['profile']] = ($per[$r['profile']] ?? 0) + $r['count'];
        }
        return [
            'total'       => $total,
            'per_profile' => $per,
            'last_unix'   => $rows[0]['unix'] ?? 0,
            'archives'    => self::archiveCount(),
        ];
    }

    // ── internals ────────────────────────────────────────────────────────────

    /** @return list<array{rule:string,message:string,line:int,file:string}> */
    private static function normFindings(mixed $raw): array
    {
        if (!is_array($raw)) {
            return [];
        }
        $out = [];
        foreach ($raw as $f) {
            if (!is_array($f)) {
                continue;
            }
            $out[] = [
                'rule'    => (string) ($f['rule'] ?? '?'),
                'message' => (string) ($f['message'] ?? ''),
                'line'    => (int) ($f['line'] ?? -1),
                'file'    => (string) ($f['file'] ?? ''),
            ];
        }
        return $out;
    }

    /**
     * @param  array<string,mixed> $context
     * @param  list<array<string,mixed>> $findings
     * @param  array{droplet:array<int,string>, filter:array<int,string>} $names
     * @return list<array{label:string,value:string,file:bool}>
     */
    private static function where(array $context, array $findings, array $names = ['droplet' => [], 'filter' => []]): array
    {
        $where = [];
        foreach ($context as $k => $v) {
            if (!is_scalar($v)) {
                continue;
            }
            // Prefer the human name; fall back to "#<id>" when it can't be resolved.
            $label = self::CTX_LABELS[$k] ?? (string) $k;
            $value = (string) $v;
            if ($k === 'droplet_id') {
                $value = $names['droplet'][(int) $v] ?? ('#' . $v);
            } elseif ($k === 'filter_id') {
                $value = $names['filter'][(int) $v] ?? ('#' . $v);
            } elseif ($k === 'name') {
                $label = 'Filter';          // the outputfilter save handler logs the name directly
            } elseif ($k === 'section_id') {
                $value = '#' . $v;
            }
            $where[] = ['label' => $label, 'value' => $value, 'file' => false];
        }
        // distinct non-empty finding files — the addon ZIP case, where the
        // offending path lives on each finding rather than in the context.
        $files = [];
        foreach ($findings as $f) {
            $file = $f['file'] ?? '';
            if ($file !== '' && !in_array($file, $files, true)) {
                $files[] = $file;
                $where[] = ['label' => '', 'value' => $file, 'file' => true];
            }
        }
        return $where;
    }
}
