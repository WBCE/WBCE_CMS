<?php
/**
 * tinymce_wbce — languages/NL.php
 * Dutch translation — only keys that differ from languages/EN.php.
 * Missing keys fall back to the English base automatically.
 *
 * @author  Slugger & Claude KI
 * @license GNU GPL2
 */

$module_name        = 'TinyMCE-configurator';
$module_description = 'Instellingen voor het uiterlijk en de functies van de TinyMCE-editor en zijn werkbalk.';

// ── Admin tool: tabs ─────────────────────────────────────────────────────────
$TXT['BASIC']                = 'Algemeen';
$TXT['CUSTOM']               = 'Aangepaste werkbalk';

// ── Admin tool: tab 1 (basic settings) ───────────────────────────────────────
$TXT['DEFAULT_TOOLBAR']      = 'Standaardwerkbalk';
$TXT['PRESET_MINIMAL']       = 'Minimaal — 1 rij, basisknoppen';
$TXT['PRESET_STANDARD']      = 'Standaard — 3 rijen (aanbevolen)';
$TXT['PRESET_FULL']          = 'Volledig — 3 rijen + lettertype/grootte';
$TXT['PRESET_CUSTOM']        = 'Aangepast (tab 2)';
$TXT['DEFAULT_TOOLBAR_HINT'] = 'Geldt voor alle nieuwe WYSIWYG-blokken — fijnafstemming in tab 2';
$TXT['CONTENT_THEME']        = 'Inhoudsthema';
$TXT['CONTENT_THEME_HINT']   = 'CSS binnen het inhoudsgebied van de editor';
$TXT['EDITORCSS_SOURCE']       = 'editor.css-bron';
$TXT['EDITORCSS_SOURCE_HINT']  = 'Welke editor.css-laag DIT profiel in het inhoudsgebied van de editor laadt. Bewust per profiel — verschillende velden op dezelfde pagina kunnen verschillende editor-CSS tonen.';
$TXT['EDITORCSS_SOURCE_AUTO']      = 'Automatisch (sjabloon → wb_config → modulestandaard)';
$TXT['EDITORCSS_SOURCE_TEMPLATE']  = 'Alleen paginasjabloon';
$TXT['EDITORCSS_SOURCE_DEFAULT']   = 'Modulestandaard (editor_default.css)';
$TXT['EDITORCSS_SOURCE_NONE']      = 'Geen (alleen inhoudsthema)';
$TXT['EDITORCSS_MAY_OVERRIDE'] = 'Dit inhoudsthema kan worden overschreven door de editor.css-bron van dit profiel (hieronder), als die naar een bestaand bestand verwijst:';
$TXT['TOOLBAR_PROFILES']      = 'Werkbalkprofielen';
$TXT['TOOLBAR_PROFILES_HINT'] = 'Volledige editor-profielen voor de backend-/admin-editor — werkbalkindeling, styling, afbeeldingen en versiegeschiedenis. Het profiel dat als "Standaard" is gemarkeerd (of "Alleen ik", indien ingesteld) wordt standaard gebruikt. Sommige modules kunnen voor afzonderlijke velden echter een eigen profiel opgeven.';
$TXT['INLINE_TOOLBAR']      = 'Inline-editorwerkbalk (FEE)';
$TXT['INLINE_TOOLBAR_HINT'] = 'Compacte werkbalk voor bewerken op de plek in de frontend. TinyMCE-werkbalksyntaxis, groepen gescheiden door "&#124;" (bijv. undo redo &#124; bold italic &#124; link). Leeg = standaard.';
// Inline (FEE) presets — de tweede, lichte lijst
$TXT['INLINE_PRESETS']         = 'Inline-profielen (FEE)';
$TXT['INLINE_PRESETS_HINT']    = 'Een aparte, lichte lijst voor bewerken op de plek in de frontend. Minder instellingen dan een volledig profiel — één sleepbare werkbalkrij en wat styling. Kies welk profiel de standaard is voor inline bewerken.';
$TXT['INLINE_MIN_HEIGHT']      = 'Minimale hoogte';
$TXT['INLINE_MIN_HEIGHT_HINT'] = 'Minimale hoogte van het bewerkbare gebied in de frontend. 0 = meegroeien met de inhoud (anders px).';
$TXT['INLINE_SKIN_HINT']       = 'Uiterlijk van de zwevende werkbalk (knoppen, dropdownmenu\'s) tijdens inline bewerken. Heeft geen invloed op de styling van de pagina zelf.';
$TXT['INLINE_ADD']             = 'Inline-profiel toevoegen';
$TXT['INLINE_ADD_PLACEHOLDER'] = 'Naam van nieuw profiel…';
$TXT['INLINE_NAME']            = 'Profielnaam';
$TXT['INLINE_NAME_HINT']       = 'Interne weergavenaam van dit profiel (verschijnt in de lijst hierboven).';
$TXT['EDITORCSS_CLASSES']      = 'CSS-klassen uit editor.css';
$TXT['EDITORCSS_CLASSES_HINT'] = 'de CSS-klassen van het sjabloon aanbieden in het Stijlen-dropdownmenu';
$TXT['EDITORCSS_CLASSES_OFF']       = 'Uit';
$TXT['EDITORCSS_CLASSES_AUTO']      = 'Automatisch (alle klassen, importcss)';
$TXT['EDITORCSS_CLASSES_ANNOTATED'] = 'Alleen geannoteerde (/* tinymce: label="…" */)';
$TXT['EDITORCSS_FILTER_HINT']  = 'Alleen bij Auto: regex om selectors te beperken (bijv. ^(btn&#124;text)-)';

