<?php
/**
 * Standalone LayoutParser
 * ────────────────────────────────────────────────────────────────────────────
 * 
 * A lightweight template parser for HTML layout strings stored in the
 * database or loaded from simple text based files (.htt).
 * 
 * Makes use of class Lang and LangPlural for easy translations.
 * 
 * @file       framework/LayoutParser.php
 * @package    Framework
 * @version    2.1.0 (adapted for use with WBCE CMS)
 * @author     Christian M. Stefan  (https://www.wbEasy.de)
 * @copyright  2025-2026 Christian M. Stefan
 * @copyright  2026 WBCE CMS Project
 * @license    GNU/GPL v2 https://www.gnu.org/licenses/gpl-2.0.html
 *
 * Zero dependencies. PHP 8.1+.
 *
 * ── Placeholders ────────────────────────────────────────────────────────────
 *  [KEY]
 *  [PREFIX.FIELD]                 dot notation — field inside a loop item
 *  [SETTINGS.DEBUG]               dot notation — nested top-level array access
 *  [KEY|filter]                   apply a predefined filter (a few are included)
 *  [KEY|filter1|filter2:"param"]  chain several filters, use parameters if provided
 *  [KEY|filter:VARIABLE.KEY]      unquoted variable reference as param (NEW)
 *                                 (allows for filter names coming from dynamic data)
 *
 * ── Dot notation ────────────────────────────────────────────────────────────
 *  Dots in key names provide three things:
 *
 *  1. Loop field access:
 *     {foreach ARTICLES as ARTICLE}
 *       [ARTICLE.TITLE]              field from the current iteration
 *       [ARTICLE._INDEX]             meta: 1-based position
 *       [ARTICLE._COUNT]             meta: total items
 *       [ARTICLE._FIRST]             meta: "1" on first iteration, "" otherwise
 *       [ARTICLE._LAST]              meta: "1" on last  iteration, "" otherwise
 *     {/foreach}
 *
 *  2. Nested array access in top-level data:
 *     $data = ['SETTINGS' => ['THEME' => 'dark']]
 *     [SETTINGS.THEME]  → "dark"
 *
 *     Resolution: fast flat-key lookup first (handles loop vars in O(1)),
 *     then dot-walk for nested arrays. Returns "" for missing keys.
 * 
 *  3. 
 *     (i18n) Lang and LangPlural integration:
 *     All language files that include their language Arrays (e.g. $TEXT, $MY_MOD)
 *     and are available in the global scope are accessible via dot notation in
 *     layouts like this:
 * 
 *     [TEXT.SAVE]                            → 'Speichern'  DE
 *     [MENU.PAGES]                           → 'Pages'      EN 
 *    
 *     // and plurals (if $MOD_FOO language array contains such key-value pairs):  
 *     [MOD_FOO.POST_COUNT|plural:5]        → '5 Posts'
 *     [MOD_FOO.POST_COUNT|plural:TOTAL]      → uses $data['TOTAL'] as count
 *     [MOD_FOO.POST_COUNT|plural:ART._COUNT] → uses loop meta-variable
 * 
 *     For this to work there is no need to pass those languages over to the 
 *     layout manually using pages. All that's needed is that the $MOD_FOO array
 *     is accessible in the global scope.
 * 
 * ── Conditionals ────────────────────────────────────────────────────────────
 *  {if KEY} … {/if}
 *  {if KEY} … {else} … {/if}
 *  {if KEY} … {elseif KEY2} … {else} … {/if}
 *  {ifnot KEY} … {/ifnot}
 *  Dotted keys work in all conditional forms: {if ARTICLE.ACTIVE}
 *
 *  Numeric operators (unquoted RHS):
 *    ==  !=  >  <  >=  <=
 *    {if PRODUCT.PRICE > 100}   {if ITEM.STOCK <= 0}
 *
 *  String equality (double-quoted RHS):
 *    ==  !=
 *    {if ARTICLE.STATUS == "published"}
 *
 *  String content operators (double-quoted RHS) — positive and negative:
 *    contains      / not-contains
 *    icontains     / not-icontains
 *    in            / not-in
 *    starts-with   / not-starts-with
 *    istarts-with  / not-istarts-with
 *    ends-with     / not-ends-with
 *    iends-with    / not-iends-with
 *
 *    {if ARTICLE.TAGS contains "Fotografie"}
 *    {if USER.ROLE not-in "admin,editor"}
 *    {if PAGE.SLUG not-starts-with "archive/"}
 *
 * ── Loops ────────────────────────────────────────────────────────────────────
 *  {foreach LIST as ITEM} … {/foreach}
 *  {foreach MAP as KEY => VALUE} … {/foreach}
 *
 *  Auto-injected per iteration (leading underscore = meta-variable):
 *    [ITEM._INDEX]   1-based position
 *    [ITEM._COUNT]   total items
 *    [ITEM._FIRST]   "1" on first iteration, "" otherwise
 *    [ITEM._LAST]    "1" on last  iteration, "" otherwise
 *
 * ── Defaults ────────────────────────────────────────────────────────────────
 *  {default KEY "fallback"}  /  {default KEY 'fallback'}  /  {default KEY word}
 *  Dotted keys work too: {default ARTICLE.AUTHOR "Anonymous"}
 *
 * ── Partials & Includes ──────────────────────────────────────────────────────
 *  Simple (inherits current scope):
 *    {partial NAME}
 *    {include "path/file.htt"} 
 *
 *  With extra data — additive (merged on top of inherited scope):
 *    {partial NAME with KEY="literal" KEY=DOTTED.VAR}
 *    {include "path/file.htt" with KEY="literal" KEY=DOTTED.VAR}
 *
 * ── Comments ─────────────────────────────────────────────────────────────────
 *  {# template comment #}       always stripped
 *  <!-- html comment -->       stripped only when setStripHtmlComments(true)
 *
 * ── Object support ───────────────────────────────────────────────────────────
 *  parse() and loop items 
 *  accept arrays, stdClass, or any object with toArray()
 *
 * ── Dump: Lookup keys, arrays and filters ────────────────────────────────────
 *    a convenient way to see what's available in a given Layout
 * 
 *  [_CONTEXT|dump]   all template variables (lang namespaces excluded)
 *  [_FILTERS|dump]   alphabetical list of registered filter names
 *  [_LANG|dump]      lang namespaces only: TEXT, MENU, MESSAGE, HINT, HEADING, OVERVIEW
 *  [KEY|dump]        print_r of any single variable (dotted keys work)
 *
 * ── Processing order (do not reorder) ────────────────────────────────────────
 *  comments → partials/includes → loops → conditionals → defaults → placeholders
 *
 */

