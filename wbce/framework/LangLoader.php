<?php
/**
 * Internationalization — LangLoader
 *
 * Global translation functions adopted for use in WBCE CMS.
 * Provides L_() for string translation and Ln_() for plural-aware
 * translation, along with internal helpers for token parsing and
 * language file inclusion. 
 *
 * @author  Christian M. Stefan  (https://www.wbEasy.de)
 * @copyright Copyright (c) 2025-2026 Christian M. Stefan
 * @copyright Copyright (c) 2026 WBCE CMS Project
 * @license   GNU General Public License version 2 or later (GPL-2.0-or-later)
 *            https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @note      _wbe_lang_include() must remain a standalone function (not a
 *            class method) so that get_defined_vars() correctly captures
 *            all variables assigned by included language files.
 *
 *  -----------------------------------------------------------------------------
 *
 * ── SYNTAX ───────────────────────────────────────────────────────────────────
 *
 * All of the following forms are equivalent and resolve identically:
 *
 *   MENU['MEDIA']   ← recommended: copy-paste directly from language file
 *   MENU["MEDIA"]   ← double-quoted variant
 *   MENU[MEDIA]     ← unquoted bracket
 *   MENU:MEDIA      ← colon
 *   {MENU:MEDIA}    ← brace-colon
 *
 * ── L_() ─────────────────────────────────────────────────────────────────────
 *
 *   L_("TEXT['SAVE']")                                  → 'Speichern'
 *   L_("TEXT['DELETE'] TEXT['PAGE']")                   → 'Löschen Seite'
 *   L_("TEXT['MISSING']||Default text")                 → 'Default text' if absent
 *   L_("TEXT['DELETE_RECORD']", 'My Page')              → 'My Page löschen'
 *   L_("TEXT['DELETE_RECORD']", "TEXT['PAGE']", 'Page') → arg as key, 'Page' fallback
 *   L_("MSG['CREATED_BY']", 'Alice', '2026-03-07')      → positional %1$s %2$s
 *
 * ── Ln_() ────────────────────────────────────────────────────────────────────
 *
 *   Ln_("TEXT['PAGE_COUNT']", 5)                        → '5 Seiten'
 *   Ln_("TEXT['PAGE_COUNT']||%d pages", 5)              → fallback if key absent
 */

// Prevent  this  file  from  being  accessed  directly
defined('WB_PATH') or die('No direct access allowed');