// ── Admin tool: tab 2 (toolbar configurator) ─────────────────────────────────
$TXT['QUICK_CONFIG']         = 'Snelle configuratie';
$TXT['MINIMAL']              = 'Minimaal';
$TXT['STANDARD']             = 'Standaard';
$TXT['FULL']                 = 'Volledig';
$TXT['AVAILABLE_BUTTONS_DRAG'] = 'Beschikbare knoppen — sleep naar een rij';
$TXT['AVAILABLE_BUTTONS']    = 'Beschikbare knoppen';
$TXT['TOOLBAR_ROWS']         = 'Werkbalkrijen (vrij vullen of leeg laten)';
$TXT['ROW']                  = 'Rij %s';
$TXT['LOADING_ICONS']        = 'Iconen laden…';
$TXT['DRAG_HERE']            = 'Sleep knoppen hierheen…';
$TXT['EDITOR_SETTINGS']      = 'Editor-instellingen';
$TXT['DEFAULT_HEIGHT']       = 'Standaardhoogte';
$TXT['MENUBAR']              = 'Menubalk';
$TXT['MENUBAR_HINT']         = 'Menubalk tonen (File, Edit, View…)';
$TXT['STATUSBAR']            = 'Statusbalk';
$TXT['STATUSBAR_HINT']       = 'Statusbalk tonen (p › strong, woordenteller…)';
$TXT['WORDCOUNT']            = 'Woordenteller';
$TXT['WORDCOUNT_HINT']       = 'aantal woorden/tekens in de statusbalk tonen (statusbalk moet aan staan)';
$TXT['UI_SKIN']              = 'UI-skin';
$TXT['UI_SKIN_HINT']         = 'Plaats nieuwe skins in <code>tinymce/skins/ui/</code>';
$TXT['REFRESH']              = 'Voorbeeld vernieuwen';
$TXT['RESET']                = 'Herstellen';
$TXT['LIVE_UPDATE']          = 'Live-update';
$TXT['LIVE_PREVIEW']         = 'Livevoorbeeld';
$TXT['REMOVE']               = 'Verwijderen';
$TXT['SEPARATOR']            = 'Scheidingsteken';
$TXT['ALREADY_USED']         = 'al gebruikt';

// ── Admin tool: small (auto-minimal) toolbar ─────────────────────────────────
$TXT['SMALL_TOOLBAR']        = 'Kleine werkbalk (bij editorhoogte &lt; %s px)';
$TXT['AUTO_MINIMAL_LABEL']   = 'Automatisch inschakelen bij editorhoogte &lt;';
$TXT['PREVIEW_SMALL']        = 'Livevoorbeeld — kleine werkbalk';
$TXT['PREVIEW_SMALL_HINT']   = '(editorhoogte &lt; %s px)';

