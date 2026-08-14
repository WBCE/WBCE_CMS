<?php
/**
 * CodeVet — PHP source vetting for admin-authored code and uploaded addons.
 *
 * Consolidates the syntax check that used to be duplicated across Droplets
 * (functions.inc.php, ajax_save_droplet.php, DropletFileManager), Outputfilter
 * (ajax_save_filter.php) and Code2 (php_syntax_check.php), and adds a
 * token-based content scan those call sites never had: a saved Droplet /
 * Outputfilter function / Code2 section could previously contain eval(),
 * system(), dynamic function calls etc. with nothing blocking it beyond
 * "does it parse".
 *
 * Two independent checks:
 *   - checkSyntax()   same if(0){}+eval()+ParseError trick as before, no
 *                     exec('php -l') since shell_exec is disabled on many hosts.
 *   - scan()          token_get_all() based rule scan, no eval() involved,
 *                     matched against a per-profile rule set (see PROFILES).
 *
 * Rule sets are data (see CodeVetProfile + PROFILES), not scan-loop branching,
 * so a new profile is a new array entry, not a new if-branch in the scanner.
 */
declare(strict_types=1);

enum CodeVetProfile: string
{
    case Droplet      = 'droplet';       // eval()'d on every page render
    case Outputfilter = 'outputfilter';  // eval() with a leading close-tag to define a filter function
    case Code2        = 'code2';         // materialised to temp/*.php and include()'d
    case Addon        = 'addon';         // module/template files extracted from an uploaded ZIP
}

final class CodeVetFinding
{
    /**
     * @param string $severity 'block' (never persisted/staged) or 'warn'
     *                         (admin is informed but the action proceeds).
     */
    public function __construct(
        public readonly string $rule,
        public readonly string $message,
        public readonly int    $line = -1,
        public readonly string $file = '',
        public readonly string $severity = 'block',
    ) {
    }
}

final class CodeVet
{
    private const LOG_FILE = 'codevet.log';

    /**
     * @var array<string, array{
     *     blockEval: bool,
     *     blockBackticks: bool,
     *     blockIncludeRequire: bool,
     *     blockDynamicCalls: bool,
     *     blockVariableFunctionCalls: bool,
     *     blockSuperglobals: string[],
     *     blockFunctions: string[],
     *     dangerousExtensions: string[]
     * }>
     */
    private const SYSTEM_FUNCTIONS = [
        'exec', 'system', 'shell_exec', 'passthru', 'popen', 'proc_open',
        'pcntl_exec', 'assert', 'create_function',
    ];
    // Subset of SYSTEM_FUNCTIONS with genuinely no legitimate use in an addon
    // (arbitrary shell/process execution). create_function() is excluded here —
    // it's PHP's userland eval() equivalent (deprecated, removed in PHP 8) and
    // still shows up in older-but-legitimate libraries, so for addons it's a
    // warn, not a hard block. It stays hard-blocked for Droplet/Outputfilter/
    // Code2 (own render-time code) via SYSTEM_FUNCTIONS above.
    private const HARD_SYSTEM_FUNCTIONS = [
        'exec', 'system', 'shell_exec', 'passthru', 'popen', 'proc_open',
        'pcntl_exec', 'assert',
    ];
    private const OBFUSCATION_FUNCTIONS = [
        'base64_decode', 'gzinflate', 'gzuncompress', 'gzdecode', 'str_rot13', 'hex2bin',
    ];
    private const FILE_FUNCTIONS = [
        'unlink', 'rmdir', 'mkdir', 'rename', 'copy', 'chmod', 'chown',
        'file_put_contents', 'file_get_contents', 'fopen', 'fwrite', 'fputs',
        'readfile', 'fpassthru', 'move_uploaded_file',
    ];
    private const SUPERGLOBALS = ['_SERVER', '_GET', '_POST', '_REQUEST', '_COOKIE', '_FILES', '_ENV', '_GLOBALS'];

    /** @var array<string, array>|null lazily built — array_merge() isn't allowed in a const expression */
    private static ?array $profiles = null;

