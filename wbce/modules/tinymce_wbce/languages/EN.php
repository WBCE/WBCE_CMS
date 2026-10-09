<?php
/**
 * tinymce_wbce — languages/EN.php
 * English base language file — every key must exist here.
 * Other languages only need to override the keys that differ.
 *
 * Namespaces:
 *   TXT — UI labels (admin tool + editor plugin dialogs)
 *   MSG — status / feedback messages
 *   BTN — toolbar button tooltips (feeds the drag&drop configurator palette)
 *
 * @author  Slugger & Claude KI
 * @license GNU GPL2
 */

// ── Admin tool: tabs ─────────────────────────────────────────────────────────
$TXT['BASIC']                = 'Basic';
$TXT['CUSTOM']               = 'Custom toolbar';

// ── Admin tool: tab 1 (basic settings) ───────────────────────────────────────
$TXT['DEFAULT_TOOLBAR']      = 'Default toolbar';
$TXT['PRESET_MINIMAL']       = 'Minimal — 1 row, basic buttons';
$TXT['PRESET_STANDARD']      = 'Standard — 3 rows (recommended)';
$TXT['PRESET_FULL']          = 'Full — 3 rows + font family/size';
$TXT['PRESET_CUSTOM']        = 'Custom (tab 2)';
$TXT['DEFAULT_TOOLBAR_HINT'] = 'Applies to all new WYSIWYG blocks — fine-tune in tab 2';
$TXT['CONTENT_THEME']        = 'Content theme';
$TXT['CONTENT_THEME_HINT']   = 'CSS inside the editor content area';
$TXT['EDITORCSS_SOURCE']       = 'editor.css source';
$TXT['EDITORCSS_SOURCE_HINT']  = 'Which editor.css layer this profile loads into the editor content area. Per-profile on purpose — different fields on the same page can show different in-editor CSS.';
$TXT['EDITORCSS_SOURCE_AUTO']      = 'Auto (template → wb_config → module default)';
$TXT['EDITORCSS_SOURCE_TEMPLATE']  = 'Page template only';
$TXT['EDITORCSS_SOURCE_WB_CONFIG'] = 'templates/wb_config/editor.css';
$TXT['EDITORCSS_SOURCE_DEFAULT']   = 'Module default (editor_default.css)';
$TXT['EDITORCSS_SOURCE_NONE']      = 'None (content theme only)';
$TXT['EDITORCSS_MAY_OVERRIDE'] = 'This content theme may be overridden by this profile\'s editor.css source (below) if it resolves to an existing file:';
$TXT['TOOLBAR_PROFILES']      = 'Toolbar profiles';
$TXT['TOOLBAR_PROFILES_HINT'] = 'Full editor profiles for the backend/admin editor — toolbar layout, styling, images and version history. The profile marked "Default" (or "Only me", if set) is used by default. Some modules can, however, specify their own profile for individual fields.';
$TXT['INLINE_TOOLBAR']      = 'Inline editor toolbar (FEE)';
$TXT['INLINE_TOOLBAR_HINT'] = 'Compact toolbar for in-place frontend editing. TinyMCE toolbar syntax, groups separated by "&#124;" (e.g. undo redo &#124; bold italic &#124; link). Empty = default.';
// Inline (FEE) presets — the second, lightweight list
$TXT['INLINE_PRESETS']         = 'Inline profiles (FEE)';
$TXT['INLINE_PRESETS_HINT']    = 'A separate, lightweight list for in-place frontend editing. Fewer settings than a full profile — one drag-and-drop toolbar row and a little styling. Choose which one is the default for inline editing.';
$TXT['INLINE_MIN_HEIGHT']      = 'Minimum height';
$TXT['INLINE_MIN_HEIGHT_HINT'] = 'Minimum height of the editable area in the frontend. 0 = grow with content (px otherwise).';
$TXT['INLINE_SKIN_HINT']       = 'Appearance of the floating toolbar (buttons, dropdowns) shown while editing inline. Has no effect on the page\'s own styling.';
$TXT['INLINE_ADD']             = 'Add inline profile';
$TXT['INLINE_ADD_PLACEHOLDER'] = 'New profile name…';
$TXT['INLINE_NAME']            = 'Profile name';
$TXT['INLINE_NAME_HINT']       = 'Internal display name for this profile (shown in the list above).';
$TXT['EDITORCSS_CLASSES']      = 'CSS classes from editor.css';
$TXT['EDITORCSS_CLASSES_HINT'] = 'offer the template\'s CSS classes in the Styles dropdown';
$TXT['EDITORCSS_CLASSES_OFF']       = 'Off';
$TXT['EDITORCSS_CLASSES_AUTO']      = 'Auto (all classes, importcss)';
$TXT['EDITORCSS_CLASSES_ANNOTATED'] = 'Only annotated (/* tinymce: label="…" */)';
$TXT['EDITORCSS_FILTER_HINT']  = 'Auto only: regex to narrow selectors (e.g. ^(btn&#124;text)-)';

