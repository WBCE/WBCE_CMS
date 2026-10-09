<?php
/**
 * tinymce_wbce — languages/DE.php
 * German translation — only keys that differ from languages/EN.php.
 * Missing keys fall back to the English base automatically.
 *
 * @author  Slugger & Claude KI
 * @license GNU GPL2
 */

$module_name        = 'TinyMCE Konfigurator';
$module_description = 'Einstellungen für das Aussehen und Features des TinyMCE Editors und seiner Toolbar.';

// ── Admin tool: tabs ─────────────────────────────────────────────────────────
$TXT['BASIC']                = 'Allgemein';
$TXT['CUSTOM']               = 'Benutzerdefinierte Toolbar';

// ── Admin tool: tab 1 (basic settings) ───────────────────────────────────────
$TXT['DEFAULT_TOOLBAR']      = 'Standard-Toolbar';
$TXT['PRESET_MINIMAL']       = 'Minimal — 1 Zeile, Basis-Buttons';
$TXT['PRESET_STANDARD']      = 'Standard — 3 Zeilen (empfohlen)';
$TXT['PRESET_FULL']          = 'Full — 3 Zeilen + Schriftart/Größe';
$TXT['PRESET_CUSTOM']        = 'Benutzerdefiniert (Tab 2)';
$TXT['DEFAULT_TOOLBAR_HINT'] = 'Gilt für alle neuen WYSIWYG-Blöcke — Feineinstellung in Tab 2';
$TXT['CONTENT_THEME_HINT']   = 'CSS im Editor-Inhalt-Bereich';
$TXT['EDITORCSS_SOURCE']       = 'editor.css-Quelle';
$TXT['EDITORCSS_SOURCE_HINT']  = 'Welche editor.css-Schicht DIESES Profil in den Editor-Inhalt-Bereich lädt. Bewusst pro Profil — verschiedene Felder auf derselben Seite können unterschiedliches Editor-CSS zeigen.';
$TXT['EDITORCSS_SOURCE_AUTO']      = 'Automatisch (Template → wb_config → Modul-Default)';
$TXT['EDITORCSS_SOURCE_TEMPLATE']  = 'Nur Seiten-Template';
$TXT['EDITORCSS_SOURCE_WB_CONFIG'] = 'templates/wb_config/editor.css';
$TXT['EDITORCSS_SOURCE_DEFAULT']   = 'Modul-Default (editor_default.css)';
$TXT['EDITORCSS_SOURCE_NONE']      = 'Keine (nur Content-Theme)';
$TXT['EDITORCSS_MAY_OVERRIDE'] = 'Dieses Content-Theme wird möglicherweise durch die editor.css-Quelle dieses Profils (unten) überschrieben, sofern sie auf eine vorhandene Datei zeigt:';
$TXT['TOOLBAR_PROFILES']      = 'Toolbar-Profile';
$TXT['TOOLBAR_PROFILES_HINT'] = 'Vollständige Editor-Profile für den Backend-/Admin-Editor — Toolbar-Aufbau, Styling, Bilder und Versionshistorie. Das als „Standard" markierte Profil (oder „Nur für mich", falls gesetzt) wird standardmäßig verwendet. Einige Module können für bestimmte Eingabe-Felder jedoch ein eigenes Profil vorgeben.';
$TXT['INLINE_TOOLBAR']      = 'Inline-Editor-Toolbar (FEE)';
$TXT['INLINE_TOOLBAR_HINT'] = 'Kompakte Toolbar fürs In-Place-Editieren im Frontend. TinyMCE-Toolbar-Syntax, Gruppen mit „&#124;" getrennt (z.B. undo redo &#124; bold italic &#124; link). Leer = Standard.';
// Inline (FEE) presets — die zweite, schlanke Liste
$TXT['INLINE_PRESETS']         = 'Inline-Profile (FEE)';
$TXT['INLINE_PRESETS_HINT']    = 'Eine separate, schlanke Liste fürs In-Place-Editieren im Frontend. Weniger Einstellungen als ein volles Profil — eine Drag-and-Drop-Toolbar-Reihe und etwas Styling. Wähle, welches Profil für inline der Standard ist.';
$TXT['INLINE_MIN_HEIGHT']      = 'Mindesthöhe';
$TXT['INLINE_MIN_HEIGHT_HINT'] = 'Mindesthöhe des editierbaren Bereichs im Frontend. 0 = wächst mit dem Inhalt (sonst px).';
$TXT['INLINE_SKIN_HINT']       = 'Aussehen der schwebenden Toolbar (Buttons, Dropdowns) beim Inline-Editieren. Hat keinen Einfluss auf das Styling der Seite selbst.';
$TXT['INLINE_ADD']             = 'Inline-Profil hinzufügen';
$TXT['INLINE_ADD_PLACEHOLDER'] = 'Name des neuen Profils…';
$TXT['INLINE_NAME']            = 'Profilname';
$TXT['INLINE_NAME_HINT']       = 'Interner Anzeigename dieses Profils (erscheint in der Liste oben).';
$TXT['EDITORCSS_CLASSES']      = 'CSS-Klassen aus editor.css';
$TXT['EDITORCSS_CLASSES_HINT'] = 'die CSS-Klassen des Templates im Styles-Dropdown anbieten';
$TXT['EDITORCSS_CLASSES_OFF']       = 'Aus';
$TXT['EDITORCSS_CLASSES_AUTO']      = 'Automatisch (alle Klassen, importcss)';
$TXT['EDITORCSS_CLASSES_ANNOTATED'] = 'Nur annotierte (/* tinymce: label="…" */)';
$TXT['EDITORCSS_FILTER_HINT']  = 'Nur bei Auto: Regex zum Eingrenzen der Selektoren (z.B. ^(btn&#124;text)-)';