// ── Admin tool: personal (localStorage) configuration ───────────────────────
$TXT['LOCAL_ACTIVE']         = '👤 Eigen configuratie actief';
$TXT['LOCAL_CLEAR']          = '✕ Eigen configuratie verwijderen';
$TXT['SAVE_LOCAL']           = '👤 Alleen voor mij';

// ── Admin tool: actions ──────────────────────────────────────────────────────
$TXT['SAVE_SETTINGS']        = 'Instellingen opslaan';
$TXT['SAVE']                 = 'Opslaan';
$TXT['BACK']                 = 'Terug';

// ── Admin tool: preset accordion ─────────────────────────────────────────────
$TXT['BADGE_DEFAULT']        = 'Standaard';
$TXT['BADGE_LOCAL']          = 'Alleen ik';
$TXT['PRESET_EXPORT']        = 'Exporteren';
$TXT['PRESET_IMPORT']        = 'Preset importeren…';
$TXT['PRESET_IMPORT_HINT']   = 'een hier geëxporteerde preset uit een .json-bestand laden';
$TXT['PRESET_RENAME']        = 'Hernoemen';
$TXT['PRESET_DELETE']        = 'Verwijderen';
$TXT['PRESET_RESET']         = 'Terugzetten naar standaard';
$TXT['PRESET_RENAME_PROMPT'] = 'Nieuwe naam voor deze preset:';
$TXT['PRESET_DELETE_CONFIRM']= 'Deze preset verwijderen? Modules die hem nog gebruiken vallen terug op de standaardpreset.';
$TXT['PRESET_RESET_CONFIRM'] = 'Deze ingebouwde preset terugzetten naar de meegeleverde standaardwaarden?';
$TXT['IMPORT_EXISTS']        = 'Er bestaat al een configuratie met id "%s".';
$TXT['IMPORT_OVERWRITE']     = 'Overschrijven';
$TXT['IMPORT_COPY']          = 'Als kopie toevoegen';
$TXT['DEFAULT_SET']          = 'Standaardpreset bijgewerkt.';

// ── Admin tool: typography & formats (per preset) ────────────────────────────
$TXT['TYPOGRAPHY']           = 'Typografie & opmaak';
$TXT['FONTS']                = 'Lettertypefamilies';
$TXT['CUSTOM_FONTS']         = 'Eigen items (één per regel, Naam=css,stack)';
$TXT['FONT_SIZES']           = 'Lettergrootten';
$TXT['SIZE_UNIT']            = 'Eenheid';
$TXT['SIZE_LIST']            = 'Groottelijst';
$TXT['USE_DEFAULTS']         = 'Standaardwaarden gebruiken';
$TXT['BLOCK_FORMATS']        = 'Alinea-opmaak & eigen stijlen';
$TXT['BLOCK_FORMATS_HINT']   = '"Alinea"-dropdownmenu + "Stijlen"-dropdownmenu';
$TXT['CUSTOM_STYLES']        = 'Eigen stijlen (getoond in het "Stijlen"-dropdownmenu)';
$TXT['CUSTOM_STYLES_HINT']   = 'Belangrijk: eigen stijlen staan in het "Stijlen"-dropdownmenu — vervang hierboven in een werkbalkrij de "Alinea"-knop door "Stijlen". Die toont je alinea-opmaak plus deze stijlen in één lijst.';
$TXT['STYLE_CLASS']          = 'CSS-klasse';
$TXT['ADD']                  = 'Toevoegen';
$TXT['EMPTY_DEFAULT_HINT']   = 'leeg = TinyMCE-standaard';
$TXT['SHOW_TOOLBAR']         = 'Werkbalk tonen';
$TXT['SHOW_TOOLBAR_HINT']    = 'uit = editor helemaal zonder werkbalk';
$TXT['TOOLBAR_SLIDING']      = 'Compacte werkbalk';
$TXT['TOOLBAR_SLIDING_HINT'] = 'één rij — de rest opent via de "…"-knop aan het eind';
$TXT['COLORS']               = 'Kleurstalen (tekstkleur & markering)';
$TXT['COLORS_HINT']          = 'Geldt voor de werkbalkknoppen "Tekstkleur" (forecolor) en "Achtergrondkleur" (backcolor) — voeg die hierboven toe aan een werkbalkrij om deze paletten te gebruiken.';
$TXT['COLOR_MODE']           = 'Paletmodus';
$TXT['COLOR_MODE_DEFAULT']   = 'TinyMCE-standaardpalet';
$TXT['COLOR_MODE_SHARED']    = 'Eén gedeeld palet voor beide';
$TXT['COLOR_MODE_SPLIT']     = 'Aparte paletten: tekstkleur / markering';
$TXT['COLOR_SHARED']         = 'Gedeeld palet';
$TXT['COLOR_FONT']           = 'Palet tekstkleur';
$TXT['COLOR_BACK']           = 'Palet markering (achtergrond)';
$TXT['COLOR_ICONS']          = 'Palet iconen (Font Awesome — leeg = tekstkleur-/gedeeld palet)';
$TXT['COLOR_LABEL']          = 'Bijschrift';
$TXT['COLOR_COLS']           = 'Staalkolommen';
$TXT['CUSTOM_COLORS']        = 'Eigen kleurkiezer toestaan';