// ── Admin tool: tab 2 (toolbar configurator) ─────────────────────────────────
$TXT['QUICK_CONFIG']         = 'Quick configuration';
$TXT['MINIMAL']              = 'Minimal';
$TXT['STANDARD']             = 'Standard';
$TXT['FULL']                 = 'Full';
$TXT['AVAILABLE_BUTTONS_DRAG'] = 'Available buttons — drag onto a row';
$TXT['AVAILABLE_BUTTONS']    = 'Available buttons';
$TXT['TOOLBAR_ROWS']         = 'Toolbar rows (fill freely or leave empty)';
$TXT['ROW']                  = 'Row %s';
$TXT['LOADING_ICONS']        = 'Loading icons…';
$TXT['DRAG_HERE']            = 'Drag buttons here…';
$TXT['EDITOR_SETTINGS']      = 'Editor settings';
$TXT['DEFAULT_HEIGHT']       = 'Default height';
$TXT['MENUBAR']              = 'Menu bar';
$TXT['MENUBAR_HINT']         = 'Show menu bar (File, Edit, View…)';
$TXT['STATUSBAR']            = 'Status bar';
$TXT['STATUSBAR_HINT']       = 'Show status bar (p › strong, word count…)';
$TXT['WORDCOUNT']            = 'Word count';
$TXT['WORDCOUNT_HINT']       = 'show word/character count in the status bar (needs status bar on)';
$TXT['UI_SKIN']              = 'UI skin';
$TXT['UI_SKIN_HINT']         = 'Place new skins in <code>tinymce/skins/ui/</code>';
$TXT['REFRESH']              = 'Refresh preview';
$TXT['RESET']                = 'Reset';
$TXT['LIVE_UPDATE']          = 'Live update';
$TXT['LIVE_PREVIEW']         = 'Live preview';
$TXT['REMOVE']               = 'Remove';
$TXT['SEPARATOR']            = 'Separator';
$TXT['ALREADY_USED']         = 'already used';

// ── Admin tool: small (auto-minimal) toolbar ─────────────────────────────────
$TXT['SMALL_TOOLBAR']        = 'Small toolbar (editor height &lt; %s px)';
$TXT['AUTO_MINIMAL_LABEL']   = 'Enable automatically when editor height &lt;';
$TXT['PREVIEW_SMALL']        = 'Live preview — small toolbar';
$TXT['PREVIEW_SMALL_HINT']   = '(editor height &lt; %s px)';

// ── Admin tool: personal (localStorage) configuration ───────────────────────
$TXT['LOCAL_ACTIVE']         = '👤 Personal configuration active';
$TXT['LOCAL_CLEAR']          = '✕ Delete personal configuration';
$TXT['SAVE_LOCAL']           = '👤 For me only';

// ── Admin tool: actions ──────────────────────────────────────────────────────
$TXT['SAVE_SETTINGS']        = 'Save settings';
$TXT['SAVE']                 = 'Save';
$TXT['BACK']                 = 'Back';

// ── Admin tool: preset accordion ─────────────────────────────────────────────
$TXT['BADGE_DEFAULT']        = 'Default';
$TXT['BADGE_LOCAL']          = 'Only me';
$TXT['PRESET_EXPORT']        = 'Export';
$TXT['PRESET_IMPORT']        = 'Import preset…';
$TXT['PRESET_IMPORT_HINT']   = 'load a preset from a .json file exported here';
$TXT['PRESET_RENAME']        = 'Rename';
$TXT['PRESET_DELETE']        = 'Delete';
$TXT['PRESET_RESET']         = 'Reset to defaults';
$TXT['PRESET_RENAME_PROMPT'] = 'New name for this preset:';
$TXT['PRESET_DELETE_CONFIRM']= 'Delete this preset? Modules still using it fall back to the default preset.';
$TXT['PRESET_RESET_CONFIRM'] = 'Reset this built-in preset to its shipped defaults?';
$TXT['IMPORT_EXISTS']        = 'A configuration with id "%s" already exists.';
$TXT['IMPORT_OVERWRITE']     = 'Overwrite';
$TXT['IMPORT_COPY']          = 'Add as copy';
$TXT['DEFAULT_SET']          = 'Default preset updated.';