// ── Admin tool: tab 2 (toolbar configurator) ─────────────────────────────────
$TXT['QUICK_CONFIG']         = 'Schnellkonfiguration';
$TXT['FULL']                 = 'Vollständig';
$TXT['AVAILABLE_BUTTONS_DRAG'] = 'Verfügbare Buttons — auf eine Zeile ziehen';
$TXT['AVAILABLE_BUTTONS']    = 'Verfügbare Buttons';
$TXT['TOOLBAR_ROWS']         = 'Toolbar-Zeilen (beliebig bestücken oder freilassen)';
$TXT['ROW']                  = 'Zeile %s';
$TXT['LOADING_ICONS']        = 'Lade Icons…';
$TXT['DRAG_HERE']            = 'Buttons hierher ziehen…';
$TXT['EDITOR_SETTINGS']      = 'Editor-Einstellungen';
$TXT['DEFAULT_HEIGHT']       = 'Standardhöhe';
$TXT['MENUBAR']              = 'Menüleiste';
$TXT['MENUBAR_HINT']         = 'Menüleiste anzeigen (File, Edit, View…)';
$TXT['STATUSBAR']            = 'Statusleiste';
$TXT['STATUSBAR_HINT']       = 'Statusleiste anzeigen (p › strong, Wortanzahl…)';
$TXT['WORDCOUNT']            = 'Wortzähler';
$TXT['WORDCOUNT_HINT']       = 'Wort-/Zeichenanzahl in der Statusleiste (Statusleiste muss an sein)';
$TXT['UI_SKIN_HINT']         = 'Neue Skins in <code>tinymce/skins/ui/</code> ablegen';
$TXT['REFRESH']              = 'Vorschau aktualisieren';
$TXT['RESET']                = 'Zurücksetzen';
$TXT['LIVE_UPDATE']          = 'Live-Update';
$TXT['LIVE_PREVIEW']         = 'Live-Vorschau';
$TXT['REMOVE']               = 'Entfernen';
$TXT['SEPARATOR']            = 'Trennlinie';
$TXT['ALREADY_USED']         = 'bereits verwendet';

// ── Admin tool: small (auto-minimal) toolbar ─────────────────────────────────
$TXT['SMALL_TOOLBAR']        = 'Kleine Toolbar (bei Editorhöhe &lt; %s px)';
$TXT['AUTO_MINIMAL_LABEL']   = 'Automatisch aktivieren bei Editorhöhe &lt;';
$TXT['PREVIEW_SMALL']        = 'Live-Vorschau — Kleine Toolbar';
$TXT['PREVIEW_SMALL_HINT']   = '(Editorhöhe &lt; %s px)';

