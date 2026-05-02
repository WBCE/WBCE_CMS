<?php
/**
 * Internationalization — LangPlural
 *
 * CLDR plural category resolver for use with Lang::getPlural() and Ln_().
 * Determines the correct plural form (one, few, many, other, ...) for a
 * given locale and count, covering over 20 language families.
 *
 * Language file authors define plural arrays once per key; the correct
 * form is selected automatically at runtime without any conditional logic
 * in application code.
 *
 * @file      framework/i18n/LangPlural.php
 * @package   Framework i18n
 * @author    Christian M. Stefan  (https://www.wbEasy.de)
 * @copyright 2025-2026 Christian M. Stefan
 * @copyright 2026 WBCE CMS Project
 * @license   GNU/GPL v2 https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @note      Rules are based on the Unicode CLDR plural specifications.
 *            See: https://www.unicode.org/cldr/charts/latest/supplemental/language_plural_rules.html
 *
 * -----------------------------------------------------------------------------
 *
 * CLDR plural category resolver.
 *
 * Returns one of: zero | one | two | few | many | other
 * for a given locale and number. Used by Lang::getPlural() and Ln_().
 *
 * Language file plural arrays use these category names as keys:
 *
 *   // EN
 *   $TEXT['GROUP_COUNT'] = [
 *       'zero'  => 'No groups',
 *       'one'   => '%d group',
 *       'other' => '%d groups',
 *   ];
 *
 *   // PL 4 forms (+ zero):
 *   $TEXT['GROUP_COUNT'] = [
 *       'zero'  => 'Brak grup',
 *       'one'   => 'Istnieje %d grupa',
 *       'few'   => 'Istnieją %d grupy',
 *       'many'  => 'Istnieje %d grup',
 *       'other' => 'Istnieje %d grup',
 *   ];
 *
 * Rules source: https://www.unicode.org/cldr/charts/latest/supplemental/language_plural_rules.html
 */
class LangPlural
{
    /** @var array<string,string>  locale → category cache */
    private static array $cache = [];

    /**
     * Return the CLDR plural category for locale + number.
     *
     * @param  string    $locale  e.g. 'de_AT', 'pl_PL', 'ru_RU'
     * @param  int|float $n       The number to categorise (absolute value used)
     * @return string             'zero'|'one'|'two'|'few'|'many'|'other'
     */
    public static function category(string $locale, int|float $n): string
    {
        $n    = abs($n);
        $lang = strtolower(explode('_', $locale)[0]);

        return match ($lang) {
            // ── 1 / other (Germanic, most common) ────────────────────────────
            'en', 'de', 'nl', 'sv', 'da', 'no', 'nb', 'nn', 'fi', 'et', 
            'hu', 'tr', 'el', 'it', 'pt', 'es', 'ca', 'bg', 'hr', 'ro'            
                => self::germanic($n),

            // ── 0-1 / other (French family) ───────────────────────────────
            'fr', 'ff', 'kab' => self::french($n),

            // ── Polish ────────────────────────────────────────────────────
            'pl' => self::polish($n),

            // ── East Slavic (Russian, Ukrainian, Belarusian) ──────────────
            'ru', 'uk', 'be' => self::eastSlavic($n),

            // ── Czech / Slovak ────────────────────────────────────────────
            'cs', 'sk' => self::czech($n),

            // ── Slovenian ─────────────────────────────────────────────────
            'sl' => self::slovenian($n),

            // ── Baltic: Lithuanian ────────────────────────────────────────
            'lt' => self::lithuanian($n),

            // ── Baltic: Latvian ───────────────────────────────────────────
            'lv' => self::latvian($n),

            // ── Arabic (6 forms) ──────────────────────────────────────────
            'ar' => self::arabic($n),

            // ── Semitic: Hebrew ───────────────────────────────────────────
            'he', 'iw' => self::hebrew($n),

            // ── Irish (5 forms) ───────────────────────────────────────────
            'ga' => self::irish($n),

            // ── Scottish Gaelic (4 forms) ─────────────────────────────────
            'gd' => self::scottishGaelic($n),

            // ── Macedonian ────────────────────────────────────────────────
            'mk' => self::macedonian($n),

            // ── Icelandic ─────────────────────────────────────────────────
            'is' => self::icelandic($n),

            // ── Zero-sensitive (e.g. Azerbaijani, Japanese — always 'other')
            'ja', 'zh', 'ko', 'vi', 'th', 'id', 'ms', 'az',
            'fa', 'ka', 'km', 'lo', 'my'
                => 'other',

            // ── Default: germanic fallback ────────────────────────────────
            default => self::germanic($n),
        };
    }

    public static function resetCache(): void { self::$cache = []; }

    // ── Rule implementations ──────────────────────────────────────────────────

    /** en, de, nl, … — n=1 → one */
    private static function germanic(int|float $n): string
    {
        return $n === 1 ? 'one' : 'other';
    }

    /** fr, … — n ≤ 1 → one */
    private static function french(int|float $n): string
    {
        return $n <= 1 ? 'one' : 'other';
    }

