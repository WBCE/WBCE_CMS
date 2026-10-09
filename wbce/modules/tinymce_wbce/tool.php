<?php
/**
 * tinymce_wbce — tool.php
 * Admin tool: TinyMCE editor configuration.
 *
 * Single tab. Each preset is an accordion row; expanding it lazy-loads (via HTMX)
 * a full editor profile — toolbar configurator + live editor + settings. The
 * preset data model lives in presets.php (Settings 'tinymce_cfg' v2).
 *
 * Reachable via: Admin Tools → tinymce_wbce
 * Included by the AdminTool runner (admin/admintools/tool.php); auth is already
 * enforced there. HTMX/AJAX endpoints echo a fragment and exit() BEFORE the
 * runner wraps the output — same trick the old save handler used.
 *
 * @author  Slugger & Claude KI
 * @license GNU GPL2
 */

defined('WB_PATH') or die(header('Location: ../index.php'));

$modUrl  = WB_URL . '/modules/tinymce_wbce';
$toolUrl = $returnUrl; // set by the AdminTool runner

Lang::loadLanguage(__DIR__);
require_once __DIR__ . '/presets.php';
require_once __DIR__ . '/plugin_i18n.php';
require_once __DIR__ . '/icons.php';

// ── Small helpers ────────────────────────────────────────────────────────────
function tmce_h($s): string { return htmlspecialchars((string)$s, ENT_QUOTES); }

// The four shipped presets: protected from delete/rename (re-seeded by load_cfg);
// they can only be reset to their defaults or overwritten via import.
if (!defined('TMCE_BUILTIN_PRESETS')) { define('TMCE_BUILTIN_PRESETS', ['standard', 'full', 'custom', 'minimal']); }

/** Sanitize a preset id from request (letters, digits, _ and -). */
function tmce_pid(string $raw): string {
    return preg_replace('/[^a-zA-Z0-9_\-]/', '', $raw);
}

/** Translated label for a built-in preset, else the stored label. */
function tmce_preset_label(string $id, array $preset): string {
    $map = ['minimal' => 'TXT:MINIMAL', 'standard' => 'TXT:STANDARD', 'full' => 'TXT:FULL', 'custom' => 'TXT:CUSTOM'];
    if (isset($map[$id])) return L_($map[$id]);
    return ($preset['label'] ?? '') !== '' ? $preset['label'] : $id;
}

/** Split a "Name=value;Name=value" TinyMCE format string into entries. */
function tmce_parse_entries(string $s): array {
    return array_values(array_filter(array_map('trim', explode(';', $s)), fn($e) => $e !== ''));
}

/** Read the available UI skins / content themes from disk. */
function tmce_list_dirs(string $path): array {
    if (!is_dir($path)) return [];
    return array_values(array_filter(scandir($path), fn($d) =>
        $d !== '.' && $d !== '..' && is_dir($path . '/' . $d)));
}

/**
 * Templates that are actually in use on this instance: every distinct non-empty
 * {TP}pages.template value, plus DEFAULT_TEMPLATE (pages with an empty template
 * inherit it). Only these are worth scanning for an overriding editor.css — an
 * installed-but-unused template can never style a page. Returned as a lookup set.
 */
function tmce_used_templates(): array {
    global $database;
    $used = [];
    if (defined('DEFAULT_TEMPLATE') && DEFAULT_TEMPLATE !== '') {
        $used[DEFAULT_TEMPLATE] = true;
    }
    if (isset($database)) {
        $rows = $database->fetchAll("SELECT DISTINCT `template` FROM `{TP}pages` WHERE `template` <> ''");
        foreach (($rows ?: []) as $row) {
            $t = is_array($row) ? (string)($row['template'] ?? '') : '';
            if ($t !== '') { $used[$t] = true; }
        }
    }
    return $used;
}

/**
 * Live scan (no cache — runs on every tool open) for editor.css files that may
 * override the in-editor CSS. Looks only in templates that are installed AND in
 * use (see tmce_used_templates) at editor.css, css/editor.css and
 * assets/editor.css, plus the global templates/wb_config/editor.css.
 * Returns [ ['scope'=>'<template>|wb_config', 'rel'=>'templates/…/editor.css'], … ].
 */
function tmce_scan_editor_css(): array {
    $found = [];
    $used  = tmce_used_templates();
    $subs  = ['editor.css', 'css/editor.css', 'assets/editor.css'];
    foreach (glob(WB_PATH . '/templates/*', GLOB_ONLYDIR) ?: [] as $dir) {
        $tpl = basename($dir);
        if ($tpl === 'wb_config') { continue; }   // global, handled below
        if (!isset($used[$tpl])) { continue; }    // installed but not in use → skip
        foreach ($subs as $sub) {
            if (is_file($dir . '/' . $sub)) {
                $found[] = ['scope' => $tpl, 'rel' => 'templates/' . $tpl . '/' . $sub];
            }
        }
    }
    if (is_file(WB_PATH . '/templates/wb_config/editor.css')) {
        $found[] = ['scope' => 'wb_config', 'rel' => 'templates/wb_config/editor.css'];
    }
    return $found;
}

/**
 * Render one preset's accordion panel body: DnD toolbar configurator +
 * editor settings + live preview. DOM ids are suffixed with the preset id so
 * several panels can coexist in the DOM (only one is ever active at a time).
 */