// ── Admin tool: personal (localStorage) configuration ───────────────────────
$TXT['LOCAL_ACTIVE']         = '👤 Eigene Konfiguration aktiv';
$TXT['LOCAL_CLEAR']          = '✕ Eigene Konfiguration löschen';
$TXT['SAVE_LOCAL']           = '👤 Nur für mich';

// ── Admin tool: actions ──────────────────────────────────────────────────────
$TXT['SAVE_SETTINGS']        = 'Einstellungen speichern';
$TXT['SAVE']                 = 'Speichern';
$TXT['BACK']                 = 'Zurück';

// ── Admin tool: preset accordion ─────────────────────────────────────────────
$TXT['BADGE_DEFAULT']        = 'Standard';
$TXT['BADGE_LOCAL']          = 'Nur für mich';
$TXT['PRESET_EXPORT']        = 'Export';
$TXT['PRESET_IMPORT']        = 'Preset importieren…';
$TXT['PRESET_IMPORT_HINT']   = 'ein hier exportiertes Preset aus einer .json-Datei laden';
$TXT['PRESET_RENAME']        = 'Umbenennen';
$TXT['PRESET_DELETE']        = 'Löschen';
$TXT['PRESET_RESET']         = 'Auf Standard zurücksetzen';
$TXT['PRESET_RENAME_PROMPT'] = 'Neuer Name für dieses Preset:';
$TXT['PRESET_DELETE_CONFIRM']= 'Dieses Preset löschen? Module, die es noch nutzen, fallen auf das Standard-Preset zurück.';
$TXT['PRESET_RESET_CONFIRM'] = 'Dieses eingebaute Preset auf die Auslieferungs-Standardwerte zurücksetzen?';
$TXT['IMPORT_EXISTS']        = 'Eine Konfiguration mit der ID „%s" existiert bereits.';
$TXT['IMPORT_OVERWRITE']     = 'Überschreiben';
$TXT['IMPORT_COPY']          = 'Als Kopie anlegen';
$TXT['DEFAULT_SET']          = 'Standard-Preset aktualisiert.';

// ── Admin tool: typography & formats (per preset) ────────────────────────────
$TXT['TYPOGRAPHY']           = 'Typografie & Formate';
$TXT['FONTS']                = 'Schriftarten';
$TXT['CUSTOM_FONTS']         = 'Eigene Einträge (eine pro Zeile, Name=css,stack)';
$TXT['FONT_SIZES']           = 'Schriftgrößen';
$TXT['SIZE_UNIT']            = 'Einheit';
$TXT['SIZE_LIST']            = 'Größenliste';
$TXT['USE_DEFAULTS']         = 'Standardwerte übernehmen';
$TXT['BLOCK_FORMATS']        = 'Absatzformate & eigene Stile';
$TXT['BLOCK_FORMATS_HINT']   = '"Absatz"-Dropdown + "Stile"-Dropdown';
$TXT['CUSTOM_STYLES']        = 'Eigene Stile (erscheinen im "Stile"-Dropdown)';
$TXT['CUSTOM_STYLES_HINT']   = 'Wichtig: Eigene Stile leben im "Stile"-Dropdown — ersetze oben in der Toolbar den "Absatz"-Button durch "Stile". Es zeigt deine Absatzformate plus diese Stile in einer Liste.';
$TXT['STYLE_CLASS']          = 'CSS-Klasse';
$TXT['ADD']                  = 'Hinzufügen';
$TXT['EMPTY_DEFAULT_HINT']   = 'leer = TinyMCE-Standard';
$TXT['SHOW_TOOLBAR']         = 'Toolbar anzeigen';
$TXT['SHOW_TOOLBAR_HINT']    = 'aus = Editor ganz ohne Toolbar';
$TXT['TOOLBAR_SLIDING']      = 'Kompakte Toolbar';
$TXT['TOOLBAR_SLIDING_HINT'] = 'eine Zeile — der Rest öffnet sich über den „…"-Button am Ende';
$TXT['COLORS']               = 'Farb-Swatches (Schriftfarbe & Marker)';
$TXT['COLORS_HINT']          = 'Gilt für die Toolbar-Buttons „Textfarbe" (forecolor) und „Hintergrundfarbe" (backcolor) — diese oben in eine Toolbar-Zeile aufnehmen, damit die Paletten greifen.';
$TXT['COLOR_MODE']           = 'Paletten-Modus';
$TXT['COLOR_MODE_DEFAULT']   = 'TinyMCE-Standardpalette';
$TXT['COLOR_MODE_SHARED']    = 'Eine gemeinsame Palette für beide';
$TXT['COLOR_MODE_SPLIT']     = 'Getrennte Paletten: Schriftfarbe / Marker';
$TXT['COLOR_SHARED']         = 'Gemeinsame Palette';
$TXT['COLOR_FONT']           = 'Palette Schriftfarbe';
$TXT['COLOR_BACK']           = 'Palette Marker (Hintergrund)';
$TXT['COLOR_ICONS']          = 'Palette Icons (Font Awesome — leer = Schriftfarbe/gemeinsame Palette)';
$TXT['COLOR_LABEL']          = 'Bezeichnung';
$TXT['COLOR_COLS']           = 'Swatch-Spalten';
$TXT['COLOR_COLS_AUTO']      = 'auto';
$TXT['CUSTOM_COLORS']        = 'Freie Farbwahl erlauben';