    /**
     * @return array{
     *     evalSeverity: string|false, blockBackticks: bool, blockIncludeRequire: bool,
     *     dynamicCallSeverity: string|false, blockVariableFunctionCalls: bool,
     *     blockSuperglobals: string[], blockFunctions: string[], warnFunctions: string[],
     *     dangerousExtensions: string[], dangerousExtensionSeverity: string
     * }
     */
    private static function rules(CodeVetProfile $profile): array
    {
        if (self::$profiles === null) {
            self::$profiles = [
                // Runs unattended on every page view via eval() — tightest profile.
                'droplet' => [
                    'evalSeverity'                => 'block',
                    'blockBackticks'              => true,
                    'blockIncludeRequire'         => true,
                    'dynamicCallSeverity'         => 'block',
                    'blockVariableFunctionCalls'  => true,
                    'blockSuperglobals'           => self::SUPERGLOBALS,
                    'blockFunctions'              => array_merge(self::SYSTEM_FUNCTIONS, self::OBFUSCATION_FUNCTIONS, self::FILE_FUNCTIONS),
                    'warnFunctions'               => [],
                    'dangerousExtensions'         => [],
                    'dangerousExtensionSeverity'  => 'block',
                ],
                // Runs on every page render too (defines the filter function via eval()).
                'outputfilter' => [
                    'evalSeverity'                => 'block',
                    'blockBackticks'              => true,
                    'blockIncludeRequire'         => true,
                    'dynamicCallSeverity'         => 'block',
                    'blockVariableFunctionCalls'  => true,
                    'blockSuperglobals'           => self::SUPERGLOBALS,
                    'blockFunctions'              => array_merge(self::SYSTEM_FUNCTIONS, self::OBFUSCATION_FUNCTIONS, self::FILE_FUNCTIONS),
                    'warnFunctions'               => [],
                    'dangerousExtensions'         => [],
                    'dangerousExtensionSeverity'  => 'block',
                ],
                // Whole PHP page-section written by an admin — legitimately needs
                // request data and file/include access, so only the constructs with
                // no legitimate reason to ever appear stay blocked.
                'code2' => [
                    'evalSeverity'                => 'block',
                    'blockBackticks'              => true,
                    'blockIncludeRequire'         => false,
                    'dynamicCallSeverity'         => 'block',
                    'blockVariableFunctionCalls'  => false,
                    'blockSuperglobals'           => [],
                    'blockFunctions'              => array_merge(self::SYSTEM_FUNCTIONS, self::OBFUSCATION_FUNCTIONS),
                    'warnFunctions'               => [],
                    'dangerousExtensions'         => [],
                    'dangerousExtensionSeverity'  => 'block',
                ],
                // Real, distributed module/template code — needs file ops, includes,
                // superglobals. Only arbitrary shell/process execution has no
                // legitimate reason to ever appear and stays hard-blocked; eval(),
                // dynamic calls, deprecated/obfuscation-flavoured functions and
                // risky extensions are common in real-world third-party code
                // (syntax-check tricks, PEAR-era libraries, packed assets) and
                // only warn — the admin is informed and can still install.
                'addon' => [
                    'evalSeverity'                => 'warn',
                    'blockBackticks'              => true,
                    'blockIncludeRequire'         => false,
                    'dynamicCallSeverity'         => 'warn',
                    'blockVariableFunctionCalls'  => false,
                    'blockSuperglobals'           => [],
                    'blockFunctions'              => self::HARD_SYSTEM_FUNCTIONS,
                    'warnFunctions'               => array_merge(['create_function'], self::OBFUSCATION_FUNCTIONS),
                    'dangerousExtensions'         => ['phtml', 'pht', 'php3', 'php4', 'php5', 'php7', 'phar', 'inc.php~'],
                    'dangerousExtensionSeverity'  => 'warn',
                ],
            ];
        }
        return self::$profiles[$profile->value];
    }

    private function __construct()
    {
    }

    // ── Syntax check ─────────────────────────────────────────────────────────