// ── Admin tool: typography & formats (per preset) ────────────────────────────
$TXT['TYPOGRAPHY']           = 'Typography & formats';
$TXT['FONTS']                = 'Font families';
$TXT['CUSTOM_FONTS']         = 'Custom entries (one per line, Name=css,stack)';
$TXT['FONT_SIZES']           = 'Font sizes';
$TXT['SIZE_UNIT']            = 'Unit';
$TXT['SIZE_LIST']            = 'Size list';
$TXT['USE_DEFAULTS']         = 'Use defaults';
$TXT['BLOCK_FORMATS']        = 'Paragraph formats & custom styles';
$TXT['BLOCK_FORMATS_HINT']   = '"Paragraph" dropdown + "Styles" dropdown';
$TXT['CUSTOM_STYLES']        = 'Custom styles (shown in the "Styles" dropdown)';
$TXT['CUSTOM_STYLES_HINT']   = 'Important: custom styles live in the "Styles" dropdown — replace the "Paragraph" button with "Styles" in a toolbar row above. It shows your paragraph formats plus these styles in one list.';
$TXT['STYLE_CLASS']          = 'CSS class';
$TXT['ADD']                  = 'Add';
$TXT['EMPTY_DEFAULT_HINT']   = 'empty = TinyMCE default';
$TXT['SHOW_TOOLBAR']         = 'Show toolbar';
$TXT['SHOW_TOOLBAR_HINT']    = 'off = editor without any toolbar';
$TXT['TOOLBAR_SLIDING']      = 'Compact toolbar';
$TXT['TOOLBAR_SLIDING_HINT'] = 'one row — the rest opens via the "…" button at the end';
$TXT['COLORS']               = 'Color swatches (font color & marker)';
$TXT['COLORS_HINT']          = 'Applies to the "Text color" (forecolor) and "Background color" (backcolor) toolbar buttons — add them to a toolbar row above to use these palettes.';
$TXT['COLOR_MODE']           = 'Palette mode';
$TXT['COLOR_MODE_DEFAULT']   = 'TinyMCE default palette';
$TXT['COLOR_MODE_SHARED']    = 'One shared palette for both';
$TXT['COLOR_MODE_SPLIT']     = 'Separate palettes: font color / marker';
$TXT['COLOR_SHARED']         = 'Shared palette';
$TXT['COLOR_FONT']           = 'Font color palette';
$TXT['COLOR_BACK']           = 'Marker palette (background)';
$TXT['COLOR_ICONS']          = 'Icon palette (Font Awesome — empty = font/shared palette)';
$TXT['COLOR_LABEL']          = 'Label';
$TXT['COLOR_COLS']           = 'Swatch columns';
$TXT['COLOR_COLS_AUTO']      = 'auto';
$TXT['CUSTOM_COLORS']        = 'Allow custom color picker';

// ── Editor plugins: shared ───────────────────────────────────────────────────
$TXT['INSERT']               = 'Insert';
$TXT['CANCEL']               = 'Cancel';
$TXT['APPLY']                = 'Apply';

// ── Editor plugin: wbce_shy ──────────────────────────────────────────────────
$TXT['SHY_TOOLTIP']          = 'Soft hyphen (break opportunity)';

// ── Editor plugin: wbce_casechange ───────────────────────────────────────────
$TXT['CASE_TOOLTIP']         = 'Change case';
$TXT['CASE_LOWER']           = 'lowercase';
$TXT['CASE_UPPER']           = 'UPPERCASE';
$TXT['CASE_TITLE']           = 'Title Case';
$TXT['CASE_SMALLCAPS']       = 'Small caps';