function tmce_render_panel_body(string $id, array $preset, string $modUrl): string {
    $uiSkins       = tmce_list_dirs(WB_PATH . '/modules/tinymce_wbce/tinymce/skins/ui');
    $contentThemes = tmce_list_dirs(WB_PATH . '/modules/tinymce_wbce/tinymce/skins/content');

    $h = tmce_h($id);

    // (The JSON data island lives in the accordion header — always present, so the
    //  "Only me" badge works even before a panel is expanded. See tmce_data_island().)

    ob_start(); ?>
    <div class="tkfg" id="tkfg-<?= $h ?>" data-preset="<?= $h ?>">

      <div class="section-label"><?= L_('TXT:AVAILABLE_BUTTONS_DRAG') ?></div>
      <div class="palette" id="tkfg-palette-<?= $h ?>"><span class="palette-loading"><?= L_('TXT:LOADING_ICONS') ?></span></div>

      <div class="section-label"><?= L_('TXT:TOOLBAR_ROWS') ?></div>
      <div id="tkfg-rows-<?= $h ?>">
        <div class="row-wrapper"><div class="row-label"><?= L_('TXT:ROW', 1) ?></div><div class="toolbar-row" id="tkfg-row-<?= $h ?>-0"></div></div>
        <div class="row-wrapper"><div class="row-label"><?= L_('TXT:ROW', 2) ?></div><div class="toolbar-row" id="tkfg-row-<?= $h ?>-1"></div></div>
        <div class="row-wrapper"><div class="row-label"><?= L_('TXT:ROW', 3) ?></div><div class="toolbar-row" id="tkfg-row-<?= $h ?>-2"></div></div>
      </div>

      <div class="section-label"><?= L_('TXT:EDITOR_SETTINGS') ?></div>
      <div class="settings-block">
        <div class="setting-row">
          <label for="tkfg-height-<?= $h ?>"><?= L_('TXT:DEFAULT_HEIGHT') ?></label>
          <div class="setting-control">
            <input type="number" id="tkfg-height-<?= $h ?>" value="<?= (int)$preset['height'] ?>" min="100" max="1200" step="10">
            <span class="unit">px</span>
          </div>
        </div>
        <div class="setting-row">
          <label for="tkfg-show-toolbar-<?= $h ?>"><?= L_('TXT:SHOW_TOOLBAR') ?></label>
          <div class="setting-control">
            <input type="checkbox" id="tkfg-show-toolbar-<?= $h ?>" <?= $preset['show_toolbar'] === '1' ? 'checked' : '' ?>>
            <span class="hint"><?= L_('TXT:SHOW_TOOLBAR_HINT') ?></span>
          </div>
        </div>
        <div class="setting-row">
          <label for="tkfg-toolbar-sliding-<?= $h ?>"><?= L_('TXT:TOOLBAR_SLIDING') ?></label>
          <div class="setting-control">
            <input type="checkbox" id="tkfg-toolbar-sliding-<?= $h ?>" <?= $preset['toolbar_sliding'] === '1' ? 'checked' : '' ?>>
            <span class="hint"><?= L_('TXT:TOOLBAR_SLIDING_HINT') ?></span>
          </div>
        </div>
        <div class="setting-row">
          <label for="tkfg-menubar-<?= $h ?>"><?= L_('TXT:MENUBAR') ?></label>
          <div class="setting-control">
            <input type="checkbox" id="tkfg-menubar-<?= $h ?>" <?= $preset['menubar'] === '1' ? 'checked' : '' ?>>
            <span class="hint"><?= L_('TXT:MENUBAR_HINT') ?></span>
          </div>
        </div>
        <div class="setting-row">
          <label for="tkfg-statusbar-<?= $h ?>"><?= L_('TXT:STATUSBAR') ?></label>
          <div class="setting-control">
            <input type="checkbox" id="tkfg-statusbar-<?= $h ?>" <?= $preset['statusbar'] === '1' ? 'checked' : '' ?>>
            <span class="hint"><?= L_('TXT:STATUSBAR_HINT') ?></span>
          </div>
        </div>
        <div class="setting-row">
          <label for="tkfg-wordcount-<?= $h ?>"><?= L_('TXT:WORDCOUNT') ?></label>
          <div class="setting-control">
            <input type="checkbox" id="tkfg-wordcount-<?= $h ?>" <?= ($preset['wordcount'] ?? '0') === '1' ? 'checked' : '' ?>>
            <span class="hint"><?= L_('TXT:WORDCOUNT_HINT') ?></span>
          </div>
        </div>
        <div class="setting-row">
          <label for="tkfg-skin-<?= $h ?>"><?= L_('TXT:UI_SKIN') ?></label>
          <div class="setting-control">
            <select id="tkfg-skin-<?= $h ?>">
              <?php foreach ($uiSkins as $s): ?>
              <option value="<?= tmce_h($s) ?>"<?= $preset['skin'] === $s ? ' selected' : '' ?>><?= tmce_h($s) ?></option>
              <?php endforeach; ?>
            </select>
            <span class="hint"><?= L_('TXT:UI_SKIN_HINT') ?></span>
          </div>
        </div>
        <div class="setting-row">
          <label for="tkfg-content-theme-<?= $h ?>"><?= L_('TXT:CONTENT_THEME') ?></label>
          <div class="setting-control">
            <select id="tkfg-content-theme-<?= $h ?>">
              <?php foreach ($contentThemes as $t): ?>
              <option value="<?= tmce_h($t) ?>"<?= $preset['content_theme'] === $t ? ' selected' : '' ?>><?= tmce_h($t) ?></option>
              <?php endforeach; ?>
            </select>
            <span class="hint"><?= L_('TXT:CONTENT_THEME_HINT') ?></span>
            <?php $edCssOverride = tmce_scan_editor_css(); if ($edCssOverride): ?>
            <div class="tmce-editorcss-note">
              <?= L_('TXT:EDITORCSS_MAY_OVERRIDE') ?>
              <ul class="tmce-editorcss-list">
                <?php foreach ($edCssOverride as $f): ?><li><code><?= tmce_h($f['rel']) ?></code></li><?php endforeach; ?>
              </ul>
            </div>
            <?php endif; ?>
          </div>
        </div>
        <div class="setting-row">
          <label for="tkfg-editorcss-source-<?= $h ?>"><?= L_('TXT:EDITORCSS_SOURCE') ?></label>
          <div class="setting-control">
            <select id="tkfg-editorcss-source-<?= $h ?>">
              <?php foreach (['auto', 'template', 'wb_config', 'default', 'none'] as $opt): ?>
              <option value="<?= $opt ?>"<?= ($preset['editor_css_source'] ?? 'auto') === $opt ? ' selected' : '' ?>>
                <?= L_('TXT:EDITORCSS_SOURCE_' . strtoupper($opt)) ?>
              </option>
              <?php endforeach; ?>
            </select>
            <span class="hint"><?= L_('TXT:EDITORCSS_SOURCE_HINT') ?></span>
          </div>
        </div>
        <div class="setting-row">
          <label for="tkfg-editorcss-classes-<?= $h ?>"><?= L_('TXT:EDITORCSS_CLASSES') ?></label>
          <div class="setting-control setting-control--wrap">
            <select id="tkfg-editorcss-classes-<?= $h ?>" class="tkfg-editorcss-classes">
              <?php foreach (['off', 'auto', 'annotated'] as $m): ?>
              <option value="<?= $m ?>"<?= ($preset['editorcss_classes'] ?? 'off') === $m ? ' selected' : '' ?>><?= L_('TXT:EDITORCSS_CLASSES_' . strtoupper($m)) ?></option>
              <?php endforeach; ?>
            </select>
            <span class="hint"><?= L_('TXT:EDITORCSS_CLASSES_HINT') ?></span>
            <!-- Forces the filter input + its hint onto their own row: a flex
                 child with flex-basis:100% always starts a fresh line when the
                 container wraps (unlike the old display:block hack, which flex
                 items ignore entirely). -->
            <span class="tkfg-break" aria-hidden="true"></span>
            <input type="text" id="tkfg-editorcss-filter-<?= $h ?>" class="tkfg-editorcss-filter"
                   value="<?= tmce_h($preset['editorcss_selector_filter'] ?? '') ?>"
                   placeholder="^(btn|text|callout)-">
            <!-- This hint is long — give it the FULL row width on its own line
                 rather than squeezing it next to the input, where it had too
                 little room and wrapped into an unreadable stack. -->
            <span class="hint tkfg-hint-block"><?= L_('TXT:EDITORCSS_FILTER_HINT') ?></span>
          </div>
        </div>
      </div>

      <?php
      // ── Typography & formats — three collapsible sub-sections ──
      $fontCatalog  = tinymce_wbce_font_catalog();
      $storedFonts  = tmce_parse_entries($preset['font_families']);
      $catalogVals  = [];
      foreach ($fontCatalog as $fid => [$fname, $fstack]) { $catalogVals[$fname . '=' . $fstack] = $fid; }
      $extraFonts   = array_values(array_filter($storedFonts, fn($e) => !isset($catalogVals[$e])));

      $blockCatalog = tinymce_wbce_block_catalog();
      $storedBlocks = tmce_parse_entries($preset['block_formats']);
      // Empty stored list = TinyMCE default → pre-check its equivalent (p, h1–h6, pre)
      $defaultBlockIds = ['p','h1','h2','h3','h4','h5','h6','pre'];

      $styleRows = json_decode($preset['style_formats'] ?: '[]', true);
      if (!is_array($styleRows)) $styleRows = [];
      $styleElements = ['p','div','span','h1','h2','h3','h4','h5','h6','pre','blockquote','code'];
      ?>
      <div class="section-label"><?= L_('TXT:TYPOGRAPHY') ?></div>

      <details class="tkfg-sub" id="tkfg-sub-fonts-<?= $h ?>">
        <summary><i class="fa fa-font tkfg-sub-icon" aria-hidden="true"></i><?= L_('TXT:FONTS') ?> <span class="tkfg-sub-hint"><?= L_('TXT:EMPTY_DEFAULT_HINT') ?></span></summary>
        <div class="tkfg-sub-body" id="tkfg-fonts-<?= $h ?>">
          <div class="tkfg-font-grid">
            <?php foreach ($fontCatalog as $fid => [$fname, $fstack]):
                $entry = $fname . '=' . $fstack; ?>
            <label class="tkfg-font-item" style="font-family:<?= tmce_h($fstack) ?>">
              <input type="checkbox" class="tkfg-font-cb" value="<?= tmce_h($entry) ?>"
                     <?= in_array($entry, $storedFonts, true) ? 'checked' : '' ?>>
              <?= tmce_h($fname) ?>
            </label>
            <?php endforeach; ?>
          </div>
          <label class="tkfg-sub-label" for="tkfg-fonts-extra-<?= $h ?>"><?= L_('TXT:CUSTOM_FONTS') ?>:</label>
          <textarea id="tkfg-fonts-extra-<?= $h ?>" class="tkfg-fonts-extra" rows="2"
                    placeholder="My Font=my font,helvetica,sans-serif"><?= tmce_h(implode("\n", $extraFonts)) ?></textarea>
        </div>
      </details>

      <details class="tkfg-sub" id="tkfg-sub-sizes-<?= $h ?>">
        <summary><i class="fa fa-text-height tkfg-sub-icon" aria-hidden="true"></i><?= L_('TXT:FONT_SIZES') ?> <span class="tkfg-sub-hint"><?= L_('TXT:EMPTY_DEFAULT_HINT') ?></span></summary>
        <div class="tkfg-sub-body">
          <div class="setting-row">
            <label for="tkfg-size-unit-<?= $h ?>"><?= L_('TXT:SIZE_UNIT') ?></label>
            <div class="setting-control">
              <select id="tkfg-size-unit-<?= $h ?>">
                <?php foreach (['px','pt','em','rem'] as $u): ?>
                <option value="<?= $u ?>"<?= $preset['font_size_unit'] === $u ? ' selected' : '' ?>><?= $u ?></option>
                <?php endforeach; ?>
              </select>
              <button type="button" class="btn-reset-rows" id="tkfg-size-defaults-<?= $h ?>" style="padding:4px 12px;font-size:.78rem"><?= L_('TXT:USE_DEFAULTS') ?></button>
            </div>
          </div>
          <div class="setting-row">
            <label for="tkfg-sizes-list-<?= $h ?>"><?= L_('TXT:SIZE_LIST') ?></label>
            <div class="setting-control">
              <?php // Always explicit — an empty field silently meant "TinyMCE default"
                    // and looked identical to the grey placeholder. ?>
              <input type="text" id="tkfg-sizes-list-<?= $h ?>" style="width:100%;max-width:420px"
                     value="<?= tmce_h($preset['font_sizes'] !== '' ? $preset['font_sizes'] : (tinymce_wbce_size_defaults()[$preset['font_size_unit']] ?? '')) ?>">
            </div>
          </div>
        </div>
      </details>

      <details class="tkfg-sub" id="tkfg-sub-blocks-<?= $h ?>">
        <summary><i class="fa fa-paragraph tkfg-sub-icon" aria-hidden="true"></i><?= L_('TXT:BLOCK_FORMATS') ?> <span class="tkfg-sub-hint"><?= L_('TXT:BLOCK_FORMATS_HINT') ?></span></summary>
        <div class="tkfg-sub-body">
          <div class="tkfg-block-grid" id="tkfg-blocks-<?= $h ?>">
            <?php foreach ($blockCatalog as $bid => [$blabel, $btag]):
                $entry   = $blabel . '=' . $btag;
                $checked = $storedBlocks ? in_array($entry, $storedBlocks, true) : in_array($bid, $defaultBlockIds, true); ?>
            <label class="tkfg-block-item">
              <input type="checkbox" class="tkfg-block-cb" value="<?= tmce_h($entry) ?>" <?= $checked ? 'checked' : '' ?>>
              <?= tmce_h($blabel) ?> <code>&lt;<?= tmce_h($btag) ?>&gt;</code>
            </label>
            <?php endforeach; ?>
          </div>

          <label class="tkfg-sub-label"><?= L_('TXT:CUSTOM_STYLES') ?></label>
          <div class="tkfg-styles-hint" id="tkfg-styles-hint-<?= $h ?>"><?= L_('TXT:CUSTOM_STYLES_HINT') ?></div>
          <div class="tkfg-styles" id="tkfg-styles-<?= $h ?>">
            <?php foreach ($styleRows as $row): ?>
            <div class="tkfg-style-row">
              <input type="text" class="tkfg-style-title" placeholder="<?= tmce_h(L_('TXT:TITLE')) ?>" value="<?= tmce_h($row['title'] ?? '') ?>">
              <select class="tkfg-style-element">
                <?php foreach ($styleElements as $el): ?>
                <option value="<?= $el ?>"<?= ($row['element'] ?? 'p') === $el ? ' selected' : '' ?>><?= $el ?></option>
                <?php endforeach; ?>
              </select>
              <input type="text" class="tkfg-style-class" placeholder="<?= tmce_h(L_('TXT:STYLE_CLASS')) ?>" value="<?= tmce_h($row['classes'] ?? '') ?>">
              <button type="button" class="tkfg-style-del" title="<?= tmce_h(L_('TXT:REMOVE')) ?>">×</button>
            </div>
            <?php endforeach; ?>
          </div>
          <button type="button" class="btn-reset-rows tkfg-style-add" id="tkfg-style-add-<?= $h ?>" style="padding:4px 12px;font-size:.78rem">+ <?= L_('TXT:ADD') ?></button>
          <template id="tkfg-style-tpl-<?= $h ?>">
            <div class="tkfg-style-row">
              <input type="text" class="tkfg-style-title" placeholder="<?= tmce_h(L_('TXT:TITLE')) ?>">
              <select class="tkfg-style-element">
                <?php foreach ($styleElements as $el): ?>
                <option value="<?= $el ?>"><?= $el ?></option>
                <?php endforeach; ?>
              </select>
              <input type="text" class="tkfg-style-class" placeholder="<?= tmce_h(L_('TXT:STYLE_CLASS')) ?>">
              <button type="button" class="tkfg-style-del" title="<?= tmce_h(L_('TXT:REMOVE')) ?>">×</button>
            </div>
          </template>
        </div>
      </details>

      <?php
      // ── Color swatches (forecolor / backcolor) ──
      $colorMode = $preset['color_mode'];
      $colorRows = static function (string $json) use ($h): string {
          $rows = json_decode($json ?: '[]', true);
          if (!is_array($rows)) $rows = [];
          $out = '';
          foreach ($rows as $row) {
              if (!is_array($row)) continue;
              $out .= '<div class="tkfg-color-row">'
                  . '<input type="text" class="tkfg-color-val" data-wbecoloris value="' . tmce_h($row['color'] ?? '') . '">'
                  . '<input type="text" class="tkfg-color-label" placeholder="' . tmce_h(L_('TXT:COLOR_LABEL')) . '" value="' . tmce_h($row['label'] ?? '') . '">'
                  . '<button type="button" class="tkfg-color-del" title="' . tmce_h(L_('TXT:REMOVE')) . '">×</button>'
                  . '</div>';
          }
          return $out;
      };
      ?>
      <details class="tkfg-sub" id="tkfg-sub-colors-<?= $h ?>">
        <summary><i class="fa fa-paint-brush tkfg-sub-icon" aria-hidden="true"></i><?= L_('TXT:COLORS') ?> <span class="tkfg-sub-hint"><?= L_('TXT:EMPTY_DEFAULT_HINT') ?></span></summary>
        <div class="tkfg-sub-body" id="tkfg-colors-<?= $h ?>">
          <div class="tkfg-styles-hint"><?= L_('TXT:COLORS_HINT') ?></div>

          <div class="setting-row">
            <label for="tkfg-color-mode-<?= $h ?>"><?= L_('TXT:COLOR_MODE') ?></label>
            <div class="setting-control">
              <select id="tkfg-color-mode-<?= $h ?>">
                <option value=""<?= $colorMode === '' ? ' selected' : '' ?>><?= L_('TXT:COLOR_MODE_DEFAULT') ?></option>
                <option value="shared"<?= $colorMode === 'shared' ? ' selected' : '' ?>><?= L_('TXT:COLOR_MODE_SHARED') ?></option>
                <option value="split"<?= $colorMode === 'split' ? ' selected' : '' ?>><?= L_('TXT:COLOR_MODE_SPLIT') ?></option>
              </select>
            </div>
          </div>

          <div class="tkfg-color-palettes" id="tkfg-color-palettes-<?= $h ?>">
            <div class="tkfg-color-palette" data-palette="shared">
              <label class="tkfg-sub-label"><?= L_('TXT:COLOR_SHARED') ?></label>
              <div class="tkfg-color-list" id="tkfg-colors-shared-<?= $h ?>"><?= $colorRows($preset['colors_shared']) ?></div>
              <button type="button" class="btn-reset-rows tkfg-color-add" data-target="tkfg-colors-shared-<?= $h ?>" style="padding:4px 12px;font-size:.78rem">+ <?= L_('TXT:ADD') ?></button>
            </div>
            <div class="tkfg-color-palette" data-palette="fore">
              <label class="tkfg-sub-label"><?= L_('TXT:COLOR_FONT') ?></label>
              <div class="tkfg-color-list" id="tkfg-colors-fore-<?= $h ?>"><?= $colorRows($preset['colors_fore']) ?></div>
              <button type="button" class="btn-reset-rows tkfg-color-add" data-target="tkfg-colors-fore-<?= $h ?>" style="padding:4px 12px;font-size:.78rem">+ <?= L_('TXT:ADD') ?></button>
            </div>
            <div class="tkfg-color-palette" data-palette="back">
              <label class="tkfg-sub-label"><?= L_('TXT:COLOR_BACK') ?></label>
              <div class="tkfg-color-list" id="tkfg-colors-back-<?= $h ?>"><?= $colorRows($preset['colors_back']) ?></div>
              <button type="button" class="btn-reset-rows tkfg-color-add" data-target="tkfg-colors-back-<?= $h ?>" style="padding:4px 12px;font-size:.78rem">+ <?= L_('TXT:ADD') ?></button>
            </div>
            <div class="tkfg-color-palette" data-palette="icons">
              <label class="tkfg-sub-label"><?= L_('TXT:COLOR_ICONS') ?></label>
              <div class="tkfg-color-list" id="tkfg-colors-icons-<?= $h ?>"><?= $colorRows($preset['colors_icons']) ?></div>
              <button type="button" class="btn-reset-rows tkfg-color-add" data-target="tkfg-colors-icons-<?= $h ?>" style="padding:4px 12px;font-size:.78rem">+ <?= L_('TXT:ADD') ?></button>
            </div>
          </div>

          <div class="setting-row">
            <label for="tkfg-color-cols-<?= $h ?>"><?= L_('TXT:COLOR_COLS') ?></label>
            <div class="setting-control">
              <input type="number" id="tkfg-color-cols-<?= $h ?>" min="1" max="12" step="1"
                     placeholder="<?= tmce_h(L_('TXT:COLOR_COLS_AUTO')) ?>" style="width:70px"
                     value="<?= tmce_h($preset['color_cols']) ?>">
              <label style="margin-left:16px;font-size:.82rem;display:inline-flex;align-items:center;gap:6px">
                <input type="checkbox" id="tkfg-custom-colors-<?= $h ?>" style="accent-color:#1a73e8" <?= $preset['custom_colors'] === '1' ? 'checked' : '' ?>>
                <?= L_('TXT:CUSTOM_COLORS') ?>
              </label>
            </div>
          </div>

          <template id="tkfg-color-tpl-<?= $h ?>">
            <div class="tkfg-color-row">
              <input type="text" class="tkfg-color-val" data-wbecoloris value="#333333">
              <input type="text" class="tkfg-color-label" placeholder="<?= tmce_h(L_('TXT:COLOR_LABEL')) ?>">
              <button type="button" class="tkfg-color-del" title="<?= tmce_h(L_('TXT:REMOVE')) ?>">×</button>
            </div>
          </template>
        </div>
      </details>

      <details class="tkfg-sub" id="tkfg-sub-history-<?= $h ?>">
        <summary><i class="fa fa-history tkfg-sub-icon" aria-hidden="true"></i><?= L_('TXT:HISTORY_SETTINGS') ?> <span class="tkfg-sub-hint"><?= L_('TXT:HISTORY_SETTINGS_HINT') ?></span></summary>
        <div class="tkfg-sub-body" id="tkfg-history-<?= $h ?>">
          <div class="setting-row">
            <label for="tkfg-history-enabled-<?= $h ?>"><?= L_('TXT:HISTORY_ENABLED') ?></label>
            <div class="setting-control">
              <input type="checkbox" id="tkfg-history-enabled-<?= $h ?>" class="tkfg-history-enabled" <?= $preset['history_enabled'] === '1' ? 'checked' : '' ?>>
              <span class="hint"><?= L_('TXT:HISTORY_ENABLED_HINT') ?></span>
            </div>
          </div>
          <div class="setting-row">
            <label for="tkfg-history-max-<?= $h ?>"><?= L_('TXT:HISTORY_MAX') ?></label>
            <div class="setting-control">
              <input type="number" id="tkfg-history-max-<?= $h ?>" class="tkfg-history-max" min="1" max="100" step="1" value="<?= (int) $preset['history_max'] ?>" style="width:70px">
              <span class="hint"><?= L_('TXT:HISTORY_MAX_HINT') ?></span>
            </div>
          </div>
          <div class="setting-row">
            <label for="tkfg-history-autoclean-<?= $h ?>"><?= L_('TXT:HISTORY_AUTOCLEAN') ?></label>
            <div class="setting-control">
              <input type="checkbox" id="tkfg-history-autoclean-<?= $h ?>" class="tkfg-history-autoclean" <?= $preset['history_autoclean'] === '1' ? 'checked' : '' ?>>
              <span class="hint"><?= L_('TXT:HISTORY_AUTOCLEAN_HINT') ?></span>
            </div>
          </div>
          <?php // B (max age) then C (keep) — only shown while autoclean (A) is on ?>
          <div id="tkfg-history-clean-<?= $h ?>"<?= $preset['history_autoclean'] === '1' ? '' : ' style="display:none"' ?>>
            <div class="setting-row">
              <label for="tkfg-history-ttl-<?= $h ?>"><?= L_('TXT:HISTORY_TTL') ?></label>
              <div class="setting-control">
                <input type="number" id="tkfg-history-ttl-<?= $h ?>" class="tkfg-history-ttl" min="1" max="3650" step="1" value="<?= (int) $preset['history_ttl_days'] ?>" style="width:70px">
                <span class="hint"><?= L_('TXT:HISTORY_TTL_HINT') ?></span>
              </div>
            </div>
            <div class="setting-row">
              <label for="tkfg-history-keep-<?= $h ?>"><?= L_('TXT:HISTORY_KEEP') ?></label>
              <div class="setting-control">
                <input type="number" id="tkfg-history-keep-<?= $h ?>" class="tkfg-history-keep" min="1" max="100" step="1" value="<?= (int) $preset['history_keep'] ?>" style="width:70px">
                <span class="hint"><?= L_('TXT:HISTORY_KEEP_HINT') ?></span>
              </div>
            </div>
          </div>
        </div>
      </details>

      <details class="tkfg-sub" id="tkfg-sub-paste-<?= $h ?>">
        <summary><i class="fa fa-image tkfg-sub-icon" aria-hidden="true"></i><?= L_('TXT:PASTE_SETTINGS') ?> <span class="tkfg-sub-hint"><?= L_('TXT:PASTE_SETTINGS_HINT') ?></span></summary>
        <div class="tkfg-sub-body" id="tkfg-paste-<?= $h ?>">
          <div class="setting-row">
            <label for="tkfg-paste-images-<?= $h ?>"><?= L_('TXT:PASTE_IMAGES') ?></label>
            <div class="setting-control">
              <input type="checkbox" id="tkfg-paste-images-<?= $h ?>" class="tkfg-paste-images" <?= $preset['paste_images'] === '1' ? 'checked' : '' ?>>
              <span class="hint"><?= L_('TXT:PASTE_IMAGES_HINT') ?></span>
            </div>
          </div>
          <div class="setting-row">
            <label for="tkfg-paste-folder-<?= $h ?>"><?= L_('TXT:PASTE_FOLDER') ?></label>
            <div class="setting-control">
              <code style="opacity:.7"><?= tmce_h(MEDIA_DIRECTORY) ?>/[<?= L_('TXT:PASTE_HOME') ?>]/</code>
              <input type="text" id="tkfg-paste-folder-<?= $h ?>" class="tkfg-paste-folder" value="<?= tmce_h($preset['paste_folder']) ?>" style="width:140px" placeholder="pasted">
              <span class="hint"><?= L_('TXT:PASTE_FOLDER_HINT') ?></span>
            </div>
          </div>
          <div class="setting-row">
            <label for="tkfg-paste-webp-<?= $h ?>"><?= L_('TXT:PASTE_WEBP') ?></label>
            <div class="setting-control">
              <input type="checkbox" id="tkfg-paste-webp-<?= $h ?>" class="tkfg-paste-webp" <?= ($preset['paste_webp'] ?? '0') === '1' ? 'checked' : '' ?>>
              <label style="opacity:.8"><?= L_('TXT:PASTE_WEBP_QUALITY') ?>
                <input type="number" id="tkfg-paste-webp-quality-<?= $h ?>" class="tkfg-paste-webp-quality" min="1" max="100" step="1" value="<?= (int) ($preset['paste_webp_quality'] ?? 82) ?>" style="width:64px">
              </label>
              <span class="hint"><?= L_('TXT:PASTE_WEBP_HINT') ?></span>
            </div>
          </div>
          <div class="setting-row">
            <label for="tkfg-img-dblclick-<?= $h ?>"><?= L_('TXT:IMG_DBLCLICK') ?></label>
            <div class="setting-control">
              <input type="checkbox" id="tkfg-img-dblclick-<?= $h ?>" class="tkfg-img-dblclick" <?= $preset['img_dblclick'] === '1' ? 'checked' : '' ?>>
              <span class="hint"><?= L_('TXT:IMG_DBLCLICK_HINT') ?></span>
            </div>
          </div>
          <div class="setting-row">
            <label for="tkfg-img-size-badge-<?= $h ?>"><?= L_('TXT:IMG_SIZE_BADGE') ?></label>
            <div class="setting-control">
              <input type="checkbox" id="tkfg-img-size-badge-<?= $h ?>" class="tkfg-img-size-badge" <?= $preset['img_size_badge'] === '1' ? 'checked' : '' ?>>
              <span class="hint"><?= L_('TXT:IMG_SIZE_BADGE_HINT') ?></span>
            </div>
          </div>
          <div class="setting-row">
            <label for="tkfg-alt-reminder-<?= $h ?>"><?= L_('TXT:ALT_REMINDER') ?></label>
            <div class="setting-control">
              <input type="checkbox" id="tkfg-alt-reminder-<?= $h ?>" class="tkfg-alt-reminder" <?= ($preset['alt_reminder'] ?? '1') !== '0' ? 'checked' : '' ?>>
              <span class="hint"><?= L_('TXT:ALT_REMINDER_HINT') ?></span>
            </div>
          </div>
        </div>
      </details>

      <details class="tkfg-sub" id="tkfg-sub-linkopts-<?= $h ?>">
        <summary><i class="fa fa-link tkfg-sub-icon" aria-hidden="true"></i><?= L_('TXT:LINKOPTS_SETTINGS') ?> <span class="tkfg-sub-hint"><?= L_('TXT:LINKOPTS_HINT') ?></span></summary>
        <div class="tkfg-sub-body" id="tkfg-linkopts-<?= $h ?>">
          <label class="tkfg-sub-label" for="tkfg-link-classes-<?= $h ?>"><?= L_('TXT:LINKOPTS_CLASSES') ?>:</label>
          <textarea id="tkfg-link-classes-<?= $h ?>" class="tkfg-link-classes tkfg-fonts-extra" rows="3" placeholder="btn&#10;btn-primary"><?= tmce_h(implode("\n", tinymce_wbce_link_tokens($preset['link_classes']))) ?></textarea>
          <label class="tkfg-sub-label" for="tkfg-link-rels-<?= $h ?>"><?= L_('TXT:LINKOPTS_RELS') ?>:</label>
          <textarea id="tkfg-link-rels-<?= $h ?>" class="tkfg-link-rels tkfg-fonts-extra" rows="3" placeholder="nofollow&#10;noopener"><?= tmce_h(implode("\n", tinymce_wbce_link_tokens($preset['link_rels']))) ?></textarea>
        </div>
      </details>

      <div class="tkfg-actions">
        <div class="tkfg-actions-left">
          <button type="button" class="btn-refresh" id="tkfg-btn-refresh-<?= $h ?>"><i class="fa fa-refresh"></i> <?= L_('TXT:REFRESH') ?></button>
          <label class="liveupdate-label">
            <input type="checkbox" id="tkfg-liveupdate-<?= $h ?>" style="accent-color:#1a73e8;width:14px;height:14px;">
            <?= L_('TXT:LIVE_UPDATE') ?>
          </label>
        </div>
        <div class="tkfg-actions-right">
          <button type="button" class="btn-save" id="tkfg-save-btn-<?= $h ?>"><?= L_('TXT:SAVE') ?></button>
        </div>
      </div>

      <div class="local-info">
        <span class="tkfg-local-badge" id="tkfg-local-badge-<?= $h ?>" style="display:none;background:#f59e0b;color:#fff;border-radius:3px;padding:2px 8px;font-size:11px;font-weight:bold"><?= L_('TXT:LOCAL_ACTIVE') ?></span>
        <button type="button" class="tkfg-clear-local-btn" id="tkfg-clear-local-btn-<?= $h ?>"
                style="display:none;font-size:11px;color:#fff;background:#c0392b;border:none;border-radius:3px;cursor:pointer;padding:2px 10px;font-weight:bold"><?= L_('TXT:LOCAL_CLEAR') ?></button>
      </div>

      <div class="preview-card">
        <div class="preview-header"><strong><?= L_('TXT:LIVE_PREVIEW') ?></strong></div>
        <div id="tkfg-editor-wrap-<?= $h ?>"><textarea id="tkfg-mce-<?= $h ?>"></textarea></div>
      </div>

      <div class="section-label"><?= L_('TXT:SMALL_TOOLBAR', '<span id="tkfg-minimal-height-label-' . $h . '">' . (int)$preset['auto_minimal_height'] . '</span>') ?></div>
      <div style="margin-bottom:8px;display:flex;align-items:center;gap:10px;">
        <label style="font-size:.8rem;color:#555;display:flex;align-items:center;gap:6px;">
          <input type="checkbox" id="tkfg-auto-minimal-<?= $h ?>" style="accent-color:#1a73e8" <?= $preset['auto_minimal'] === '1' ? 'checked' : '' ?>>
          <?= L_('TXT:AUTO_MINIMAL_LABEL') ?>
          <input type="number" id="tkfg-auto-minimal-height-<?= $h ?>" value="<?= (int)$preset['auto_minimal_height'] ?>"
                 min="50" max="500" step="10" style="width:64px;padding:2px 6px;border:1px solid #ccc;border-radius:4px;font-size:.82rem">
          px
        </label>
      </div>
      <div class="section-label" style="font-size:.78rem;color:#555;margin-bottom:4px;"><?= L_('TXT:AVAILABLE_BUTTONS') ?></div>
      <div class="palette" id="tkfg-palette-min-<?= $h ?>"><span class="palette-loading"><?= L_('TXT:LOADING_ICONS') ?></span></div>
      <div id="tkfg-minimal-rows-<?= $h ?>">
        <div class="row-wrapper"><div class="row-label"><?= L_('TXT:ROW', 1) ?></div><div class="toolbar-row" id="tkfg-row-m0-<?= $h ?>"></div></div>
      </div>

      <div class="preview-card" id="tkfg-mini-wrap-<?= $h ?>" style="display:none;margin-top:12px">
        <div class="preview-header">
          <strong><?= L_('TXT:PREVIEW_SMALL') ?></strong>
          <span style="font-size:.75rem;color:#888;margin-left:8px"><?= L_('TXT:PREVIEW_SMALL_HINT', (int)$preset['auto_minimal_height']) ?></span>
        </div>
        <div id="tkfg-mini-editor-wrap-<?= $h ?>"><textarea id="tkfg-mce-min-<?= $h ?>"></textarea></div>
      </div>
    </div><!-- /.tkfg -->
    <?php
    return ob_get_clean();
}