// ── Editor plugins: shared ───────────────────────────────────────────────────
$TXT['INSERT']               = 'Einfügen';
$TXT['CANCEL']               = 'Abbrechen';
$TXT['APPLY']                = 'Übernehmen';

// ── Editor plugin: wblink (internal page links) ──────────────────────────────
$TXT['SHY_TOOLTIP']          = 'Weiches Trennzeichen (Trennstelle bei Umbruch)';

$TXT['CASE_TOOLTIP']         = 'Groß-/Kleinschreibung ändern';
$TXT['CASE_LOWER']           = 'kleinbuchstaben';
$TXT['CASE_UPPER']           = 'GROSSBUCHSTABEN';
$TXT['CASE_TITLE']           = 'Wortanfänge Groß';
$TXT['CASE_SMALLCAPS']       = 'Kapitälchen';

$TXT['HISTORY_TITLE']        = 'Versionsverlauf';
$TXT['HISTORY_CHOOSE']       = 'Version zum Wiederherstellen wählen:';
$TXT['HISTORY_RESTORE']      = 'Diese Version wiederherstellen';
$TXT['HISTORY_RESTORED']     = 'Version wiederhergestellt.';
$TXT['HISTORY_NONE']         = 'Für dieses Feld sind noch keine Versionen gespeichert.';
$TXT['HISTORY_CLOSE']        = 'Schließen';
$TXT['HISTORY_CURRENT']      = 'Aktuell';
// Admin-Tool-Einstellungen (pro Preset)
$TXT['HISTORY_SETTINGS']     = 'Versionsverlauf';
$TXT['HISTORY_SETTINGS_HINT']= 'für dieses Toolbar-Preset';
$TXT['HISTORY_ENABLED']      = 'Versionsverlauf aktiv';
$TXT['HISTORY_ENABLED_HINT'] = 'aus = keine neuen Versionen aufzeichnen';
$TXT['HISTORY_MAX']          = 'Max. Versionen pro Feld';
$TXT['HISTORY_MAX_HINT']     = 'ältere werden darüber hinaus verworfen';
$TXT['HISTORY_KEEP']         = 'Beim automatischen Aufräumen behalten';
$TXT['HISTORY_KEEP_HINT']    = 'so viele neueste Versionen bleiben beim Aufräumen erhalten';
$TXT['HISTORY_AUTOCLEAN']    = 'Alte Versionen automatisch löschen';
$TXT['HISTORY_AUTOCLEAN_HINT']= 'Versionen älter als das Alter unten verwerfen (über die behaltenen hinaus)';
$TXT['HISTORY_TTL']          = 'Max. Alter (Tage)';
$TXT['HISTORY_TTL_HINT']     = 'ältere Versionen werden beim nächsten Speichern/Öffnen entfernt';
$TXT['HISTORY_DELETE']       = 'Diese Version löschen';
// Bilder einfügen (pro Preset)
$TXT['PASTE_SETTINGS']       = 'Bilder einfügen';
$TXT['PASTE_SETTINGS_HINT']  = 'Bilder aus der Zwischenablage → in den Media-Ordner hochladen';
$TXT['PASTE_IMAGES']         = 'Eingefügte Bilder hochladen';
$TXT['PASTE_IMAGES_HINT']    = 'aus = TinyMCE-Standard (als Data-URI einbetten)';
$TXT['PASTE_FOLDER']         = 'Ziel-Unterordner';
$TXT['PASTE_FOLDER_HINT']    = 'wird hinter den Home-Folder des Nutzers gesetzt (falls vorhanden)';
$TXT['PASTE_WEBP']           = 'In WebP umwandeln';
$TXT['PASTE_WEBP_QUALITY']   = 'Qualität';
$TXT['PASTE_WEBP_HINT']      = 'eingefügte Bilder werden als .webp gespeichert (Fallback auf Original, falls nicht unterstützt)';
$TXT['PASTE_HOME']           = 'home';
$TXT['IMG_DBLCLICK']         = 'Doppelklick aufs Bild → Dateibrowser';
$TXT['IMG_DBLCLICK_HINT']    = 'öffnet elFinder zum Ersetzen statt des Bild-Dialogs';
$TXT['IMG_SIZE_BADGE']       = 'Größen-Badge auf Bildern anzeigen';
$TXT['IMG_SIZE_BADGE_HINT']  = 'zeigt die Größe in % vom Original; Klick setzt auf 100% zurück';
$TXT['IMG_BADGE_RESET']      = 'Klicken für 100%';
$TXT['ALT_REMINDER']         = 'Alt-Text-Erinnerung';
$TXT['ALT_REMINDER_HINT']    = 'Bilder ohne alt-Attribut markieren (nur Erinnerung, kein Zwang)';
$TXT['IMG_ALT_MISSING']      = 'Alt-Attribut fehlt';
// Link-Dialog Optionslisten (pro Preset)
$TXT['LINKOPTS_SETTINGS']    = 'Link-Optionen';
$TXT['LINKOPTS_HINT']        = 'auswählbare Klassen-/rel-Werte im Link-Dialog';
$TXT['LINKOPTS_CLASSES']     = 'CSS-Klassen (eine pro Zeile)';
$TXT['LINKOPTS_RELS']        = 'rel-Werte (eine pro Zeile)';