// ── Editor plugins: shared ───────────────────────────────────────────────────
$TXT['INSERT']               = 'Invoegen';
$TXT['CANCEL']               = 'Annuleren';
$TXT['APPLY']                = 'Toepassen';

// ── Editor plugin: wbce_shy ──────────────────────────────────────────────────
$TXT['SHY_TOOLTIP']          = 'Zacht afbreekstreepje (afbreekkans)';

// ── Editor plugin: wbce_casechange ───────────────────────────────────────────
$TXT['CASE_TOOLTIP']         = 'Hoofdlettergebruik wijzigen';
$TXT['CASE_LOWER']           = 'kleine letters';
$TXT['CASE_UPPER']           = 'HOOFDLETTERS';
$TXT['CASE_TITLE']           = 'Beginhoofdletters';
$TXT['CASE_SMALLCAPS']       = 'Kleinkapitaal';

// ── Editor plugin: wbce_history ──────────────────────────────────────────────
$TXT['HISTORY_TITLE']        = 'Versiegeschiedenis';
$TXT['HISTORY_CHOOSE']       = 'Kies een versie om te herstellen:';
$TXT['HISTORY_RESTORE']      = 'Deze versie herstellen';
$TXT['HISTORY_RESTORED']     = 'Versie hersteld.';
$TXT['HISTORY_NONE']         = 'Nog geen opgeslagen versies voor dit veld.';
$TXT['HISTORY_CLOSE']        = 'Sluiten';
$TXT['HISTORY_CURRENT']      = 'Huidige';
// Admin-tool-instellingen (per preset)
$TXT['HISTORY_SETTINGS']     = 'Versiegeschiedenis';
$TXT['HISTORY_SETTINGS_HINT']= 'voor deze werkbalkpreset';
$TXT['HISTORY_ENABLED']      = 'Versiegeschiedenis inschakelen';
$TXT['HISTORY_ENABLED_HINT'] = 'uit = geen nieuwe versies vastleggen';
$TXT['HISTORY_MAX']          = 'Max. versies per veld';
$TXT['HISTORY_MAX_HINT']     = 'oudere worden daarboven verwijderd';
$TXT['HISTORY_KEEP']         = 'Behouden bij automatisch opschonen';
$TXT['HISTORY_KEEP_HINT']    = 'zoveel recente versies blijven bij het opschonen altijd behouden';
$TXT['HISTORY_AUTOCLEAN']    = 'Oude versies automatisch verwijderen';
$TXT['HISTORY_AUTOCLEAN_HINT']= 'versies ouder dan de leeftijd hieronder verwijderen (naast de behouden versies)';
$TXT['HISTORY_TTL']          = 'Max. leeftijd (dagen)';
$TXT['HISTORY_TTL_HINT']     = 'oudere versies worden bij de volgende keer opslaan/openen verwijderd';
$TXT['HISTORY_DELETE']       = 'Deze versie verwijderen';
// Afbeeldingen plakken (per preset)
$TXT['PASTE_SETTINGS']       = 'Afbeeldingen plakken';
$TXT['PASTE_SETTINGS_HINT']  = 'afbeeldingen van het klembord → uploaden naar de mediamap';
$TXT['PASTE_IMAGES']         = 'Geplakte afbeeldingen uploaden';
$TXT['PASTE_IMAGES_HINT']    = 'uit = TinyMCE-standaard behouden (insluiten als data-URI)';
$TXT['PASTE_FOLDER']         = 'Doelsubmap';
$TXT['PASTE_FOLDER_HINT']    = 'wordt na de home-map van de gebruiker geplaatst (indien aanwezig)';
$TXT['PASTE_WEBP']           = 'Naar WebP converteren';
$TXT['PASTE_WEBP_QUALITY']   = 'Kwaliteit';
$TXT['PASTE_WEBP_HINT']      = 'geplakte afbeeldingen worden opgeslagen als .webp (valt terug op het origineel indien niet ondersteund)';
$TXT['PASTE_HOME']           = 'home';
$TXT['IMG_DBLCLICK']         = 'Dubbelklik op afbeelding → bestandsbrowser';
$TXT['IMG_DBLCLICK_HINT']    = 'opent elFinder om een vervanging te kiezen in plaats van het afbeeldingsvenster';
$TXT['IMG_SIZE_BADGE']       = 'Groottebadge op afbeeldingen tonen';
$TXT['IMG_SIZE_BADGE_HINT']  = 'toont de grootte als % van het origineel; klik zet terug op 100%';
$TXT['IMG_BADGE_RESET']      = 'Klik om terug te zetten op 100%';
$TXT['ALT_REMINDER']         = 'Alt-tekst-herinnering';
$TXT['ALT_REMINDER_HINT']    = 'afbeeldingen zonder alt-attribuut markeren (alleen herinnering, geen afdwinging)';
$TXT['IMG_ALT_MISSING']      = 'Alt-attribuut ontbreekt';
// Link-dialoog optielijsten (per preset)
$TXT['LINKOPTS_SETTINGS']    = 'Linkopties';
$TXT['LINKOPTS_HINT']        = 'selecteerbare class-/rel-waarden in het linkvenster';
$TXT['LINKOPTS_CLASSES']     = 'CSS-klassen (één per regel)';
$TXT['LINKOPTS_RELS']        = 'rel-waarden (één per regel)';

