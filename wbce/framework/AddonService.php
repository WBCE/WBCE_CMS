<?php
/**
 * WBCE CMS — AddonService.php
 *
 * Core addon lifecycle engine adopted to work for WBCE CMS.
 * Handles install, uninstall, activate/deactivate, staging, DB registration
 * and script execution for modules, templates, and languages.
 *
 * Used by: installer, updater, AddonManager (admin tool).
 *
 * @author    Christian M. Stefan
 * @copyright 2025-2026 Christian M. Stefan
 * @copyright 2026 WBCE CMS Project
 * @license    GNU/GPL 2  https://www.gnu.org/licenses/gpl-2.0.html
 *
 * ── Addon States ──────────────────────────────────────────────────────────────
 *
 *  STAGED        var/addons/{type}s/{dir}/    not in DB   Upload/Fetch landing zone
 *  UNREGISTERED  /modules/{dir}/              not in DB   FTP upload or fresh CMS install
 *  INACTIVE      /modules/{dir}/              in DB       type = 'module_off'
 *  INSTALLED     /modules/{dir}/              in DB       type = 'module'
 *
 * ── Lifecycle flow ────────────────────────────────────────────────────────────
 *
 *  stageFromUpload/Url/Zip()  →  STAGED
 *  installFromStaged()        →  INSTALLED
 *  installFromDisk()          →  INSTALLED    (FTP upload or fresh install)
 *  deactivate()               →  INACTIVE
 *  activate()                 →  INSTALLED
 *  uninstall()                →  STAGED       (files preserved in var/addons/)
 *  delete()                   →  GONE
 *
 * ── Signal pattern ────────────────────────────────────────────────────────────
 *
 * Every write-operation returns an array of signal records:
 *   ['signal' => 'ADDON_INSERTED_OK', 'label' => 'My Module']
 *
 * Signals map to $SIGNAL entries for i18n:
 *   sprintf($SIGNAL[$r['signal']], $type, $r['label'])
 *
 * ── Capture Mode ──────────────────────────────────────────────────────────────
 *
 * install.php / upgrade.php run inside Capture Mode.
 * log_ok() / log_err() etc. route into the signal buffer automatically.
 */

defined('WB_PATH') or die('No direct access allowed');

class AddonService
{
    // ── Properties ────────────────────────────────────────────────────────────

    protected Database $db;

    private array    $signals  = [];
    private ?Closure $progress = null;
    private string   $tempDir;
    private string   $stageBase;

    // ── Capture Mode — static so log_ok() etc. can reach it ──────────────────

    private static bool  $captureActive = false;
    private static array $captureBuffer = [];

    // ── Constructor ───────────────────────────────────────────────────────────

    public function __construct()
    {
        $this->db        = $GLOBALS['database'];
        $this->tempDir   = rtrim(WB_PATH . '/temp', '/');
        $this->stageBase = rtrim(WB_PATH . '/var/addons', '/');
    }

    // ── Configuration ─────────────────────────────────────────────────────────

    public function setProgress(callable $fn): static
    {
        $this->progress = Closure::fromCallable($fn);
        return $this;
    }

    public function getSignals(): array { return $this->signals; }

    public function hasError(): bool
    {
        foreach ($this->signals as $r) {
            if (!$this->isOkSignal($r['signal'])) return true;
        }
        return false;
    }

    // ── Capture Mode ──────────────────────────────────────────────────────────

    public static function isCaptureActive(): bool { return self::$captureActive; }

    public static function captureSignal(string $signal, string $label): void
    {
        self::$captureBuffer[] = ['signal' => $signal, 'label' => $label];
    }

    private function startCapture(): void
    {
        self::$captureActive = true;
        self::$captureBuffer = [];
    }

    private function stopCapture(): array
    {
        self::$captureActive = false;
        $buf = self::$captureBuffer;
        self::$captureBuffer = [];
        return $buf;
    }

    // ── Staging helpers ───────────────────────────────────────────────────────

    protected function getStagedDir(string $type, string $dir): string
    {
        return $this->stageBase . '/' . $type . 's/' . $dir;
    }

    protected function ensureStageDir(string $type): bool
    {
        @mkdir($this->stageBase, 0755, true);
        $path = $this->stageBase . '/' . $type . 's';
        return is_dir($path) || (bool)@mkdir($path, 0755, true);
    }

    // ── STAGE ─────────────────────────────────────────────────────────────────

    /**
     * Stage an addon from a $_FILES upload entry.
     */
    public function stageFromUpload(array $fileEntry): array
    {
        $this->signals = [];

        if (!empty($fileEntry['error']) || empty($fileEntry['tmp_name'])) {
            return $this->addSignal('ADDON_UPLOAD_ERROR',
                $this->uploadErrorKey($fileEntry['error'] ?? -1));
        }

        $tempZip = tempnam($this->tempDir, 'wbce_stage_');
        if (!move_uploaded_file($fileEntry['tmp_name'], $tempZip)) {
            return $this->addSignal('ADDON_UPLOAD_ERROR', 'UPLOAD_ERR_CANT_WRITE');
        }

        return $this->stageFromZip($tempZip, true);
    }

