<?php
/**
 * Alerts — Flash messages, inline alerts, and toast notifications.
 *
 * Standalone replacement for FlashMessages (PlasticBrain) + old MessageBox.
 * Backward-compatible alias: MessageBox
 */
declare(strict_types=1);

class Alerts
{
    // =====================================================================
    //  Constants
    // =====================================================================
    public const INFO    = 'i';
    public const SUCCESS = 's';
    public const WARNING = 'w';
    public const ERROR   = 'e';

    /** @deprecated Use type constants directly */
    public const defaultType = self::INFO;

    /** Map short type keys → human-readable names */
    private const TYPE_MAP = [
        self::ERROR   => 'error',
        self::WARNING => 'warning',
        self::SUCCESS => 'success',
        self::INFO    => 'info',
    ];

    /** * Unicode Standard Icons (Variation Selector \uFE0E forces monochrome text)
     */
   private const ICON_MAP = [
        'success' => "\u{2714}\u{FE0E}",  // ✔ (Heavy Check Mark)
        'error'   => "\u{2718}\u{FE0E}",  // ✘ (Heavy Ballot X)
        'warning' => "\u{26A0}\u{FE0E}",  // ⚠ (Warning Sign)
        'info'    => "\u{1F6C8}\u{FE0E}", // 🛈 (Circled Italic I)
    ];

    // =====================================================================
    //  Properties
    // =====================================================================

    private array $directMessages = [];
    private bool $useSession;
    private ?string $redirectUrl = null;
    public bool $showMissingKeys = true;

    // Backward-compat properties
    public string $closeBtn = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
    public string $msgWrapper = "<div class='%s'>%s</div>\n";
    public string $msgBefore  = '';
    public string $msgAfter   = '';
    public string $stickyCssClass = 'sticky';
    public string $msgCssClass    = 'alert dismissable';

    public array $cssClassMap = [
        self::INFO    => 'alert-info',
        self::SUCCESS => 'alert-success',
        self::WARNING => 'alert-warning',
        self::ERROR   => 'alert-danger',
    ];

    /** @deprecated Use the type constants instead */
    protected array $msgTypes = [
        self::ERROR   => 'error',
        self::WARNING => 'warning',
        self::SUCCESS => 'success',
        self::INFO    => 'info',
    ];

    public function __construct(bool $useSession = true)
    {
        $this->useSession = $useSession;
        if ($useSession && session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION['flash_messages'] ??= [];
        }
    }

    // =====================================================================
    //  Adding messages
    // =====================================================================

    public function success(string $message, mixed $redirectUrl = null, bool $sticky = false): self 
    { return $this->add($message, self::SUCCESS, $redirectUrl, $sticky); }

    public function error(string $message, mixed $redirectUrl = null, bool $sticky = false): self 
    { return $this->add($message, self::ERROR, $redirectUrl, $sticky); }

    public function warning(string $message, mixed $redirectUrl = null, bool $sticky = false): self 
    { return $this->add($message, self::WARNING, $redirectUrl, $sticky); }

    public function info(string $message, mixed $redirectUrl = null, bool $sticky = false): self 
    { return $this->add($message, self::INFO, $redirectUrl, $sticky); }

    public function sticky(string $message, mixed $redirectUrl = null, string $type = self::INFO): self
    { return $this->add($message, $type, $redirectUrl, true); }

    public function add(string $message, string $type = self::INFO, mixed $redirectUrl = null, bool $sticky = false): self
    {
        if ($message === '') return $this;

        $type = strtolower($type[0] ?? 'i');
        if (!isset(self::TYPE_MAP[$type])) $type = self::INFO;

        if ($type === self::ERROR) $sticky = true;

        $msgData = ['message' => $message, 'sticky' => $sticky];

        $this->directMessages[] = [
            'type'    => self::TYPE_MAP[$type],
            'message' => $message,
            'sticky'  => $sticky,
        ];

        if ($this->useSession && session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION['flash_messages'][$type][] = $msgData;
        }

        if (!empty($redirectUrl)) {
            $this->redirectUrl = (string) $redirectUrl;
            $this->doRedirect();
        }

        return $this;
    }

    // =====================================================================
    //  Output Methods
    // =====================================================================

    public function render(): string
    {
        $output = '';
        if ($this->useSession && session_status() === PHP_SESSION_ACTIVE && !empty($_SESSION['flash_messages'])) {
            foreach (array_keys(self::TYPE_MAP) as $typeKey) {
                if (empty($_SESSION['flash_messages'][$typeKey])) continue;
                $messages = [];
                foreach ($_SESSION['flash_messages'][$typeKey] as $msgData) {
                    $messages[] = $this->processTranslation($msgData['message']);
                }
                $output .= $this->renderMessages($messages, self::TYPE_MAP[$typeKey]);
                unset($_SESSION['flash_messages'][$typeKey]);
            }
        }

        $grouped = [];
        foreach ($this->directMessages as $msg) {
            $grouped[$msg['type']][] = $this->processTranslation($msg['message']);
        }
        foreach ($grouped as $typeName => $messages) {
            $output .= $this->renderMessages($messages, $typeName);
        }

        $this->directMessages = [];
        return $output;
    }

