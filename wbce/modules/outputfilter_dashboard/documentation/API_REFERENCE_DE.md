# OutputFilter Dashboard — API-Referenz

Die vollständige Referenz aller `opf_*`-Funktionen und des `$filter`-Arrays.

Wie das alles zusammenspielt — einen Filter schreiben, paketieren, ausliefern —
steht zuerst im [Entwicklerhandbuch](DEVELOPER_GUIDE_DE.md).

---

## Inhalt

**Konstanten und Tokens**
[Stufen-Konstanten](#stage-constants) ·
[Pfad-Konstanten und Tokens](#path-constants-and-tokens)

**Die Filterfunktion**
[Prototyp und Parameter](#the-filter-function)

**Registrierung**
[opf_register_filter](#opf-register-filter) ·
[Das $filter-Array](#the-filter-array) ·
[opf_move_up_before](#opf-move-up-before) ·
[opf_unregister_filter](#opf-unregister-filter)

**Inhalts-Helfer**
[opf_find_class](#opf-find-class) ·
[opf_add_class](#opf-add-class) ·
[opf_add_class_to_class](#opf-add-class-to-class) ·
[opf_add_class_to_attr](#opf-add-class-to-attr) ·
[opf_cut_extract](#opf-cut-extract) ·
[opf_glue_extract](#opf-glue-extract)

**Laufzeit-Helfer**
[opf_filter_get_data](#opf-filter-get-data) ·
[opf_filter_get_rel_pos](#opf-filter-get-rel-pos) ·
[opf_filter_exists](#opf-filter-exists) ·
[opf_filter_is_active](#opf-filter-is-active) ·
[opf_is_childpage](#opf-is-childpage) ·
[opf_filter_get_additional_values](#opf-filter-get-additional-values) ·
[opf_filter_name_to_setting](#opf-filter-name-to-setting)

**Veraltet**
[Alte Frontend-Datei-Registrierung](#legacy-frontend-file-registration)

---

## Konstanten und Tokens

<a id="stage-constants"></a>

### Stufen-Konstanten

Definiert in `functions.php`. Verwende immer die Konstante, nie ihren
gespeicherten String-Wert.

| Konstante                | Gespeicherter Wert | Wird angewendet auf                                                               |
| ------------------------ | ------------------ | --------------------------------------------------------------------------------- |
| `OPF_TYPE_SECTION_FIRST` | `2section_first`   | Section-Inhalt, vor allen `OPF_TYPE_SECTION`-Filtern.                             |
| `OPF_TYPE_SECTION`       | `3section`         | Section-Inhalt — die Ausgabe der `view.php` eines Moduls.                         |
| `OPF_TYPE_SECTION_LAST`  | `5section_last`    | Section-Inhalt, nach allen `OPF_TYPE_SECTION`-Filtern.                            |
| `OPF_TYPE_PAGE_FIRST`    | `6page_first`      | Die zusammengebaute Seite, vor allen `OPF_TYPE_PAGE`-Filtern.                     |
| `OPF_TYPE_PAGE`          | `7page`            | Die zusammengebaute Seite — Template, `<head>`, Menüs, Snippets, jede Section.    |
| `OPF_TYPE_PAGE_LAST`     | `8page_last`       | Die zusammengebaute Seite, nach allen `OPF_TYPE_PAGE`-Filtern.                    |
| `OPF_TYPE_PAGE_FINAL`    | `9page_final`      | Die zusammengebaute Seite, nach allen `OPF_TYPE_PAGE_LAST`-Filtern. Ganz zuletzt. |

Section-Filter laufen pro Section, bevor die Section ins Template eingesetzt
wird. Page-Filter laufen einmal pro Request, auf der fertigen Seite.

<a id="path-constants-and-tokens"></a>

### Pfad-Konstanten und Tokens

Tokens werden beim Lesen der gespeicherten Filterwerte ersetzt, damit eine
Registrierung über Installationen hinweg portabel bleibt:

| Token               | Wird ersetzt durch                                             |
| ------------------- | -------------------------------------------------------------- |
| `{SYSVAR:WB_PATH}`  | Die Konstante `WB_PATH`.                                       |
| `{SYSVAR:WB_URL}`   | Die Konstante `WB_URL`.                                        |
| `{OPF:PLUGIN_PATH}` | `WB_PATH/modules/outputfilter_dashboard/plugins/<dein_plugin>` |
| `{OPF:PLUGIN_URL}`  | `WB_URL/modules/outputfilter_dashboard/plugins/<dein_plugin>`  |

Im Filtercode selbst nimmst du die Konstanten:

| Konstante          | Wert                                                     |
| ------------------ | -------------------------------------------------------- |
| `OPF_PLUGINS_PATH` | `WB_PATH . '/modules/outputfilter_dashboard/plugins/'`   |
| `OPF_PLUGINS_URL`  | `WB_URL . '/modules/outputfilter_dashboard/plugins/'`    |
| `OPF_VERBOSE`      | Ob `opf_*`-Funktionen Warnungen ausgeben. Folgt `DEBUG`. |

Zwei Konstanten aus der `config.php` ändern das Verhalten der Pipeline:

| Konstante                  | Wirkung                                                                                                         |
| -------------------------- | --------------------------------------------------------------------------------------------------------------- |
| `WB_OPF_BE_OFF`            | Wenn definiert (Wert egal), wird kein Filter auf Backend-Ausgabe angewendet. Der Notausgang.                    |
| `OPF_ASSETS_CACHE_BUSTING` | Schaltet das eigene `?<mtime>`-Cache-Busting von AssetQueue ein. Normalerweise über den Filterschalter gesetzt. |

---

<a id="the-filter-function"></a>

## Die Filterfunktion

Muss einen eindeutigen Namen haben; das Präfix `opff_` ist Konvention.

**Prototyp**

`bool` **opff_eindeutiger_name** ( `string` &$content, `int` $page_id, `int` $section_id, `string` $module, `object` $wb )

**Parameter**

| Parameter     | Typ    | Beschreibung                                                                                  |
| ------------- | ------ | --------------------------------------------------------------------------------------------- |
| `&$content`   | string | Der Inhalt der Seite bzw. Section, per Referenz. Direkt ändern.                               |
| `$page_id`    | int    | Die aktuelle Seiten-ID.                                                                       |
| `$section_id` | int    | Die aktuelle Section-ID. Bei den vier `OPF_TYPE_PAGE*`-Stufen immer `FALSE`.                  |
| `$module`     | string | Der Verzeichnisname des aktuellen Moduls. Bei den vier `OPF_TYPE_PAGE*`-Stufen immer `FALSE`. |
| `$wb`         | object | Instanz der WBCE-Frontend-Klasse.                                                             |

**Rückgabe**

Normalerweise `TRUE`. `FALSE` **nur** dann, wenn `$content` beschädigt oder in
einem undefinierten Zustand sein könnte.

**Beispiel**

```php
function opff_x_statt_u(&$content, $page_id, $section_id, $module, $wb)
{
    $content = str_replace('U', 'X', $content);
    return true;
}
```

---

## Registrierung

<a id="opf-register-filter"></a>

### opf_register_filter()

Registriert einen neuen Filter oder aktualisiert einen bestehenden gleichen
Namens. Wird von Plugin- und Modul-Filtern verwendet; Inline-Filter laufen
stattdessen über das Dashboard.

**Prototyp**

`bool` **opf_register_filter** ( `array` $filter, `bool` $serialized = FALSE )

**Parameter**

- **$filter** — `(array)` die Filterdefinition, siehe
  [Das `$filter`-Array](#the-filter-array).
- **$serialized** — `(bool)` ob `$filter` serialisiert übergeben wird.

**Rückgabe**

`TRUE` bei Erfolg, sonst `FALSE`. Im Fehlerfall wird zusätzlich ein
`E_USER_WARNING` mit der Ursache ausgegeben.

**Eine bestehende Registrierung aktualisieren.** Existiert bereits ein Filter
desselben Namens (oder derselben `id`), ist das ein Update statt eines Inserts.
Sofern nicht `'force' => TRUE` übergeben wird, bleiben die gespeicherten Werte
von `active`, `modules`, `pages` und `pages_parent` **erhalten** — ein erneut
laufender Installer beim Upgrade überschreibt also nie die Zuordnung, die ein
Administrator eingestellt hat.

**CodeVet.** *Neu in WBCE 1.7.0.* Ist `$filter['func']` gesetzt, läuft der Code
durch [`CodeVet`](../../../framework/CodeVet.php) — eine PHP-Syntaxprüfung plus
Sicherheits-Scan —, bevor irgendetwas in die Datenbank geschrieben wird. Eine
Ablehnung gibt **bedingungslos** `FALSE` zurück: Anders als die weicheren
Prüfungen weiter unten wird diese nicht durch `'force' => TRUE` gelockert. Die
konkrete Ursache liegt danach in `$GLOBALS['opf_codevet_error']` als
`['message' => …, 'line' => …]`.

**Beispiel**

```php
$filter = [
    'name'     => 'PrettyPrint: Google-Code-Prettify',
    'type'     => OPF_TYPE_SECTION,
    'file'     => '{SYSVAR:WB_PATH}/modules/opf_prettify/filter.php',
    'funcname' => 'opff_prettify',
    'csspath'  => '{SYSVAR:WB_PATH}/modules/opf_prettify/prettify/prettify.css',
    'modules'  => 'download_gallery,manual,news,wysiwyg',
];
opf_register_filter($filter);
```

<a id="the-filter-array"></a>

### Das $filter-Array

| Schlüssel           | Typ          | Beschreibung                                                                                                   |
| ------------------- | ------------ | -------------------------------------------------------------------------------------------------------------- |
| `name`              | string       | Eindeutiger Name des Filters, z. B. `Correct Date filter`. Pflicht.                                            |
| `type`              | string       | Die Stufe — eine der [Stufen-Konstanten](#stage-constants). Pflicht.                                           |
| `funcname`          | string       | Eindeutiger Name der Filterfunktion. Sollte mit `opff_` beginnen. Pflicht.                                     |
| `file`              | string       | Absoluter Pfad (oder Token-Pfad) der Datei mit der Funktion. Entweder `file` oder `func`.                      |
| `func`              | string       | Der Quelltext der Funktion selbst, als String. Für sehr kurze Funktionen. Siehe [func](#func).                 |
| `plugin`            | string       | Der Verzeichnisname deines Plugins. Kennzeichnet den Filter als Plugin-Filter; für Plugins Pflicht.            |
| `active`            | int          | Nach der Installation aktiv (`1`) oder inaktiv (`0`). Vorgabe `1`.                                             |
| `allowedit`         | int          | Ob der Admin die Einstellungen des Filters ändern darf. Vorgabe `0`. Siehe [allowedit](#allowedit).            |
| `allowedittarget`   | int          | Ob der Admin nur die Seiten-/Modul-Zuordnung ändern darf. Vorgabe `1`.                                         |
| `desc`              | string/array | Beschreibung. Ein einfacher String oder ein Array mit Sprachschlüsseln. Siehe [desc](#desc).                   |
| `helppath`          | string/array | Pfad oder URL einer Hilfedatei je Sprache, z. B. `['EN' => …, 'DE' => …]`. Wird zum `?`-Symbol der Zeile.      |
| `configurl`         | string       | URL der eigenen Einstellungsseite des Filters. Wird zum Zahnrad-Symbol der Zeile. Vorgabe `''`.                |
| `csspath`           | string       | Absoluter Pfad einer CSS-Datei. Lässt das CSS-Editor-Symbol der Zeile erscheinen. Vorgabe `''`.                |
| `modules`           | string/array | Module, für die der Filter gilt (nur Section-Stufen). Siehe [modules](#modules).                               |
| `pages`             | string/array | Seiten-IDs, für die der Filter gilt (nur Page-Stufen). Siehe [pages](#pages).                                  |
| `pages_parent`      | string/array | Seiten-IDs **samt Unterseiten**. Siehe [pages_parent](#pages-parent).                                          |
| `additional_fields` | array        | Zusätzliche Einstellungsfelder. Siehe [additional_fields](#additional-fields).                                 |
| `force`             | bool         | Lockert die weicheren Prüfungen und überschreibt beim Update die gespeicherte Zuordnung. Umgeht CodeVet nicht. |
| `targets`           | string/array | Alter Alias für `modules`.                                                                                     |

<a id="func"></a>

#### func

Die Filterfunktion als String. Praktisch für sehr kurze Funktionen; für alles
Größere `file` verwenden.

```php
$function = '
    function opff_search_highlight(&$content, $page_id, $section_id, $module, $wb) {
        if (isset($_GET["searchresult"]) && is_numeric($_GET["searchresult"])
            && !isset($_GET["nohighlight"]) && !empty($_GET["sstring"])) {
            $arr_string = explode(" ", $_GET["sstring"]);
            if ($_GET["searchresult"] == 2) { // exakte Übereinstimmung
                $arr_string[0] = str_replace("_", " ", $arr_string[0]);
            }
            $content = search_highlight($content, $arr_string);
        }
        return true;
    }
';

opf_register_filter([
    'name'     => 'Searchresult Highlighting',
    'type'     => OPF_TYPE_SECTION_LAST,
    'funcname' => 'opff_search_highlight',
    'func'     => $function,
    'modules'  => 'all',
]);
```

Der Funktionsname im String muss zu `funcname` passen, sonst schlägt die
Registrierung fehl (bzw. wird der Filter mit `'force' => TRUE` inaktiv und mit
einem vorangestellten Warnkommentar gespeichert).

Als `func` übergebener Code läuft durch CodeVet — siehe
[opf_register_filter](#opf-register-filter).

<a id="file"></a>

#### file

Absoluter Pfad der Datei mit der Funktion. Nimm ein Token, damit die
Registrierung portabel bleibt:

```php
opf_register_filter([
    'name'     => 'Searchresult Highlighting',
    'type'     => OPF_TYPE_SECTION_LAST,
    'funcname' => 'opff_search_highlight',
    'file'     => '{SYSVAR:WB_PATH}/modules/myfilter/filter.php',
    'modules'  => 'all',
]);
```

```php
<?php
// modules/myfilter/filter.php
function opff_search_highlight(&$content, $page_id, $section_id, $module, $wb)
{
    // ...
    return true;
}
```

Die Registrierung schlägt fehl, wenn die Datei nicht existiert oder nicht
lesbar ist.

<a id="allowedit"></a>

#### allowedit

Mit `allowedit => 1` darf der Administrator im Dashboard ändern:

- den Namen des Filters,
- seine Stufe,
- den Funktionsnamen,
- den Funktionsrumpf (nur bei Inline-Filtern),
- die Modul- und Seiten-Zuordnung.

**Empfehlung: `'allowedit' => 0`** (die Vorgabe) für Filter, die du ausliefertst.
Du hast den Filter geschrieben; dass ein fremder Admin seinen Funktionsnamen
umbenennen kann, ist kein Feature.

<a id="allowedittarget"></a>

#### allowedittarget

Eine Erweiterung von `allowedit`. Mit `allowedit => 0` und
`allowedittarget => 1` darf der Administrator **ausschließlich** die Modul- und
Seiten-Zuordnung ändern — sonst nichts.

**Empfehlung: `'allowedittarget' => 1`** (die Vorgabe). Du kennst den Filter,
der Admin kennt seine Website.

Ist `allowedit` gleich `1`, hat dieser Schlüssel keine Bedeutung.

<a id="desc"></a>

#### desc

Entweder ein einfacher String:

```php
'desc' => "Hier folgt die Beschreibung.\nText...",
```

oder ein Array mit Sprachschlüsseln, das mindestens einen `EN`-Eintrag
enthalten muss:

```php
'desc' => [
    'EN' => "Description\n...",
    'DE' => "Beschreibung\n...",
],
```

<a id="modules"></a>

#### modules

Für welche Module ein Filter einer Section-Stufe gilt. Bei den Page-Stufen wird
der Schlüssel ignoriert.

```php
'modules' => 'wysiwyg,news'
'modules' => ['wysiwyg', 'news']
'modules' => 'all'
```

Kategorie-Aliase werden zu einer Modulliste aufgelöst:

| Alias                | Steht für                                                                                                                                                                                                                     |
| -------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `all`                | Alle installierten Module                                                                                                                                                                                                     |
| `all_page_types`     | wysiwyg, news, guestbook, download_gallery, manual, mapbaker, newsarc                                                                                                                                                         |
| `all_form_types`     | form, formx, mpform, miniform                                                                                                                                                                                                 |
| `all_gallery_types`  | Auto_Gallery, fancy_box, flickrgallery, foldergallery, gallery, gdpics, imageflow, imagegallery, lightbox2, lightbox, minigallery, minigal2, panoramic_image, pickle, picturebox, tiltviewer, smoothgallery, swift, slideshow |
| `all_wrapper_types`  | curl, inlinewrapper, wrapper                                                                                                                                                                                                  |
| `all_calendar_types` | calendar, concert, event, event_calendar, extcal, procalendar                                                                                                                                                                 |
| `all_shop_types`     | bakery, gocart, anyitems, lastitems                                                                                                                                                                                           |
| `all_code_types`     | code, code2, show_code, show_code_geshi, color4code                                                                                                                                                                           |
| `all_poll_types`     | doodler, polls                                                                                                                                                                                                                |
| `all_listing_types`  | accordion, addressbok, aggregator, bookings_v2, bookmarks, cabin, dirlist, enhanced_aggregator, faqbaker, faqmaker, glossary, jqCollapse, members, mfz, sitemap                                                               |
| `all_various_types`  | audioplayer, feedback, forum, newsreader, shoutbox, simpleviewer, small_ads, wb-forum, zitate                                                                                                                                 |

Maßgeblich ist die Zuordnung in `opf_modules_categories()` in `functions.php`.

<a id="pages"></a>

#### pages

Seiten-IDs, für die ein Filter einer Page-Stufe gilt. Alias `all` für alle
Seiten.

```php
'pages' => '21,121,16'            // als Liste
'pages' => ['21', '121', '16']    // als Array
'pages' => 'all'
```

<a id="pages-parent"></a>

#### pages_parent

Wie `pages`, aber der Filter gilt zusätzlich für **alle Unterseiten** der
genannten Seiten.

```php
// Seite 12 und 21, plus alles darunter
'pages_parent' => '12,21'
'pages_parent' => ['12', '21']
```

Hier werden zwei Sonderwerte akzeptiert:

| Wert      | Bedeutung                                                                        |
| --------- | -------------------------------------------------------------------------------- |
| `backend` | Den Filter zusätzlich auf die gesamte Verwaltungsausgabe von WBCE anwenden.      |
| `search`  | Den Filter zusätzlich auf die Suchergebnis-Ausgabe anwenden (intern als ID `0`). |

```php
'pages_parent' => 'all,backend,search',
```

<a id="additional-fields"></a>

#### additional_fields

Zusätzliche Konfigurationsfelder, die auf der Einstellungsseite des Filters
gerendert werden. Für Filter, die ein wenig Konfiguration brauchen, aber kein
eigenes Admin-Tool rechtfertigen. Ausgelesen werden die Werte mit
[opf_filter_get_additional_values](#opf-filter-get-additional-values).

`allowedit` hat auf diese Felder keine Wirkung — sie sind immer bearbeitbar.

**Feldtypen**

| Typ        | Wird gerendert als                                    |
| ---------- | ----------------------------------------------------- |
| `text`     | Ein HTML-Textfeld                                     |
| `textarea` | Ein HTML-Textbereich                                  |
| `editarea` | Ein Textbereich mit angehängtem Editor                |
| `radio`    | HTML-Radiobuttons                                     |
| `checkbox` | HTML-Checkboxen                                       |
| `select`   | Ein HTML-Auswahlfeld                                  |
| `array`    | Ein einfaches Schlüssel-Wert-Array als Textfeld-Paare |

**Feld-Schlüssel**

| Schlüssel  | Beschreibung                                                                                                                |
| ---------- | --------------------------------------------------------------------------------------------------------------------------- |
| `label`    | Beschriftung. Ein einfacher String (`'Datum eingeben'`) oder ein Array mit Sprachschlüsseln. (`text` gilt als alter Alias.) |
| `variable` | Name der Variable, unter der der Wert gespeichert wird — dieser Schlüssel liest ihn wieder aus.                             |
| `type`     | Einer der Feldtypen oben.                                                                                                   |
| `name`     | Wert für das HTML-Attribut `name`. Radiobuttons teilen sich einen Namen.                                                    |
| `value`    | Vorgabewert. `select` und `array` brauchen ein Array.                                                                       |
| `checked`  | Bei `radio`/`checkbox`: `'checked' => 1`. Bei `select`: `'checked' => 'wert'` — den gewählten Wert wiederholen.             |
| `style`    | Optional, bei `text`/`textarea`/`editarea`: `width:` und/oder `height:`.                                                    |

**Beispiel — verschiedene Felder**

```php
'additional_fields' => [
    [                              // Textfeld "Name"
        'type'     => 'text',
        'label'    => 'Name',
        'variable' => 'name',
        'name'     => 'af_name',
        'value'    => '',
        'style'    => 'width: 98%;',
    ],
    [                              // Textbereich "Langtext"
        'type'     => 'textarea',
        'label'    => 'Langtext',
        'variable' => 'text',
        'name'     => 'af_long_text',
        'value'    => 'Vorgabetext',
        'style'    => 'width: 98%;',
    ],
    [                              // Auswahlfeld "Farbe"
        'type'     => 'select',
        'label'    => 'Farbe',
        'variable' => 'colour',
        'name'     => 'af_colour',
        'value'    => ['red' => 'Rot', 'blue' => 'Blau', 'green' => 'Grün'],
        'checked'  => 'blue',
    ],
    [                              // Checkbox
        'type'     => 'checkbox',
        'label'    => 'ECMA-Variante verwenden',
        'variable' => 'use_ecma',
        'name'     => 'af_use_ecma',
        'value'    => 'use_ecma',
        'checked'  => 1,
    ],
],
```

**Beispiel — mit Sprachunterstützung**

```php
'additional_fields' => [
    [
        'type'     => 'text',
        'label'    => ['EN' => 'Locale', 'DE' => 'Spracheinstellung'],
        'variable' => 'locale',
        'name'     => 'af_locale',
        'value'    => 'de_DE.utf-8',
        'style'    => 'width: 98%;',
    ],
    [
        'type'     => 'array',
        'label'    => ['EN' => 'Date format', 'DE' => 'Datumsformat'],
        'variable' => 'date_formats',
        'name'     => 'af_date_formats',
        'value'    => [
            'D M d, Y'    => 'a b d Y',
            'M d Y'       => 'b d. %Y',
            'd M Y'       => 'd. b %Y',
            'jS F, Y'     => 'e. B %Y',
            'l, jS F, Y'  => 'A, e. B Y',
        ],
    ],
    [
        'type'     => 'select',
        'label'    => ['EN' => 'Colour', 'DE' => 'Farbe'],
        'variable' => 'colour',
        'name'     => 'af_colour',
        'value'    => [
            'red'   => ['EN' => 'Red',   'DE' => 'Rot'],
            'blue'  => ['EN' => 'Blue',  'DE' => 'Blau'],
            'green' => ['EN' => 'Green', 'DE' => 'Grün'],
        ],
        'checked'  => 'blue',
    ],
],
```

<a id="opf-move-up-before"></a>

### opf_move_up_before()

Verschiebt einen Filter nach oben, vor einen anderen. Aufzurufen nach
[opf_register_filter](#opf-register-filter) im eigenen Installer — frisch
registrierte Filter landen am Ende ihrer Stufen-Gruppe.

Wiederhole den Aufruf mit weiteren Referenznamen für jeden Filter, von dem du
weißt, dass er nach deinem laufen muss. Beide Filter müssen auf derselben Stufe
liegen.

**Prototypen**

`bool` **opf_move_up_before** ( `string` $name, `string` $ref_name )
`array` **opf_move_up_before** ( `string` $name, `array` $ref_name )
`int` **opf_move_up_before** ( `string` $name )

**Parameter**

- **$name** — `(string)` der zu verschiebende Filter.
- **$ref_name** — `(string)` der Filter, über den er gesetzt wird, oder
  `(array)` mehrere Namen; dann wird `$name` über den obersten von ihnen
  gesetzt. Ganz weglassen setzt `$name` an den Anfang seiner Gruppe.

**Rückgabe**

`TRUE` bei Erfolg; `FALSE`, wenn die Stufen nicht zusammenpassen oder ein Filter
nicht gefunden wurde; ein Array von Booleans, wenn `$ref_name` ein Array ist;
die neue Position, wenn `$ref_name` weggelassen wurde.

**Beispiel**

```php
opf_move_up_before('opf CSS to head', 'Searchengine Highlighter');
```

<a id="opf-unregister-filter"></a>

### opf_unregister_filter()

Entfernt einen Filter. Aus der `uninstall.php` deines Moduls aufzurufen.

**Nicht** aus der `plugin_uninstall.php` eines Plugins aufrufen — die
Registrierung eines Plugins entfernt das Dashboard selbst.

**Prototyp**

`bool` **opf_unregister_filter** ( `string` $name )

**Parameter**

- **$name** — `(string)` Name des zu entfernenden Filters.

**Rückgabe**

`TRUE` bei Erfolg, sonst `FALSE`.

**Beispiel**

```php
opf_unregister_filter('PrettyPrint: Google-Code-Prettify');
```

---

<a id="content-helpers"></a>

## Inhalts-Helfer

Alle behandeln Argumente wie `$tag`, `$attr` und ähnliche als reguläre
PCRE-Ausdrücke, angewandt **ungreedy** (`PCRE_UNGREEDY`). Einfangende Gruppen
sind in diesen Argumenten nicht erlaubt — immer `(?:…)` verwenden.

<a id="opf-find-class"></a>

### opf_find_class()

Prüft, ob eine Klasse — oder irgendein anderer Attributwert — im Inhalt
vorkommt. Der billige Frühausstieg, mit dem jeder Filter beginnen sollte.

**Prototyp**

`bool` **opf_find_class** ( `string` $content, `string` $match, `string` $tag = '', `string` $attr = 'class' )

**Parameter**

| Parameter  | Typ          | Beschreibung                              |
| ---------- | ------------ | ----------------------------------------- |
| `$content` | string       | Der zu durchsuchende Inhalt.              |
| `$match`   | string/regex | Wonach gesucht wird.                      |
| `$tag`     | string/regex | HTML-Tag zur Einschränkung. Vorgabe `''`. |
| `$attr`    | string/regex | Zu prüfendes Attribut. Vorgabe `class`.   |

**Rückgabe**

`1`, wenn `$match` vorkommt, `0`, wenn nicht, `FALSE` bei einem Fehler.

**Beispiele**

```php
// irgendwo im Inhalt
if (opf_find_class($content, 'special_class')) {
    // ... Filter anwenden
}

// nur innerhalb eines <pre>-Tags: <pre class="class1 php_highlighter">
if (opf_find_class($content, 'php_highlighter', 'pre')) {

// eine ID statt einer Klasse: <div class="class2" id="tagSphere">
if (opf_find_class($content, 'tagSphere', 'div', 'id')) {

// Regex: Klasse "cpp", "c" oder "c++" innerhalb von <pre> oder <textarea>
if (opf_find_class($content, '(cpp|c|c\+\+)', '(pre|textarea)')) {
```

<a id="opf-add-class"></a>

### opf_add_class()

Hängt eine Klasse an jedes Vorkommen eines HTML-Tags.

**Prototyp**

`bool` **opf_add_class** ( `string` &$content, `string` $class, `string` $tag )

**Parameter**

| Parameter  | Typ          | Beschreibung           |
| ---------- | ------------ | ---------------------- |
| `$content` | string       | Inhalt, per Referenz.  |
| `$class`   | string       | Hinzuzufügende Klasse. |
| `$tag`     | string/regex | HTML-Tag, z. B. `pre`. |

**Rückgabe**

`TRUE`, wenn die Klasse hinzugefügt wurde, sonst `FALSE`.

**Beispiele**

```php
// jedes <pre>
opf_add_class($content, 'prettyPrint', 'pre');

// jedes <pre> und <code> -- beachte die nicht einfangende Gruppe
opf_add_class($content, 'prettyPrint', '(?:pre|code)');
```

<a id="opf-add-class-to-class"></a>

### opf_add_class_to_class()

Hängt eine Klasse an jedes Element, das bereits eine bestimmte andere Klasse
trägt.

**Prototyp**

`bool` **opf_add_class_to_class** ( `string` &$content, `string` $class, `string` $present_class, `string` $tag = '' )

**Parameter**

| Parameter        | Typ          | Beschreibung                              |
| ---------------- | ------------ | ----------------------------------------- |
| `$content`       | string       | Inhalt, per Referenz.                     |
| `$class`         | string       | Hinzuzufügende Klasse.                    |
| `$present_class` | string/regex | Klasse, die bereits vorhanden sein muss.  |
| `$tag`           | string/regex | HTML-Tag zur Einschränkung. Vorgabe `''`. |

**Rückgabe**

`TRUE`, wenn die Klasse hinzugefügt wurde, sonst `FALSE`.

**Beispiele**

```php
opf_add_class_to_class($content, 'php', 'prettify');
// <pre class="prettify">      wird zu  <pre class="prettify php">

opf_add_class_to_class($content, 'php', 'prettify', 'code');
// <pre class="prettify">      bleibt   <pre class="prettify">
// <code class="prettify">     wird zu  <code class="prettify php">
```

<a id="opf-add-class-to-attr"></a>

### opf_add_class_to_attr()

Hängt eine Klasse an jedes HTML-Tag, das einen bestimmten Attributwert trägt.

**Prototyp**

`bool` **opf_add_class_to_attr** ( `string` &$content, `string` $class, `string` $attr, `string` $value, `string` $tag = '' )

**Parameter**

| Parameter  | Typ          | Beschreibung                              |
| ---------- | ------------ | ----------------------------------------- |
| `$content` | string       | Inhalt, per Referenz.                     |
| `$class`   | string       | Hinzuzufügende Klasse.                    |
| `$attr`    | string/regex | Attribut, das vorhanden sein muss.        |
| `$value`   | string/regex | Der Wert dieses Attributs.                |
| `$tag`     | string/regex | HTML-Tag zur Einschränkung. Vorgabe `''`. |

**Rückgabe**

`TRUE`, wenn die Klasse hinzugefügt wurde, sonst `FALSE`.

<a id="opf-cut-extract"></a>

### opf_cut_extract()

Schneidet Regex-Treffer aus dem Inhalt heraus und ersetzt sie durch eindeutige
Platzhalter, sodass der Rest der Verarbeitung sie nicht anfassen kann. Wieder
eingesetzt wird mit [opf_glue_extract](#opf-glue-extract).

**Prototyp**

`array` **opf_cut_extract** ( `string` &$content, `string` $regex, `string` $subpattern = 0, `string` $modifiers = 'iU', `string` $delimiter = '~', `array` $extracts = '' )

**Parameter**

| Parameter     | Typ    | Beschreibung                                                                            |
| ------------- | ------ | --------------------------------------------------------------------------------------- |
| `$content`    | string | Inhalt, per Referenz.                                                                   |
| `$regex`      | string | PCRE-Muster ohne Delimiter und Modifier, z. B. `<input[^>]+/>`. Standardmäßig ungreedy. |
| `$subpattern` | int    | Welches Teilmuster verwendet wird. Vorgabe `0` (der ganze Treffer).                     |
| `$modifiers`  | string | PCRE-Modifier. Vorgabe `iU`.                                                            |
| `$delimiter`  | string | Delimiter. Vorgabe `~`.                                                                 |
| `$extracts`   | array  | Ein Array aus einem früheren Aufruf, in das weiter gesammelt wird. Optional.            |

**Rückgabe**

Ein Array der Ausschnitte, leer, wenn das Muster nicht traf. `FALSE` bei einem
Fehler (mit `E_USER_WARNING`).

**Beispiel — wie es aussieht**

```php
function opff_links_bearbeiten(&$content, $page_id, $section_id, $module, $wb)
{
    $extracts = opf_cut_extract($content, '<a href=[^>]+>.*</a>');
    // ... an $extracts arbeiten ...
    opf_glue_extract($content, $extracts);
    return true;
}
```

`$extracts` enthält danach Paare aus Platzhalter und Original:

```php
'@@@OPF_EXTRACT_494e430fc8cf1@@@' => '<a href="…" class="menu_current"> start </a>'
'@@@OPF_EXTRACT_494e430fc8d06@@@' => '<a href="…" class="menu_default"> News </a>'
```

und `$content` trägt die Platzhalter dort, wo die Links standen.

**Beispiel — ein vollständiger Filter**

```php
// Page (last): '#' in einem Menütitel in einen Zeilenumbruch verwandeln, damit
// ein Menütitel "Eintrag# mit Umbruch" als "Eintrag<br /> mit Umbruch" erscheint.
// Beachte $subpattern = 1: nur der (.*)-Teil wird ausgeschnitten, nicht das ganze <a>.
function opff_menu_umbruch(&$content, $page_id, $section_id, $module, $wb)
{
    $extracts = opf_cut_extract($content, '<a href=[^>]*class="menu[^>]*>(.*)</a>', 1, 'iUs');

    if ($extracts === false) {
        return false;   // Inhalt könnte beschädigt sein -- das melden
    }

    foreach ($extracts as $key => $str) {
        $extracts[$key] = str_replace('#', '<br />', $str);
    }

    opf_glue_extract($content, $extracts);
    return true;
}
```

<a id="opf-glue-extract"></a>

### opf_glue_extract()

Setzt Ausschnitte wieder in den Inhalt ein und ersetzt die Platzhalter durch
ihre (möglicherweise veränderten) Werte.

**Prototyp**

`bool` **opf_glue_extract** ( `string` &$content, `array` $extracts )

**Parameter**

- **$content** — `(string)` Inhalt, per Referenz.
- **$extracts** — `(array)` ein Array aus
  [opf_cut_extract](#opf-cut-extract).

**Rückgabe**

`TRUE`.

---

## Laufzeit-Helfer

<a id="opf-filter-get-data"></a>

### opf_filter_get_data()

Liefert den vollständigen gespeicherten Datensatz eines Filters. Ohne Argument
aus einer Filterfunktion heraus aufgerufen, liefert sie den Datensatz des
gerade laufenden Filters — der übliche Weg, die eigene Registrierung
anzusehen.

**Prototyp**

`array` **opf_filter_get_data** ( `string` $name = '' )

**Parameter**

- **$name** — `(string)` Name des Filters. `''` = der aktuelle.

**Rückgabe**

Die Daten des Filters, oder `FALSE` bei einem Fehler.

**Beispiel**

```php
$data = opf_filter_get_data();
var_dump($data);
```

```php
array
    'id'              => '12'
    'userfunc'        => '0'
    'position'        => '1'
    'active'          => '1'
    'allowedit'       => '0'
    'allowedittarget' => '1'
    'name'            => 'PrettyPrint: Google-Code-Prettify'
    'func'            => ''
    'type'            => '3section'
    'file'            => '/var/www/wbce/modules/opf_prettify/filter.php'
    'csspath'         => '/var/www/wbce/modules/opf_prettify/prettify/prettify.css'
    'funcname'        => 'opff_prettify'
    'configurl'       => ''
    'modules'         => array('download_gallery', 'manual', 'news', 'wysiwyg')
    'desc'            => 'Prettifies all <pre class="prettyprint"> and ...'
    'pages'           => array('all', '99', '104', …)
    'pages_parent'    => array('all', '99', '104', …)
    'current'         => true
    'activated'       => false
    'failed'          => false
```

<a id="opf-filter-get-rel-pos"></a>

### opf_filter_get_rel_pos()

Wo ein anderer Filter relativ zum gerade laufenden sitzt — ist er schon
ausgeführt worden oder kommt er noch?

**Prototyp**

`int` **opf_filter_get_rel_pos** ( `string` $name )

**Parameter**

- **$name** — `(string)` Name des zu prüfenden Filters.

**Rückgabe**

| Wert    | Bedeutung                                                                   |
| ------- | --------------------------------------------------------------------------- |
| `-1`    | Filter `$name` lief vor dem aktuellen.                                      |
| `0`     | Filter `$name` *ist* der aktuelle.                                          |
| `1`     | Filter `$name` läuft später.                                                |
| `FALSE` | Filter `$name` ist inaktiv, nicht installiert, oder es trat ein Fehler auf. |

**Beispiele**

```php
if (opf_filter_get_rel_pos('Menu Linebreak')) {
    // installiert und aktiv (und nicht der aktuelle)
}
```

```php
$pos = opf_filter_get_rel_pos('Menu Linebreak');
if ($pos == 1) {
    // wird nach diesem aufgerufen
} elseif ($pos == -1) {
    // wurde bereits vor diesem aufgerufen
}
```

<a id="opf-filter-exists"></a>

### opf_filter_exists()

Ob ein Filter überhaupt installiert ist — unabhängig davon, ob er aktiv ist.

**Prototyp**

`bool` **opf_filter_exists** ( `string` $name, `bool` $verbose = FALSE )

**Parameter**

- **$name** — `(string)` Name des zu prüfenden Filters.
- **$verbose** — `(bool)` ein `E_USER_WARNING` ausgeben, wenn er nicht
  existiert.

**Rückgabe**

`TRUE`, wenn der Filter installiert ist, sonst `FALSE`.

**Beispiel**

```php
if (opf_filter_exists('Menu Linebreak')) {
    // ...
}
```

<a id="opf-filter-is-active"></a>

### opf_filter_is_active()

Ob ein Filter **für das aktuelle Modul und die aktuelle Seite** aktiv ist —
nicht bloß global eingeschaltet.

**Prototyp**

`bool` **opf_filter_is_active** ( `string` $name )

**Parameter**

- **$name** — `(string)` Name des Filters.

**Rückgabe**

`TRUE`, wenn der Filter für das aktuelle Modul und die aktuelle Seiten-ID aktiv
ist, sonst `FALSE`.

**Beispiel**

```php
if (opf_filter_is_active('Menu Linebreak')) {
    // ...
}
```

<a id="opf-is-childpage"></a>

### opf_is_childpage()

Ob eine Seite eine Unterseite einer anderen ist — oder dieselbe Seite.

**Prototyp**

`bool` **opf_is_childpage** ( `int` $child, `int` $parent )

**Parameter**

- **$child** — `(int)` Seiten-ID der möglichen Unterseite.
- **$parent** — `(int)` Seiten-ID, gegen die geprüft wird.

**Rückgabe**

`TRUE`, wenn `$child` unterhalb von `$parent` liegt oder beide dieselbe Seite
sind. Sonst `FALSE`.

**Beispiel**

```php
if (opf_is_childpage(101, 9)) {
    // Seite 101 liegt unterhalb von Seite 9
}
```

<a id="opf-filter-get-additional-values"></a>

### opf_filter_get_additional_values()

Liefert die aktuellen Werte der unter
[additional_fields](#additional-fields) deklarierten Felder, jeweils unter dem
`variable`-Schlüssel des Feldes.

**Prototyp**

`array` **opf_filter_get_additional_values** ( `void` )

**Rückgabe**

Ein Array der Werte, oder `FALSE` bei einem Fehler.

**Beispiel**

```php
$values       = opf_filter_get_additional_values();
$locale       = $values['locale'];
$date_formats = $values['date_formats'];
```

<a id="opf-filter-name-to-setting"></a>

### opf_filter_name_to_setting()

Wandelt den Namen eines Filters in den `settings`-Schlüssel um, der seinen
Aktivzustand hält. Der Ein-/Aus-Zustand jedes Filters wird in eine
Settings-Zeile gespiegelt, die `Settings::setup()` von WBCE dann bei jedem
Request zu einer gleichnamigen PHP-Konstante erhebt — so kann ein Schalter im
Dashboard eine Konfigurationskonstante steuern.

**Prototyp**

`string` **opf_filter_name_to_setting** ( `string` $name )

**Parameter**

- **$name** — `(string)` Name des Filters.

**Rückgabe**

Der Name der zugehörigen Einstellung, kleingeschrieben.

**Beispiel**

```php
if (Settings::Get(opf_filter_name_to_setting($name))) {
    // der Filter ist eingeschaltet
}
```

Ein Filter, der zusätzlich das Backend betrifft, bekommt eine zweite Zeile mit
dem Suffix `_be`.

---

<a id="legacy-frontend-file-registration"></a>

## Veraltet: alte Frontend-Datei-Registrierung

**Veraltet seit WBCE 1.7.0.** `opf_register_frontend_files()`,
`opf_register_onload_event()`, `opf_register_onload()` und
`opf_register_document_ready()` waren das modul-eigene Head-/Body-Injektions\-
system, älter als die Asset-Pipeline von WBCE. Sie schrieben in die
modul-lokalen Globals `$opf_HEADER` / `$opf_BODY`, die
`opf_insert_frontend_files()` dann in die Seite ausgoss.

Alle vier hatten in der ausgelieferten Codebasis keinen Aufrufer mehr. Statt
aber früher geschriebene site-spezifische Filter zu zerbrechen, wurden sie als
dünne Kompatibilitäts-Shims behalten, die an den passenden AssetQueue-Aufruf
weiterreichen (`I::insertCssFile()` / `insertJsFile()` / `insertCssCode()` /
`insertJsCode()`).

**Schreibe neue Filter direkt gegen die `I::`-Methoden.**

Ein Verhaltenshinweis: Der Parameter `$iehack` von
`opf_register_frontend_files()` (Umhüllung mit IE-Conditional-Comments) landet
unabhängig von `$target` immer im `<body>`, weil dieser Weg über
`I::insertHtmlCode()` führt und AssetQueue beliebiges HTML im `<head>` niemals
zulässt.

`$opf_HEADER` / `$opf_BODY` und `opf_insert_frontend_files()` selbst wurden
**nicht** entfernt — der ausgelieferte Filter *Assets Cache Busting* liest und
schreibt diese Globals direkt. Schreibe aus eigenen Filtern nicht hinein.

---

*OutputFilter-Dashboard-Dokumentation — CC BY-SA 3.0 DE. Siehe
[LICENSE.md](../LICENSE.md).*