if (!function_exists('L_')) {
    
require_once __DIR__.'/Lang.php';        // Load class Lang independently of WbAuto
require_once __DIR__.'/LangPlural.php';  // Load plural rules independently of WbAuto
    
function L_(string $str, mixed ...$args): string
{
    $resolved = _wbe_resolve($str);

    // No args provided — if the resolved string still has %s placeholders,
    // fill them with the translator hint from the key ('record|Delete %s' → 'record').
    // This makes L_("TEXT['DELETE_RECORD']") render as "Delete record" rather
    // than leaving a bare %s or an empty gap in the output.
    if (empty($args)) {
        if (str_contains($resolved, '%s') || str_contains($resolved, '%d')) {
            [$ns, $key] = _wbe_parse_key($str);
            $hint = ($ns && $key) ? Lang::getHint($ns, $key) : '';
            if ($hint !== '') {
                return _wbe_sprintf($resolved, [$hint]);
            }
        }
        return $resolved;
    }

    // Process args — if an arg looks like a translation key, resolve it.
    // On miss: consume next plain-string arg as fallback, or use hint, or auto-generate.
    $processed = [];
    $i         = 0;
    $count     = count($args);

    while ($i < $count) {
        $arg = $args[$i];

        if (is_string($arg) && _wbe_is_key($arg)) {
            [$ns, $key] = _wbe_parse_key($arg);

            if (!Lang::has($ns, $key)) {
                // 1. Next plain-string arg as explicit fallback
                $next = $args[$i + 1] ?? null;
                if ($next !== null && is_string($next) && !_wbe_is_key($next)) {
                    $processed[] = $next;
                    $i += 2;
                    continue;
                }
                // 2. Hint from the main string key ('record|Delete %s' → 'record')
                [$mns, $mkey] = _wbe_parse_key($str);
                $hint         = ($mns && $mkey) ? Lang::getHint($mns, $mkey) : '';
                $processed[]  = $hint !== '' ? $hint : ucfirst(strtolower(str_replace('_', ' ', $key)));
            } else {
                $processed[] = Lang::get($ns, $key);
            }
        } else {
            $processed[] = (string)$arg;
        }

        $i++;
    }

    return _wbe_sprintf($resolved, $processed);
}

function Ln_(string|array $str, int $n): string
{
    // Raw variable usage: Ln_($TEXT['USERS_AMOUNT'], 5)
    // The value is already the plural array or a plain format string —
    // no registry lookup needed, resolve directly.
    if (is_array($str)) {
        if ($n === 0 && isset($str['zero'])) {
            return sprintf((string)$str['zero'], $n);
        }
        $category = LangPlural::category(Lang::getLocale(), $n);
        $form     = $str[$category] ?? $str['other'] ?? $str['many'] ?? $str['one'] ?? reset($str);
        return sprintf((string)$form, $n);
    }

    // A plain string value (not a key): Ln_('%d items', 5) → '5 items'
    if (!str_contains($str, '[') && !str_contains($str, ':')) {
        return sprintf($str, $n);
    }

    // Key string usage: Ln_("TEXT['USERS_AMOUNT']", 5)
    $fallback = '';
    if (str_contains($str, '||')) {
        [$str, $fallback] = explode('||', $str, 2);
    }
    [$ns, $key] = _wbe_parse_key(trim($str));
    if (!$ns) {
        return $fallback !== '' ? sprintf($fallback, $n) : sprintf($str, $n);
    }
    return Lang::getPlural($ns, $key, $n, $fallback);
}

// ── Internal helpers ──────────────────────────────────────────────────────────

/**
 * Does a string look like a translation key?
 * Accepts all supported syntaxes after stripping optional quotes.
 */
function _wbe_is_key(string $s): bool
{
    $s = _wbe_strip_quotes($s);
    return (bool)preg_match(
        '/^\{?[A-Z][A-Z0-9_]*(?::[A-Z0-9_]+|\[[A-Z0-9_]+\])\}?$/i',
        $s
    );
}

/**
 * Parse any supported key syntax into [namespace, key].
 * Returns ['', ''] if not a valid key.
 *
 * Handles:
 *   MENU['MEDIA']   MENU["MEDIA"]   MENU[MEDIA]
 *   MENU:MEDIA      {MENU:MEDIA}
 *
 * Quote stripping is done before the regex so the patterns stay simple.
 */
function _wbe_parse_key(string $s): array
{
    $s = _wbe_strip_quotes($s);
    $s = trim($s, '{}');

    // Bracket syntax: NS[KEY]
    if (preg_match('/^([A-Z][A-Z0-9_]*)\[([A-Z0-9_]+)\]$/i', $s, $m)) {
        return [$m[1], $m[2]];
    }
    // Colon syntax: NS:KEY
    if (preg_match('/^([A-Z][A-Z0-9_]*):([A-Z0-9_]+)$/i', $s, $m)) {
        return [$m[1], $m[2]];
    }
    return ['', ''];
}

/**
 * Strip optional surrounding quotes from bracket keys.
 * MENU['MEDIA']  →  MENU[MEDIA]
 * MENU["MEDIA"]  →  MENU[MEDIA]
 * TEXT:SAVE      →  TEXT:SAVE   (unchanged — no brackets)
 */
function _wbe_strip_quotes(string $s): string
{
    // Remove quotes immediately inside brackets: NS['KEY'] or NS["KEY"]
    return preg_replace('/\[([\'"]?)([A-Z0-9_]+)\1\]/i', '[$2]', $s);
}

/**
 * Resolve all translation tokens in a string, including || fallback.
 *
 * Resolves all supported syntaxes — quotes stripped before lookup.
 * Supports multiple tokens in one string: "TEXT['DELETE'] TEXT['PAGE']"
 */
function _wbe_resolve(string $input): string
{
    // Split off || fallback before any processing
    $fallback = null;
    if (str_contains($input, '||')) {
        [$input, $fallback] = explode('||', $input, 2);
        $input = trim($input);
    }

    // Normalise: strip quotes from all bracket tokens so the token-matching
    // regex stays simple — no quoting ambiguity in the pattern itself.
    $input = _wbe_strip_quotes($input);

    // Find all tokens: NS[KEY]  {NS:KEY}  NS:KEY
    if (!preg_match_all(
        '/\{[A-Z][A-Z0-9_]*:[A-Z0-9_]+\}|[A-Z][A-Z0-9_]*\[[A-Z0-9_]+\]|[A-Z][A-Z0-9_]*:[A-Z0-9_]+/i',
        $input, $matches
    )) {
        return $fallback ?? $input;
    }

    $anyHit = false;

    foreach ($matches[0] as $token) {
        [$ns, $key] = _wbe_parse_key($token);
        if (!$ns) continue;

        if (Lang::has($ns, $key)) {
            $input  = str_replace($token, Lang::get($ns, $key), $input);
            $anyHit = true;
        } else {
            if (Lang::isTracking()) {
                $trace  = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
                $caller = $trace[2] ?? $trace[1] ?? $trace[0];
                Lang::recordMiss($ns, $key, $caller['file'] ?? '', (int)($caller['line'] ?? 0));
            }
            $replacement = $fallback ?? ucfirst(strtolower(str_replace('_', ' ', $key)));
            $input = str_replace($token, $replacement, $input);
        }
    }

    if (!$anyHit && $fallback !== null) return $fallback;
    return $input;
}

/**
 * Apply %s and %1$s positional substitutions.
 *
 * Two separate passes avoid optional capture-group null issues across
 * PHP/PCRE versions that affect preg_replace_callback.
 */
function _wbe_sprintf(string $str, array $args): string
{
    if (empty($args) || !str_contains($str, '%')) return $str;

    // Pass 1 — positional: %1$s %2$s (required digit group, no ambiguity)
    $str = preg_replace_callback(
        '/%([1-9]\d*)\$s/',
        static function (array $m) use ($args): string {
            return (string)($args[(int)$m[1] - 1] ?? '');
        },
        $str
    );

    // Pass 2 — sequential: replace each %s one at a time with next arg
    foreach ($args as $arg) {
        $new = preg_replace('/%s/', (string)$arg, $str, 1);
        if ($new === $str) break;   // no more %s in string
        $str = $new;
    }

    return $str;
}

} // end function_exists guard


/**
 * Standalone include helper for language files.
 *
 * MUST be a plain function, not a class method — files included inside a
 * method run in method scope, so variables they assign ($TEXT, $MENU etc.)
 * become method-locals and are lost on return. Inside a plain function,
 * get_defined_vars() captures everything the included file assigned.
 */
function _wbe_lang_include(string $file): array
{
    $_wbe_ret = (include $file);
    $vars = get_defined_vars();
    $vars['_wbe_returned'] = $_wbe_ret;
    return $vars;
}