    /**
     * Stage an addon by fetching a ZIP from a remote URL via cURL.
     */
    public function stageFromUrl(string $zipUrl): array
    {
        $this->signals = [];

        if (!filter_var($zipUrl, FILTER_VALIDATE_URL)) {
            return $this->addSignal('ADDON_FETCH_ERROR', $zipUrl);
        }
        if (!function_exists('curl_init')) {
            return $this->addSignal('ADDON_FETCH_ERROR', 'cURL extension not available');
        }

        $tempZip = tempnam($this->tempDir, 'wbce_remote_');
        $ch      = curl_init($zipUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 60,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_USERAGENT      => 'WBCE-AddonService/' . (defined('WBCE_VERSION') ? WBCE_VERSION : '1.x'),
        ]);
        $data     = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error    = curl_error($ch);
        curl_close($ch);

        if ($data === false || $httpCode !== 200) {
            @unlink($tempZip);
            return $this->addSignal('ADDON_FETCH_ERROR', $error ?: "HTTP $httpCode — $zipUrl");
        }

        file_put_contents($tempZip, $data);
        $this->addSignal('ADDON_FETCH_OK', basename($zipUrl));

        return $this->stageFromZip($tempZip, true);
    }

    /**
     * Extract a ZIP into the staging area.
     */
    public function stageFromZip(string $zipPath, bool $deleteZip = false): array
    {
        $tempUnzip = $this->tempDir . '/unzip_' . uniqid();

        try {
            $extractError = $this->extractZip($zipPath, $tempUnzip);
            if ($extractError !== null) {
                return $this->addSignal('ADDON_EXTRACT_ERROR', $extractError);
            }

            $info = $this->readInfo($tempUnzip);
            if ($info === null) {
                return $this->addSignal('ADDON_INFO_INVALID', basename($zipPath));
            }

            $type     = $info['_type'];
            $dir      = $info['_directory'];
            $stageDir = $this->getStagedDir($type, $dir);

            $this->ensureStageDir($type);
            if (is_dir($stageDir)) $this->removePath($stageDir);

            $this->copyDir($tempUnzip, $stageDir);
            $this->addSignal('ADDON_STAGED', $dir);

        } finally {
            $this->removePath($tempUnzip);
            if ($deleteZip && file_exists($zipPath)) @unlink($zipPath);
        }

        return $this->signals;
    }

    // ── INSTALL from staged ───────────────────────────────────────────────────

    /**
     * Install a staged addon: copy to target dir, write DB, run install/upgrade.php.
     *
     * @param string $dir   Directory name as found in var/addons/{type}s/
     * @param string $type  'module' | 'template' | 'language'
     */
    public function installFromStaged(string $dir, string $type): array
    {
        $this->signals = [];
        $stageDir      = $this->getStagedDir($type, $dir);

        if (!is_dir($stageDir)) {
            return $this->addSignal('ADDON_PATH_NOT_FOUND', $dir);
        }

        return $this->_doInstall($stageDir, $dir, $type, cleanupSource: true);
    }

    // ── INSTALL from disk (FTP upload / fresh CMS install) ───────────────────

    /**
     * Install or upgrade an addon that already sits in the target directory.
     *
     * Decision logic:
     *   - Not in DB at all        → run install.php
     *   - In DB, same version     → ADDON_ALREADY_CURRENT (skip)
     *   - In DB, older version    → run upgrade.php
     *
     * @param string $dir        Directory name inside /modules/ or /templates/
     * @param string $type       'module' | 'template' | 'language'
     * @param bool   $runScript  Run install/upgrade.php (default: true)
     *                           Pass false for pure DB-sync (update.php TRUNCATE reload)
     */
    public function installFromDisk(string $dir, string $type, bool $runScript = true): array
    {
        $this->signals = [];
        $targetDir     = $this->getTargetDir($type, $dir);

        if ($type === 'language') {
            $targetDir = WB_PATH . '/languages';
        }

        if (!is_dir($targetDir) && !is_file($targetDir)) {
            return $this->addSignal('ADDON_PATH_NOT_FOUND', $dir);
        }

        $sourcePath = ($type === 'language')
            ? WB_PATH . '/languages/' . $dir . '.php'
            : $targetDir;

        $info = $this->readInfo($sourcePath, $type);
        if ($info === null) {
            return $this->addSignal('ADDON_INFO_INVALID', $dir);
        }

        $existingRow = $this->db->fetchRow(
            "SELECT `version`, `type` FROM `{TP}addons`
             WHERE `directory` = ? AND `type` IN (?, ?)",
            [$dir, $type, $type . '_off']
        );

        $action = 'install';
        if ($existingRow !== null) {
            $installedVersion = $existingRow['version'] ?? '0';
            $newVersion       = $info['_version'] ?? '';
            if ($newVersion !== '' && $this->versionCompare($installedVersion, $newVersion, '>=')) {
                return $this->addSignal('ADDON_ALREADY_CURRENT', $dir);
            }
            $action = 'upgrade';
        }

        // Precheck
        foreach ($this->runPrecheck($type === 'language' ? dirname($sourcePath) : $targetDir) as $r) {
            $this->addSignal($r['signal'], $r['label']);
        }
        if ($this->hasError()) return $this->signals;

        // DB register
        foreach ($this->dbRegister($type === 'language' ? dirname($sourcePath) : $targetDir, $type) as $r) {
            $this->addSignal($r['signal'], $r['label']);
        }

        // Run install/upgrade script
        if ($runScript && $type !== 'language') {
            $scriptPath = $this->getTargetDir($type, $dir) . '/' . $action . '.php';
            foreach ($this->runScript($scriptPath, $dir) as $r) {
                $this->addSignal($r['signal'], $r['label']);
            }
        }

        return $this->signals;
    }

    // ── ACTIVATE / DEACTIVATE ─────────────────────────────────────────────────

    /**
     * Deactivate an installed addon.
     * Sets type 'module' → 'module_off'. Files stay in place.
     */
    public function deactivate(string $directory, string $type): array
    {
        $this->signals = [];

        if ($type === 'module') {
            $isCore = (bool)$this->db->fetchValue(
                "SELECT `core` FROM `{TP}addons` WHERE `directory` = ? AND `type` IN (?,?)",
                [$directory, 'module', 'module_off']
            );
            if ($isCore) return $this->addSignal('ADDON_IS_CORE', $directory);
        }

        $this->db->query(
            "UPDATE `{TP}addons` SET `type` = ? WHERE `directory` = ? AND `type` = ?",
            [$type . '_off', $directory, $type]
        );

        if ($this->db->hasError()) {
            return $this->addSignal('ADDON_DB_ERROR', $this->db->getError());
        }

        return $this->addSignal('ADDON_DEACTIVATED', $directory);
    }

    /**
     * Reactivate a deactivated addon.
     * Sets type 'module_off' → 'module'.
     */
    public function activate(string $directory, string $type): array
    {
        $this->signals = [];

        $this->db->query(
            "UPDATE `{TP}addons` SET `type` = ? WHERE `directory` = ? AND `type` = ?",
            [$type, $directory, $type . '_off']
        );

        if ($this->db->hasError()) {
            return $this->addSignal('ADDON_DB_ERROR', $this->db->getError());
        }

        return $this->addSignal('ADDON_ACTIVATED', $directory);
    }

    // ── UNINSTALL — soft ──────────────────────────────────────────────────────

    /**
     * Soft uninstall: move files to var/addons/, remove DB entry.
     * The addon's own DB tables (if any) are NOT dropped.
     *
     * @param bool $runScript  Run uninstall.php before moving (default: false)
     */
    public function uninstall(string $directory, string $type, bool $runScript = false): array
    {
        $this->signals = [];

        // Validate directory name
        if ($type === 'language') {
            if (!preg_match('/^[A-Z]{2}$/', $directory)) {
                return $this->addSignal('ADDON_INFO_INVALID', $directory);
            }
        } elseif (!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9._-]*[a-zA-Z0-9]$/', $directory)) {
            return $this->addSignal('ADDON_INFO_INVALID', $directory);
        }

        // Realpath jail — normalize separators for Windows compatibility
        $rootDir = str_replace('\\', '/', $this->getRootDir($type));
        $rawReal = realpath($rootDir . DIRECTORY_SEPARATOR . $directory);
        $realDir = $rawReal ? str_replace('\\', '/', $rawReal) : false;

        if (!$realDir || strpos($realDir, $rootDir) !== 0) {
            return $this->addSignal('ADDON_PATH_NOT_FOUND', $directory);
        }

        $targetDir = $this->getTargetDir($type, $directory);

        // Guards
        if (!empty($this->checkInUse($directory, $type))) {
            return $this->addSignal('ADDON_IN_USE', $directory);
        }
        if ($type === 'module') {
            @include_once $targetDir . '/info.php';
            if ((isset($core) && $core === true) ||
                (isset($module_level) && $module_level === 'core')) {
                return $this->addSignal('ADDON_IS_CORE', $directory);
            }
        }
        if ($type === 'template') {
            if ($directory === (string)(defined('DEFAULT_THEME')    ? DEFAULT_THEME    : '')) return $this->addSignal('ADDON_IS_DEFAULT', $directory);
            if ($directory === (string)(defined('DEFAULT_TEMPLATE') ? DEFAULT_TEMPLATE : '')) return $this->addSignal('ADDON_IS_DEFAULT', $directory);
        }
        if ($type === 'language') {
            if ($directory === DEFAULT_LANGUAGE || $directory === LANGUAGE) {
                return $this->addSignal('ADDON_IS_DEFAULT', $directory);
            }
        }

        $writeCheck = ($type === 'language') ? WB_PATH . '/languages/' : $targetDir;
        if (!is_writable($writeCheck)) {
            return $this->addSignal('ADDON_NOT_WRITABLE', $writeCheck);
        }

        // Optionally run uninstall.php
        if ($runScript && $type !== 'language') {
            foreach ($this->runScript($targetDir . '/uninstall.php', $directory) as $r) {
                $this->addSignal($r['signal'], $r['label']);
            }
        }

        // Move to staging (copy+delete for cross-drive Windows compatibility)
        $this->ensureStageDir($type);
        $stageDir = $this->getStagedDir($type, $directory);
        if (is_dir($stageDir)) $this->removePath($stageDir);

        if ($type === 'language') {
            $src = WB_PATH . '/languages/' . $directory . '.php';
            @mkdir(dirname($stageDir), 0755, true);
            if (@copy($src, $stageDir . '.php')) {
                @unlink($src);
            }
        } else {
            if (!@rename($targetDir, $stageDir)) {
                $this->copyDir($targetDir, $stageDir);
                $this->removePath($targetDir);
            }
        }

        $this->addSignal('ADDON_UNSTAGED', $directory);

        // Remove from DB (including _off variants)
        $this->db->query(
            "DELETE FROM `{TP}addons` WHERE `directory` = ? AND `type` IN (?, ?)",
            [$directory, $type, $type . '_off']
        );
        if ($this->db->hasError()) {
            $this->addSignal('ADDON_DB_ERROR', $this->db->getError());
        }

        // Template: reassign pages to default
        if ($type === 'template' && defined('DEFAULT_TEMPLATE')) {
            $this->db->query(
                "UPDATE `{TP}pages` SET `template` = ? WHERE `template` = ?",
                [DEFAULT_TEMPLATE, $directory]
            );
        }

        return $this->signals;
    }

    // ── DELETE — permanent ────────────────────────────────────────────────────

    /**
     * Permanently delete a staged addon from var/addons/.
     * Only works on STAGED addons — not on installed ones.
     */
    public function delete(string $dir, string $type): array
    {
        $this->signals = [];
        $stageDir = $this->getStagedDir($type, $dir);

        // Guard: core addons cannot be deleted even from staging
        if ($type === 'module') {
            $infoFile = $stageDir . '/info.php';
            if (is_readable($infoFile)) {
                $v = $this->readInfoVars(['core', 'module_level'], file_get_contents($infoFile));
                if ($v['core'] === true || $v['module_level'] === 'core') {
                    return $this->addSignal('ADDON_IS_CORE', $dir);
                }
            }
        }

        if (!is_dir($stageDir)) {
            return $this->addSignal('ADDON_PATH_NOT_FOUND', $dir);
        }

        $rm = $this->removePath($stageDir);
        return $this->addSignal(
            in_array($rm, ['RM_DIR_OK', 'RM_FILE_OK']) ? 'ADDON_DELETED' : 'ADDON_DELETE_ERROR',
            $dir
        );
    }

    // ── RELOAD — DB sync, known addons only ───────────────────────────────────

    /**
     * Re-read info.php for addons already registered in DB.
     * Does NOT register new/unknown addons (use installFromDisk() for that).
     * Excludes inactive addons (_off) — they keep their current DB state.
     *
     * @param string $type  'module' | 'template' | 'language' | 'all'
     */
    public function reload(string $type): array
    {
        $this->signals = [];

        foreach (($type === 'all' ? ['module', 'template', 'language'] : [$type]) as $t) {
            $registered = $this->db->fetchAll(
                "SELECT `directory` FROM `{TP}addons` WHERE `type` = ?", [$t]
            );

            $count = 0;
            foreach ($registered as $row) {
                $dir  = $row['directory'];
                $path = ($t === 'language')
                    ? WB_PATH . '/languages/' . $dir . '.php'
                    : $this->getTargetDir($t, $dir);

                if (!file_exists($path)) {
                    $this->addSignal('ADDON_PATH_NOT_FOUND', $dir);
                    continue;
                }

                foreach ($this->dbRegister(
                    $t === 'language' ? dirname($path) : $path, $t
                ) as $r) {
                    $this->addSignal($r['signal'], $r['label']);
                }
                $count++;
            }

            $this->addSignal('ADDON_RELOAD_OK', "$t: $count refreshed");
        }

        return $this->signals;
    }

    // ── MANUAL SCRIPT ─────────────────────────────────────────────────────────

    /**
     * Run a specific lifecycle script on an already-installed addon.
     */
    public function runAddonScript(string $directory, string $type, string $action): array
    {
        $this->signals = [];

        if (!in_array($action, ['install', 'upgrade', 'uninstall'], true)) {
            return $this->addSignal('ADDON_INFO_INVALID', "Unknown action: $action");
        }

        $targetDir = $this->getTargetDir($type, $directory);
        foreach ($this->runScript($targetDir . '/' . $action . '.php', $directory) as $r) {
            $this->addSignal($r['signal'], $r['label']);
        }
        foreach ($this->dbRegister($targetDir, $type) as $r) {
            $this->addSignal($r['signal'], $r['label']);
        }

        return $this->signals;
    }

    // ── Precheck ──────────────────────────────────────────────────────────────

    public function runPrecheck(string $addonPath): array
    {
        $results = [];
        $file    = rtrim($addonPath, '/') . '/precheck.php';
        if (!file_exists($file)) return $results;

        unset($PRECHECK);
        include $file;
        if (empty($PRECHECK) || !is_array($PRECHECK)) return $results;

        $order  = ['WBCE_VERSION','WB_VERSION','WB_ADDONS','PHP_VERSION','PHP_EXTENSIONS','PHP_SETTINGS','CUSTOM_CHECKS'];
        $checks = [];
        foreach ($order as $k) { if (isset($PRECHECK[$k])) $checks[$k] = $PRECHECK[$k]; }

        foreach ($checks as $key => $value) {
            switch ($key) {
                case 'WBCE_VERSION': case 'WB_VERSION':
                    $const = ($key === 'WBCE_VERSION') ? 'WBCE_VERSION' : 'WB_VERSION';
                    $op = $value['OPERATOR'] ?? '>='; $req = $value['VERSION'] ?? '';
                    if (!$req || !defined($const)) break;
                    $ok = $this->versionCompare(constant($const), $req, $op);
                    $results[] = ['signal' => $ok ? 'ADDON_PRECHECK_OK' : 'ADDON_PRECHECK_FAILED',
                                  'label'  => "$key $op $req (have: " . constant($const) . ")"];
                    break;
                case 'PHP_VERSION':
                    $op = $value['OPERATOR'] ?? '>='; $req = $value['VERSION'] ?? '';
                    if (!$req) break;
                    $ok = $this->versionCompare(PHP_VERSION, $req, $op);
                    $results[] = ['signal' => $ok ? 'ADDON_PRECHECK_OK' : 'ADDON_PRECHECK_FAILED',
                                  'label'  => "PHP $op $req (have: " . PHP_VERSION . ")"];
                    break;
                case 'PHP_EXTENSIONS':
                    foreach ((array)$value as $ext) {
                        $ok = extension_loaded(strtolower($ext));
                        $results[] = ['signal' => $ok ? 'ADDON_PRECHECK_OK' : 'ADDON_PRECHECK_FAILED',
                                      'label'  => "Extension: $ext"];
                    }
                    break;
                case 'WB_ADDONS':
                    foreach ((array)$value as $addon => $spec) {
                        $ver = is_array($spec) ? ($spec['VERSION'] ?? '') : '';
                        $op  = is_array($spec) ? ($spec['OPERATOR'] ?? '>=') : '';
                        $row = $this->db->fetchRow(
                            "SELECT `version` FROM `{TP}addons` WHERE `directory` = ?", [$addon]);
                        $ok  = $row !== null && ($ver === '' || $this->versionCompare($row['version'], $ver, $op));
                        $results[] = ['signal' => $ok ? 'ADDON_PRECHECK_OK' : 'ADDON_PRECHECK_FAILED',
                                      'label'  => "Addon: $addon" . ($ver ? " $op $ver" : '')];
                    }
                    break;
                case 'CUSTOM_CHECKS':
                    foreach ((array)$value as $lbl => $spec) {
                        $ok = (bool)($spec['STATUS'] ?? false);
                        $results[] = ['signal' => $ok ? 'ADDON_PRECHECK_OK' : 'ADDON_PRECHECK_FAILED',
                                      'label'  => $lbl];
                    }
                    break;
            }
        }

        return $results;
    }

    // ── In-use checks ─────────────────────────────────────────────────────────

    public function checkInUse(string $directory, string $type): array
    {
        return match ($type) {
            'module'   => array_column($this->db->fetchAll(
                "SELECT `page_id` FROM `{TP}sections` WHERE `module` = ?", [$directory]), 'page_id'),
            'template' => array_column($this->db->fetchAll(
                "SELECT `page_id` FROM `{TP}pages` WHERE `template` = ?", [$directory]), 'page_id'),
            'language' => array_column($this->db->fetchAll(
                "SELECT `user_id` FROM `{TP}users` WHERE `language` = ? LIMIT 10", [$directory]), 'user_id'),
            default    => [],
        };
    }

    // ── DB registration ───────────────────────────────────────────────────────

    public function dbRegister(string $path, string $type): array
    {
        $info = $this->readInfo($path, $type);
        if ($info === null) {
            return [['signal' => 'ADDON_INFO_INVALID', 'label' => $path]];
        }

        $dir    = $info['_directory'];
        $exists = (bool)$this->db->fetchValue(
            "SELECT COUNT(*) FROM `{TP}addons` WHERE `type` IN (?, ?) AND `directory` = ?",
            [$type, $type . '_off', $dir]
        );

        $row = $info;
        $row['type']         = $type;
        $row['updated_when'] = time();
        $row['updated_by']   = (int)($GLOBALS['admin']?->get_user_id() ?? 0);
        unset($row['_type'], $row['_directory'], $row['_version']);

        $exists
            ? $this->db->upsertRow('{TP}addons', 'directory', $row)
            : $this->db->insertRow('{TP}addons', $row);

        if ($this->db->hasError()) {
            return [['signal' => 'ADDON_DB_ERROR', 'label' => $this->db->getError()]];
        }

        return [['signal' => $exists ? 'ADDON_UPDATED_OK' : 'ADDON_INSERTED_OK',
                 'label'  => $info['name'] ?? $dir]];
    }

    // ── Filesystem ────────────────────────────────────────────────────────────

    public function extractZip(string $zipPath, string $target): ?string
    {
        if (!is_readable($zipPath))      return "ZIP not readable: $zipPath";
        if (!class_exists('ZipArchive')) return 'ZipArchive extension not available';

        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) return "ZipArchive::open() failed";

        $root = $this->findAddonRootInZip($zip);
        @mkdir($target, 0755, true);

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);
            if ($root !== '' && str_starts_with($name, $root)) {
                $name = substr($name, strlen($root));
            } elseif ($root !== '') {
                continue;
            }
            if ($name === '' || str_ends_with($name, '/')) continue;
            $safe = ltrim(str_replace(['../', '..\\', "\0"], '', $name), '/');
            $dest = $target . '/' . $safe;
            @mkdir(dirname($dest), 0755, true);
            file_put_contents($dest, $zip->getFromIndex($i));
        }
        $zip->close();

        return file_exists($target . '/info.php') ? null : 'No info.php — not a valid WBCE addon';
    }

    public function removePath(string $path): string
    {
        if (function_exists('removePath')) return removePath($path, false);
        $path = rtrim($path, DIRECTORY_SEPARATOR);
        if (!file_exists($path))  return 'RM_PATH_NOT_FOUND';
        if (is_file($path))       return @unlink($path) ? 'RM_FILE_OK' : 'RM_PATH_COULD_NOT_REMOVE';
        if (!is_readable($path))  return 'RM_PATH_NOT_READABLE';
        foreach (scandir($path) as $item) {
            if ($item === '.' || $item === '..') continue;
            $this->removePath($path . DIRECTORY_SEPARATOR . $item);
        }
        return @rmdir($path) ? 'RM_DIR_OK' : 'RM_PATH_COULD_NOT_REMOVE';
    }

    // ── Signal helpers ────────────────────────────────────────────────────────

    protected function addSignal(string $signal, string $label): array
    {
        $record = ['signal' => $signal, 'label' => $label];
        $this->signals[] = $record;
        if ($this->progress !== null) ($this->progress)($record);
        return $this->signals;
    }

    public function isOkSignal(string $signal): bool
    {
        return in_array($signal, [
            'ADDON_DETECTED', 'ADDON_INSERTED_OK', 'ADDON_UPDATED_OK',
            'ADDON_SCRIPT_OK', 'ADDON_EXTRACT_OK', 'ADDON_PRECHECK_OK',
            'ADDON_REMOVED_OK', 'ADDON_RELOAD_OK', 'ADDON_FETCH_OK',
            'ADDON_UP_TO_DATE', 'ADDON_ALREADY_CURRENT', 'ADDON_SCRIPT_NOT_FOUND',
            'ADDON_STAGED', 'ADDON_UNSTAGED', 'ADDON_DELETED',
            'ADDON_ACTIVATED', 'ADDON_DEACTIVATED',
        ], true);
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    /**
     * Shared install logic used by installFromStaged().
     */
    private function _doInstall(string $sourceDir, string $dir, string $type, bool $cleanupSource = false): array
    {
        $info = $this->readInfo($sourceDir, $type);
        if ($info === null) {
            return $this->addSignal('ADDON_INFO_INVALID', $dir);
        }

        foreach ($this->runPrecheck($sourceDir) as $r) {
            $this->addSignal($r['signal'], $r['label']);
        }
        if ($this->hasError()) return $this->signals;

        $targetDir = $this->getTargetDir($type, $dir);
        $action    = $this->detectAction($type, $dir, $targetDir, $info['_version'] ?? '');

        if ($action === 'skip') {
            return $this->addSignal('ADDON_ALREADY_CURRENT', $dir);
        }
        if ($type !== 'language' && !$this->ensureWritable($targetDir)) {
            return $this->addSignal('ADDON_NOT_WRITABLE', $targetDir);
        }

        if ($type === 'language') {
            foreach (glob($sourceDir . '/??.php') ?: [] as $f) {
                copy($f, WB_PATH . '/languages/' . basename($f));
            }
        } else {
            $this->copyDir($sourceDir, $targetDir);
            $this->chmodDir($targetDir);
        }

        foreach ($this->dbRegister($sourceDir, $type) as $r) {
            $this->addSignal($r['signal'], $r['label']);
        }

        if ($type !== 'language') {
            foreach ($this->runScript($targetDir . '/' . $action . '.php', $dir) as $r) {
                $this->addSignal($r['signal'], $r['label']);
            }
        }

        if ($cleanupSource && is_dir($sourceDir)) {
            $this->removePath($sourceDir);
        }

        return $this->signals;
    }

    private function runScript(string $scriptPath, string $label): array
    {
        if (!file_exists($scriptPath)) {
            return [['signal' => 'ADDON_SCRIPT_NOT_FOUND',
                     'label'  => $label . ' — ' . basename($scriptPath)]];
        }

        // Both `global $database` and direct `$database` usage supported
        $GLOBALS['database'] = $this->db;
        $database            = $this->db;

        $this->startCapture();
        ob_start();

        try {
            require $scriptPath;
        } catch (Throwable $e) {
            ob_end_clean();
            $this->stopCapture();
            return [['signal' => 'ADDON_SCRIPT_ERROR',
                     'label'  => $label . ': ' . $e->getMessage()]];
        }

        $raw      = ob_get_clean();
        $captured = $this->stopCapture();

        if (!empty($captured)) return $captured;

        $suffix = trim($raw) !== '' ? ' [' . mb_substr(strip_tags($raw), 0, 100) . ']' : '';
        return [['signal' => 'ADDON_SCRIPT_OK',
                 'label'  => $label . ' — ' . basename($scriptPath) . $suffix]];
    }

    protected function readInfo(string $path, string $typeHint = ''): ?array
    {
        $path   = rtrim($path, '/');
        $isLang = ($typeHint === 'language')
            || (!file_exists($path . '/info.php') && !empty(glob($path . '/??.php')));

        if ($isLang) {
            $files = is_file($path) ? [$path] : (glob($path . '/??.php') ?: []);
            if (empty($files)) return null;
            $data = (string)@file_get_contents($files[0]);
            $code = strtoupper(preg_replace('/^.*([a-zA-Z]{2})\.php$/', '$1', $files[0]));
            $v    = $this->readInfoVars(['language_name','language_version','language_author','language_platform','core'], $data);
            return [
                '_type' => 'language', '_directory' => $code, '_version' => '',
                'directory'   => $code,
                'name'        => $v['language_name']     ?: $code,
                'version'     => $v['language_version']  ?: '',
                'author'      => $v['language_author']   ?: '',
                'platform'    => $v['language_platform'] ?: '',
                'description' => '', 'function' => '', 'license' => 'GNU GPL',
                'core'        => ($v['core'] === true) ? 1 : 0,
            ];
        }

        $infoFile = $path . '/info.php';
        if (!file_exists($infoFile)) return null;
        $data = (string)@file_get_contents($infoFile);

        $v = $this->readInfoVars([
            'module_directory', 'module_name', 'module_description',
            'module_function',  'module_type', 'module_version',
            'module_platform',  'module_author', 'module_license', 'module_level',
            'core',
            'template_directory', 'template_name', 'template_description',
            'template_function',  'template_version', 'template_platform',
            'template_author',    'template_license',
        ], $data);

        if ($v['module_directory'] !== null) {
            $fn = strtolower($v['module_function'] ?: $v['module_type'] ?: '');
            return [
                '_type'       => 'module',
                '_directory'  => $v['module_directory'],
                '_version'    => $v['module_version']     ?: '',
                'directory'   => $v['module_directory'],
                'name'        => $v['module_name']        ?: '',
                'description' => $v['module_description'] ?: '',
                'function'    => $fn,
                'version'     => $v['module_version']     ?: '',
                'platform'    => $v['module_platform']    ?: '',
                'author'      => $v['module_author']      ?: '',
                'license'     => $v['module_license']     ?: 'unknown',
                'core'        => ($v['core'] === true || $v['module_level'] === 'core') ? 1 : 0,
            ];
        }

        if ($v['template_directory'] !== null) {
            return [
                '_type'       => 'template',
                '_directory'  => $v['template_directory'],
                '_version'    => $v['template_version']     ?: '',
                'directory'   => $v['template_directory'],
                'name'        => $v['template_name']        ?: '',
                'description' => $v['template_description'] ?: '',
                'function'    => $v['template_function']    ?: 'template',
                'version'     => $v['template_version']     ?: '',
                'platform'    => $v['template_platform']    ?: '',
                'author'      => $v['template_author']      ?: '',
                'license'     => $v['template_license']     ?: 'unknown',
                'core'        => ($v['core'] === true) ? 1 : 0,
            ];
        }

        return null;
    }

    /**
     * Extract multiple variable assignments from PHP source in one token pass.
     *
     * Sentinel: null = not found. Booleans returned as real PHP true/false.
     *
     * @param  string[] $search  Variable names without $ prefix
     * @param  string   $data    PHP source code
     * @return array<string, string|bool|null>
     */
    protected function readInfoVars(array $search, string $data): array
    {
        $result = array_fill_keys($search, null);
        $wanted = array_flip($search);

        $tokens = token_get_all('<?php ' . $data);
        $count  = count($tokens);

        for ($i = 0; $i < $count; $i++) {
            if (!is_array($tokens[$i]) || $tokens[$i][0] !== T_VARIABLE) continue;

            $varName = ltrim($tokens[$i][1], '$');
            if (!isset($wanted[$varName])) continue;

            $j = $i + 1;
            while ($j < $count && is_array($tokens[$j]) && $tokens[$j][0] === T_WHITESPACE) $j++;
            if (!isset($tokens[$j]) || $tokens[$j] !== '=') continue;
            $j++;

            while ($j < $count && is_array($tokens[$j]) && $tokens[$j][0] === T_WHITESPACE) $j++;
            if (!isset($tokens[$j])) continue;

            $tok = $tokens[$j];

            if (is_array($tok) && $tok[0] === T_CONSTANT_ENCAPSED_STRING) {
                $result[$varName] = trim($tok[1], "'\"");
            } elseif (is_array($tok) && $tok[0] === T_STRING) {
                $lower = strtolower($tok[1]);
                if ($lower === 'true')  $result[$varName] = true;
                if ($lower === 'false') $result[$varName] = false;
            }

            if (!in_array(null, $result, true)) break;
        }

        return $result;
    }

    private function findAddonRootInZip(ZipArchive $zip): string
    {
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);
            if (basename($name) === 'info.php') {
                $dir = dirname($name);
                return ($dir === '.') ? '' : $dir . '/';
            }
        }
        return '';
    }

    private function detectAction(string $type, string $dir, string $targetDir, string $newVersion): string
    {
        if ($type === 'language') return 'install';
        if (!is_dir($targetDir) || !file_exists($targetDir . '/info.php')) return 'install';
        $data    = (string)@file_get_contents($targetDir . '/info.php');
        $varName = ($type === 'template') ? 'template_version' : 'module_version';
        $v       = $this->readInfoVars([$varName], $data);
        $inst    = $v[$varName] ?: '0';
        return ($newVersion !== '' && $this->versionCompare($inst, $newVersion, '>=')) ? 'skip' : 'upgrade';
    }

    protected function getTargetDir(string $type, string $dir): string
    {
        return match ($type) {
            'template' => WB_PATH . '/templates/' . $dir,
            'language' => WB_PATH . '/languages/' . $dir . '.php',
            default    => WB_PATH . '/modules/'   . $dir,
        };
    }

    protected function getRootDir(string $type): string
    {
        return match ($type) {
            'template' => WB_PATH . '/templates',
            'language' => WB_PATH . '/languages',
            default    => WB_PATH . '/modules',
        };
    }

    private function ensureWritable(string $dir): bool
    {
        return is_dir($dir) ? is_writable($dir) : (bool)@mkdir($dir, 0755, true);
    }

    private function copyDir(string $from, string $to): void
    {
        @mkdir($to, 0755, true);
        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($from, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($it as $item) {
            $dest = $to . '/' . substr($item->getPathname(), strlen($from) + 1);
            $item->isDir() ? @mkdir($dest, 0755, true) : copy($item->getPathname(), $dest);
        }
    }

    private function chmodDir(string $dir): void
    {
        if (!defined('STRING_FILE_MODE') || !defined('STRING_DIR_MODE')) return;
        $fm = octdec(STRING_FILE_MODE);
        $dm = octdec(STRING_DIR_MODE);
        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($it as $item) @chmod($item->getPathname(), $item->isDir() ? $dm : $fm);
    }

    private function versionCompare(string $v1, string $v2, string $op): bool
    {
        $map = ['alpha' => '1', 'beta' => '2', 'rc' => '4', 'final' => '8'];
        foreach ($map as $w => $n) { $v1 = str_ireplace($w, $n, $v1); $v2 = str_ireplace($w, $n, $v2); }
        return version_compare(str_replace(' ', '', $v1), str_replace(' ', '', $v2), $op);
    }

    protected function normalizeVersion(string $v): string
    {
        return preg_replace('/[^0-9.]/', '', $v);
    }

    private function uploadErrorKey(int $code): string
    {
        return match ($code) {
            UPLOAD_ERR_INI_SIZE   => 'UPLOAD_ERR_INI_SIZE',
            UPLOAD_ERR_FORM_SIZE  => 'UPLOAD_ERR_FORM_SIZE',
            UPLOAD_ERR_PARTIAL    => 'UPLOAD_ERR_PARTIAL',
            UPLOAD_ERR_NO_FILE    => 'UPLOAD_ERR_NO_FILE',
            UPLOAD_ERR_NO_TMP_DIR => 'UPLOAD_ERR_NO_TMP_DIR',
            UPLOAD_ERR_CANT_WRITE => 'UPLOAD_ERR_CANT_WRITE',
            UPLOAD_ERR_EXTENSION  => 'UPLOAD_ERR_EXTENSION',
            default               => 'UPLOAD_ERR_UNKNOWN',
        };
    }
}