// Prevent  this  file  from  being  accessed  directly
defined('WB_PATH') or die('No direct access allowed');

class LayoutParser
{
    // ── Properties ───────────────────────────────────────────────────────────

    /** @var array<string, callable> */
    private array $filters = [];

    /** @var array<string, string> */
    private array $partials = [];

    private string $partialsPath = '';
    private bool $stripHtmlComments = false;

    /**
     * Runtime globals registered via addGlobal() / addGlobals().
     * @var array<string, mixed>
     */
    private array $globals = [];

    /**
     * Active variable scope at the current point in the parse pipeline.
     * @var array|null
     */
    protected ?array $currentScope = null;

    // ── Constructor ───────────────────────────────────────────────────────────

    public function __construct(string $partialsPath = '')
    {
        $this->partialsPath = rtrim($partialsPath, '/\\');

        $this->filters = [

            // Case
            'upper'    => fn($v)               => strtoupper((string) $v),
            'lower'    => fn($v)               => strtolower((string) $v),
            'ucfirst'  => fn($v)               => ucfirst(strtolower((string) $v)),
            'ucwords'  => fn($v)               => ucwords(strtolower((string) $v)),

            // Whitespace / length
            'trim'     => fn($v)               => trim((string) $v),
            'truncate' => fn($v, $len = '80')  => mb_strlen((string) $v) > (int) $len
                              ? mb_substr((string) $v, 0, (int) $len) . '…'
                              : (string) $v,

            // Text formatting
            'nl2br'    => fn($v)               => nl2br(htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8')),
            'excerpt'  => fn($v, $len = '120') => mb_substr(strip_tags((string) $v), 0, (int) $len) . '…',
            'striphtml'=> fn($v)               => strip_tags((string) $v),

            // Numbers & dates
            'int'      => fn($v)               => (string)(int) $v,
            'float'    => fn($v, $dec = '2')   => number_format((float) $v, (int) $dec),
            'date'     => fn($v, $fmt = 'd.m.Y')  => $v ? date($fmt, strtotime((string) $v)) : '',
            'time'     => fn($v, $fmt = 'H:i')    => $v ? date($fmt, strtotime((string) $v)) : '',

            // HTML safety
            'escape'   => fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'),
            'raw'      => fn($v) => (string) $v,

            // ── plural (NEW) ──────────────────────────────────────────────────
            // Resolves a plural array from the Lang registry to the correct
            // form for a given count.
            //
            // $value    : the plural array from getRegistry(), e.g.
            //             ['one' => '%d Seite', 'other' => '%d Seiten']
            //             A plain string value is also accepted and sprintf'd.
            // $param    : the count — either a quoted literal ("5") or an
            //             unquoted variable key (TOTAL, ARTICLE._COUNT).
            //             Variable keys are resolved from $this->currentScope.
            // $isVarRef : true when $param was specified without quotes —
            //             signals that $param should be treated as a scope key.
            //
            // Usage in templates:
            //   [TEXT.PAGE_COUNT|plural:"1"]            → '1 Seite'
            //   [TEXT.PAGE_COUNT|plural:"5"]            → '5 Seiten'
            //   [TEXT.PAGE_COUNT|plural:TOTAL]          → resolves $data['TOTAL']
            //   [TEXT.PAGE_COUNT|plural:ARTICLE._COUNT] → resolves loop meta-var
            'plural' => function(mixed $val, ?string $param = null, bool $isVarRef = false): string {
                // Resolve the count
                $count = 0;
                if ($param !== null) {
                    if ($isVarRef && $this->currentScope !== null) {
                        $count = (int)($this->_resolveDotKey($param, $this->currentScope) ?? 0);
                    } else {
                        $count = (int)$param;
                    }
                }

                // Plain string value — just sprintf the count in
                if (!is_array($val)) {
                    return sprintf((string)$val, $count);
                }

                // Optional explicit 'zero' key — takes priority over CLDR rules
                if ($count === 0 && isset($val['zero'])) {
                    return sprintf((string)$val['zero'], $count);
                }

                // Pick the correct CLDR plural category
                // Requires Lang and LangPlural to be loaded
                $category = class_exists('LangPlural')
                    ? LangPlural::category(
                          class_exists('Lang') ? Lang::getLocale() : 'EN',
                          $count
                      )
                    : ($count === 1 ? 'one' : 'other');

                $form = $val[$category] ?? $val['other'] ?? $val['one'] ?? reset($val);
                return sprintf((string)$form, $count);
            },

            // Debug
            'dump' => fn(mixed $value, ?string $param = null): string
                => $this->_printDebug($value, $param),
        ];
    }

    // ── Public API — Configuration ────────────────────────────────────────────

    public function addFilter(string $name, callable $fn): static
    {
        $this->filters[strtolower($name)] = $fn;
        return $this;
    }

    public function addPartial(string $name, string $html): static
    {
        $this->partials[strtoupper($name)] = $html;
        return $this;
    }

    public function setPartialsPath(string $path): static
    {
        $this->partialsPath = rtrim($path, '/\\');
        return $this;
    }

    public function setStripHtmlComments(bool $value): static
    {
        $this->stripHtmlComments = $value;
        return $this;
    }

    public function addGlobal(string $key, mixed $value): static
    {
        $this->globals[strtoupper($key)] = $value;
        return $this;
    }

    public function addGlobals(array $globals): static
    {
        foreach ($globals as $key => $value) {
            $this->globals[strtoupper($key)] = $value;
        }
        return $this;
    }

    protected function _defaultGlobals(): array
    {
        $defaults = [
            'TEMPLATE_URL'  => (defined('TEMPLATE') && defined('WB_URL'))
                                    ? WB_URL . '/templates/' . TEMPLATE
                                    : '',
            'WB_URL'        => defined('WB_URL')   ? WB_URL   : '',
            'ADMIN_URL'     => defined('ADMIN_URL') ? ADMIN_URL : '',
        ];

        // Automatically merge the full Lang registry if Lang is loaded.
        // This makes [TEXT.SAVE], [MENU.PAGES], [MOD_NEWS.TITLE] etc. available
        // in every template without any manual addGlobals() call.
        // Lang data has lower priority than $data passed to parse() —
        // a key in $data always overrides a translation of the same name.
        if (class_exists('Lang', false)) {
            $defaults = array_merge(Lang::getRegistry(), $defaults);
        }

        return $defaults;
    }

    // ── Public API — Parse ────────────────────────────────────────────────────

    public function parse(string $layout, array|object $data): string
    {
        $merged = array_merge(
            $this->_uppercaseKeys($this->_defaultGlobals()),
            $this->_uppercaseKeys($this->globals),
            $this->_uppercaseKeys($this->_normalize($data))
        );

        $previousScope      = $this->currentScope;
        $this->currentScope = $merged;

        $html = $layout;
        $html = $this->_stripComments($html);
        $html = $this->_resolvePartials($html, $this->currentScope);
        $html = $this->_resolveLoops($html, $this->currentScope);
        $html = $this->_resolveConditionals($html, $this->currentScope);
        $html = $this->_resolveDefaults($html, $this->currentScope);
        $html = $this->_resolvePlaceholders($html, $this->currentScope);

        $this->currentScope = $previousScope;

        return $html;
    }

    // ── Private — Key resolution ──────────────────────────────────────────────

    private function _resolveDotKey(string $key, array $data, bool $raw = false): mixed
    {
        if (array_key_exists($key, $data)) {
            $value = $data[$key];
            if ($raw) { return $value; }
            return (is_scalar($value) || $value === null) ? $value : null;
        }

        if (str_contains($key, '.')) {
            $parts   = explode('.', $key);
            $current = $data;

            foreach ($parts as $part) {
                if (is_array($current) && array_key_exists($part, $current)) {
                    $current = $current[$part];
                } else {
                    return null;
                }
            }

            if ($raw) { return $current; }
            return (is_scalar($current) || $current === null) ? $current : null;
        }

        return null;
    }

    // ── Private — Normalization ───────────────────────────────────────────────

    private function _normalize(mixed $value): array
    {
        if (is_array($value)) return $value;
        if (is_object($value)) {
            return method_exists($value, 'toArray') ? $value->toArray() : (array) $value;
        }
        return [];
    }

    private function _uppercaseKeys(array $data): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            $upperKey          = is_string($key) ? strtoupper($key) : $key;
            $result[$upperKey] = is_array($value) ? $this->_uppercaseKeys($value) : $value;
        }
        return $result;
    }

    // ── Private — Parse `with` attribute ─────────────────────────────────────

    private function _parseWith(string $withStr, array $scope): array
    {
        $withStr = trim($withStr);
        if ($withStr === '') return $scope;

        $extra = [];
        preg_match_all(
            '~([A-Z_][A-Z0-9_]*)=(?:"([^"]*)"|([ A-Z_][A-Z0-9_.]*))~',
            $withStr, $matches, PREG_SET_ORDER
        );

        foreach ($matches as $m) {
            $extra[$m[1]] = ($m[2] !== '')
                ? $m[2]
                : (string)($this->_resolveDotKey(trim($m[3]), $scope) ?? '');
        }

        return array_merge($scope, $extra);
    }

    // ── Private pipeline — Step 0: Comments ──────────────────────────────────

    private function _stripComments(string $html): string
    {
        $html = preg_replace('~\{#.*?#\}~s', '', $html);
        if ($this->stripHtmlComments) {
            $html = preg_replace('~<!--.*?-->~s', '', $html);
        }
        return $html;
    }

    // ── Private pipeline — Step 1: Partials & Includes ───────────────────────

    private function _resolvePartials(string $html, array $data): string
    {
        $html = preg_replace_callback(
            '~\{partial\s+([A-Z_]+)(?:\s+with\s+(.*?))?\s*\}~s',
            function ($m) use ($data) {
                $name  = strtoupper($m[1]);
                $scope = $this->_parseWith($m[2] ?? '', $data);
                if (!isset($this->partials[$name])) {
                    return "<!-- partial '{$name}' not registered -->";
                }
                return $this->parse($this->partials[$name], $scope);
            },
            $html
        );

        if ($this->partialsPath !== '') {
            $html = preg_replace_callback(
                '~\{include\s+"([^"]+)"(?:\s+with\s+(.*?))?\s*\}~s',
                function ($m) use ($data) {
                    $safeName = str_replace(['../', '..\\', "\0"], '', $m[1]);
                    $fullPath = $this->partialsPath . DIRECTORY_SEPARATOR . ltrim($safeName, '/\\');
                    $scope    = $this->_parseWith($m[2] ?? '', $data);
                    if (!is_file($fullPath)) {
                        return "<!-- include '{$safeName}' not found -->";
                    }
                    return $this->parse(file_get_contents($fullPath), $scope);
                },
                $html
            );
        }

        return $html;
    }

    // ── Private pipeline — Step 2: Loops ─────────────────────────────────────

    private function _resolveLoops(string $html, array $data): string
    {
        return preg_replace_callback(
            '~\{foreach\s+([A-Z_]+)\s+as\s+(?:([A-Z_]+)\s*=>\s*)?([A-Z_]+)\s*\}\s*(.*?)\s*\{/foreach\}~s',
            function ($m) use ($data) {
                $listName = $m[1];
                $keyAlias = $m[2] !== '' ? strtoupper($m[2]) : null;
                $prefix   = strtoupper($m[3]);
                $blockTpl = $m[4];

                $items = $data[$listName] ?? [];
                if (!is_iterable($items) || empty($items)) return '';

                $total  = count($items);
                $output = '';
                $index  = 0;

                foreach ($items as $mapKey => $item) {
                    if (is_scalar($item) || $item === null) {
                        $item = ['' => $item, 'VALUE' => $item];
                    } else {
                        $item = $this->_normalize($item);
                    }

                    $scopeData = $data;
                    if ($keyAlias !== null) {
                        $scopeData[$keyAlias] = (string) $mapKey;
                    }

                    $scopeData[$prefix . '._INDEX'] = $index + 1;
                    $scopeData[$prefix . '._COUNT'] = $total;
                    $scopeData[$prefix . '._FIRST'] = ($index === 0)          ? '1' : '';
                    $scopeData[$prefix . '._LAST']  = ($index === $total - 1) ? '1' : '';

                    foreach ($item as $k => $v) {
                        $k        = strtoupper((string) $k);
                        $fieldKey = ($k === '') ? $prefix : $prefix . '.' . $k;
                        $scopeData[$fieldKey] = $v;
                    }

                    $output .= $this->parse($blockTpl, $scopeData);
                    $index++;
                }

                return $output;
            },
            $html
        );
    }

    // ── Private pipeline — Step 3: Conditionals ──────────────────────────────

    private function _resolveConditionals(string $html, array $data): string
    {
        $inner    = '(?:(?!\{(?:if|ifnot)[\s_])[\s\S])*?';
        $previous = null;

        while ($previous !== $html) {
            $previous = $html;

            $html = preg_replace_callback(
                '~\{if\s+([^\}]+)\}
                  (' . $inner . ')
                  ((?:\{elseif\s+[^\}]+\}' . $inner . ')*)
                  (?:\{else\}(' . $inner . '))?
                  \{/if\}~x',
                function ($m) use ($data, $inner) {
                    $thenBlock   = $m[2];
                    $elseifChain = $m[3];
                    $elseBlock   = $m[4] ?? '';

                    if ($this->_evaluateCondition(trim($m[1]), $data)) return $thenBlock;

                    if ($elseifChain !== '') {
                        preg_match_all(
                            '~\{elseif\s+([^\}]+)\}([\s\S]*?)(?=\{elseif\s|\z)~',
                            $elseifChain, $branches, PREG_SET_ORDER
                        );
                        foreach ($branches as $branch) {
                            if ($this->_evaluateCondition(trim($branch[1]), $data)) {
                                return $branch[2];
                            }
                        }
                    }

                    return $elseBlock;
                },
                $html
            );

            $html = preg_replace_callback(
                '~\{ifnot\s+([A-Z_][A-Z0-9_.]*)\s*\}(' . $inner . ')\{/ifnot\}~x',
                function ($m) use ($data) {
                    return empty($this->_resolveDotKey($m[1], $data)) ? $m[2] : '';
                },
                $html
            );
        }

        return $html;
    }

    private function _evaluateCondition(string $condStr, array $data): bool
    {
        $keyPat = '[A-Z_][A-Z0-9_.]*';

        if (preg_match(
            '~^(' . $keyPat . ')\s+
              (not-icontains|not-contains|not-in|
               not-istarts-with|not-starts-with|
               not-iends-with|not-ends-with|
               icontains|contains|in|
               istarts-with|starts-with|
               iends-with|ends-with)
              \s+"([^"]*)"$~x',
            $condStr, $m
        )) {
            $actual = (string)($this->_resolveDotKey($m[1], $data) ?? '');
            $op     = $m[2];
            $rhs    = $m[3];
            $negate = str_starts_with($op, 'not-');
            $baseOp = $negate ? substr($op, 4) : $op;

            $match = match($baseOp) {
                'contains'    => str_contains($actual, $rhs),
                'icontains'   => str_contains(strtolower($actual), strtolower($rhs)),
                'in'          => in_array($actual, array_map('trim', explode(',', $rhs)), strict: true),
                'starts-with' => str_starts_with($actual, $rhs),
                'istarts-with'=> str_starts_with(strtolower($actual), strtolower($rhs)),
                'ends-with'   => str_ends_with($actual, $rhs),
                'iends-with'  => str_ends_with(strtolower($actual), strtolower($rhs)),
            };

            return $negate ? !$match : $match;
        }

        if (preg_match(
            '~^(' . $keyPat . ')\s*(==|!=|>=|<=|>|<)\s*(?:"([^"]*)"|([-]?\d+\.?\d*))$~',
            $condStr, $m
        )) {
            $actual   = (string)($this->_resolveDotKey($m[1], $data) ?? '');
            $op       = $m[2];
            $isQuoted = ($m[4] === '');
            $rhs      = $isQuoted ? $m[3] : $m[4];

            if ($isQuoted && ($op === '==' || $op === '!=')) {
                return ($op === '==') ? ($actual === $rhs) : ($actual !== $rhs);
            }

            $left  = (float) $actual;
            $right = (float) $rhs;

            return match($op) {
                '==' => $left === $right,
                '!=' => $left !== $right,
                '>'  => $left >   $right,
                '<'  => $left <   $right,
                '>=' => $left >=  $right,
                '<=' => $left <=  $right,
            };
        }

        if (preg_match('~^(' . $keyPat . ')$~', $condStr, $m)) {
            return !empty($this->_resolveDotKey($m[1], $data));
        }

        return false;
    }

    // ── Private pipeline — Step 4: Inline defaults ───────────────────────────

    private function _resolveDefaults(string $html, array $data): string
    {
        return preg_replace_callback(
            '~\{default\s+([A-Z_][A-Z0-9_.]*)\s+(?:"([^"]*)"|\'([^\']*)\'|([^\s\}]+))\s*\}~',
            function ($m) use ($data) {
                $fallback = $m[2] !== '' ? $m[2] : ($m[3] !== '' ? $m[3] : $m[4]);
                $value    = $this->_resolveDotKey($m[1], $data);
                return !empty($value) ? (string) $value : $fallback;
            },
            $html
        );
    }

    // ── Private pipeline — Step 5: Placeholders & filters ────────────────────

    private function _resolvePlaceholders(string $html, array $data): string
    {
        return preg_replace_callback(
            '~\[([A-Z_][A-Z0-9_.]*)((?:\|[a-z]+(?::(?:"[^"]*"|\d+|[A-Z_][A-Z0-9_.]*))?)+)?\]~',
            function ($m) use ($data) {
                $key         = $m[1];
                $filterStr   = $m[2] ?? '';
                $filterChain = $this->_parseFilterChain($filterStr);

                if ($key === '_CONTEXT' || $key === '_FILTERS' || $key === '_LANG') {

                    // Namespaces that belong to _LANG, not _CONTEXT
                    $langKeys = ['TEXT', 'MESSAGE', 'HINT', 'MENU', 'HEADING', 'OVERVIEW'];

                    $rawValue = match ($key) {
                        '_CONTEXT' => array_filter(
                            $this->currentScope ?? [],
                            fn($k) => !in_array($k, $langKeys, true),
                            ARRAY_FILTER_USE_KEY
                        ),
                        '_LANG'    => array_intersect_key(
                            $this->currentScope ?? [],
                            array_flip($langKeys)
                        ),
                        '_FILTERS' => (function () {
                            $n = array_keys($this->filters); sort($n); return $n;
                        })(),
                    };

                    $dumpParam = null;
                    foreach ($filterChain as [$name, $param]) {
                        if ($name === 'dump') { $dumpParam = $param; break; }
                    }

                    return $this->_printDebug($rawValue, $dumpParam, $key);
                }

                $hasDump   = false;
                $dumpParam = null;
                $hasPlural = false;
                foreach ($filterChain as [$fname, $fparam]) {
                    if ($fname === 'dump')   { $hasDump = true; $dumpParam = $fparam; break; }
                    if ($fname === 'plural') { $hasPlural = true; }
                }

                if ($hasDump) {
                    $rawValue = $this->_resolveDotKey($key, $data, raw: true);
                    return $this->_printDebug($rawValue ?? '', $dumpParam, $key);
                }

                // plural filter needs the raw value (array) — bypass the scalar guard
                if ($hasPlural) {
                    $rawValue = $this->_resolveDotKey($key, $data, raw: true);
                    return $this->_applyFilters($rawValue ?? '', $filterChain, $key, $data);
                }

                $value = (string)($this->_resolveDotKey($key, $data) ?? '');
                return $this->_applyFilters($value, $filterChain, $key, $data);
            },
            $html
        );
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    /**
     * Parse a filter chain string into an array of [name, param, isVarRef] tuples.
     *
     * Recognises two param forms:
     *   |filter:"quoted literal"  → param = 'quoted literal',  isVarRef = false
     *   |filter:VARIABLE.KEY      → param = 'VARIABLE.KEY',    isVarRef = true
     *   |filter                   → param = null,              isVarRef = false
     *
     * isVarRef = true signals that the param is a scope key to be resolved
     * at filter-application time, not a literal value. Used by the |plural
     * filter to resolve dynamic counts like ARTICLE._COUNT.
     *
     * @return array<array{0: string, 1: string|null, 2: bool}>
     */
    private function _parseFilterChain(string $filterStr): array
    {
        if ($filterStr === '') return [];

        $chain = [];

        // CHANGE 1: Extended regex — captures both quoted literals and unquoted var refs
        // Group 1: filter name
        // Group 2: quoted literal param (inside "…")
        // Group 3: unquoted variable reference (UPPER_SNAKE or dotted UPPER.SNAKE)
        preg_match_all(
            '~\|([a-z]+)(?::(?:"([^"]*)"|(\d+|[A-Z_][A-Z0-9_.]*)))?~',
            $filterStr, $matches, PREG_SET_ORDER
        );

        foreach ($matches as $match) {
            $quoted   = isset($match[2]) && $match[2] !== '' ? $match[2] : null;
            $unquoted = isset($match[3]) && $match[3] !== '' ? $match[3] : null;
            $param    = $quoted ?? $unquoted;
            $isVarRef = ($unquoted !== null);

            $chain[] = [$match[1], $param, $isVarRef];
        }

        return $chain;
    }

    /**
     * Apply a filter chain to a string value.
     *
     * CHANGE 2: Accepts $scope parameter for resolving variable-ref params.
     * CHANGE 3: Passes isVarRef flag as third argument to filter callables.
     *
     * Filter callable signature:
     *   fn(mixed $value, ?string $param = null, bool $isVarRef = false): string
     *
     * Most built-in filters ignore $isVarRef — only |plural uses it.
     *
     * @param array<array{0: string, 1: string|null, 2: bool}> $filterChain
     */
    private function _applyFilters(string $value, array $filterChain, string $heading = '', array $scope = []): string
    {
        $skipEscape = false;

        foreach ($filterChain as [$name, $param, $isVarRef]) {
            if ($name === 'raw') {
                $skipEscape = true;
                continue;
            }

            if ($name === 'escape') {
                $value      = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                $skipEscape = true;
                continue;
            }

            if ($name === 'dump') {
                $value      = $this->_printDebug($value, $param, $heading);
                $skipEscape = true;
                continue;
            }

            if (isset($this->filters[$name])) {
                $fn = $this->filters[$name];
                // CHANGE 3: pass isVarRef as third argument
                // For plural: resolves $param as a scope key when isVarRef=true
                // For all other filters: third arg is ignored (variadic safety)
                $value = (string)($param !== null
                    ? $fn($value, $param, $isVarRef)
                    : $fn($value));
            }
        }

        if (!$skipEscape) {
            $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }

        return $value;
    }

    private function _printDebug(mixed $value, ?string $param = null, string $heading = ''): string
    {
        $isDetail = ($param === 'detail');
        $label    = $heading !== '' ? '[' . $heading . ']' : 'Debug';

        ob_start();

        if (function_exists('dump')) {
            dump($value, $label, $isDetail);
        } else {
            echo '<details style="margin:1em 0;border:1px solid #dee2e6;border-radius:4px;'
               . 'font-family:monospace;font-size:13px;background:#f8f9fa;">';
            echo '<summary style="cursor:pointer;padding:.5rem 1rem;font-weight:600;'
               . 'color:#1247d8;list-style:none;">Debug: '
               . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</summary>';
            echo '<pre style="margin:0;padding:1rem;max-height:600px;overflow:auto;'
               . 'white-space:pre-wrap;color:#c01727;background:#fffeed;'
               . 'border-top:1px solid #dee2e6;">';
            $isDetail ? var_dump($value) : print_r($value);
            echo '</pre></details>';
        }

        return ob_get_clean();
    }
}