    public function renderFragment(): string
    {
        $output = '';
        $grouped = [];
        foreach ($this->directMessages as $msg) {
            $grouped[$msg['type']][] = $this->processTranslation($msg['message']);
        }
        foreach ($grouped as $typeName => $messages) {
            $output .= $this->renderMessages($messages, $typeName);
        }
        $this->directMessages = [];
        return $output;
    }

    public function fetchDisplay($types = null): string { return $this->render(); }

    public function display($types = null, bool $print = true): string|false
    {
        $output = $this->render();
        if ($print) { echo $output; return false; }
        return $output;
    }

    // =====================================================================
    //  Toast (floating notification via HX-Trigger)
    // =====================================================================

    public function toast(string $message, string $type = 'success', int $duration = 4000): void
    {
        if ($type === 'error') $duration = 0;
        $translated = $this->processTranslation($message);

        $existing = [];
        foreach (headers_list() as $header) {
            if (str_starts_with($header, 'HX-Trigger:')) {
                $existing = json_decode(substr($header, 12), true) ?? [];
                header_remove('HX-Trigger');
                break;
            }
        }

        $existing['showToast'] = [
            'message'  => $translated,
            'type'     => $type,
            'duration' => $duration,
            'icon'     => self::ICON_MAP[$type] ?? '',
        ];

        header('HX-Trigger: ' . json_encode($existing));
    }
    
    // =====================================================================
    //  Session-based Toasts (for redirect workflows without htmx)
    // =====================================================================

    /**
     * Queue a toast for the next page load (stored in session).
     *
     * Use this instead of toast() when doing a redirect.
     * HX-Trigger headers are lost on redirect, but session data survives.
     *
     * @param string $message   Message text (translation patterns supported)
     * @param string $type      'success', 'error', 'warning', 'info'
     * @param int    $duration  Auto-dismiss in ms (0 = sticky)
     */
    public function sessionToast(string $message, string $type = 'success', int $duration = 4000): self
    {
        if ($type === 'error') $duration = 0;

        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION['pending_toasts'][] = [
                'message'  => $this->processTranslation($message),
                'type'     => $type,
                'duration' => $duration,
            ];
        }

        return $this;
    }

    /**
     * Render any pending session toasts as <script> tags.
     *
     * Call this in the template or at the end of the page output.
     * Each pending toast is rendered as a showToast() JS call.
     * Toasts are cleared from session after rendering.
     *
     * @return string  Script tags calling showToast(), or empty string
     */
    /**
     * Render any pending session toasts as self-contained HTML+JS.
     *
     * Includes the toast container and showToast() function inline
     * if they haven't been loaded via _toast.inc.twig. This makes it
     * work in any context — admin tools, modules, standalone pages.
     *
     * @return string  HTML with script tags, or empty string
     */
    public function renderPendingToasts(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE
            || empty($_SESSION['pending_toasts'])) {
            return '';
        }

        $calls = '';
        foreach ($_SESSION['pending_toasts'] as $toast) {
            $msg      = addslashes($toast['message']);
            $type     = addslashes($toast['type']);
            $duration = (int) $toast['duration'];
            $icon     = addslashes(self::ICON_MAP[$toast['type']] ?? '');
            $calls   .= "  _showToast('{$msg}', '{$type}', {$duration}, '{$icon}');\n";
        }

        unset($_SESSION['pending_toasts']);

        if ($calls === '') return '';

        // Self-contained: check if showToast exists, if not provide minimal version
        return <<<HTML
<script>
(function() {
    // Use global showToast if available (_toast.inc.twig loaded),
    // otherwise create a minimal inline version
    var _showToast = (typeof window.showToast === 'function')
        ? window.showToast
        : function(message, type, duration, icon) {
            type     = type || 'success';
            duration = (typeof duration !== 'undefined') ? duration : 4000;
            icon     = icon || '';

            // Ensure container exists
            var container = document.getElementById('toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'toast-container';
                container.style.cssText = 'position:fixed;top:60px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:8px;pointer-events:none;max-width:420px;width:100%';
                document.body.appendChild(container);
            }

            var colors = {
                success: 'background:#d4edda;border-left:4px solid #28a745;color:#155724',
                error:   'background:#f8d7da;border-left:4px solid #dc3545;color:#721c24',
                warning: 'background:#fff3cd;border-left:4px solid #ffc107;color:#856404',
                info:    'background:#d1ecf1;border-left:4px solid #17a2b8;color:#0c5460'
            };

            var toast = document.createElement('div');
            toast.style.cssText = 'pointer-events:auto;min-width:280px;padding:14px 42px 14px 18px;border-radius:6px;box-shadow:0 4px 16px rgba(0,0,0,.18);font-size:.925em;line-height:1.4;position:relative;' + (colors[type] || colors.info);

            toast.innerHTML = (icon ? '<span style="margin-right:8px">' + icon + '</span>' : '')
                + '<span>' + message + '</span>'
                + '<button onclick="this.parentElement.remove()" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;font-size:1.3em;cursor:pointer;opacity:.5;color:inherit">\u00D7</button>';

            container.prepend(toast);

            if (duration > 0) {
                setTimeout(function() { toast.remove(); }, duration);
            }
        };

{$calls}})();
</script>