    /**
     * @param string   $code  Raw PHP body, without surrounding <?php ?> tags.
     * @param int|null &$line Set to the offending line in $code (1-based), or
     *                        -1 if the code is valid. The code is wrapped in
     *                        `if(0){\n...\n}` before eval() — line 1 of that
     *                        wrapper is the injected `if(0){`, so the reported
     *                        line is offset by -1 to map back to $code.
     * @return string|null The ParseError message, or null if the code is valid.
     */
    public static function checkSyntax(string $code, ?int &$line = null): ?string
    {
        $line = -1;
        try {
            @eval("if(0){\n{$code}\n}");
        } catch (\ParseError $e) {
            $line = max(1, $e->getLine() - 1);
            return $e->getMessage();
        }
        return null;
    }

    // ── Content scan ─────────────────────────────────────────────────────────

    /**
     * @return CodeVetFinding[] Empty array means the code is clean for this profile.
     */
    public static function scan(string $code, CodeVetProfile $profile): array
    {
        $rules    = self::rules($profile);
        $findings = [];
        // Same line so reported line numbers still match the original,
        // tag-less source the caller passed in.
        $tokens   = @token_get_all('<?php ' . $code);

        foreach ($tokens as $i => $token) {
            if (!is_array($token)) {
                if ($token === '`' && $rules['blockBackticks']) {
                    $findings[] = new CodeVetFinding('backtick', 'Execution operator (backticks) is forbidden');
                }
                continue;
            }

            [$id, $content, $line] = $token;

            if ($id === T_COMMENT || $id === T_DOC_COMMENT || $id === T_WHITESPACE) {
                continue;
            }

            if ($id === T_EVAL && $rules['evalSeverity'] !== false) {
                $findings[] = new CodeVetFinding('eval', 'eval() is forbidden', $line, '', $rules['evalSeverity']);
                continue;
            }

            if ($rules['blockIncludeRequire']
                && in_array($id, [T_INCLUDE, T_INCLUDE_ONCE, T_REQUIRE, T_REQUIRE_ONCE], true)) {
                $findings[] = new CodeVetFinding('include', 'include/require is forbidden in this profile', $line);
                continue;
            }

            if ($id === T_VARIABLE && $rules['blockSuperglobals']) {
                $name = ltrim($content, '$');
                if (in_array($name, $rules['blockSuperglobals'], true)) {
                    $findings[] = new CodeVetFinding('superglobal', "Superglobal access is forbidden: {$content}", $line);
                    continue;
                }
            }

            if ($id === T_VARIABLE && $rules['blockVariableFunctionCalls']
                && self::isFollowedByCallParens($tokens, $i)) {
                $findings[] = new CodeVetFinding('variable_call', "Variable function call is forbidden: {$content}()", $line);
                continue;
            }

            if ($id === T_STRING) {
                $lower = strtolower($content);

                if (!self::isFollowedByCallParens($tokens, $i) || self::isMemberAccess($tokens, $i)) {
                    continue;
                }

                if ($rules['dynamicCallSeverity'] !== false && in_array($lower, ['call_user_func', 'call_user_func_array'], true)) {
                    $findings[] = new CodeVetFinding('dynamic_call', "Dynamic function call is forbidden: {$content}()", $line, '', $rules['dynamicCallSeverity']);
                    continue;
                }

                if (in_array($lower, $rules['blockFunctions'], true)) {
                    $findings[] = new CodeVetFinding('blocked_function', "Function call is forbidden: {$content}()", $line, '', 'block');
                    continue;
                }

                if (in_array($lower, $rules['warnFunctions'], true)) {
                    $findings[] = new CodeVetFinding('blocked_function', "Function call is forbidden: {$content}()", $line, '', 'warn');
                    continue;
                }
            }
        }

        return $findings;
    }

    public static function isSafe(string $code, CodeVetProfile $profile): bool
    {
        return self::checkSyntax($code) === null && self::hasBlocking(self::scan($code, $profile)) === false;
    }

    /**
     * @param CodeVetFinding[] $findings
     */
    public static function hasBlocking(array $findings): bool
    {
        foreach ($findings as $finding) {
            if ($finding->severity === 'block') {
                return true;
            }
        }
        return false;
    }

    /**
     * @param CodeVetFinding[] $findings
     * @return CodeVetFinding[]
     */
    public static function onlyWarnings(array $findings): array
    {
        return array_values(array_filter($findings, static fn (CodeVetFinding $f) => $f->severity === 'warn'));
    }

