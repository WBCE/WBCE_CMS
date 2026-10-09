<?php
/**
 * MdReaderLink
 *
 * Fluent builder for MarkdownWbce popup links.
 * Available FE+BE once MarkdownWbce is installed, via initialize.php — no
 * manual require needed. reader.php itself still requires a valid backend
 * Admin session, so a link rendered on a live frontend page only works for
 * an editor already logged into the backend in the same browser (see
 * modules/MarkdownWbce/docs/README.md for the read/write permission model).
 *
 * ── Usage ────────────────────────────────────────────────────────────────────
 *
 *  // Variante A — single file (most common)
 *  echo MdReaderLink::file('/modules/my_mod/docs/README.md')
 *              ->title('My Module')
 *              ->linkHtml('Documentation');
 *
 *  // Absolute WB_PATH also accepted — converted automatically
 *  echo MdReaderLink::file(WB_PATH . '/modules/my_mod/docs/README.md')
 *              ->linkHtml('Documentation');
 *
 *  // Variante B — multiple files with tabs
 *  echo MdReaderLink::docs([
 *          ['path' => '/modules/my_mod/docs/README.md',    'label' => 'Overview'],
 *          ['path' => '/modules/my_mod/docs/CHANGELOG.md', 'label' => 'Changelog'],
 *      ])
 *      ->title('My Module')
 *      ->linkHtml('Documentation');
 *
 *  // Variante D — directory (reads md_reader.json automatically)
 *  echo MdReaderLink::dir('/modules/my_mod/docs/')
 *              ->linkHtml('Documentation');
 *
 *  // URL only — when you want to build your own <a> tag
 *  $url = MdReaderLink::file('/modules/my_mod/docs/README.md')->url();
 *
 * ── Popup defaults ───────────────────────────────────────────────────────────
 *  Width  : 1100px
 *  Height : 820px
 *  Toolbar, menubar, location bar: all off (user cannot edit the URL)
 *
 * @package  MarkdownWbce
 * @author   Christian M. Stefan (https://www.wbEasy.de/)
 * @version  0.1.0
 */
defined('WB_PATH') or die('No direct access allowed');

class MdReaderLink
{
    // ── Internal state ────────────────────────────────────────────────────────

    /** @var string  'file' | 'docs' | 'dir' */
    private string $mode;

    /** @var string  Relative path for mode=file */
    private string $filePath = '';

    /** @var array   [{path, label}, …] for mode=docs */
    private array $docsArray = [];

    /** @var string  Relative directory path for mode=dir */
    private string $dirPath = '';

    /** @var string  Window / page title */
    private string $title = '';

    /** @var int  Popup width in px */
    private int $popupWidth = 1100;

    /** @var int  Popup height in px */
    private int $popupHeight = 820;

    // ── Constructor (private — use static factories) ──────────────────────────

    private function __construct(string $mode)
    {
        $this->mode = $mode;
    }

    // ── Static factories ──────────────────────────────────────────────────────

    /**
     * Variante A — single Markdown file.
     *
     * Accepts either a path relative to WB_PATH ('/modules/…/README.md')
     * or an absolute filesystem path (WB_PATH . '/modules/…/README.md').
     * Absolute paths are converted to relative automatically.
     */
    public static function file(string $path): self
    {
        $obj = new self('file');
        $obj->filePath = self::_normalise($path);
        return $obj;
    }

    /**
     * Variante B — multiple files shown as tabs.
     *
     * Each entry: ['path' => '/modules/…/file.md', 'label' => 'Tab label']
     * Absolute paths in 'path' are normalised automatically.
     *
     * @param array<array{path: string, label?: string}> $docs
     */
    public static function docs(array $docs): self
    {
        $obj = new self('docs');
        foreach ($docs as $doc) {
            $obj->docsArray[] = [
                'path'  => self::_normalise($doc['path'] ?? ''),
                'label' => $doc['label'] ?? basename($doc['path'] ?? ''),
            ];
        }
        return $obj;
    }

    /**
     * Variante D — directory containing an md_reader.json manifest.
     * Falls back to README.md in that directory when no manifest is found.
     *
     * Accepts relative or absolute paths.
     */
    public static function dir(string $path): self
    {
        $obj = new self('dir');
        $obj->dirPath = rtrim(self::_normalise($path), '/') . '/';
        return $obj;
    }

    // ── Fluent setters ────────────────────────────────────────────────────────

