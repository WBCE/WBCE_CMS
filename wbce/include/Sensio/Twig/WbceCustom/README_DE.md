# WBCE CMS — Twig Referenz

> Gültig für WBCE CMS 1.7.0+  
> Alle Twig-Templates werden über `getTwig()` instanziiert.  
> **Kein Symfony, kein `app.*`, kein `request.*`** — das ist reines Twig mit WBCE-Erweiterungen.

---

## Inhaltsverzeichnis

1. [getTwig() — Instanziierung](#gettwigtemplatepfad-section_id-cache)
2. [Automatisch verfügbare Globals](#automatisch-verfügbare-globals)
3. [Twig-Funktionen (SimpleFunctions)](#twig-funktionen-simplefunctions)
4. [Twig-Filter (Extensions)](#twig-filter-extensions)
5. [Twig-Tags (TokenParser)](#twig-tags-tokenparsers)
6. [Modul-eigene Twig-Funktionen registrieren](#modul-eigene-twig-funktionen-registrieren)
7. [Do & Don't — Häufige Fehler](#do--dont--häufige-fehler)

---

## `getTwig($templatePfad, $section_id, $cache)`

`getTwig()` ist die einzige Methode um Twig in WBCE zu instanziieren. Sie ist sowohl im Frontend als auch im Backend verfügbar.

```php
// Einfach (nur Template-Pfad):
$oTwig = getTwig(CHUB_PATH . '/twig/');

// Mit Section-ID (lädt zusätzliche Section-Globals, siehe unten):
$oTwig = getTwig(WB_PATH . $this->activeLopi, $section_id);

// Mit Cache (Pfad zu einem beschreibbaren Verzeichnis):
$oTwig = getTwig($path, null, WB_PATH . '/temp/LopiCache/');
```

### Template laden und rendern

```php
// render() gibt einen String zurück:
$html = $oTwig->load('my_template.twig')->render(['key' => 'value']);
echo $html;

// display() gibt direkt aus:
$oTwig->load('my_template.twig')->display(['key' => 'value']);

// Globale Variable nachträglich hinzufügen:
$oTwig->addGlobal('MY_VAR', $value);
```

### Konfiguration

| Option             | Wert                                                                                |
|--------------------|-------------------------------------------------------------------------------------|
| `autoescape`       | `false` — kein automatisches Escaping, muss manuell mit `\|e` oder `\|raw` erfolgen |
| `strict_variables` | `false` — undefinierte Variablen ergeben `null`, kein Error                         |
| `debug`            | `true` wenn Admin eingeloggt oder `WB_TWIG_DEBUG = true`                            |

---

## Automatisch verfügbare Globals

Diese Variablen sind **in jedem Template verfügbar** ohne sie explizit zu übergeben.

### Immer verfügbar (Frontend + Backend)

| Variable       | Typ    | Inhalt                                                   |
|----------------|--------|----------------------------------------------------------|
| `FTAN`           | string | CSRF-Token HTML `<input type="hidden" ...>`            |
| `WB_URL`         | string | Basis-URL der Website, z.B. `https://example.com/wbce` |
| `ADMIN_URL`      | string | URL zum Admin-Bereich                                  |
| `INCLUDE_URL`    | string | `WB_URL . '/include'`                                  |
| `MODULES_URL`    | string | `WB_URL . '/modules'`                                  |
| `THEME_URL`      | string | URL des aktiven Themes                                 |
| `SEC_ANCHOR`     | string | Anker-Prefix für Sections                              |
| `LANGUAGE`       | string | Aktive Sprache, z.B. `'DE'`                            |
| `MEDIA_URL`      | string | URL zum Media-Verzeichnis                              |
| `DATE_FORMAT`    | string | Datumsformat aus CMS-Einstellungen                     |
| `TIME_FORMAT`    | string | Zeitformat aus CMS-Einstellungen                       |
| `TEMPLATE_URL`   | string | URL des aktiven Templates (wenn definiert)             |
| `IS_ADMIN`       | bool   | Ist der aktuelle User Admin?                           |
| `IS_SUPERADMIN`  | bool   | Ist der aktuelle User Superadmin?                      |
| `USER_LOGGED_IN` | bool   | Ist ein User eingeloggt?                               |

### Nur wenn Section-ID übergeben wurde

Wenn `getTwig($path, $section_id)` aufgerufen wird, stehen zusätzlich zur Verfügung:
 
| Variable           | Typ    | Inhalt                                                |
|--------------------|--------|-------------------------------------------------------|
| `SECTION_ID`       | int    | Aktuelle Section-ID                                   |
| `PAGE_ID`          | int    | Seiten-ID der Section                                 |
| `PAGE_TITLE`       | string | Titel der Seite                                       |
| `PAGE_URL`         | string | Vollständige URL der Seite                            |
| `ADDON_URL`        | string | URL des Moduls (`WB_URL . '/modules/' . $module_dir`) |
| `LAYOUT_BLOCK`     | int    | Template-Block-Nummer                                 |
| `PUBLIC_START`     | int    | Veröffentlichungs-Start (Unix-Timestamp, 0 = immer)   |
| `PUBLIC_END`       | int    | Veröffentlichungs-Ende (Unix-Timestamp, 0 = immer)    |
| `SECTION_POSITION` | int    | Position der Section auf der Seite                    |

### Nur für AdminTools

| Variable  | Typ    | Inhalt                         |
|-----------|--------|--------------------------------|
| `TOOL_NAME` | string | Name des AdminTools          |
| `TOOL_URI`  | string | URL zum AdminTool            |
| `ADDON_URL` | string | URL des Modul-Verzeichnisses |

---

## Twig-Funktionen (SimpleFunctions)

### `L_(key)` — Übersetzungsstring

Ruft die WBCE-eigene `L_()`-Funktion auf.

```twig
{{ L_('TEXT:SAVE') }}
{{ L_('TEXT:TITLE') }}
{{ L_('MENU:PAGES') }}
```

Format: `'ARRAY:KEY'` — z.B. `TEXT:SAVE` → `$TEXT['SAVE']`.

### `Ln_(key, n)` — Pluralform

```twig
{{ Ln_('TEXT:PAGE_COUNT', 5) }}
{# → '5 Seiten' (wenn Pluralform definiert) #}
```

### `getIDKEY(id)` — Sichere ID-Übergabe

Erzeugt einen signierten Einmal-Token für sensible IDs (IDKEY-System).
Entspricht `$admin->getIDKEY($id)`.

```twig
<input type="hidden" name="item_id" value="{{ getIDKEY(item.item_id) }}">
```

### `getSetting(name, default)` — CMS-Einstellung lesen

Liest eine Einstellung aus `Settings::get()`.

```twig
{{ getSetting('website_title') }}
{{ getSetting('my_module_flag', 'fallback_value') }}
{% if getSetting('frontend_login') == 'true' %}...{% endif %}
```

### `has_value(value)` — Wert prüfen

Gibt `true` zurück wenn der Wert nicht leer ist (auch für Arrays).

```twig
{% if has_value(item.teaser) %}
    <p>{{ item.teaser }}</p>
{% endif %}

{% if has_value(item.images) %}
    {# images ist ein nicht-leeres Array #}
{% endif %}
```

### `check_io(value, compare)` — Checkbox/Radio-State

Gibt `' checked'` zurück wenn `value` mit `compare` übereinstimmt. Loose-Vergleich: `'1'`, `1`, `true` matchen alle.

```twig
<input type="checkbox" name="use_cats" value="1"{{ check_io(cfg.use_cats, 1) }}>
<input type="radio" name="sort" value="ASC"{{ check_io(cfg.sort_dir, 'ASC') }}>
```

### `insertJsFile(url, position, id)` — JavaScript einbinden

Entspricht `I::insertJsFile()`. Position-Werte: `'BODY'`, `'HEAD'`, `'BODY BTM-'`, `'HEAD BTM-'`.

```twig
{{ insertJsFile(MODULES_URL ~ '/my_module/js/app.js', 'BODY') }}
{{ insertJsFile([url1, url2], 'BODY BTM-') }}
```

### `insertCssFile(url, position, id)` — CSS einbinden

Entspricht `I::insertCssFile()`. Standard-Position: `'HEAD BTM-'`.

```twig
{{ insertCssFile(MODULES_URL ~ '/my_module/css/style.css') }}
```

### `insertJsCode(code, position, id)` — Inline-JS

```twig
{{ insertJsCode('console.log("hello");', 'BODY BTM-') }}
```

### `insertCssCode(code, position, id)` — Inline-CSS

```twig
{{ insertCssCode('.my-class { color: red; }') }}
```

### `insertFile(url, position, type)` — Generischer Asset-Loader

Erkennt den Dateityp automatisch aus der Dateiendung.

```twig
{{ insertFile(MODULES_URL ~ '/my_module/lib.js') }}
```

### `wordcut(str, length, break, cut)` — Text kürzen

Kürzt Text auf `length` Zeichen, entfernt HTML-Tags, fügt `…` an.

```twig
{{ wordcut(item.teaser, 150) }}
{{ wordcut(item.description, 200, '\n', true) }}
```

### `debug_dump(var, caption, isVarDump)` — Debug-Ausgabe

Entspricht der WBCE-Funktion `debug_dump()`. Formatierte `print_r()`-Ausgabe.

```twig
{{ debug_dump(item) }}
{{ debug_dump(cfg, 'Config-Array') }}
```

### `uniqid(prefix)` — Eindeutige ID

```twig
<div id="{{ uniqid('section-') }}">
```

### `parse_simple_md_tags(str)` / `md_filter(str)` — Markdown-Inline-Parsing

Konvertiert einfache Markdown-Tags: `**fett**`, `*kursiv*`, `` `code` ``.

```twig
{{ item.teaser|raw }}
{# oder: #}
{{ parse_simple_md_tags(item.teaser)|raw }}
```

### `theme_file(filename)` — Theme-Datei-URL

Gibt die URL einer Theme-Datei zurück, mit Fallback auf `theme_fallbacks/`.

```twig
<img src="{{ theme_file('icons/logo.svg') }}">
```

### `theme_icon(filename)` — Theme-Icon-URL

Spezifisch für Backend-Icons aus dem aktiven Theme oder `theme_fallbacks/icons/`.

```twig
<img src="{{ theme_icon('edit.png') }}" alt="Edit">
```

### `CodeMirror(id, syntax, options)` — CodeMirror-Editor

Nur verfügbar wenn das Modul `CodeMirror_Config` installiert ist.

```twig
{{ CodeMirror('my-textarea', 'php', {theme: 'monokai'})|raw }}
```

### `has_permission(name, type)` — Berechtigungsprüfung

```twig
{% if has_permission('pages_modify') %}...{% endif %}
{% if has_permission('my_module', 'MODULE') %}...{% endif %}
{# type: 'SYSTEM' (default) | 'MODULE' | 'TEMPLATE' #}
```

### `get_group_permissions(groupId)` — Gruppenrechte

Gibt die Berechtigungsstruktur einer Gruppe zurück (für Gruppen-Verwaltungs-UI).

### `get_area_states(groupId)` — Bereichs-Status

Gibt Tri-State-Status (`none`, `partial`, `full`) pro Berechtigungsbereich zurück.

---

## Twig-Filter (Extensions)

Zusätzlich zu den Standard-Twig-Filtern stehen zur Verfügung:

### `|strip_tags` — HTML-Tags entfernen

```twig
{{ item.teaser|strip_tags }}
```

### `|string`, `|int`, `|float`, `|bool` — Typ-Casting

Konvertiert einen Wert explizit in den gewünschten PHP-Typ.
Registriert via `CastExtension` in `TwigLoader.php`.

```twig
{# Typ-sicherer Vergleich (häufigster Anwendungsfall): #}
{% if active_tag == tag.tag_id|string %}...{% endif %}
{% if "1" == use_cats|string %}...{% endif %}

{# Typ-Konvertierung: #}
{{ item.price|float }}
{{ item.count|int }}
{% if item.visible|bool %}...{% endif %}
```

**Installation:**
1. `CastExtension.php` nach `include/Twig/WbceCustom/Extension/` kopieren
2. In `TwigLoader.php` nach den bestehenden `addExtension()`-Zeilen einfügen:
   ```php
   $oTwig->addExtension(new \Twig\WbceCustom\Extension\CastExtension());
   ```

### `|unserialize` — PHP-Serialisierung aufheben

```twig
{% set data = serialized_string|unserialize %}
{% for key, val in data %}...{% endfor %}
```

### Standard-Twig-Filter die in WBCE funktionieren

```twig
{{ value|e }}           {# HTML-escapen (Alias: |escape) #}
{{ value|raw }}         {# Kein Escaping — für HTML-Output #}
{{ value|default('') }} {# Fallback-Wert wenn null/undefined #}
{{ value|upper }}
{{ value|lower }}
{{ value|trim }}
{{ value|length }}
{{ value|slice(0, 80) }}
{{ value|replace({'foo': 'bar'}) }}
{{ value|json_encode }}
{{ value|date('d.m.Y') }}
{{ array|join(', ') }}
{{ array|keys }}
{{ array|first }}
{{ array|last }}
{{ array|sort }}
{{ array|merge(other_array) }}
```

**Nicht verfügbar:** `|convert_encoding`.  
**Nicht verfügbar:** `app.*`, `request.*` (Symfony-spezifisch).

---

## Twig-Tags (TokenParsers)

### `{% switch %}` — Switch/Case

WBCE ergänzt Twig um einen `switch`-Block (in Standard-Twig nicht enthalten):

```twig
{% switch variable %}

    {% case "value1" %}
        Erster Fall

    {% case "value2" or "value3" %}
        Zweiter oder Dritter Fall

    {% default %}
        Standardfall

{% endswitch %}
```

---

## Modul-eigene Twig-Funktionen registrieren

### Methode A — `TwigFunctions.php` (empfohlen für `twig_extend`-Module)

Wenn ein Modul `'twig_extend'` in `$module_function` hat, wird automatisch `modules/{dir}/TwigFunctions.php` geladen. Die Variable `$oTwig` ist direkt verfügbar:

```php
// modules/my_module/TwigFunctions.php
$oTwig->addFunction(new \Twig\TwigFunction('df_picture',
    function (array $img, string $cut = 'th', array $attrs = []): string {
        return df_picture($img, $cut, $attrs);
    },
    ['is_safe' => ['html']]  // Output wird nicht escaped
));
```

```php
// info.php
$module_function = 'page,initialize,twig_extend';
```

### Methode B — direkt nach `getTwig()`

```php
$oTwig = getTwig(CHUB_PATH . '/twig/');
$oTwig->addFunction(new \Twig\TwigFunction('my_func', function($x) {
    return my_helper($x);
}));
```

### `is_safe` — Wenn die Funktion HTML zurückgibt

Standardmäßig wird der Rückgabewert von Twig-Funktionen escaped. Wenn die Funktion sicheres HTML zurückgibt, `is_safe` setzen:

```php
new \Twig\TwigFunction('render_widget', $fn, ['is_safe' => ['html']])
```

Im Template dann **ohne** `|raw`:
```twig
{{ render_widget(data) }}   {# korrekt, wenn is_safe gesetzt #}
{{ render_widget(data)|raw }} {# auch möglich, aber redundant #}
```

---

## Do & Don't — Häufige Fehler

### Symfony-Syntax — gibt es in WBCE nicht

```twig
{# FALSCH — Symfony-spezifisch: #}
{{ app.request.query.get('tag') }}
{{ app.user.username }}

{# RICHTIG — Wert in PHP ermitteln und als Variable übergeben: #}
{# PHP: 'active_tag' => (int)($_GET['tag'] ?? 0) #}
{{ active_tag }}
```

### `|string`-Filter — jetzt verfügbar via CastExtension

```twig
{# Korrekt (nach Installation der CastExtension): #}
{% if active_tag == tag.tag_id|string %}...{% endif %}

{# Alternativ — nativer Twig-Weg ohne Extension: #}
{% if active_tag == tag.tag_id %}          {# Integer-Vergleich direkt #}
{% if active_tag == (tag.tag_id ~ '') %}   {# Konkatenation = String-Cast #}
```

### HTML-Output aus Funktionen

```twig
{# PHP-Code der HTML zurückgibt — immer |raw: #}
{{ DF_FORM|raw }}
{{ DF_IMAGE_MANAGER|raw }}
{{ FTAN|raw }}

{# Alternativ is_safe=['html'] in der Funktionsdefinition setzen #}
```

### Escaping

```twig
{# autoescape ist false — manuell escapen wenn nötig: #}
{{ item.title|e }}           {# HTML-sicher #}
{{ item.user_html|raw }}     {# Vertrauenswürdiges HTML #}
<input value="{{ item.title|e('html_attr') }}">  {# In Attributen #}
```

### FTAN in AJAX-Formularen

```twig
{# Der FTAN-Global enthält das vollständige <input>-Element: #}
{{ FTAN|raw }}

{# Für AJAX (nur den Token-Wert): #}
ftan: '{{ FTAN|strip_tags }}'
```

### `strict_variables = false`

Undefinierte Variablen geben `null` zurück, keinen Error. Zur Sicherheit `default()` verwenden:

```twig
{{ item.optional_field|default('') }}
{% if item.images|default([])|length > 0 %}
```

---

## Lopi-Pfad-Erweiterung

Wenn `getTwig($path, $section_id)` aufgerufen wird und das Modul ein `LayoutPlugins/`-Verzeichnis hat, wird dieses dem Twig-Loader automatisch als Suchpfad hinzugefügt. Das erlaubt `{% include 'my_partial.twig' %}` relativ zu `LayoutPlugins/`.

Ebenso wird `templates/{theme}/mod_{module_dir}/LayoutPlugins/` als Suchpfad hinzugefügt, wenn es existiert — Theme-Override für Lopi-Templates.

---

*WBCE CMS 1.7.0 — https://wbce.org*  
*Dokument erstellt auf Basis der TwigWbceCustom-Implementierung, 2026-05-15*