// ── Editor plugin: wbce_history ──────────────────────────────────────────────
$TXT['HISTORY_TITLE']        = 'Version history';
$TXT['HISTORY_CHOOSE']       = 'Choose a version to restore:';
$TXT['HISTORY_RESTORE']      = 'Restore this version';
$TXT['HISTORY_RESTORED']     = 'Version restored.';
$TXT['HISTORY_NONE']         = 'No saved versions for this field yet.';
$TXT['HISTORY_CLOSE']        = 'Close';
$TXT['HISTORY_CURRENT']      = 'Current';
// Admin-tool settings (per preset)
$TXT['HISTORY_SETTINGS']     = 'Version history';
$TXT['HISTORY_SETTINGS_HINT']= 'per this toolbar preset';
$TXT['HISTORY_ENABLED']      = 'Enable version history';
$TXT['HISTORY_ENABLED_HINT'] = 'off = record no new versions';
$TXT['HISTORY_MAX']          = 'Max. versions per field';
$TXT['HISTORY_MAX_HINT']     = 'oldest are dropped beyond this';
$TXT['HISTORY_KEEP']         = 'Keep on automatic cleanup';
$TXT['HISTORY_KEEP_HINT']    = 'most recent versions the cleanup always retains';
$TXT['HISTORY_AUTOCLEAN']    = 'Auto-delete old versions';
$TXT['HISTORY_AUTOCLEAN_HINT']= 'drop versions older than the age below (beyond the kept ones)';
$TXT['HISTORY_TTL']          = 'Max. age (days)';
$TXT['HISTORY_TTL_HINT']     = 'versions older than this are pruned on next save/open';
$TXT['HISTORY_DELETE']       = 'Delete this version';
// Paste images (per preset)
$TXT['PASTE_SETTINGS']       = 'Paste images';
$TXT['PASTE_SETTINGS_HINT']  = 'clipboard images → upload to the media folder';
$TXT['PASTE_IMAGES']         = 'Upload pasted images';
$TXT['PASTE_IMAGES_HINT']    = 'off = keep TinyMCE default (embed as data URI)';
$TXT['PASTE_FOLDER']         = 'Target sub-folder';
$TXT['PASTE_FOLDER_HINT']    = 'placed after the user\'s home folder, if any';
$TXT['PASTE_WEBP']           = 'Convert to WebP';
$TXT['PASTE_WEBP_QUALITY']   = 'Quality';
$TXT['PASTE_WEBP_HINT']      = 'pasted images are saved as .webp (falls back to the original if unsupported)';
$TXT['PASTE_HOME']           = 'home';
$TXT['IMG_DBLCLICK']         = 'Double-click image → file browser';
$TXT['IMG_DBLCLICK_HINT']    = 'opens elFinder to pick a replacement instead of the image dialog';
$TXT['IMG_SIZE_BADGE']       = 'Show size badge on images';
$TXT['IMG_SIZE_BADGE_HINT']  = 'shows the size as % of the original; click resets to 100%';
$TXT['IMG_BADGE_RESET']      = 'Click to reset to 100%';
$TXT['ALT_REMINDER']         = 'Alt-text reminder';
$TXT['ALT_REMINDER_HINT']    = 'mark images with a missing alt attribute (reminder only, no enforcement)';
$TXT['IMG_ALT_MISSING']      = 'Missing alt attribute';
// Link dialog option lists (per preset)
$TXT['LINKOPTS_SETTINGS']    = 'Link options';
$TXT['LINKOPTS_HINT']        = 'selectable class / rel values in the link dialog';
$TXT['LINKOPTS_CLASSES']     = 'CSS classes (one per line)';
$TXT['LINKOPTS_RELS']        = 'rel values (one per line)';

// ── Editor plugin: wbce_codesample ───────────────────────────────────────────
$TXT['CODESAMPLE_TITLE']     = 'Insert/edit code sample';
$TXT['CODESAMPLE_LANGUAGE']  = 'Language:';

// ── Editor plugin: fa_picker (Font Awesome icons) ────────────────────────────
$TXT['FA_TITLE']             = 'Insert Font Awesome icon';
$TXT['FA_SEARCH']            = 'Search icons…';
$TXT['FA_SIZE']              = 'Size';
$TXT['FA_COLOR']             = 'Color';
$TXT['FA_INHERIT']           = 'inherit';
$TXT['FA_NO_RESULTS']        = 'No icons found.';

// ── Editor plugin: wblink (internal page links) ──────────────────────────────
$TXT['WBLINK_TITLE']         = 'Insert internal link';
$TXT['WBLINK_SEARCH']        = 'Search (ID or title)…';
$TXT['WBLINK_TEXT']          = 'Link text';
$TXT['WBLINK_OPEN_IN']       = 'Open in';
$TXT['WBLINK_SAME_TAB']      = 'Same tab (_self)';
$TXT['WBLINK_NEW_TAB']       = 'New tab (_blank)';
$TXT['WBLINK_NO_PAGES']      = 'No pages available.';
$TXT['WBLINK_NO_RESULTS']    = 'No pages found.';