/** JSON data island for a preset — always present in the header. */
function tmce_data_island(string $id, array $preset): string {
    $data = [
        'toolbar_row1'        => $preset['toolbar_row1'],
        'toolbar_row2'        => $preset['toolbar_row2'],
        'toolbar_row3'        => $preset['toolbar_row3'],
        'toolbar_minimal'     => $preset['toolbar_minimal'],
        'auto_minimal'        => $preset['auto_minimal'],
        'auto_minimal_height' => $preset['auto_minimal_height'],
        'height'              => $preset['height'],
        'show_toolbar'        => $preset['show_toolbar'],
        'toolbar_sliding'     => $preset['toolbar_sliding'],
        'skin'                => $preset['skin'],
        'menubar'             => $preset['menubar'],
        'statusbar'           => $preset['statusbar'],
        'wordcount'           => $preset['wordcount'] ?? '0',
        'content_theme'       => $preset['content_theme'],
        'editor_css_source'   => $preset['editor_css_source'] ?? 'auto',
        'font_families'       => $preset['font_families'],
        'font_sizes'          => $preset['font_sizes'],
        'font_size_unit'      => $preset['font_size_unit'],
        'block_formats'       => $preset['block_formats'],
        'style_formats'       => $preset['style_formats'],
        'editorcss_classes'         => $preset['editorcss_classes'],
        'editorcss_selector_filter' => $preset['editorcss_selector_filter'],
        'color_mode'          => $preset['color_mode'],
        'colors_shared'       => $preset['colors_shared'],
        'colors_fore'         => $preset['colors_fore'],
        'colors_back'         => $preset['colors_back'],
        'colors_icons'        => $preset['colors_icons'],
        'color_cols'          => $preset['color_cols'],
        'custom_colors'       => $preset['custom_colors'],
        'history_enabled'     => $preset['history_enabled'],
        'history_max'         => $preset['history_max'],
        'history_keep'        => $preset['history_keep'],
        'history_autoclean'   => $preset['history_autoclean'],
        'history_ttl_days'    => $preset['history_ttl_days'],
        'paste_images'        => $preset['paste_images'],
        'paste_folder'        => $preset['paste_folder'],
        'paste_webp'          => $preset['paste_webp'] ?? '0',
        'paste_webp_quality'  => $preset['paste_webp_quality'] ?? '82',
        'img_dblclick'        => $preset['img_dblclick'],
        'img_size_badge'      => $preset['img_size_badge'],
        'alt_reminder'        => $preset['alt_reminder'] ?? '1',
        'link_classes'        => $preset['link_classes'],
        'link_rels'           => $preset['link_rels'],
    ];
    return '<script type="application/json" id="tkfg-data-' . tmce_h($id) . '">'
        . json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        . '</script>';
}