// ── Capture-aware log functions ───────────────────────────────────────────────
//
// Loaded with AddonService — available as soon as the autoloader registers it.
// When AddonService::isCaptureActive() is true (during install.php execution),
// log calls route to the signal buffer instead of being echoed.

if (!function_exists('log_ok')) {
    function log_ok(string $msg): void
    {
        if (AddonService::isCaptureActive()) {
            AddonService::captureSignal('ADDON_SCRIPT_OK', $msg); return;
        }
        echo '<div class="log-ok">✓ ' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . "</div>\n";
        flush();
    }
}

if (!function_exists('log_info')) {
    function log_info(string $msg): void
    {
        if (AddonService::isCaptureActive()) {
            AddonService::captureSignal('ADDON_SCRIPT_OK', $msg); return;
        }
        echo '<div class="log-info">› ' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . "</div>\n";
        flush();
    }
}

if (!function_exists('log_warn')) {
    function log_warn(string $msg): void
    {
        if (AddonService::isCaptureActive()) {
            AddonService::captureSignal('ADDON_SCRIPT_ERROR', $msg); return;
        }
        echo '<div class="log-warn">⚠ ' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . "</div>\n";
        flush();
    }
}

if (!function_exists('log_err')) {
    function log_err(string $msg): void
    {
        if (AddonService::isCaptureActive()) {
            AddonService::captureSignal('ADDON_SCRIPT_ERROR', $msg); return;
        }
        global $_fatal;
        $_fatal = true;
        echo '<div class="log-err">✗ ' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . "</div>\n";
        flush();
    }
}

if (!function_exists('log_sep')) {
    function log_sep(string $label = ''): void
    {
        if (AddonService::isCaptureActive()) return;
        echo '<div class="sep">── ' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . " ──</div>\n";
        flush();
    }
}