// ── Editor plugin: wbce_codesample ───────────────────────────────────────────
$TXT['CODESAMPLE_TITLE']     = 'Codevoorbeeld invoegen/bewerken';
$TXT['CODESAMPLE_LANGUAGE']  = 'Taal:';

// ── Editor plugin: fa_picker (Font Awesome icons) ────────────────────────────
$TXT['FA_TITLE']             = 'Font Awesome-icoon invoegen';
$TXT['FA_SEARCH']            = 'Iconen zoeken…';
$TXT['FA_SIZE']              = 'Grootte';
$TXT['FA_COLOR']             = 'Kleur';
$TXT['FA_INHERIT']           = 'overnemen';
$TXT['FA_NO_RESULTS']        = 'Geen iconen gevonden.';

// ── Editor plugin: wblink (internal page links) ──────────────────────────────
$TXT['WBLINK_TITLE']         = 'Interne link invoegen';
$TXT['WBLINK_SEARCH']        = 'Pagina zoeken (ID of titel)…';
$TXT['WBLINK_TEXT']          = 'Linktekst';
$TXT['WBLINK_OPEN_IN']       = 'Openen in';
$TXT['WBLINK_SAME_TAB']      = 'Zelfde tabblad (_self)';
$TXT['WBLINK_NEW_TAB']       = 'Nieuw tabblad (_blank)';
$TXT['WBLINK_NO_PAGES']      = 'Geen pagina\'s beschikbaar.';
$TXT['WBLINK_NO_RESULTS']    = 'Geen pagina\'s gevonden.';