    // ── Directory / ZIP scan ─────────────────────────────────────────────────

    /**
     * Scans every .php file under $dir against the given profile. Also flags
     * files with a dangerous double/legacy extension (e.g. .phtml, .php5)
     * that would execute as PHP on some server configs but skip a ".php"-only
     * content scan elsewhere in the pipeline.
     *
     * @return CodeVetFinding[]
     */
    public static function scanDirectory(string $dir, CodeVetProfile $profile): array
    {
        $findings = [];
        $rules    = self::rules($profile);
        if (!is_dir($dir)) {
            return $findings;
        }

        $it = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($it as $fileInfo) {
            if (!$fileInfo->isFile()) {
                continue;
            }
            $relative = ltrim(str_replace('\\', '/', substr($fileInfo->getPathname(), strlen($dir))), '/');
            $ext      = strtolower($fileInfo->getExtension());

            if (in_array($ext, $rules['dangerousExtensions'], true)) {
                $findings[] = new CodeVetFinding('dangerous_extension', "Disallowed file extension: .{$ext}", -1, $relative, $rules['dangerousExtensionSeverity']);
                continue;
            }
            if ($ext !== 'php') {
                continue;
            }

            $code = @file_get_contents($fileInfo->getPathname());
            if ($code === false) {
                continue;
            }
            foreach (self::scan($code, $profile) as $finding) {
                $findings[] = new CodeVetFinding($finding->rule, $finding->message, $finding->line, $relative, $finding->severity);
            }
        }

        return $findings;
    }

    // ── Audit log ────────────────────────────────────────────────────────────

    /**
     * @param CodeVetFinding[] $findings
     */
    public static function logEvent(string $action, CodeVetProfile $profile, array $findings, array $context = []): void
    {
        if (!defined('WB_PATH')) {
            return;
        }
        $logDir = rtrim(WB_PATH, '/\\') . '/var/code_vet';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
            @file_put_contents($logDir . '/index.php', '<?php header("Location: ../../index.php", true, 301);' . PHP_EOL);
        }
        if (!is_dir($logDir) || !is_writable($logDir)) {
            return;
        }
        $logFile = $logDir . '/' . self::LOG_FILE;
        if (is_file($logFile) && filesize($logFile) > 5242880) {
            @rename($logFile, $logDir . '/codevet-' . date('Ymd-His') . '.log');
        }

        $entry = [
            'time'     => date('c'),
            'action'   => $action,
            'profile'  => $profile->value,
            'status'   => $findings === [] ? 'passed' : 'blocked',
            'findings' => array_map(
                static fn (CodeVetFinding $f) => ['rule' => $f->rule, 'message' => $f->message, 'line' => $f->line, 'file' => $f->file],
                $findings
            ),
            'context'  => $context,
        ];

        @file_put_contents($logFile, json_encode($entry, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    // ── Token helpers ────────────────────────────────────────────────────────

    private static function isFollowedByCallParens(array $tokens, int $i): bool
    {
        $next = self::nextSignificant($tokens, $i);
        return $next !== null && $tokens[$next] === '(';
    }

    private static function isMemberAccess(array $tokens, int $i): bool
    {
        $prev = self::prevSignificant($tokens, $i);
        if ($prev === null) {
            return false;
        }
        $token = $tokens[$prev];
        return is_array($token) && in_array($token[0], [T_OBJECT_OPERATOR, T_NULLSAFE_OBJECT_OPERATOR, T_DOUBLE_COLON, T_FUNCTION], true);
    }

    private static function nextSignificant(array $tokens, int $i): ?int
    {
        for ($j = $i + 1; $j < count($tokens); $j++) {
            if (self::isInsignificant($tokens[$j])) {
                continue;
            }
            return $j;
        }
        return null;
    }

    private static function prevSignificant(array $tokens, int $i): ?int
    {
        for ($j = $i - 1; $j >= 0; $j--) {
            if (self::isInsignificant($tokens[$j])) {
                continue;
            }
            return $j;
        }
        return null;
    }

    private static function isInsignificant(mixed $token): bool
    {
        return is_array($token) && in_array($token[0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT], true);
    }
}