// ── Editor plugin: link (merged link dialog) ─────────────────────────────────
$TXT['LINK']                 = 'Link';
$TXT['INTERNAL_LINK']        = 'Internal link';
$TXT['EMAIL']                = 'Email';
$TXT['PHONE']                = 'Phone';
$TXT['URL']                  = 'URL';
$TXT['TEXT_TO_DISPLAY']      = 'Text to display';
$TXT['TITLE']                = 'Title';
$TXT['TARGET']               = 'Target';
$TXT['CSS_CLASS']            = 'Class';
$TXT['REL']                  = 'Rel';
$TXT['SAME_WINDOW']          = 'Same window';
$TXT['NEW_WINDOW']           = 'New window';
$TXT['BREAK_FRAMESET']       = 'Break out of frameset';
$TXT['SUBJECT']              = 'Subject';
$TXT['MESSAGE']              = 'Message';
$TXT['LINK_DIALOG_TITLE']    = 'Insert/edit link';
$TXT['LINK_PAGE_ITEM']       = 'Or a specific item on this page';
$TXT['LINKIMG_TAB']          = 'Media';
$TXT['LINKIMG_URL']          = 'File URL';
$TXT['LINKIMG_BROWSE']       = 'Choose file…';
$TXT['LINK_STYLE']           = 'Style (inline CSS)';

// ── Editor plugin: wbdroplets ────────────────────────────────────────────────
$TXT['DROPLET_TITLE']        = 'Insert droplet';
$TXT['DROPLET_NONE']         = 'No droplets available.';

// ── Editor plugin: wbcodemirror + CodeMirror popup ───────────────────────────
$TXT['SOURCE_CODE']          = 'Source code';
$TXT['CM_TOOLTIP']           = 'Source code (CodeMirror)';
$TXT['CM_SHORTCUT_HINT']     = 'Ctrl+Enter = Apply · Esc = Cancel';

// ── Messages ─────────────────────────────────────────────────────────────────
$MSG['SAVED']                = 'Settings saved.';
$MSG['SAVE_FAILED']          = 'Saving failed.';
$MSG['SAVE_OK']              = '✓ Saved';
$MSG['SAVE_ERROR']           = '✗ Error';

// ── Toolbar button tooltips (configurator palette) ───────────────────────────
$BTN['bold']                 = 'Bold';
$BTN['italic']               = 'Italic';
$BTN['underline']            = 'Underline';
$BTN['strikethrough']        = 'Strikethrough';
$BTN['removeformat']         = 'Remove formatting';
$BTN['forecolor']            = 'Text color';
$BTN['backcolor']            = 'Background color';
$BTN['alignleft']            = 'Align left';
$BTN['aligncenter']          = 'Align center';
$BTN['alignright']           = 'Align right';
$BTN['alignjustify']         = 'Justify';
$BTN['bullist']              = 'Bullet list';
$BTN['numlist']              = 'Numbered list';
$BTN['outdent']              = 'Decrease indent';
$BTN['indent']               = 'Increase indent';
$BTN['blockquote']           = 'Blockquote';
$BTN['link']                 = 'Insert/edit link';
$BTN['wblink']               = 'WBCE link';
$BTN['image']                = 'Insert/edit image';
$BTN['media']                = 'Insert/edit media';
$BTN['table']                = 'Table';
$BTN['wbdroplets']           = 'WBCE droplets';
$BTN['fa_picker']            = 'Font Awesome icon';
$BTN['wbce_casechange']      = 'Change case (Aa)';
$BTN['wbce_codesample']      = 'Code sample';
$BTN['wbce_history']         = 'Version history';
$BTN['hr']                   = 'Horizontal rule';
$BTN['wbce_shy']             = 'Soft hyphen';
$BTN['lineheight']           = 'Line height';
$BTN['emoticons']            = 'Emojis';
$BTN['subscript']            = 'Subscript';
$BTN['superscript']          = 'Superscript';
$BTN['charmap']              = 'Special character';
$BTN['code']                 = 'Source code';
$BTN['wbcodemirror']         = 'Source code (CodeMirror)';
$BTN['fullscreen']           = 'Fullscreen';
$BTN['undo']                 = 'Undo';
$BTN['redo']                 = 'Redo';
$BTN['blocks']               = 'Paragraph format';
$BTN['styles']               = 'Styles';
$BTN['fontfamily']           = 'Font family';
$BTN['fontsize']             = 'Font size';
$BTN['|']                    = 'Separator — reusable';