    /**
     * Set the popup window title (shown in the MDR header and <title>).
     * When omitted, MDR derives the title from the filename or manifest.
     */
    public function title(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Override the popup dimensions (default: 1100 × 820).
     */
    public function popupSize(int $width, int $height): self
    {
        $this->popupWidth  = $width;
        $this->popupHeight = $height;
        return $this;
    }

    // ── Output methods ────────────────────────────────────────────────────────

    /**
     * Returns the raw reader.php URL (no HTML, no popup JS).
     * Use this when you want to build your own <a> or trigger.
     */
    public function url(): string
    {
        $base   = WB_URL . '/modules/MarkdownWbce/reader.php';
        $params = $this->_buildParams();
        return $base . '?' . http_build_query($params, '', '&');
    }

    /**
     * Returns a complete <a> tag that opens the MDR as a popup.
     *
     * @param string $label   Link text (HTML-escaped automatically)
     * @param string $class   Optional CSS class for the <a> tag
     * @param string $attrs   Optional extra HTML attributes, e.g. 'data-foo="bar"'
     */
    public function linkHtml(string $label, string $class = '', string $attrs = ''): string
    {
        $url      = htmlspecialchars($this->url(), ENT_QUOTES, 'UTF-8');
        $label    = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
        $classAttr = $class ? ' class="' . htmlspecialchars($class, ENT_QUOTES, 'UTF-8') . '"' : '';
        $attrsStr  = $attrs ? ' ' . $attrs : '';
        $js        = $this->_popupJs();

        return '<a href="' . $url . '"'
             . $classAttr
             . $attrsStr
             . ' onclick="' . htmlspecialchars($js, ENT_QUOTES, 'UTF-8') . '">'
             . $label
             . '</a>';
    }

    /**
     * Returns a <button> element that opens the MDR popup.
     *
     * @param string $label  Button text (HTML-escaped automatically)
     * @param string $class  Optional CSS class
     */
    public function buttonHtml(string $label, string $class = ''): string
    {
        $url       = htmlspecialchars($this->url(), ENT_QUOTES, 'UTF-8');
        $label     = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
        $classAttr = $class ? ' class="' . htmlspecialchars($class, ENT_QUOTES, 'UTF-8') . '"' : '';
        $js        = $this->_popupJs();

        return '<button type="button"'
             . $classAttr
             . ' onclick="' . htmlspecialchars($js, ENT_QUOTES, 'UTF-8') . '">'
             . $label
             . '</button>';
    }

    /**
     * Returns just the popup onclick JS (window.open(...);return false;),
     * without the surrounding <a>/<button> tag — for templates that already
     * have their own separate href/onclick slots (e.g.
     * onclick="{{ tpl_help_onclick }}" next to href="{{ tpl_help_url }}").
     */
    public function popupOnclick(): string
    {
        return $this->_popupJs();
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    /**
     * Convert absolute WB_PATH-based path to relative (/modules/…).
     * Relative paths (starting with '/') are returned as-is.
     */
    private static function _normalise(string $path): string
    {
        $path = str_replace('\\', '/', $path);
        $root = str_replace('\\', '/', WB_PATH);

        // Absolute path that starts with WB_PATH → strip prefix
        if (str_starts_with($path, $root)) {
            $path = substr($path, strlen($root));
        }

        // Ensure leading slash
        return '/' . ltrim($path, '/');
    }

    /**
     * Build the GET parameter array for the current mode.
     * @return array<string, string>
     */
    private function _buildParams(): array
    {
        $params = [];

        switch ($this->mode) {
            case 'file':
                $params['doc'] = $this->filePath;
                break;

            case 'docs':
                $params['docs'] = json_encode($this->docsArray, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                break;

            case 'dir':
                $params['dir'] = $this->dirPath;
                break;
        }

        if ($this->title !== '') {
            $params['title'] = $this->title;
        }

        return $params;
    }

    /**
     * Build the inline onclick JS string for window.open().
     * noopener prevents the popup from accessing window.opener.
     */
    private function _popupJs(): string
    {
        $url = $this->url();
        $w   = $this->popupWidth;
        $h   = $this->popupHeight;

        return "window.open('{$url}','mdr_popup',"
             . "'width={$w},height={$h},"
             . "toolbar=no,menubar=no,location=no,"
             . "scrollbars=yes,resizable=yes,noopener')"
             . ";return false;";
    }
}