    /**
     * Polish
     * one:   n = 1
     * few:   n % 10 in 2–4  AND  n % 100 not in 12–14
     * many:  n % 10 = 0, 1, 5–9  OR  n % 100 in 11–14
     * other: everything else (fractions)
     */
    private static function polish(int|float $n): string
    {
        $mod10  = (int)$n % 10;
        $mod100 = (int)$n % 100;

        if ($n === 1)                                                       return 'one';
        if ($mod10 >= 2 && $mod10 <= 4 && ($mod100 < 12 || $mod100 > 14))   return 'few';
        if ($mod10 <= 1 || $mod10 >= 5 || ($mod100 >= 12 && $mod100 <= 14)) return 'many';
        return 'other';
    }

    /**
     * East Slavic: Russian, Ukrainian, Belarusian
     * one:   n % 10 = 1  AND  n % 100 ≠ 11
     * few:   n % 10 in 2–4  AND  n % 100 not in 12–14
     * many:  everything else
     */
    private static function eastSlavic(int|float $n): string
    {
        $mod10  = (int)$n % 10;
        $mod100 = (int)$n % 100;

        if ($mod10 === 1 && $mod100 !== 11)                               return 'one';
        if ($mod10 >= 2 && $mod10 <= 4 && ($mod100 < 10 || $mod100 > 20)) return 'few';
        return 'many';
    }

    /**
     * Czech / Slovak
     * one: n = 1 | few: n in 2–4 | many: everything else
     */
    private static function czech(int|float $n): string
    {
        if ($n === 1)                  return 'one';
        if ($n >= 2 && $n <= 4)        return 'few';
        return 'many';
    }

    /**
     * Slovenian (4 forms)
     * one:  n % 100 = 1
     * two:  n % 100 = 2
     * few:  n % 100 in 3–4
     * other: everything else
     */
    private static function slovenian(int|float $n): string
    {
        $mod100 = (int)$n % 100;
        if ($mod100 === 1)                return 'one';
        if ($mod100 === 2)                return 'two';
        if ($mod100 >= 3 && $mod100 <= 4) return 'few';
        return 'other';
    }

    /**
     * Lithuanian
     * one:  n % 10 = 1  AND  n % 100 not in 11–19
     * few:  n % 10 in 2–9  AND  n % 100 not in 11–19
     * many: everything else (fractions)
     * other: — (not used, maps to many)
     */
    private static function lithuanian(int|float $n): string
    {
        $mod10  = (int)$n % 10;
        $mod100 = (int)$n % 100;
        $teen   = $mod100 >= 11 && $mod100 <= 19;

        if ($mod10 === 1 && !$teen)               return 'one';
        if ($mod10 >= 2 && $mod10 <= 9 && !$teen) return 'few';
        return 'many';
    }

    /**
     * Latvian
     * zero: n = 0
     * one:  n % 10 = 1  AND  n % 100 ≠ 11
     * other: everything else
     */
    private static function latvian(int|float $n): string
    {
        if ($n === 0)                                      return 'zero';
        if ((int)$n % 10 === 1 && (int)$n % 100 !== 11)    return 'one';
        return 'other';
    }

    /**
     * Arabic (6 forms)
     * zero:  n = 0
     * one:   n = 1
     * two:   n = 2
     * few:   n % 100 in 3–10
     * many:  n % 100 in 11–99
     * other: everything else
     */
    private static function arabic(int|float $n): string
    {
        $mod100 = (int)$n % 100;
        if ($n === 0)                              return 'zero';
        if ($n === 1)                              return 'one';
        if ($n === 2)                              return 'two';
        if ($mod100 >= 3  && $mod100 <= 10)        return 'few';
        if ($mod100 >= 11 && $mod100 <= 99)        return 'many';
        return 'other';
    }

    /**
     * Hebrew
     * one:   n = 1
     * two:   n = 2
     * many:  n ≥ 20 and n % 10 = 0
     * other: everything else
     */
    private static function hebrew(int|float $n): string
    {
        if ($n === 1)                         return 'one';
        if ($n === 2)                         return 'two';
        if ($n >= 20 && (int)$n % 10 === 0)   return 'many';
        return 'other';
    }

    /**
     * Irish (5 forms)
     * one:  n = 1 | two: n = 2
     * few:  n in 3–6 | many: n in 7–10
     * other: everything else
     */
    private static function irish(int|float $n): string
    {
        if ($n === 1)                 return 'one';
        if ($n === 2)                 return 'two';
        if ($n >= 3 && $n <= 6)       return 'few';
        if ($n >= 7 && $n <= 10)      return 'many';
        return 'other';
    }

    /**
     * Scottish Gaelic (4 forms)
     * one:  n in 1, 11 | two:  n in 2, 12
     * few:  n in 3–10, 13–19 | other: everything else
     */
    private static function scottishGaelic(int|float $n): string
    {
        if ($n === 1  || $n === 11)                           return 'one';
        if ($n === 2  || $n === 12)                           return 'two';
        if (($n >= 3  && $n <= 10) || ($n >= 13 && $n <= 19)) return 'few';
        return 'other';
    }

    /**
     * Macedonian
     * one:  n % 10 = 1 (not 11)
     * other: everything else
     */
    private static function macedonian(int|float $n): string
    {
        return ((int)$n % 10 === 1 && $n !== 11) ? 'one' : 'other';
    }

    /**
     * Icelandic
     * one:  n % 10 = 1  AND  n % 100 ≠ 11
     * other: everything else
     */
    private static function icelandic(int|float $n): string
    {
        return ((int)$n % 10 === 1 && (int)$n % 100 !== 11) ? 'one' : 'other';
    }
}