HTML;
    }

    // =====================================================================
    //  Status Queries
    // =====================================================================

    public function hasErrors(): bool
    {
        foreach ($this->directMessages as $msg) {
            if ($msg['type'] === 'error') return true;
        }
        return (session_status() === PHP_SESSION_ACTIVE) && !empty($_SESSION['flash_messages'][self::ERROR]);
    }

    public function hasMessages(?string $type = null): bool
    {
        if (!empty($this->directMessages)) {
            if ($type === null) return true;
            foreach ($this->directMessages as $msg) {
                if ($msg['type'] === $type) return true;
            }
        }

        if (session_status() !== PHP_SESSION_ACTIVE) return false;

        if ($type !== null) {
            $key = array_search($type, self::TYPE_MAP, true) ?: $type;
            return !empty($_SESSION['flash_messages'][$key]);
        }

        foreach (array_keys(self::TYPE_MAP) as $k) {
            if (!empty($_SESSION['flash_messages'][$k])) return true;
        }

        return false;
    }

    // =====================================================================
    //  Navigation & Session
    // =====================================================================

    public function redirect(string $url): void
    {
        $this->redirectUrl = $url;
        $this->doRedirect();
    }

    public function clear(string|array|null $types = null): self
    {
        if ($types === null) {
            if (session_status() === PHP_SESSION_ACTIVE) unset($_SESSION['flash_messages']);
            $this->directMessages = [];
        } else {
            if (session_status() === PHP_SESSION_ACTIVE) {
                foreach ((array) $types as $type) unset($_SESSION['flash_messages'][$type]);
            }
        }
        return $this;
    }

    public static function isHtmx(): bool
    {
        return !empty($_SERVER['HTTP_HX_REQUEST']);
    }

    // =====================================================================
    //  Translation Logic
    // =====================================================================

    public function L_(string $str): string { return $this->processTranslation($str); }

    protected function processTranslation(string $str): string
    {
        if (!str_contains($str, ':')) return $str;
        if (str_contains($str, '{')) {
            return preg_replace_callback('/\{([A-Z0-9_]+:[A-Z0-9_]+)(?:\|(\d+))?\}/', function ($m) {
                if (isset($m[2])) return function_exists('Ln_') ? Ln_($m[1], (int) $m[2]) : $m[0];
                return function_exists('L_') ? L_($m[1]) : $m[0];
            }, $str);
        }
        return (preg_match('/^[A-Z0-9_]+:[A-Z0-9_]+$/', $str) && function_exists('L_')) ? L_($str) : $str;
    }

    // =====================================================================
    //  Rendering Internals
    // =====================================================================

    protected function renderMessages(array $messages, string $type): string
    {
        if (!defined('WB_FRONTEND') && isset($GLOBALS['admin'])) {
            return $this->renderTwig($messages, $type);
        }
        return $this->renderHtml($messages, $type);
    }

    protected function renderTwig(array $messages, string $type): string
    {
        $toTwig = [
            'MESSAGES'     => $messages,
            'MESSAGE_TYPE' => $type,
            'UNICODE_ICON' => self::ICON_MAP[$type] ?? '',
        ];
        ob_start();
        $GLOBALS['admin']->getThemeFile('_message_box.inc.twig', $toTwig);
        return str_replace('alertbox_' . $type, 'alertbox_' . $type . ' dismissable', ob_get_clean());
    }

    protected function renderHtml(array $messages, string $type): string
    {
        $icon   = self::ICON_MAP[$type] ?? '';
        $sticky = ($type === 'error');
        $class  = 'alertbox_' . $type . ' dismissable' . ($sticky ? ' sticky' : '');
        $close  = $sticky ? '<button class="msg-close" onclick="this.parentElement.remove()">×</button>' : '';

        $body = '';
        foreach ($messages as $msg) { $body .= '<p>' . $msg . '</p>'; }

        return sprintf('<div class="%s">%s<span class="signal-icon">%s</span> %s</div>' . "\n", $class, $close, $icon, $body);
    }

    private function doRedirect(): void
    {
        if ($this->redirectUrl === null) return;
        if (!headers_sent()) { header('Location: ' . $this->redirectUrl); exit; }
        echo '<script>window.location="' . addslashes($this->redirectUrl) . '";</script>';
        exit;
    }
}