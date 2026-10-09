# OutputFilter Dashboard — Entwicklerhandbuch

Wie man einen Outputfilter für WBCE schreibt, paketiert und ausliefert.

Jede hier erwähnte Funktion ist vollständig in der
[API-Referenz](API_REFERENCE_DE.md) dokumentiert. Das Dashboard selbst — was die
Bildschirme tun, was die Symbole bedeuten — behandelt das
[Handbuch](USER_GUIDE_DE.md).

---

## Inhalt

- [Die Pipeline](#die-pipeline)
- [Die Filterfunktion](#die-filterfunktion)
- [Drei Wege, einen Filter auszuliefern](#drei-wege-einen-filter-auszuliefern)
- [Inline-Filter](#inline-filter)
- [Plugin-Filter](#plugin-filter)
- [Modul-Filter](#modul-filter)
- [Ausführungsreihenfolge steuern](#ausfuhrungsreihenfolge-steuern)
- [Einen Filter eingrenzen](#einen-filter-eingrenzen)
- [Eigene Einstellungsfelder](#eigene-einstellungsfelder)
- [CSS und JavaScript laden](#css-und-javascript-laden)
- [CodeVet: die Sicherheitsschranke](#codevet-die-sicherheitsschranke)
- [Mit dem Inhalt arbeiten](#mit-dem-inhalt-arbeiten)
- [Backend-Filter](#backend-filter)
- [Assets Cache Busting: zwei Mechanismen, ein Schalter](#assets-cache-busting-zwei-mechanismen-ein-schalter)
- [Veraltete APIs](#veraltete-apis)
- [Fehlersuche](#fehlersuche-dev)

---

<a id="die-pipeline"></a>

## Die Pipeline

WBCE ruft `opf_controller()` an festen Punkten während des Seitenaufbaus auf.
Jeder Aufruf führt alle aktiven Filter dieser Stufe aus, in der Reihenfolge, die
das Dashboard zeigt, und reicht den Inhalt von einem zum nächsten weiter.

Es gibt zwei Ebenen und sieben Stufen:

| Konstante                | Wird angewendet auf                                                               |
| ------------------------ | --------------------------------------------------------------------------------- |
| `OPF_TYPE_SECTION_FIRST` | Section-Inhalt, vor allen `OPF_TYPE_SECTION`-Filtern.                             |
| `OPF_TYPE_SECTION`       | Section-Inhalt — die Ausgabe der `view.php` eines Moduls.                         |
| `OPF_TYPE_SECTION_LAST`  | Section-Inhalt, nach allen `OPF_TYPE_SECTION`-Filtern.                            |
| `OPF_TYPE_PAGE_FIRST`    | Die zusammengebaute Seite, vor allen `OPF_TYPE_PAGE`-Filtern.                     |
| `OPF_TYPE_PAGE`          | Die zusammengebaute Seite — Template, `<head>`, Menüs, Snippets, jede Section.    |
| `OPF_TYPE_PAGE_LAST`     | Die zusammengebaute Seite, nach allen `OPF_TYPE_PAGE`-Filtern.                    |
| `OPF_TYPE_PAGE_FINAL`    | Die zusammengebaute Seite, nach allen `OPF_TYPE_PAGE_LAST`-Filtern. Ganz zuletzt. |

Section-Filter laufen pro Section, bevor die Section ins Template eingesetzt
wird. Page-Filter laufen einmal pro Request, auf der fertigen Seite, kurz bevor
sie zum Browser geht.

Die Konstanten sind in `functions.php` definiert; ihre gespeicherten Werte sind
Sortier-Strings (`'3section'`, `'7page'`, …). Verwende immer die Konstanten, nie
die Strings.

Die richtige Stufe zu wählen ist wichtiger, als es aussieht:

- Auf Markup reagieren, das ein anderer Filter erzeugt? Dann danach laufen.
- Den `<head>` des Templates sehen müssen? Dann braucht es eine Page-Stufe — ein
  Section-Filter bekommt ihn nie zu Gesicht.
- Nach allen anderen aufräumen? `OPF_TYPE_PAGE_FINAL`. Dort sitzen *Remove
  System PH* und *Assets Cache Busting*.

<a id="die-filterfunktion"></a>

## Die Filterfunktion

Eine Funktion, ein eindeutiger Name, per Konvention mit `opff_`-Präfix:

```php
function opff_eindeutiger_name(&$content, $page_id, $section_id, $module, $wb)
{
    // ...
    return true;
}
```

| Parameter     | Typ    | Beschreibung                                                                               |
| ------------- | ------ | ------------------------------------------------------------------------------------------ |
| `&$content`   | string | Der Inhalt der Seite bzw. der Section, **per Referenz**. Direkt ändern, nicht zurückgeben. |
| `$page_id`    | int    | Die aktuelle Seiten-ID.                                                                    |
| `$section_id` | int    | Die aktuelle Section-ID. Auf den Page-Stufen immer `FALSE`.                                |
| `$module`     | string | Der Verzeichnisname des aktuellen Moduls. Auf den Page-Stufen immer `FALSE`.               |
| `$wb`         | object | Die Instanz der WBCE-Frontend-Klasse.                                                      |

**Rückgabewert.** Normalerweise `TRUE` zurückgeben. `FALSE` **nur** dann, wenn
`$content` möglicherweise beschädigt oder in einem undefinierten Zustand ist —
das ist ein Signal an die Pipeline, kein allgemeiner Fehlercode.

Ein minimales Beispiel:

```php
function opff_x_statt_u(&$content, $page_id, $section_id, $module, $wb)
{
    $content = str_replace('U', 'X', $content);
    return true;
}
```

Ein realistisches — ein Syntax-Highlighter, aber nur auf Seiten, die ihn
brauchen:

```php
function opff_prettify(&$content, $page_id, $section_id, $module, $wb)
{
    $PATH = WB_URL . '/modules/opf_prettify/prettify';

    if (opf_find_class($content, 'prettyprint', '(pre|code)')) {
        I::insertCssFile($PATH . '/prettify.css');
        I::insertJsFile($PATH . '/prettify.js');

        if (opf_find_class($content, 'lang-sql', '(pre|code)')) {
            I::insertJsFile($PATH . '/lang-sql.js');
        }

        I::insertJsCode(
            "window.addEventListener('DOMContentLoaded', prettyPrint, false);"
        );
    }

    return true;
}
```

Beachte den Aufbau: **erst prüfen, dann arbeiten.** Ein Filter, der auf jeder
Seite läuft, sollte auf den Seiten, die ihn nicht brauchen, fast nichts kosten.

<a id="drei-wege-einen-filter-auszuliefern"></a>

## Drei Wege, einen Filter auszuliefern

Alle drei landen in derselben Tabelle und laufen über denselben Aufruf von
`opf_register_filter()`. Der Unterschied ist, wo der Code liegt und wer ihn
installiert.

| Form       | Code liegt in                    | Installiert von                                      | Geeignet für                                                      |
| ---------- | -------------------------------- | ---------------------------------------------------- | ----------------------------------------------------------------- |
| **Inline** | Der Datenbank (Spalte `func`)    | Einem Admin, der ihn ins Dashboard tippt             | Website-spezifische Einzelfälle, schnelle Experimente.            |
| **Plugin** | `plugins/<name>/filter.php`      | Einem ZIP-Upload oder der Auslieferung in `plugins/` | Einen Filter, den du eigenständig weitergeben willst.             |
| **Modul**  | Dem Ordner deines eigenen Moduls | Der `install.php` deines Moduls                      | Einen Filter, der nur als Teil eines größeren Moduls Sinn ergibt. |

Inline- und Plugin-Filter lassen sich im Dashboard ineinander umwandeln; ein
Export erzeugt immer ein Plugin-Paket.

<a id="inline-filter"></a>

## Inline-Filter

Hier gibt es nichts zu bauen. Ein Admin klickt auf **Neuer Inline-Filter**,
füllt Name, Beschreibung, Stufe und Zuordnung aus, speichert einmal, und der
Code-Editor erscheint.

Zwei Konsequenzen, mit denen man rechnen sollte:

- Der Code liegt in der Datenbank, ist also weder in der Versionsverwaltung noch
  in einem Deployment. Alles, was einen Neuaufbau der Website überleben soll,
  gehört in ein Plugin oder ein Modul.
- Er läuft bei jedem Speichern durch
  [CodeVet](#codevet-die-sicherheitsschranke). Inline-Filtercode kann kein
  `eval()`, keine variablen Funktionsaufrufe, keine Backticks und kein
  `call_user_func()` verwenden.

<a id="plugin-filter"></a>

## Plugin-Filter

Ein Plugin ist ein Ordner — als ZIP weitergegeben — unter
`modules/outputfilter_dashboard/plugins/`:

```
opf_mein_filter/
├── plugin_info.php        Metadaten
├── plugin_install.php     der opf_register_filter()-Aufruf
├── filter.php             die Filterfunktion
├── plugin_uninstall.php   optionales Aufräumen
├── index.php              Redirect-Schutz
└── README.md              optional, wird als Hilfeseite des Filters angezeigt
```

Jede `plugin_install.php` unter `plugins/` wird ausgeführt, wenn das Modul
installiert oder aktualisiert wird — ein in diesem Ordner ausgeliefertes Plugin
installiert sich also automatisch. Ein hochgeladenes ZIP wird dorthin entpackt
und sein Installer ausgeführt.

### plugin_info.php

```php
<?php
$plugin_directory   = 'opf_assets_cache_busting';
$plugin_name        = 'Assets Cache Busting';
$plugin_version     = '1.1.0';
$plugin_author      = 'Dein Name';
$plugin_license     = 'GNU General Public License, Version 3';
$plugin_description = 'Verhindert, dass Browser veraltete CSS-/JS-Dateien aus dem Cache ausliefern';
```

### plugin_install.php

```php
<?php
if (!defined('WB_PATH')) die(header('Location: ../../index.php'));

opf_register_filter([
    'name'            => 'Assets Cache Busting',
    'type'            => OPF_TYPE_PAGE_FINAL,
    'file'            => '{OPF:PLUGIN_PATH}/filter.php',
    'funcname'        => 'opff_assets_cache_busting',
    'plugin'          => 'opf_assets_cache_busting',
    'desc'            => [
        'EN' => 'Prevents browsers from delivering outdated CSS/JS files from cache.',
        'DE' => 'Verhindert, dass Browser veraltete CSS-/JS-Dateien aus dem Cache ausliefern.',
    ],
    'modules'         => 'all',
    'active'          => 1,
    'allowedit'       => 0,
    'allowedittarget' => 1,
]);
```

Setze `'plugin'` auf den Verzeichnisnamen deines Plugins — daran erkennt das
Dashboard den Filter als Plugin-Filter, und darauf bauen Export und Umwandlung
auf.

Frisch registrierte Filter werden ans Ende ihrer Stufen-Gruppe gehängt. Für eine
bestimmte Position siehe
[Ausführungsreihenfolge steuern](#ausfuhrungsreihenfolge-steuern).

Jeder Schlüssel ist unter
[Das `$filter`-Array](API_REFERENCE_DE.md#the-filter-array) dokumentiert.

### filter.php

```php
<?php
if (!defined('WB_PATH')) die(header('Location: ../../index.php'));

function opff_assets_cache_busting(&$content, $page_id, $section_id, $module, $wb)
{
    // ...
    return true;
}
```

### plugin_uninstall.php

Optional. Räum hier Dateien oder Einstellungen weg, die dein Plugin angelegt
hat.

```php
<?php
if (!defined('WB_PATH')) die(header('Location: ../../index.php'));

// Hier aufräumen.
// Aber opf_unregister_filter() NICHT in dieser Datei aufrufen --
// die Registrierung entfernt das Dashboard selbst.
```

### Pfad-Tokens und Konstanten

Schreibe nie einen absoluten Pfad fest in eine Registrierung — dasselbe Paket
muss auf jeder Installation funktionieren. Vier Tokens werden beim Laden des
Filters ersetzt:

| Token               | Wird ersetzt durch                                             |
| ------------------- | -------------------------------------------------------------- |
| `{SYSVAR:WB_PATH}`  | Die Konstante `WB_PATH`.                                       |
| `{SYSVAR:WB_URL}`   | Die Konstante `WB_URL`.                                        |
| `{OPF:PLUGIN_PATH}` | `WB_PATH/modules/outputfilter_dashboard/plugins/<dein_plugin>` |
| `{OPF:PLUGIN_URL}`  | `WB_URL/modules/outputfilter_dashboard/plugins/<dein_plugin>`  |

Innerhalb der Filterfunktion selbst nimmst du stattdessen die Konstanten:

```php
OPF_PLUGINS_PATH   // WB_PATH . '/modules/outputfilter_dashboard/plugins/'
OPF_PLUGINS_URL    // WB_URL  . '/modules/outputfilter_dashboard/plugins/'
```

<a id="modul-filter"></a>

## Modul-Filter

Um einen Filter als Teil deines eigenen Moduls zu registrieren, rufst du
[`opf_register_filter()`](API_REFERENCE_DE.md#opf-register-filter) in dessen
`install.php` auf und
[`opf_unregister_filter()`](API_REFERENCE_DE.md#opf-unregister-filter) in dessen
`uninstall.php`.

Sichere beide mit einer Dateiprüfung ab, damit dein Modul sich auch auf einer
Website installieren lässt, auf der das Dashboard fehlt, und schreibe den
Installer so, dass er einen zweiten Durchlauf übersteht (`CREATE TABLE ... IF
NOT EXISTS` für eigene Tabellen).

### install.php

```php
<?php

// ... dein normaler Installationscode ...

if (file_exists(WB_PATH . '/modules/outputfilter_dashboard/functions.php')) {
    require_once WB_PATH . '/modules/outputfilter_dashboard/functions.php';

    opf_register_filter([
        'name'      => 'Searchengine Highlighter',
        'type'      => OPF_TYPE_PAGE_LAST,
        'file'      => '{SYSVAR:WB_PATH}/modules/searchengine_highlight/filter.php',
        'funcname'  => 'opff_searchengine_highlight',
        'desc'      => 'Hebt Suchmaschinen-Treffer hervor',
        'active'    => 1,
        'allowedit' => 0,
    ]);
}

// ... Rest deines Installationscodes ...
```

### uninstall.php

```php
<?php

// ... dein normaler Deinstallationscode ...

if (file_exists(WB_PATH . '/modules/outputfilter_dashboard/functions.php')) {
    require_once WB_PATH . '/modules/outputfilter_dashboard/functions.php';
    opf_unregister_filter('Searchengine Highlighter');
}
```

### precheck.php

Nutze das Precheck-System von WBCE, um die Installation zu verweigern, wenn das
Dashboard fehlt oder zu alt ist:

```php
<?php
if (!defined('WB_PATH')) die(header('Location: ../index.php'));

$PRECHECK = [];
$PRECHECK['WB_VERSION'] = ['VERSION' => '2.8', 'OPERATOR' => '>='];
$PRECHECK['WB_ADDONS']  = [
    'outputfilter_dashboard' => ['VERSION' => '1.3.2', 'OPERATOR' => '>='],
];
```

### Der Rest des Moduls

Ein reines Filter-Modul ist trotzdem ein normales WBCE-Modul und braucht die
üblichen zwei Dateien.

`index.php`:

```php
<?php
header('Location: ../../index.php');
```

`info.php`:

```php
<?php
$module_directory   = 'searchengine_highlight';
$module_name        = 'Search Engine Highlighter';
$module_function    = 'filter';
$module_version     = '0.1';
$module_platform    = 'WBCE 1.7.x';
$module_author      = 'Dein Name';
$module_license     = 'GNU General Public License, Version 3';
$module_description = 'Hebt Suchmaschinen-Treffer auf der Seite hervor';
```

<a id="ausfuhrungsreihenfolge-steuern"></a>

## Ausführungsreihenfolge steuern

Neue Filter landen am Ende ihrer Stufen-Gruppe. Wenn deiner vor einem bestimmten
anderen laufen muss, sag das bei der Installation mit
[`opf_move_up_before()`](API_REFERENCE_DE.md#opf-move-up-before):

```php
// muss laufen, bevor der Such-Highlighter die Seite sieht
opf_move_up_before('opf CSS to head', 'Searchengine Highlighter');

// oder: an den Anfang der eigenen Gruppe
opf_move_up_before('Droplets Injector');
```

Ein Array von Namen verschiebt den Filter über den obersten von mehreren.

Die richtige *Stufe* zu wählen ist besser, als um eine Position innerhalb einer
Stufe zu kämpfen. Wenn dein Filter wirklich als Letzter laufen muss, sagt
`OPF_TYPE_PAGE_FINAL` genau das — strukturell, und auch noch, nachdem jemand die
Liste umsortiert hat.

<a id="einen-filter-eingrenzen"></a>

## Einen Filter eingrenzen

Drei Registrierungsschlüssel entscheiden, wo ein Filter läuft. Sie richtig zu
setzen ist der Unterschied zwischen einem Filter, der nichts kostet, und einem,
der über jede Seite der Website eine Regex laufen lässt.

| Schlüssel      | Gilt für       | Bedeutung                                                                                 |
| -------------- | -------------- | ----------------------------------------------------------------------------------------- |
| `modules`      | Section-Stufen | Auf welchen Modultypen der Filter läuft. `'all'` oder eine Liste, z. B. `'wysiwyg,news'`. |
| `pages`        | Page-Stufen    | Seiten-IDs, auf denen der Filter läuft. `'all'` oder `'21,121,16'`.                       |
| `pages_parent` | Page-Stufen    | Seiten-IDs **samt allen Unterseiten**. Nimmt auch `backend` und `search`.                 |

Für `modules` gibt es mehrere Sammel-Aliase (`'all_page_types'`,
`'all_gallery_types'`, `'all_form_types'`, …) — die vollständige Liste steht bei
[`modules`](API_REFERENCE_DE.md#modules).

Mit `allowedittarget => 1` (der Vorgabe) darf ein Admin diese Zuordnung im
Dashboard anpassen, ohne sonst irgendetwas ändern zu können. Das ist fast immer
das Richtige — du kennst den Filter, er kennt seine Website.

<a id="eigene-einstellungsfelder"></a>

## Eigene Einstellungsfelder

Ein Filter, der Konfiguration braucht, aber kein eigenes Admin-Tool rechtfertigt,
kann bei der Registrierung `additional_fields` deklarieren. Das Dashboard
rendert sie auf der Einstellungsseite des Filters; ausgelesen werden die Werte
mit
[`opf_filter_get_additional_values()`](API_REFERENCE_DE.md#opf-filter-get-additional-values):

```php
'additional_fields' => [
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

```php
$values = opf_filter_get_additional_values();
$colour = $values['colour'];
```

Feldtypen: `text`, `textarea`, `editarea`, `radio`, `checkbox`, `select`,
`array`. Alle Einzelheiten unter
[`additional_fields`](API_REFERENCE_DE.md#additional-fields).

<a id="css-und-javascript-laden"></a>

## CSS und JavaScript laden

Nimm **AssetQueue** — die Asset-Pipeline von WBCE, überall als `I::` verfügbar:

```php
I::insertCssFile(WB_URL . '/modules/mein_filter/style.css');
I::insertJsFile(WB_URL . '/modules/mein_filter/script.js');
I::insertCssCode('.meine-klasse { color: red; }');
I::insertJsCode("console.log('hallo');");
```

AssetQueue entfernt Dubletten, sortiert, minifiziert und bündelt je nach
Konfiguration und schreibt das Ergebnis zum richtigen Zeitpunkt in `<head>` und
`<body>` — ausgelöst wird diese Injektion vom Kernfilter *Class Insert Helper*.

Reihe Assets aus deiner Filterfunktion heraus ein, bedingt, erst wenn du weißt,
dass die Seite sie wirklich braucht. Schreibe keine `<link>`- oder
`<script>`-Tags selbst in `$content`.

Die vollständige API steht in
[`framework/Assets/AssetQueue.php`](../../../framework/Assets/AssetQueue.php).

<a id="codevet-die-sicherheitsschranke"></a>

## CodeVet: die Sicherheitsschranke

*Neu in WBCE 1.7.0.* Filtercode, den ein Admin bearbeiten kann, ist Code, der
über ein Webformular hereinkommt — also wird er geprüft, bevor er gespeichert
wird. [`framework/CodeVet.php`](../../../framework/CodeVet.php) läuft auf jedem
Weg, der Filtercode schreibt:

- beim klassischen Speichern über das Formular,
- beim AjaxSave des Code-Editors,
- in `opf_register_filter()` selbst, sobald `$filter['func']` gesetzt ist,
- und über den Inhalt eines hochgeladenen Plugin-ZIPs, bevor es abgelegt wird.

Geprüft wird die PHP-Syntax wirklich, dazu läuft ein auf `token_get_all()`
basierender Scan, der `eval()`, `call_user_func()`/`call_user_func_array()`,
variable Funktionsaufrufe, Backtick-Operatoren sowie diverse System- und
Verschleierungsfunktionen blockiert.

Daraus folgen drei Dinge:

- **Eine CodeVet-Ablehnung ist absolut.** `opf_register_filter()` gibt `FALSE`
  zurück, und anders als die weicheren Validierungsprüfungen (fehlender Name,
  fehlender funcname, …) wird diese *nicht* durch `'force' => TRUE` gelockert.
  Kaputter oder unsicherer Code wird nie gespeichert, weder aktiv noch inaktiv.
- **Schreibe Filter, die ohne die blockierten Konstrukte auskommen.** Das sind
  keine exotischen Randfälle; über `call_user_func()` zu dispatchen ist eine
  verbreitete Gewohnheit. Ruf die Funktion stattdessen direkt auf.
- **Befunde werden protokolliert.** Blockaden und Warnungen landen in
  `var/code_vet/codevet.log` und sind im Errorlogger im Tab *CodeVet* sichtbar.

Filtercode, der aus einer `file` geladen wird (Plugin- und Modul-Filter), wird
bei der Installation des Pakets geprüft, nicht bei jedem Seitenaufruf.

<a id="mit-dem-inhalt-arbeiten"></a>

## Mit dem Inhalt arbeiten

Das Modul bringt Helfer für die Dinge mit, die Filter immer wieder tun.
Vollständig dokumentiert sind sie in der
[API-Referenz](API_REFERENCE_DE.md#content-helpers); die Kurzfassung:

| Funktion                   | Wofür                                                                             |
| -------------------------- | --------------------------------------------------------------------------------- |
| `opf_find_class()`         | Ist diese Klasse / dieses Attribut überhaupt vorhanden? Der billige Frühausstieg. |
| `opf_add_class()`          | Eine Klasse an jedes Vorkommen eines Tags hängen.                                 |
| `opf_add_class_to_class()` | Eine Klasse an Elemente hängen, die bereits eine andere Klasse tragen.            |
| `opf_add_class_to_attr()`  | Eine Klasse an Elemente hängen, die einen bestimmten Attributwert tragen.         |
| `opf_cut_extract()`        | Regex-Treffer aus dem Inhalt herausschneiden und durch Platzhalter ersetzen.      |
| `opf_glue_extract()`       | Sie wieder einsetzen.                                                             |

`opf_cut_extract()` / `opf_glue_extract()` sind das Muster für „diese Fragmente
verändern, ohne dass der Rest meiner Verarbeitung sie anfasst":

```php
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

Du kannst die Pipeline aus einem Filter heraus auch über sich selbst befragen:
[`opf_filter_get_data()`](API_REFERENCE_DE.md#opf-filter-get-data) (der eigene
Registrierungsdatensatz, inklusive `additional_values`),
[`opf_filter_get_rel_pos()`](API_REFERENCE_DE.md#opf-filter-get-rel-pos) (ist
Filter X schon gelaufen oder kommt er noch?),
[`opf_filter_is_active()`](API_REFERENCE_DE.md#opf-filter-is-active) und
[`opf_filter_exists()`](API_REFERENCE_DE.md#opf-filter-exists).

<a id="backend-filter"></a>

## Backend-Filter

Ein `backend` in `pages_parent` wendet den Filter zusätzlich auf die
Verwaltungsausgabe von WBCE an:

```php
'pages_parent' => 'all,backend,search',
```

Die Kernfilter machen das — das Backend ist HTML, das dieselbe Asset-Injektion
und Platzhalter-Bereinigung braucht wie das Frontend.

Zwei Einschränkungen:

- **Über das Dashboard ist es alles oder nichts.** Es gibt keine Oberfläche, um
  ein einzelnes Admin-Tool anzusteuern. Die Engine kann es —
  `opf_apply_filters()` wird aus `admin/admintools/tool.php` mit dem Verzeichnis
  des Tools als `$module` aufgerufen —, aber nutzen kann das nur eine im Code
  geschriebene Registrierung.
- **Ein kaputter Backend-Filter sperrt dich aus dem Backend aus.** Weise deine
  Nutzer auf den Notausgang hin: Wird `WB_OPF_BE_OFF` in der `config.php`
  definiert, ist jede Backend-Filterung abgeschaltet.

Teste Backend-Filter auf einer Website, aus der du dich aussperren darfst.

<a id="assets-cache-busting-zwei-mechanismen-ein-schalter"></a>

## Assets Cache Busting: zwei Mechanismen, ein Schalter

Wissenswert, wenn du an Asset-URLs rührst, denn es erklärt, warum eine einzige
Checkbox scheinbar zwei verschiedene Dinge tut.

Der Filter **Assets Cache Busting** ist auf `OPF_TYPE_PAGE_FINAL` registriert,
nach *Remove System PH*, sieht also immer die endgültig zusammengebaute Seite.
Er erledigt zwei getrennte Aufgaben:

1. **Er schaltet das eigene Cache-Busting von AssetQueue ein.** AssetQueue hängt
   an jede Datei, die es selbst verwaltet, ein `?<mtime>`, sobald die Konstante
   `OPF_ASSETS_CACHE_BUSTING` gesetzt ist. Das Umschalten des Filters schreibt
   die Settings-Zeile `opf_assets_cache_busting`, und `Settings::setup()` hebt
   jede Settings-Zeile zu Beginn jedes Requests in eine gleichnamige Konstante —
   die Checkbox ist also exakt gleichbedeutend damit, die Konstante von Hand zu
   definieren.
2. **Er behandelt alles, was AssetQueue nicht verwaltet.** Handgeschriebene
   `<link>`- und `<script>`-Tags in Section-Inhalten, alte Templates, Ausgaben
   anderer Filter — `opff_assets_cache_busting()` durchsucht die fertige Seite
   nach genau diesen und hängt `?<mtime>` an alle, die noch keines haben.

Die Konstante deckt die eigenen Dateien von AssetQueue ab, der Regex-Durchlauf
des Filters alles andere; eine Checkbox steuert beides. Dieselbe Einstellung
findet sich im Admin-Tool **Asset Optimizer**.

<a id="veraltete-apis"></a>

## Veraltete APIs

**Veraltet seit WBCE 1.7.0:** `opf_register_frontend_files()`,
`opf_register_onload_event()`, `opf_register_onload()` und
`opf_register_document_ready()`.

Das war das modul-eigene Head-/Body-Injektionssystem, älter als die
Asset-Pipeline von WBCE. Es schrieb in die modul-lokalen Globals
`$opf_HEADER`/`$opf_BODY`, die `opf_insert_frontend_files()` dann in die Seite
ausgoss.

In der ausgelieferten Codebasis hatte keine der vier Funktionen noch einen
Aufrufer. Statt aber site-spezifische Filter von vor 1.7.0 zu zerbrechen, wurden
alle vier als dünne Kompatibilitäts-Shims behalten, die an den passenden
`I::`-Aufruf weiterreichen. **Schreibe neuen Code direkt gegen
`I::insertCssFile()` / `insertJsFile()` / `insertCssCode()` /
`insertJsCode()`.**

Ein Verhaltenshinweis, falls du das alte `opf_register_frontend_files()` noch
aufrufst: Sein Parameter `$iehack` (Umhüllung mit IE-Conditional-Comments)
landet unabhängig von `$target` immer im `<body>`, weil dieser Weg über
`I::insertHtmlCode()` führt und AssetQueue beliebiges HTML im `<head>` niemals
erlaubt.

Schreibe aus eigenen Filtern nicht in `$opf_HEADER`/`$opf_BODY`.

<a id="fehlersuche-dev"></a>

## Fehlersuche

**Ausführliche Warnungen.** Die meisten `opf_*`-Funktionen schweigen über
Probleme der Sorte „Filter nicht gefunden", solange `OPF_VERBOSE` aus ist. Der
Wert folgt standardmäßig `DEBUG` — definiere `DEBUG` während der Entwicklung in
deiner `config.php`, und die Funktionen fangen an, `E_USER_WARNING`s abzusetzen.

**Datenbankfehler** werden immer ins PHP-Fehlerprotokoll geschrieben,
unabhängig von `DEBUG`.

**Backend-Aussperrung.** `define('WB_OPF_BE_OFF', 'off');` in der `config.php`
schaltet jede Backend-Filterung ab. Denk daran, bevor du einen Backend-Filter
testest, nicht danach.

**Die eigene Registrierung ansehen**, aus dem Filter heraus:

```php
var_dump(opf_filter_get_data());
```

Das liefert den vollständigen gespeicherten Datensatz — Stufe, Zuordnung,
funcname, Zusatzwerte —, und meist reicht das schon, um zu sehen, warum ein
Filter nicht dort feuert, wo man ihn erwartet hat.

**CodeVet-Befunde** werden als JSON-Zeilen nach `var/code_vet/codevet.log`
geschrieben und im Errorlogger im Tab *CodeVet* angezeigt.

---

*OutputFilter-Dashboard-Dokumentation — CC BY-SA 3.0 DE. Siehe
[LICENSE.md](../LICENSE.md).*