$TXT['CODESAMPLE_TITLE']     = 'Code-Beispiel einfügen/bearbeiten';
$TXT['CODESAMPLE_LANGUAGE']  = 'Sprache:';

$TXT['FA_TITLE']             = 'Font-Awesome-Icon einfügen';
$TXT['FA_SEARCH']            = 'Icons durchsuchen…';
$TXT['FA_SIZE']              = 'Größe';
$TXT['FA_COLOR']             = 'Farbe';
$TXT['FA_INHERIT']           = 'erben';
$TXT['FA_NO_RESULTS']        = 'Keine Icons gefunden.';

$TXT['WBLINK_TITLE']         = 'Internen Link einfügen';
$TXT['WBLINK_SEARCH']        = 'Seite suchen (ID oder Titel)…';
$TXT['WBLINK_TEXT']          = 'Linktext';
$TXT['WBLINK_OPEN_IN']       = 'Öffnen in';
$TXT['WBLINK_SAME_TAB']      = 'Gleichem Tab (_self)';
$TXT['WBLINK_NEW_TAB']       = 'Neuem Tab (_blank)';
$TXT['WBLINK_NO_PAGES']      = 'Keine Seiten vorhanden.';
$TXT['WBLINK_NO_RESULTS']    = 'Keine Seiten gefunden.';

// ── Editor plugin: link (merged link dialog) ─────────────────────────────────
$TXT['INTERNAL_LINK']        = 'Interner Link';
$TXT['EMAIL']                = 'E-Mail';
$TXT['PHONE']                = 'Telefon';
$TXT['TEXT_TO_DISPLAY']      = 'Linktext';
$TXT['TITLE']                = 'Titel';
$TXT['TARGET']               = 'Ziel';
$TXT['CSS_CLASS']            = 'Klasse';
$TXT['SAME_WINDOW']          = 'Gleiches Fenster';
$TXT['NEW_WINDOW']           = 'Neues Fenster';
$TXT['BREAK_FRAMESET']       = 'Frameset sprengen';
$TXT['SUBJECT']              = 'Betreff';
$TXT['MESSAGE']              = 'Nachricht';
$TXT['LINK_DIALOG_TITLE']    = 'Link einfügen/bearbeiten';
$TXT['LINK_PAGE_ITEM']       = 'Oder ein bestimmtes Element auf dieser Seite';
$TXT['LINKIMG_TAB']          = 'Medien';
$TXT['LINKIMG_URL']          = 'Datei-URL';
$TXT['LINKIMG_BROWSE']       = 'Datei wählen…';
$TXT['LINK_STYLE']           = 'Style (inline CSS)';