/**
 * Faithful mini-toolbar preview for a collapsed header: every non-empty row,
 * rendered with the real TinyMCE icons (extracted server-side, see icons.php).
 * Dropdowns and text buttons fall back to short labels.
 */
function tmce_preview_chips(array $preset): string {
    $icons     = tinymce_wbce_button_icons();
    $dropdowns = ['blocks' => 'Paragraph', 'fontfamily' => 'Font', 'fontsize' => '12px', 'styles' => 'Styles', 'wbce_casechange' => 'Aa'];
    $textBtns  = ['wbdroplets' => '[[]]'];

    $out = '';
    foreach (['toolbar_row1', 'toolbar_row2', 'toolbar_row3'] as $key) {
        $tokens = preg_split('/\s+/', trim((string)$preset[$key]), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        if (!$tokens) continue;
        $chips = '';
        foreach ($tokens as $tok) {
            if ($tok === '|')                { $chips .= '<span class="tmce-pv-sep"></span>'; continue; }
            if (isset($icons[$tok]))         { $chips .= '<span class="tmce-pv-icon">' . $icons[$tok] . '</span>'; continue; }
            if (isset($dropdowns[$tok]))     { $chips .= '<span class="tmce-pv-dd">' . tmce_h($dropdowns[$tok]) . '</span>'; continue; }
            $label = $textBtns[$tok] ?? $tok;
            $chips .= '<span class="tmce-pv-txt">' . tmce_h($label) . '</span>';
        }
        $out .= '<span class="tmce-pv-row">' . $chips . '</span>';
    }
    return $out;
}

/**
 * Single-row icon preview for an inline (FEE) preset toolbar — same icon chips as
 * tmce_preview_chips(), but for one toolbar string (mirrors the JS in
 * inline-presets.js so server- and client-rendered previews match).
 */
function tmce_inline_preview_chips(string $toolbar): string {
    $icons     = tinymce_wbce_button_icons();
    $dropdowns = ['blocks' => 'Paragraph', 'fontfamily' => 'Font', 'fontsize' => '12px', 'styles' => 'Styles', 'wbce_casechange' => 'Aa'];
    $textBtns  = ['wbdroplets' => '[[]]'];

    $tokens = preg_split('/\s+/', trim($toolbar), -1, PREG_SPLIT_NO_EMPTY) ?: [];
    $chips  = '';
    foreach ($tokens as $tok) {
        if ($tok === '|')            { $chips .= '<span class="tmce-pv-sep"></span>'; continue; }
        if (isset($icons[$tok]))     { $chips .= '<span class="tmce-pv-icon">' . $icons[$tok] . '</span>'; continue; }
        if (isset($dropdowns[$tok])) { $chips .= '<span class="tmce-pv-dd">' . tmce_h($dropdowns[$tok]) . '</span>'; continue; }
        $chips .= '<span class="tmce-pv-txt">' . tmce_h($textBtns[$tok] ?? $tok) . '</span>';
    }
    return '<span class="tmce-pv-row">' . $chips . '</span>';
}

// ── Endpoints (fragment responses) ───────────────────────────────────────────
// The AdminTool runner already buffered the admin page header (Admin::print_header
// → ob_start) BEFORE this file runs. Discard every open buffer so the endpoint
// emits a clean fragment, not the whole admin page. Auth already ran in the
// runner, so discarding output here is safe.
function tmce_begin_fragment(): void {
    while (ob_get_level() > 0) { ob_end_clean(); }
}

$action = $_GET['action'] ?? '';

if ($action === 'panel') {
    tmce_begin_fragment();
    header('Cache-Control: no-store'); // panel reflects live settings — never cache
    $id  = tmce_pid($_GET['id'] ?? '');
    $cfg = tinymce_wbce_load_cfg();
    $preset = $cfg['presets'][$id] ?? tinymce_wbce_resolve_preset($id);
    echo tmce_render_panel_body($id, $preset, $modUrl);
    exit;
}

if ($action === 'save_preset') {
    tmce_begin_fragment();
    header('Content-Type: text/html; charset=utf-8');
    $id      = tmce_pid($_POST['id'] ?? '');
    $payload = json_decode($_POST['preset_json'] ?? '[]', true);
    if (!is_array($payload)) $payload = [];

    $cfg = tinymce_wbce_load_cfg();
    if ($id === '' || !isset($cfg['presets'][$id])) {
        http_response_code(400);
        exit;
    }
    $allowed = ['toolbar_row1','toolbar_row2','toolbar_row3','toolbar_minimal',
                'auto_minimal','auto_minimal_height','height','skin',
                'menubar','statusbar','wordcount','content_theme','show_toolbar','toolbar_sliding',
                'font_families','font_sizes','font_size_unit','block_formats','style_formats',
                'editorcss_classes','editorcss_selector_filter','editor_css_source',
                'color_mode','colors_shared','colors_fore','colors_back','colors_icons','color_cols','custom_colors',
                'history_enabled','history_max','history_keep','history_autoclean','history_ttl_days',
                'paste_images','paste_folder','paste_webp','paste_webp_quality','img_dblclick','img_size_badge','alt_reminder',
                'link_classes','link_rels'];
    $merged  = array_merge($cfg['presets'][$id], array_intersect_key($payload, array_flip($allowed)));

    // Guard: a broken style_formats JSON must never reach the editor
    if (($merged['style_formats'] ?? '') !== '') {
        $decoded = json_decode($merged['style_formats'], true);
        $merged['style_formats'] = is_array($decoded) && $decoded
            ? json_encode(array_values($decoded), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            : '';
    }
    // #1 editor.css class picker: constrain the mode; trim the selector filter.
    if (!in_array($merged['editorcss_classes'] ?? 'off', ['off','auto','annotated'], true)) {
        $merged['editorcss_classes'] = 'off';
    }
    $merged['editorcss_selector_filter'] = trim((string)($merged['editorcss_selector_filter'] ?? ''));
    $merged['wordcount'] = (($merged['wordcount'] ?? '0') === '1') ? '1' : '0';
    if (!in_array($merged['font_size_unit'] ?? 'px', ['px','pt','em','rem'], true)) {
        $merged['font_size_unit'] = 'px';
    }

    // Color swatches: whitelist the mode, sanitize every color via the core
    // sanitizeCssColor() allowlist, clamp the grid columns.
    if (!in_array($merged['color_mode'] ?? '', ['', 'shared', 'split'], true)) {
        $merged['color_mode'] = '';
    }
    foreach (['colors_shared', 'colors_fore', 'colors_back', 'colors_icons'] as $ckey) {
        $merged[$ckey] = tinymce_wbce_sanitize_color_list((string)($merged[$ckey] ?? ''));
    }
    $cols = trim((string)($merged['color_cols'] ?? ''));
    $merged['color_cols']    = ($cols !== '' && (int)$cols >= 1) ? (string)min(12, (int)$cols) : '';
    $merged['custom_colors'] = ($merged['custom_colors'] ?? '1') === '0' ? '0' : '1';

    // Version history: on/off, clamp counts, keep <= max.
    $merged['history_enabled'] = (($merged['history_enabled'] ?? '1') === '0') ? '0' : '1';
    $hMax  = max(1, min(100, (int)($merged['history_max']  ?? 10)));
    $hKeep = max(1, min(100, (int)($merged['history_keep'] ?? 10)));
    $merged['history_max']  = (string) $hMax;
    $merged['history_keep'] = (string) min($hKeep, $hMax);
    $merged['history_autoclean'] = (($merged['history_autoclean'] ?? '0') === '1') ? '1' : '0';
    $merged['history_ttl_days']  = (string) max(1, min(3650, (int)($merged['history_ttl_days'] ?? 90)));

    // Paste images: on/off + sanitized sub-folder.
    $merged['paste_images'] = (($merged['paste_images'] ?? '0') === '1') ? '1' : '0';
    $merged['paste_folder'] = tinymce_wbce_paste_sanitize_folder((string)($merged['paste_folder'] ?? 'pasted'));
    $merged['paste_webp'] = (($merged['paste_webp'] ?? '0') === '1') ? '1' : '0';
    $merged['paste_webp_quality'] = (string) max(1, min(100, (int)($merged['paste_webp_quality'] ?? 82)));
    $merged['img_dblclick']   = (($merged['img_dblclick'] ?? '0') === '1') ? '1' : '0';
    $merged['img_size_badge'] = (($merged['img_size_badge'] ?? '0') === '1') ? '1' : '0';
    $merged['alt_reminder']   = (($merged['alt_reminder']   ?? '0') === '1') ? '1' : '0';

    // Link-dialog option lists: sanitize to clean, de-duplicated tokens.
    $merged['link_classes'] = implode("\n", tinymce_wbce_link_tokens((string)($merged['link_classes'] ?? '')));
    $merged['link_rels']    = implode("\n", tinymce_wbce_link_tokens((string)($merged['link_rels'] ?? '')));
    $cfg['presets'][$id] = tinymce_wbce_normalize_preset($merged);
    Settings::set('tinymce_cfg', json_encode($cfg, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

    echo tmce_preview_chips($cfg['presets'][$id]); // refreshed collapsed preview strip
    exit;
}

if ($action === 'set_default') {
    tmce_begin_fragment();
    header('Content-Type: application/json');
    $id  = tmce_pid($_POST['id'] ?? '');
    $cfg = tinymce_wbce_load_cfg();
    if ($id !== '' && isset($cfg['presets'][$id])) {
        $cfg['default_preset'] = $id;
        Settings::set('tinymce_cfg', json_encode($cfg, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
    echo json_encode(['success' => true, 'default' => $cfg['default_preset']]);
    exit;
}

// #2 Export one preset as a downloadable JSON envelope (portable between installs).
if ($action === 'export_preset') {
    tmce_begin_fragment();
    $id  = tmce_pid($_GET['id'] ?? '');
    $cfg = tinymce_wbce_load_cfg();
    if ($id === '' || !isset($cfg['presets'][$id])) { http_response_code(404); exit; }
    $preset = $cfg['presets'][$id];
    $env = [
        '_type'    => 'tinymce_wbce_preset',
        '_version' => 2,
        'id'       => $id,
        'label'    => tmce_preset_label($id, $preset),
        'preset'   => $preset,
    ];
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename="tinymce-preset-' . $id . '.json"');
    echo json_encode($env, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

// #2 Import a preset from a JSON envelope → stored under a fresh, non-colliding id.
if ($action === 'import_preset') {
    tmce_begin_fragment();
    header('Content-Type: application/json');
    $env = json_decode((string)($_POST['preset_json'] ?? ''), true);
    if (!is_array($env) || ($env['_type'] ?? '') !== 'tinymce_wbce_preset' || !is_array($env['preset'] ?? null)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'invalid']);
        exit;
    }
    $cfg  = tinymce_wbce_load_cfg();
    $mode = (($_POST['mode'] ?? '') === 'overwrite') ? 'overwrite' : 'copy';
    $base = tmce_pid((string)($env['id'] ?? 'imported'));
    if ($base === '') { $base = 'imported'; }
    if ($mode === 'overwrite' && isset($cfg['presets'][$base])) {
        $id = $base;                       // replace the colliding preset in place
    } else {
        $id = $base; $n = 2;               // copy → next free id
        while (isset($cfg['presets'][$id])) { $id = $base . '-' . $n; $n++; }
    }
    $preset = tinymce_wbce_normalize_preset($env['preset']);
    if (trim((string)($env['label'] ?? '')) !== '') { $preset['label'] = (string)$env['label']; }
    $cfg['presets'][$id] = $preset;
    Settings::set('tinymce_cfg', json_encode($cfg, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    echo json_encode(['success' => true, 'id' => $id]);
    exit;
}

// #2b Rename (label only — the id is an API key modules reference). Custom presets
// only; built-in labels are translated/fixed.
if ($action === 'rename_preset') {
    tmce_begin_fragment();
    header('Content-Type: application/json');
    $id  = tmce_pid($_POST['id'] ?? '');
    $lbl = trim((string)($_POST['label'] ?? ''));
    $cfg = tinymce_wbce_load_cfg();
    if ($id === '' || !isset($cfg['presets'][$id]) || in_array($id, TMCE_BUILTIN_PRESETS, true) || $lbl === '') {
        http_response_code(400); echo json_encode(['success' => false]); exit;
    }
    $cfg['presets'][$id]['label'] = mb_substr($lbl, 0, 64);
    Settings::set('tinymce_cfg', json_encode($cfg, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    echo json_encode(['success' => true, 'label' => $cfg['presets'][$id]['label']]);
    exit;
}

// #2b Delete — custom presets only; built-ins are re-seeded by load_cfg anyway.
if ($action === 'delete_preset') {
    tmce_begin_fragment();
    header('Content-Type: application/json');
    $id  = tmce_pid($_POST['id'] ?? '');
    $cfg = tinymce_wbce_load_cfg();
    if ($id === '' || !isset($cfg['presets'][$id]) || in_array($id, TMCE_BUILTIN_PRESETS, true)) {
        http_response_code(400); echo json_encode(['success' => false]); exit;
    }
    unset($cfg['presets'][$id]);
    if (($cfg['default_preset'] ?? '') === $id) { $cfg['default_preset'] = 'standard'; }
    Settings::set('tinymce_cfg', json_encode($cfg, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    echo json_encode(['success' => true]);
    exit;
}

// #2b Reset a built-in preset back to its shipped defaults.
if ($action === 'reset_preset') {
    tmce_begin_fragment();
    header('Content-Type: application/json');
    $id       = tmce_pid($_POST['id'] ?? '');
    $cfg      = tinymce_wbce_load_cfg();
    $builtins = tinymce_wbce_builtin_presets();
    if ($id === '' || !isset($builtins[$id])) {
        http_response_code(400); echo json_encode(['success' => false]); exit;
    }
    $cfg['presets'][$id] = $builtins[$id];
    Settings::set('tinymce_cfg', json_encode($cfg, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    echo json_encode(['success' => true]);
    exit;
}

// ── Inline (FEE) presets — the second, lightweight list ──────────────────────

// Persist one inline preset (toolbar + the few appearance fields + label).
if ($action === 'save_inline_preset') {
    tmce_begin_fragment();
    header('Content-Type: application/json');
    $id  = tmce_pid($_POST['id'] ?? '');
    $cfg = tinymce_wbce_load_cfg();
    if ($id === '' || !isset($cfg['inline_presets'][$id])) {
        http_response_code(400); echo json_encode(['success' => false]); exit;
    }
    $merged = array_merge($cfg['inline_presets'][$id], [
        'toolbar'    => (string) ($_POST['toolbar']    ?? ''),
        'min_height' => (string) ($_POST['min_height'] ?? '0'),
        'skin'       => (string) ($_POST['skin']       ?? 'oxide'),
        'menubar'    => (($_POST['menubar'] ?? '0') === '1') ? '1' : '0',
    ]);
    if (isset($_POST['label'])) { $merged['label'] = mb_substr(trim((string) $_POST['label']), 0, 64); }
    $cfg['inline_presets'][$id] = tinymce_wbce_normalize_inline_preset($merged);
    Settings::set('tinymce_cfg', json_encode($cfg, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    echo json_encode(['success' => true, 'toolbar' => $cfg['inline_presets'][$id]['toolbar']]);
    exit;
}

// Add a new inline preset (seeded from the built-in default toolbar).
if ($action === 'add_inline_preset') {
    tmce_begin_fragment();
    header('Content-Type: application/json');
    $cfg   = tinymce_wbce_load_cfg();
    $label = mb_substr(trim((string) ($_POST['label'] ?? '')), 0, 64);
    // Derive an id from the label, else "inline"; ensure it is free.
    $base = tmce_pid(strtolower(str_replace(' ', '-', $label)));
    if ($base === '') { $base = 'inline'; }
    $id = $base; $n = 2;
    while (isset($cfg['inline_presets'][$id])) { $id = $base . '-' . $n; $n++; }
    $seed = tinymce_wbce_builtin_inline_presets()['default'];
    $seed['label'] = ($label !== '') ? $label : $id;
    $cfg['inline_presets'][$id] = tinymce_wbce_normalize_inline_preset($seed);
    Settings::set('tinymce_cfg', json_encode($cfg, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    echo json_encode(['success' => true, 'id' => $id]);
    exit;
}

// Delete an inline preset — the 'default' one is protected (always present).
if ($action === 'delete_inline_preset') {
    tmce_begin_fragment();
    header('Content-Type: application/json');
    $id  = tmce_pid($_POST['id'] ?? '');
    $cfg = tinymce_wbce_load_cfg();
    if ($id === '' || $id === 'default' || !isset($cfg['inline_presets'][$id])) {
        http_response_code(400); echo json_encode(['success' => false]); exit;
    }
    unset($cfg['inline_presets'][$id]);
    if (($cfg['default_inline_preset'] ?? '') === $id) { $cfg['default_inline_preset'] = 'default'; }
    Settings::set('tinymce_cfg', json_encode($cfg, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    echo json_encode(['success' => true]);
    exit;
}

// Choose which inline preset is the default (what FEE uses when none is named).
if ($action === 'set_default_inline') {
    tmce_begin_fragment();
    header('Content-Type: application/json');
    $id  = tmce_pid($_POST['id'] ?? '');
    $cfg = tinymce_wbce_load_cfg();
    if ($id !== '' && isset($cfg['inline_presets'][$id])) {
        $cfg['default_inline_preset'] = $id;
        Settings::set('tinymce_cfg', json_encode($cfg, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
    echo json_encode(['success' => true, 'default' => $cfg['default_inline_preset'] ?? 'default']);
    exit;
}

// ── Main page ────────────────────────────────────────────────────────────────
$cfg      = tinymce_wbce_load_cfg();
$presets  = $cfg['presets'];
$default  = $cfg['default_preset'];

// Button tooltips for the configurator palette — from the BTN language namespace
$btnLabelsJson = json_encode($GLOBALS['BTN'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

// Assets — module-own files get an mtime cache-buster so edits always reach
// the browser without a hard reload (same rationale as the link plugin in
// include.php; AssetQueue keeps existing query strings intact).
$tmceVer = function (string $rel) use ($modUrl): string {
    return $modUrl . $rel . '?v=' . (@filemtime(__DIR__ . $rel) ?: 1);
};
Alerts::ensureToastAssets(); // window.showToast() for save feedback
I::insertJsFile(WB_URL . '/include/htmx/htmx.min.js', 'head_early');
loadPlugin('include/wbeColoris');
// wbeSelect.css defines the --ws-* theme variables; MicroModal's own CSS
// depends on them (no fallbacks) — both are already the proven stack behind
// the tinymce_wbce link plugin's dialog. Used here for the import-collision
// confirm dialog, so it shares that exact look instead of a bespoke overlay.
loadPlugin('include/wbeSelect');
loadPlugin('include/micromodal');

I::insertCssFile($tmceVer('/css/tool.css'));
I::insertCssFile($tmceVer('/css/toolbar-configurator.css'));
I::insertCssFile($tmceVer('/css/preset-accordion.css'));

$tkfgConfig = [
    'modUrl'        => $modUrl,
    'toolUrl'       => $toolUrl,
    'lang'          => (defined('LANGUAGE') && strtoupper(LANGUAGE) === 'DE') ? 'de' : 'en',
    'cmButton'      => file_exists(WB_PATH . '/modules/CodeMirror_Config/CodeEditor.php') ? 'wbcodemirror' : 'code',
    'defaultPreset' => $default,
    'sizeDefaults'  => tinymce_wbce_size_defaults(),
    'faCssUrl'      => WB_URL . '/include/font-awesome/css/font-awesome.min.css',
    'contentExtrasUrl' => $tmceVer('/css/content-extras.css'),
    'presetIds'     => array_keys($presets),   // for the import-collision dialog
];
$tkfgI18n = [
    'dragHere'    => L_('TXT:DRAG_HERE'),
    'remove'      => L_('TXT:REMOVE'),
    'separator'   => L_('TXT:SEPARATOR'),
    'alreadyUsed' => L_('TXT:ALREADY_USED'),
    'save'        => L_('TXT:SAVE'),
    'saveLocal'   => L_('TXT:SAVE_LOCAL'),
    'saveOk'      => L_('MSG:SAVE_OK'),
    'saveError'   => L_('MSG:SAVE_ERROR'),
    'savedMsg'    => L_('MSG:SAVED'),
    'saveFailed'  => L_('MSG:SAVE_FAILED'),
    'defaultSet'  => L_('TXT:DEFAULT_SET'),
    'renamePrompt'   => L_('TXT:PRESET_RENAME_PROMPT'),
    'deleteConfirm'  => L_('TXT:PRESET_DELETE_CONFIRM'),
    'resetConfirm'   => L_('TXT:PRESET_RESET_CONFIRM'),
    'importExists'   => L_('TXT:IMPORT_EXISTS'),
    'importOverwrite'=> L_('TXT:IMPORT_OVERWRITE'),
    'importCopy'     => L_('TXT:IMPORT_COPY'),
    'importTitle'    => L_('TXT:PRESET_IMPORT'),
    'cancel'         => L_('TXT:CANCEL'),
];
$iconsJson = json_encode(tinymce_wbce_button_icons(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
I::insertJsCode(
    'const BUTTON_LABELS = ' . $btnLabelsJson . ';'
    . 'window.TKFG_ICONS = ' . $iconsJson . ';'
    . 'window.TKFG_CONFIG = ' . json_encode($tkfgConfig, JSON_UNESCAPED_SLASHES) . ';'
    . 'window.TKFG_I18N = ' . json_encode($tkfgI18n, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . ';'
);
I::insertJsFile($modUrl . '/tinymce/tinymce.min.js');
I::insertJsFile($tmceVer('/js/fontsize-label-fix.js'));
I::insertJsFile($tmceVer('/js/image-alt-badge.js'));
I::insertJsFile($tmceVer('/js/preset-configurator.js'));
I::insertJsFile($tmceVer('/js/preset-accordion.js'));
I::insertJsFile($tmceVer('/js/inline-presets.js'));

// Preferred preset order: built-ins first (in canonical order), then custom user presets
$order = [];
foreach (['standard','full','custom','minimal'] as $bid) {
    if (isset($presets[$bid])) $order[] = $bid;
}
foreach (array_keys($presets) as $pid) {
    if (!in_array($pid, $order, true)) $order[] = $pid;
}

// Inline (FEE) presets — the second, lightweight list. 'default' is guaranteed
// first by tinymce_wbce_load_inline_presets().
$inlinePresets = $cfg['inline_presets'] ?? [];
$defInline     = $cfg['default_inline_preset'] ?? 'default';
$inlineOrder   = array_keys($inlinePresets);
$uiSkins       = tmce_list_dirs(WB_PATH . '/modules/tinymce_wbce/tinymce/skins/ui');
?>

<section class="cp-main">
    <nav class="cp-tabs tabs-right">
        <ul class="pane-tabs">
            <li class="area-title">TinyMCE Configuration</li>
        </ul>
    </nav>

    <div class="tmce-cfg-section">
        <h3 class="tmce-inline-title"><?= L_('TXT:TOOLBAR_PROFILES') ?></h3>
        <p class="tmce-inline-subhint"><?= L_('TXT:TOOLBAR_PROFILES_HINT') ?></p>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var inp = document.getElementById('tkfg-import-file');
        if (!inp) { return; }
        var C = window.TKFG_CONFIG || {}, I18N = window.TKFG_I18N || {};
        var url = inp.getAttribute('data-import-url');

        function send(text, mode) {
            var fd = new FormData();
            fd.set('preset_json', text);
            if (mode) { fd.set('mode', mode); }
            fetch(url, { method: 'POST', body: fd, credentials: 'same-origin' })
                .then(function (r) { return r.json().catch(function () { return null; }); })
                .then(function (res) {
                    if (res && res.success) {
                        if (window.showToast) { window.showToast(I18N.savedMsg || 'Imported', 'success'); }
                        window.location.reload();
                    } else if (window.showToast) {
                        window.showToast(I18N.saveError || 'Import failed', 'error');
                    }
                })
                .catch(function () {
                    if (window.showToast) { window.showToast(I18N.saveError || 'Import failed', 'error'); }
                });
        }

        // 3-way choice when the imported id already exists. Built as a
        // MicroModal dialog — same markup/CSS as the tinymce_wbce link
        // plugin's dialog (include/micromodal/modal.css, themed via
        // include/wbeSelect/wbeSelect.css's --ws-* variables) instead of a
        // bespoke overlay, so it isn't missing the theme those variables
        // come from and doesn't drift from the rest of the module's dialogs.
        var COLLISION_MODAL_ID = 'tmce-import-collision-modal';

        function askCollision(id, onChoose) {
            var existing = document.getElementById(COLLISION_MODAL_ID);
            if (existing) { existing.remove(); }

            var modal = document.createElement('div');
            modal.id = COLLISION_MODAL_ID;
            modal.className = 'modal micromodal-slide';
            modal.setAttribute('aria-hidden', 'true');
            modal.innerHTML = ''
                + '<div class="modal__overlay" tabindex="-1" data-micromodal-close>'
                +   '<div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="tmce-import-title">'
                +     '<header class="modal__header">'
                +       '<h1 class="dialog__title" id="tmce-import-title"></h1>'
                +       '<button type="button" class="modal__close" data-micromodal-close aria-label="close"></button>'
                +     '</header>'
                +     '<main class="modal__content"><p></p></main>'
                +     '<footer class="modal__footer">'
                +       '<button type="button" class="modal__btn modal__btn-primary" data-c="overwrite"></button>'
                +       '<button type="button" class="modal__btn" data-c="copy"></button>'
                +       '<button type="button" class="modal__btn" data-micromodal-close></button>'
                +     '</footer>'
                +   '</div>'
                + '</div>';

            modal.querySelector('#tmce-import-title').textContent = I18N.importTitle || 'Import preset';
            modal.querySelector('.modal__close').setAttribute('aria-label', I18N.cancel || 'Cancel');
            modal.querySelector('.modal__content p').textContent =
                (I18N.importExists || 'A configuration with id "%s" already exists.').replace('%s', id);
            var btns = modal.querySelectorAll('.modal__btn');
            btns[0].textContent = I18N.importOverwrite || 'Overwrite';
            btns[1].textContent = I18N.importCopy || 'Add as copy';
            btns[2].textContent = I18N.cancel || 'Cancel';

            modal.addEventListener('click', function (e) {
                var btn = e.target.closest('[data-c]');
                if (btn) { window.MicroModal.close(COLLISION_MODAL_ID); onChoose(btn.getAttribute('data-c')); }
            });
            document.body.appendChild(modal);
            window.MicroModal.show(COLLISION_MODAL_ID, { onClose: function (m) { m.remove(); } });
        }

        inp.addEventListener('change', function () {
            var file = inp.files && inp.files[0];
            if (!file) { return; }
            var reader = new FileReader();
            reader.onload = function () {
                var text = String(reader.result || ''), envId = '';
                try { var env = JSON.parse(text); envId = (env && env.id) ? String(env.id) : ''; } catch (e) {}
                var known = C.presetIds || [];
                if (envId && known.indexOf(envId) !== -1) {
                    askCollision(envId, function (mode) { send(text, mode); });
                } else {
                    send(text, 'copy');
                }
                inp.value = ''; // allow re-importing the same file
            };
            reader.readAsText(file);
        });
    });
    </script>

    <div class="tmce-preset-accordion cp-pane-inlay" id="tmce-preset-accordion">
        <?php foreach ($order as $id):
            $preset    = $presets[$id];
            $isDefault = ($id === $default);
            $h         = tmce_h($id);
        ?>
        <?php $isBuiltin = in_array($id, TMCE_BUILTIN_PRESETS, true); ?>
        <div class="tmce-preset" data-preset="<?= $h ?>">
            <?= tmce_data_island($id, $preset) ?>
            <div class="tmce-preset-head" role="button" tabindex="0" aria-expanded="false"
                 hx-get="<?= tmce_h($toolUrl) ?>&action=panel&id=<?= $h ?>"
                 hx-target="#tkfg-panel-<?= $h ?>" hx-swap="innerHTML" hx-trigger="tkfg:load">
                <span class="tmce-chev">▸</span>
                <span class="tmce-preset-name"><?= tmce_h(tmce_preset_label($id, $preset)) ?></span>
                <div class="tmce-preset-preview" id="tmce-preview-<?= $h ?>"><?= tmce_preview_chips($preset) ?></div>
                <span class="tmce-badges">
                    <button type="button" class="tmce-badge tmce-badge-default<?= $isDefault ? ' on' : '' ?>"
                            data-act="default" data-preset="<?= $h ?>"><?= L_('TXT:BADGE_DEFAULT') ?></button>
                    <button type="button" class="tmce-badge tmce-badge-local"
                            data-act="local" data-preset="<?= $h ?>"><?= L_('TXT:BADGE_LOCAL') ?></button>
                    <button type="button"
                            class="tmce-badge tmce-badge-export"
                            title="<?= tmce_h(L_('TXT:PRESET_EXPORT')) ?>"
                            onclick="event.stopPropagation(); window.location.href='<?= tmce_h($toolUrl) ?>&action=export_preset&id=<?= $h ?>';">
                        <i class="fa fa-download"></i>
                    </button>
                    <?php if ($isBuiltin): ?>
                    <button type="button" class="tmce-badge tmce-badge-reset" data-act="reset" data-preset="<?= $h ?>"
                            title="<?= tmce_h(L_('TXT:PRESET_RESET')) ?>"><i class="fa fa-undo"></i></button>
                    <?php else: ?>
                    <button type="button" class="tmce-badge tmce-badge-rename" data-act="rename" data-preset="<?= $h ?>"
                            title="<?= tmce_h(L_('TXT:PRESET_RENAME')) ?>"><i class="fa fa-pencil"></i></button>
                    <button type="button" class="tmce-badge tmce-badge-delete" data-act="delete" data-preset="<?= $h ?>"
                            title="<?= tmce_h(L_('TXT:PRESET_DELETE')) ?>"><i class="fa fa-trash"></i></button>
                    <?php endif; ?>
                </span>
            </div>
            <div class="tmce-panel-body" id="tkfg-panel-<?= $h ?>" hidden></div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Import lives AFTER the config profiles (it imports full presets). -->
    <div class="tmce-preset-tools tmce-cfg-section" id="tmce-import-tools">
        <label class="tmce-btn-import">
            <input type="file" accept="application/json,.json" id="tkfg-import-file" hidden
                   data-import-url="<?= tmce_h($toolUrl) ?>&amp;action=import_preset">
            <?= L_('TXT:PRESET_IMPORT') ?>
        </label>
        <span class="hint"><?= L_('TXT:PRESET_IMPORT_HINT') ?></span>
    </div>

    <!--     
        Inline (FEE) presets — the second, lightweight list.
        Leave this hook to FEE here. (Christian)
    -->
    <?php 
    $useFee = file_exists(WB_PATH . '/modules/fee/info.php');
    if($useFee == true):
    ?>
    <div class="tmce-cfg-section">
        <h3 class="tmce-inline-title"><?= L_('TXT:INLINE_PRESETS') ?></h3>
        <p class="tmce-inline-subhint"><?= L_('TXT:INLINE_PRESETS_HINT') ?></p>
    </div>

    <div class="tmce-inline-accordion cp-pane-inlay" id="tmce-inline-accordion"
         data-save-url="<?= tmce_h($toolUrl) ?>&amp;action=save_inline_preset"
         data-add-url="<?= tmce_h($toolUrl) ?>&amp;action=add_inline_preset"
         data-delete-url="<?= tmce_h($toolUrl) ?>&amp;action=delete_inline_preset"
         data-default-url="<?= tmce_h($toolUrl) ?>&amp;action=set_default_inline">
        <?php foreach ($inlineOrder as $iid):
            $ip      = $inlinePresets[$iid];
            $ih      = tmce_h($iid);
            $isDefIn = ($iid === $defInline);
            $ipLabel = ($ip['label'] ?? '') !== '' ? $ip['label'] : $iid;
            $island  = [
                'label'      => (string) ($ip['label'] ?? ''),
                'toolbar'    => (string) ($ip['toolbar'] ?? ''),
                'min_height' => (string) ($ip['min_height'] ?? '0'),
                'skin'       => (string) ($ip['skin'] ?? 'oxide'),
                'menubar'    => (string) ($ip['menubar'] ?? '0'),
            ];
        ?>
        <div class="tmce-inline-preset" data-inline="<?= $ih ?>">
            <script type="application/json" class="tmce-inline-data"><?= json_encode($island, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
            <div class="tmce-inline-head" role="button" tabindex="0" aria-expanded="false">
                <span class="tmce-chev">▸</span>
                <span class="tmce-inline-name"><?= tmce_h($ipLabel) ?></span>
                <div class="tmce-inline-tb-preview"><?= tmce_inline_preview_chips($ip['toolbar'] ?? '') ?></div>
                <span class="tmce-badges">
                    <button type="button" class="tmce-badge tmce-badge-default<?= $isDefIn ? ' on' : '' ?>"
                            data-iact="default" data-inline="<?= $ih ?>"><?= L_('TXT:BADGE_DEFAULT') ?></button>
                    <?php if ($iid !== 'default'): ?>
                    <button type="button" class="tmce-badge tmce-badge-delete" data-iact="delete" data-inline="<?= $ih ?>"
                            title="<?= tmce_h(L_('TXT:PRESET_DELETE')) ?>">🗑</button>
                    <?php endif; ?>
                </span>
            </div>
            <div class="tmce-inline-body" id="tmce-inline-body-<?= $ih ?>" hidden>
                <div class="tkfg tmce-inline-cfg">
                    <div class="section-label"><?= L_('TXT:INLINE_TOOLBAR') ?></div>
                    <p class="hint tmce-inline-cfg-hint"><?= L_('TXT:INLINE_TOOLBAR_HINT') ?></p>
                    <div class="palette" id="tmce-inline-palette-<?= $ih ?>"></div>
                    <div class="row-wrapper"><div class="toolbar-row" id="tmce-inline-row-<?= $ih ?>"></div></div>

                    <div class="setting-row">
                        <label for="tmce-inline-name-<?= $ih ?>"><?= L_('TXT:INLINE_NAME') ?></label>
                        <div class="setting-control">
                            <input type="text" id="tmce-inline-name-<?= $ih ?>" class="tmce-inline-f-label"
                                   value="<?= tmce_h($ip['label'] ?? '') ?>"<?= $iid === 'default' ? ' placeholder="Default"' : '' ?>>
                            <span class="hint"><?= L_('TXT:INLINE_NAME_HINT') ?></span>
                        </div>
                    </div>
                    <div class="setting-row">
                        <label for="tmce-inline-minh-<?= $ih ?>"><?= L_('TXT:INLINE_MIN_HEIGHT') ?></label>
                        <div class="setting-control">
                            <input type="number" id="tmce-inline-minh-<?= $ih ?>" class="tmce-inline-f-minheight"
                                   min="0" max="2000" step="10" value="<?= (int) ($ip['min_height'] ?? 0) ?>" style="width:80px">
                            <span class="hint"><?= L_('TXT:INLINE_MIN_HEIGHT_HINT') ?></span>
                        </div>
                    </div>
                    <div class="setting-row">
                        <label for="tmce-inline-skin-<?= $ih ?>"><?= L_('TXT:UI_SKIN') ?></label>
                        <div class="setting-control">
                            <select id="tmce-inline-skin-<?= $ih ?>" class="tmce-inline-f-skin">
                                <?php foreach ($uiSkins as $s): ?>
                                <option value="<?= tmce_h($s) ?>"<?= ($ip['skin'] ?? '') === $s ? ' selected' : '' ?>><?= tmce_h($s) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="hint"><?= L_('TXT:INLINE_SKIN_HINT') ?></span>
                        </div>
                    </div>
                    <div class="setting-row">
                        <label for="tmce-inline-menubar-<?= $ih ?>"><?= L_('TXT:MENUBAR') ?></label>
                        <div class="setting-control">
                            <input type="checkbox" id="tmce-inline-menubar-<?= $ih ?>" class="tmce-inline-f-menubar" <?= ($ip['menubar'] ?? '0') === '1' ? 'checked' : '' ?>>
                            <span class="hint"><?= L_('TXT:MENUBAR_HINT') ?></span>
                        </div>
                    </div>

                    <div class="tkfg-actions">
                        <div class="tkfg-actions-right">
                            <button type="button" class="tmce-inline-save tmce-btn-import" data-inline="<?= $ih ?>"><?= L_('TXT:SAVE') ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <div class="tmce-inline-add-row">
            <input type="text" id="tmce-inline-add-name" placeholder="<?= tmce_h(L_('TXT:INLINE_ADD_PLACEHOLDER')) ?>" style="max-width:220px">
            <button type="button" id="tmce-inline-add-btn" class="tmce-btn-import">+ <?= L_('TXT:INLINE_ADD') ?></button>
        </div>
    </div>
    <?php endif; ?>

    <div class="cp-buttons-row">
        <button type="button" class="tmce-btn-cancel"
                onclick="window.location.href='<?= ADMIN_URL ?>/admintools/index.php'">&laquo; <?= L_('TXT:BACK') ?></button>
    </div>
</section><!-- /.cp-main -->
