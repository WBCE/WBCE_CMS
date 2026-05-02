<?php
/**
 * WBCE CMS AdminTool: tool_debug_dump
 * 
 * @platform    WBCE CMS 1.7.x and higher
 * @package     modules/tool_debug_dump
 * @author      Christian M. Stefan (https://www.wbEasy.de)
 * @copyright   Christian M. Stefan (2018, 2026)
 * @license     GNU/GPL2
 */

(count(get_included_files()) != 1) or die();


/**
 * Renders a structured debug panel for any PHP variable.
 *
 * PHP's job here is minimal: detect the type, serialise the value to the
 * most appropriate text representation, and embed it in the panel markup.
 * All syntax colouring and pretty-printing is handled client-side by
 * debug_dump.js
 * 
 * This is a total rewrite of the previous debug_dump function from 2018.
 *
 * data-format values understood by the JS prettifier:
 *   json      — JSON string (arrays, objects, serialisable values)
 *   var_dump  — raw var_dump() text
 *   print_r   — raw print_r() text (fallback for non-serialisable values)
 *   string    — plain string value
 *   scalar    — int, float, bool, null
 *
 * @param  mixed   $mVar          The variable or expression to inspect.
 * @param  string  $sHeading      Optional label shown in the panel header.
 * @param  bool    $bUse_var_dump Use var_dump() output instead of JSON.
 * @param  mixed   $mTwig         Twig template name when called from a template.
 */
function debug_dump($mVar = '', $sHeading = '', $bUse_var_dump = false, $mTwig = false): void
{
    // ── 1. Type label ─────────────────────────────────────────────────────────
    $type = match (true) {
        is_object($mVar) => 'object',
        is_array($mVar)  => 'array',
        is_string($mVar) => 'string',
        is_bool($mVar)   => 'bool',
        is_int($mVar)    => 'int',
        is_float($mVar)  => 'float',
        is_null($mVar)   => 'null',
        is_scalar($mVar) => 'scalar',
        default          => 'unknown',
    };

    // ── 2. Call-site information ──────────────────────────────────────────────
    $trace   = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
    $srcFile = $trace['file'] ?? '';
    $srcLine = $trace['line'] ?? 0;

    $varName = '';
    if ($srcFile && is_readable($srcFile)) {
        $lines = file($srcFile);
        $src   = $lines[$srcLine - 1] ?? '';
        if (preg_match('/\bdebug_dump\s*\(\s*(.+?)(?:\s*,|\s*\))/i', trim($src), $m)) {
            $varName = trim($m[1]);
        }
    }

    // ── 3. Stable block ID for localStorage ──────────────────────────────────
    $blockId = 'dd-' . substr(md5($srcFile . ':' . $srcLine . ':' . $sHeading), 0, 10);

    // ── 4. Location label ─────────────────────────────────────────────────────
    if ($mTwig !== false) {
        $location = '<span class="src-twig">' . htmlspecialchars((string)$mTwig, ENT_QUOTES, 'UTF-8').'</span>';
    } elseif ($srcFile) {
        $shortFile = str_replace(WB_PATH . DIRECTORY_SEPARATOR, '[WB_PATH]\\', $srcFile);
        $location  = htmlspecialchars($shortFile, ENT_QUOTES, 'UTF-8') . ' (L ' . $srcLine.')';
    } else {
        $location = 'unknown';
    }

    // ── 5. Serialise value ────────────────────────────────────────────────────
    if ($bUse_var_dump) {
        ob_start();
        var_dump($mVar);
        $content = htmlspecialchars(ob_get_clean(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $format  = 'var_dump';

    } elseif (is_array($mVar) || is_object($mVar)) {
        $json = json_encode($mVar, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json !== false) {
            $content = htmlspecialchars($json, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $format  = 'json';
        } else {
            ob_start();
            print_r($mVar);
            $content = htmlspecialchars(ob_get_clean(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $format  = 'print_r';
        }

    } elseif (is_bool($mVar)) {
        $content = $mVar ? 'true' : 'false';
        $format  = 'scalar';

    } elseif (is_null($mVar)) {
        $content = 'null';
        $format  = 'scalar';

    } elseif (is_string($mVar)) {
        $content = htmlspecialchars($mVar, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $format  = 'string';

    } else {
        $content = htmlspecialchars((string)$mVar, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $format  = 'scalar';
    }

    $TXT_CLOSE       = L_("TEXT:CLOSE");
    $TXT_FULL_HEIGHT = (LANGUAGE == 'DE') 
            ? "Auf volle Höhe erweitern" 
            : "Expand to full height";    
    $TXT_REDUCE_HEIGHT = (LANGUAGE == 'DE') 
            ? "Höhe reduzieren" 
            : "Reduce height";
    // ── 6. Labels ─────────────────────────────────────────────────────────────
    $safeHeading  = htmlspecialchars($sHeading, ENT_QUOTES, 'UTF-8');
    $safeVarName  = htmlspecialchars($varName,  ENT_QUOTES, 'UTF-8');
    $primaryLabel = $safeVarName ?: $safeHeading;
    $aliasHtml    = ($safeVarName && $safeHeading)
        ? '<span class="dd-alias">' . $safeHeading . '</span>'
        : '';
    $countHtml = is_countable($mVar)
        ? '<span class="dd-count">' . count($mVar) . '</span>'
        : '';
    $boxHeight = unserialize(DEBUG_DUMP_CFG)['height'];

// ── 7. HTML ───────────────────────────────────────────────────────────────────
$toHTML = <<<_HTML
<details class="debug-dump dd-t-{$type}" id="{$blockId}" data-box-height="{$boxHeight}" open>
    <summary class="dd-summary">
        <span class="dd-badge">{$type}</span>
        <span class="dd-name">{$primaryLabel}{$aliasHtml}{$countHtml}</span>
        <span class="dd-loc">{$location}</span>
        
        <button class="dd-expand" 
                type="button"
                title="{$TXT_FULL_HEIGHT}"           
                data-alt-title="{$TXT_REDUCE_HEIGHT}" 
                onclick="event.stopPropagation();ddExpand(this)">
            <span class="dd-expand-icon-wrapper"></span>
        </button>
        
        <button class="dd-close" type="button" 
                aria-label="Close panel" 
                title="{$TXT_CLOSE}"
                onclick="event.stopPropagation();this.closest('details.debug-dump').remove()">
            <span aria-hidden="true">&#x2715;</span>
        </button>
    </summary>
    
    <div class="dd-body">
        <pre class="dd-pre" data-format="{$format}">{$content}</pre>
    </div>
</details>
_HTML;
    
    echo $toHTML;
}