// ── Editor plugin: wbdroplets ────────────────────────────────────────────────
$TXT['DROPLET_TITLE']        = 'Droplet einfügen';
$TXT['DROPLET_NONE']         = 'Keine Droplets vorhanden.';

// ── Editor plugin: wbcodemirror + CodeMirror popup ───────────────────────────
$TXT['SOURCE_CODE']          = 'Quellcode';
$TXT['CM_TOOLTIP']           = 'Quellcode (CodeMirror)';
$TXT['CM_SHORTCUT_HINT']     = 'Strg+Enter = Übernehmen · Esc = Abbrechen';

// ── Messages ─────────────────────────────────────────────────────────────────
$MSG['SAVED']                = 'Einstellungen gespeichert.';
$MSG['SAVE_FAILED']          = 'Speichern fehlgeschlagen.';
$MSG['SAVE_OK']              = '✓ Gespeichert';
$MSG['SAVE_ERROR']           = '✗ Fehler';

// ── Toolbar button tooltips (configurator palette) ───────────────────────────
$BTN['bold']                 = 'Fett';
$BTN['italic']               = 'Kursiv';
$BTN['underline']            = 'Unterstrichen';
$BTN['strikethrough']        = 'Durchgestrichen';
$BTN['removeformat']         = 'Formatierung entfernen';
$BTN['forecolor']            = 'Textfarbe';
$BTN['backcolor']            = 'Hintergrundfarbe';
$BTN['alignleft']            = 'Linksbündig';
$BTN['aligncenter']          = 'Zentriert';
$BTN['alignright']           = 'Rechtsbündig';
$BTN['alignjustify']         = 'Blocksatz';
$BTN['bullist']              = 'Aufzählungsliste';
$BTN['numlist']              = 'Nummerierte Liste';
$BTN['outdent']              = 'Einzug verringern';
$BTN['indent']               = 'Einzug erhöhen';
$BTN['blockquote']           = 'Zitat';
$BTN['link']                 = 'Link einfügen/bearbeiten';
$BTN['wblink']               = 'WBCE-Link';
$BTN['image']                = 'Bild einfügen/bearbeiten';
$BTN['media']                = 'Medien einfügen/bearbeiten';
$BTN['table']                = 'Tabelle';
$BTN['wbdroplets']           = 'WBCE-Droplets';
$BTN['fa_picker']            = 'Font-Awesome-Icon';
$BTN['wbce_casechange']      = 'Groß-/Kleinschreibung (Aa)';
$BTN['wbce_codesample']      = 'Code-Beispiel';
$BTN['wbce_history']         = 'Versionsverlauf';
$BTN['hr']                   = 'Trennlinie';
$BTN['wbce_shy']             = 'Weiches Trennzeichen (&shy;)';
$BTN['lineheight']           = 'Zeilenhöhe';
$BTN['emoticons']            = 'Emojis';
$BTN['subscript']            = 'Tiefgestellt';
$BTN['superscript']          = 'Hochgestellt';
$BTN['charmap']              = 'Sonderzeichen';
$BTN['code']                 = 'Quellcode';
$BTN['wbcodemirror']         = 'Quellcode (CodeMirror)';
$BTN['fullscreen']           = 'Vollbild';
$BTN['undo']                 = 'Rückgängig';
$BTN['redo']                 = 'Wiederholen';
$BTN['blocks']               = 'Absatzformat';
$BTN['styles']               = 'Stile';
$BTN['fontfamily']           = 'Schriftart';
$BTN['fontsize']             = 'Schriftgröße';
$BTN['|']                    = 'Trennlinie — beliebig oft verwendbar';