// ── Editor plugin: link (merged link dialog) ─────────────────────────────────
$TXT['INTERNAL_LINK']        = 'Interne link';
$TXT['EMAIL']                = 'E-mail';
$TXT['PHONE']                = 'Telefoon';
$TXT['TEXT_TO_DISPLAY']      = 'Weer te geven tekst';
$TXT['TITLE']                = 'Titel';
$TXT['TARGET']               = 'Doel';
$TXT['CSS_CLASS']            = 'Klasse';
$TXT['SAME_WINDOW']          = 'Zelfde venster';
$TXT['NEW_WINDOW']           = 'Nieuw venster';
$TXT['BREAK_FRAMESET']       = 'Uit frameset breken';
$TXT['SUBJECT']              = 'Onderwerp';
$TXT['MESSAGE']              = 'Bericht';
$TXT['LINK_DIALOG_TITLE']    = 'Link invoegen/bewerken';
$TXT['LINK_PAGE_ITEM']       = 'Of een specifiek item op deze pagina';
$TXT['LINKIMG_TAB']          = 'Media';
$TXT['LINKIMG_URL']          = 'Bestands-URL';
$TXT['LINKIMG_BROWSE']       = 'Bestand kiezen…';
$TXT['LINK_STYLE']           = 'Stijl (inline CSS)';

// ── Editor plugin: wbdroplets ────────────────────────────────────────────────
$TXT['DROPLET_TITLE']        = 'Droplet invoegen';
$TXT['DROPLET_NONE']         = 'Geen droplets beschikbaar.';

// ── Editor plugin: wbcodemirror + CodeMirror popup ───────────────────────────
$TXT['SOURCE_CODE']          = 'Broncode';
$TXT['CM_TOOLTIP']           = 'Broncode (CodeMirror)';
$TXT['CM_SHORTCUT_HINT']     = 'Ctrl+Enter = Toepassen · Esc = Annuleren';

// ── Messages ─────────────────────────────────────────────────────────────────
$MSG['SAVED']                = 'Instellingen opgeslagen.';
$MSG['SAVE_FAILED']          = 'Opslaan mislukt.';
$MSG['SAVE_OK']              = '✓ Opgeslagen';
$MSG['SAVE_ERROR']           = '✗ Fout';

// ── Toolbar button tooltips (configurator palette) ───────────────────────────
$BTN['bold']                 = 'Vet';
$BTN['italic']               = 'Cursief';
$BTN['underline']            = 'Onderstrepen';
$BTN['strikethrough']        = 'Doorhalen';
$BTN['removeformat']         = 'Opmaak verwijderen';
$BTN['forecolor']            = 'Tekstkleur';
$BTN['backcolor']            = 'Achtergrondkleur';
$BTN['alignleft']            = 'Links uitlijnen';
$BTN['aligncenter']          = 'Centreren';
$BTN['alignright']           = 'Rechts uitlijnen';
$BTN['alignjustify']         = 'Uitvullen';
$BTN['bullist']              = 'Opsommingslijst';
$BTN['numlist']              = 'Genummerde lijst';
$BTN['outdent']              = 'Inspringing verkleinen';
$BTN['indent']               = 'Inspringing vergroten';
$BTN['blockquote']           = 'Citaat';
$BTN['link']                 = 'Link invoegen/bewerken';
$BTN['wblink']               = 'WBCE-link';
$BTN['image']                = 'Afbeelding invoegen/bewerken';
$BTN['media']                = 'Media invoegen/bewerken';
$BTN['table']                = 'Tabel';
$BTN['wbdroplets']           = 'WBCE-droplets';
$BTN['fa_picker']            = 'Font Awesome-icoon';
$BTN['wbce_casechange']      = 'Hoofdlettergebruik (Aa)';
$BTN['wbce_codesample']      = 'Codevoorbeeld';
$BTN['wbce_history']         = 'Versiegeschiedenis';
$BTN['hr']                   = 'Horizontale lijn';
$BTN['wbce_shy']             = 'Zacht afbreekstreepje (&shy;)';
$BTN['lineheight']           = 'Regelhoogte';
$BTN['emoticons']            = 'Emoji\'s';
$BTN['subscript']            = 'Subscript';
$BTN['superscript']          = 'Superscript';
$BTN['charmap']              = 'Speciaal teken';
$BTN['code']                 = 'Broncode';
$BTN['wbcodemirror']         = 'Broncode (CodeMirror)';
$BTN['fullscreen']           = 'Volledig scherm';
$BTN['undo']                 = 'Ongedaan maken';
$BTN['redo']                 = 'Opnieuw';
$BTN['blocks']               = 'Alinea-opmaak';
$BTN['styles']               = 'Stijlen';
$BTN['fontfamily']           = 'Lettertype';
$BTN['fontsize']             = 'Lettergrootte';
$BTN['|']                    = 'Scheidingsteken — herbruikbaar';
