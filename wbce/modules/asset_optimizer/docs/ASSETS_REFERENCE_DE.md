# WBCE Asset-System — Referenz

Vollständiges Nachschlagewerk zum Asset-System: alle `I::`-Methoden, alle
Positions-Namen, die Dateikonvention, die `asset-pos`-Syntax und die
Alt-nach-Neu-Zuordnungen aus WBCE 1.x.

> Zum **Lernen** eignet sich der Tab
> [**Assets im Template**](?tool=asset_optimizer&tab=docs&doc=embed) besser
> (`ASSETS_TUTORIAL.md` — Schritt für Schritt, mit „Aufruf → so sieht das HTML
> aus"). Wer nur das Backend-Tool bedienen will: Tab
> [**Anleitung**](?tool=asset_optimizer&tab=docs&doc=guide) (`TOOL_GUIDE.md`).

---

## 1. Was ist das Asset-System?

Wer in WBCE ein Modul oder Template entwickelt, muss früher oder später CSS-
und JavaScript-Dateien in die Seite einbinden. Die naive Lösung — `<link>`- und
`<script>`-Tags direkt in den Modulcode schreiben — führt schnell zu Problemen:

- Dieselbe jQuery-Version wird von fünf verschiedenen Modulen geladen.
- Ein Modul schreibt `<link>` mitten in den `<body>`, weil es keinen anderen Weg kennt.
- Der `<head>` ist voll mit Tags, die niemand kontrolliert.

Das **Asset-System** löst das. Es stellt eine zentrale Queue bereit, in die alle
Komponenten (Module, Templates, Snippets) ihre Assets eintragen. Erst am Ende des
Seitenaufbaus injiziert das System alles auf einmal an der richtigen Stelle —
dedupliziert, geordnet, ohne Doppelladungen.

**Zugriff:** Über die statische Klasse `I` — von überall im CMS aufrufbar.

```php
// Beispiel: Ein Modul trägt eine CSS-Datei ein
I::insertCssFile(WB_URL . '/modules/mein_modul/assets/frontend.css');
```

---

## 2. Wie funktioniert es?

```
┌─────────────────────────────────────────────────────────┐
│  Seitenaufbau (PHP)                                     │
│                                                         │
│  Module, Templates, Snippets rufen I:: auf              │
│    → Assets landen in der internen Queue                │
│                                                         │
│  Am Ende: I::process($html)                             │
│    1. Scanner: sucht Assets im Body mit asset-pos=""    │
│    2. Injektion: schreibt Queue-Einträge an HTML-Anker  │
│    3. Reset: Queue wird geleert                         │
└─────────────────────────────────────────────────────────┘
```

**Wichtige Eigenschaften:**

- **Deduplizierung** — Dieselbe Datei wird nur einmal geladen, egal wie oft sie
  eingetragen wird. Das gilt auch dann, wenn die URL einmal als Token
  (`{MODULES}/foo/bar.css`) und einmal als vollständige URL angegeben wird.
- **Kein Template-Eingriff nötig** — Das System findet `</head>` und `</body>`
  selbst. Templates brauchen keine Platzhalter.
- **Reihenfolge** — Innerhalb einer Position gilt: wer zuerst einträgt, erscheint
  zuerst im HTML.

---

## 3. Injection Points — Übersichtskarte

```
<html>
<head>
  <title>Seitentitel</title>
  │
  ├── head_early   ← Nach </title> — Preloads, kritisches CSS
  │
  │   <meta ...>
  │   <meta name="description" ...>
  ├── head_middle  ← Meta-Tags, Titel-Ersetzung
  │
  │   <link rel="stylesheet" href="theme.css">
  ├── head_late    ← CSS-Standard-Position
  │
  └── head_last    ← Absolut letztes vor </head>
</head>
<body>
  │
  ├── body_top     ← Absolut erstes im Body (z. B. GTM noscript)
  │
  ├── body_early   ← Früher Body-Bereich
  │
  │   ... Seiteninhalt ...
  │
  ├── body_late    ← JS-Standard-Position (mit defer)
  │
  └── body_last    ← Absolut letztes vor </body>
</body>
</html>
```

**Kurzform:** Wenn keine Position angegeben wird, landen CSS-Dateien in
`head_late` und JavaScript-Dateien in `body_late`.

---

## 4. Einstieg: Die Dateikonvention

Der einfachste Weg, Assets in WBCE einzubinden: **Dateien richtig benennen und
im Modul-Verzeichnis ablegen.** Das CMS lädt sie automatisch.

```file-tree
modules/
└── mein_modul/
    ├── assets/              ← empfohlener Unterordner (aufgeräumter)
    │   ├── frontend.css     → wird auf Frontend-Seiten geladen (head_late)
    │   ├── frontend.js      → wird auf Frontend-Seiten geladen (body_late)
    │   └── frontend_body.js → wird auf Frontend-Seiten in body_late geladen
    │
    ├── frontend.css         → alternativ: direkt im Modulordner (legacy)
    └── frontend.js          → alternativ: direkt im Modulordner (legacy)
```

Für das Backend gelten dieselben Regeln — nur mit `backend` statt `frontend`:

```file-tree
assets/
    backend.css
    backend.js
    backend_body.js
```

**Wie wird das ausgelöst?** Das Template ruft einmalig `register_frontend_modfiles()`
auf — das CMS erledigt den Rest automatisch für alle aktiven Module der Seite.

> Die Dateikonvention ist die bevorzugte Methode für einfache Fälle.
> Für komplexere Szenarien steht die PHP-API zur Verfügung (→ Abschnitt 5).

---

## 5. PHP-API — Schritt für Schritt

Alle Methoden sind statisch und können von überall aufgerufen werden:

```php
I::insertCssFile(...);
I::insertJsFile(...);
// usw.
```

### 5.1 CSS-Dateien einbinden

```php
// Einfachste Form — landet in head_late
I::insertCssFile(WB_URL . '/modules/mein_modul/assets/frontend.css');

// Mit Token-Syntax (kürzer, empfohlen)
I::insertCssFile('{MODULES}/mein_modul/assets/frontend.css');

// Andere Position
I::insertCssFile('{MODULES}/mein_modul/assets/critical.css', 'head_early');

// Mit media-Attribut (nur für Druck)
I::insertCssFile('{MODULES}/mein_modul/assets/print.css', 'head_late', ['media' => 'print']);

// Mehrere Dateien auf einmal
I::insertCssFile([
    '{MODULES}/mein_modul/assets/base.css',
    '{MODULES}/mein_modul/assets/layout.css',
]);
```

**Verfügbare URL-Tokens:**

| Token         | Wird ersetzt durch                 |
| ------------- | ---------------------------------- |
| `{MODULES}`   | URL des modules/-Verzeichnisses    |
| `{WB_URL}`    | Basis-URL der WBCE-Installation    |
| `{TEMPLATE}`  | URL des aktiven Frontend-Templates |
| `{THEME_URL}` | URL des aktiven Admin-Themes       |
| `{MEDIA_URL}` | URL des Media-Verzeichnisses       |

Eigene Tokens können registriert werden:

```php
I::addUrlToken('{MEIN_PLUGIN}', WB_URL . '/modules/mein_plugin');
I::insertCssFile('{MEIN_PLUGIN}/assets/style.css');
```

---

### 5.2 JavaScript-Dateien einbinden

```php
// Standard — landet in body_late, bekommt automatisch defer=""
I::insertJsFile('{MODULES}/mein_modul/assets/frontend.js');

// In den Head (z. B. für Bibliotheken die früh geladen werden müssen)
I::insertJsFile('{MODULES}/mein_modul/assets/lib.js', 'head_early');

// Als ES-Modul
I::insertJsFile('{MODULES}/mein_modul/assets/app.js', 'body_late', ['type' => 'module']);

// defer explizit deaktivieren (ungewöhnlich)
I::insertJsFile('{MODULES}/mein_modul/assets/sync.js', 'body_late', ['defer' => false]);
```

> **Hinweis:** Dateien die in `body_*` Positionen landen, erhalten automatisch
> `defer="defer"`. Das ist modernes, performantes Standardverhalten.

---

### 5.3 Inline CSS und JS

Manchmal müssen kleine Code-Schnipsel direkt in die Seite eingebettet werden —
ohne eigene Datei.

```php
// Inline CSS — landet in <style>-Block in head_late
I::insertCssCode('.mein-banner { background: #f00; padding: 1em; }');

// CSS-Variablen früh setzen
I::insertCssCode(':root { --primary: #3498db; }', 'head_early');

// Inline JS — landet in <script>-Block in body_late
I::insertJsCode('document.addEventListener("DOMContentLoaded", function() {
    console.log("Seite geladen");
});');

// JS-Variable früh verfügbar machen
I::insertJsCode('window.MEIN_MODUL_CONFIG = ' . json_encode($config) . ';', 'head_late');
```

---

### 5.4 HTML-Blöcke injizieren

Für Fälle, wo reines HTML (kein CSS oder JS) in den Body eingefügt werden soll:

```php
// Cookie-Banner am Seitenanfang
I::insertHtmlCode(
    '<div id="cookie-banner" class="banner">Wir verwenden Cookies...</div>',
    'body_early',
    'cookie-banner'   // optionale ID für späteres Entfernen
);

// Verstecktes Modal am Seitenende
I::insertHtmlCode('<div id="modal-overlay" hidden></div>', 'body_late');
```

---

### 5.5 Meta-Tags setzen

```php
// Kurzform: name + content (ersetzt vorhandenen Tag gleichen Namens)
I::insertMeta('description', 'Meine Seite über Holzmöbel');
I::insertMeta('robots',      'noindex, nofollow');
I::insertMeta('author',      'Max Mustermann');

// Vollständiger Tag — für Open Graph, Twitter Cards etc.
I::insertMeta('<meta property="og:title" content="Mein Titel">');
I::insertMeta('<meta property="og:image" content="' . WB_URL . '/media/bild.jpg">');

// Immer hinzufügen, nie ersetzen
I::insertMeta('<meta property="og:locale" content="de_DE">', 'add');

// Legacy Array-Syntax (weiterhin unterstützt)
I::insertMetaTag(['name' => 'keywords', 'content' => 'Möbel, Holz, Design']);
```

**Wie funktioniert die Ersetzung?** Das System sucht im bestehenden `<head>` nach
einem `<meta>`-Tag mit demselben `name`-, `property`- oder `http-equiv`-Attribut
und ersetzt ihn direkt an seiner Position. Wird kein passender Tag gefunden, wird
der neue Tag in `head_middle` eingefügt.

---

### 5.6 Seitentitel setzen

```php
// Ersetzt den <title>-Tag des Templates
I::insertTitle('Mein Produkt — ' . WEBSITE_TITLE);
```

Auch hier sucht das System den vorhandenen `<title>`-Tag und ersetzt ihn in-place.

---

### 5.7 Dateien zu Bundles zusammenfassen

Mehrere CSS- oder JS-Dateien können zu einer einzigen Datei zusammengeführt
werden. Das reduziert HTTP-Requests und beschleunigt die Seite.

```php
// CSS-Bundle
I::insertCssBundle([
    '{MODULES}/mein_modul/assets/base.css',
    '{MODULES}/mein_modul/assets/layout.css',
    '{MODULES}/mein_modul/assets/components.css',
], 'mein-modul-bundle');

// JS-Bundle
I::insertJsBundle([
    '{MODULES}/mein_modul/assets/utils.js',
    '{MODULES}/mein_modul/assets/app.js',
], 'mein-modul-app');
```

**Wie funktioniert der Cache?** Das Bundle wird als Datei in `cache/assets/`
gespeichert. Der Cache wird automatisch invalidiert, sobald sich eine der
Quelldateien ändert (Änderungszeitstempel wird geprüft).

**Wichtig:** Dateien die in einem Bundle enthalten sind, werden nicht nochmals
einzeln geladen — auch dann nicht, wenn sie später über `I::insertCssFile()`
oder die automatische Dateikonvention eingetragen werden.

**CDN-URLs in Bundles:** Externe URLs (http/https) können nicht gebündelt werden.
Sie werden automatisch als einzelne `<link>`- bzw. `<script>`-Tags nach dem Bundle
eingereiht. Empfehlung: CDN-Dateien lieber direkt mit `I::insertCssFile()` /
`I::insertJsFile()` einbinden, um die Ladereihenfolge explizit zu steuern.

**Minifizierung.** Diese Konstanten steuern die Verkleinerung. Im Alltag stellt
man sie über das **Asset-Optimizer-Tool** im Backend (das schreibt sie nach
`var/config_constants.ini.php`); wer sie von Hand setzt, nimmt
`var/config_constants.ini.php` oder – nicht empfohlen – `config.php`:

```php
define('MINIFY_CSS', true);   // CSS minifizieren (Bundles + Einzeldateien)
define('MINIFY_JS',  true);   // JS  minifizieren (Bundles + Einzeldateien)

// Entwickler-Quellansicht: nur für eingeloggte Admins — Bundling wird aufgelöst
// und Minifizierung deaktiviert, jede Datei erscheint einzeln in den DevTools.
// Alle anderen Besucher erhalten weiterhin gebündelte/minifizierte Assets.
// (Alte Schreibweisen ASSET_MINIFY_DEBUG / MINIFY_ASSETS_DEBUG gelten weiter.)
define('ASSETS_MINIFY_DEBUG', true);

// Eigenes Cache-Verzeichnis für minifizierte und gebündelte Dateien.
// Standard: WB_PATH/cache/assets/
define('MINIFY_ASSETS_DIR', '/absolute/path/to/custom/cache/');

// Auf false setzen, um das .min. im Cache-Dateinamen wegzulassen.
// Standard: true  →  mymodule-css-main.min.css
//           false →  mymodule-css-main.css
define('MINIFY_USE_SUFFIX', false);

// Cache-Busting: hängt ?mtime an jede lokale Asset-URL. Wird über das
// Asset-Optimizer-Tool bzw. den Filter "Assets Cache Busting" gesetzt und
// landet in der settings-Tabelle, nicht in einer Datei.
define('OPF_ASSETS_CACHE_BUSTING', true);
```

Ist `MINIFY_CSS` bzw. `MINIFY_JS` aktiv, werden auch lokale Einzeldateien beim
ersten Aufruf minifiziert und in `cache/assets/` abgelegt — mit
menschenlesbarem Namen:

```
modules/mymodule/assets/backend_custom.css  →  cache/assets/mymodule-assets-backend_custom.min.css
templates/wbcetik/css/main.css              →  cache/assets/wbcetik-css-main.min.css
```

Externe URLs (CDN) werden nie minifiziert.

---

## 6. Assets ohne PHP: `asset-pos` und `<!--(MOVE)-->`

Manchmal ist es nicht möglich, `I::` direkt per PHP aufzurufen. Das passiert z. B.:

- im **WYSIWYG-Editor** — ein Redakteur fügt einen Embed-Code mit `<script>` oder
  `<link>` ein, der mitten in den Seiteninhalt gerät
- in einer **Twig-Vorlage** — du schreibst reines HTML/Twig ohne eingebetteten PHP-Code
- in **Modul-Ausgaben**, die als fertige HTML-Strings aus einer Datenbank kommen
- bei **Drittanbieter-Codes** (Google Maps, Analytics, Widget-Embeds), die du nur
  copy-pasten kannst

Das Asset-System löst das mit zwei Mechanismen: dem `asset-pos`-Attribut und der
`<!--(MOVE)-->`-Block-Syntax. Beide werden vom Scanner am Ende des Seitenaufbaus
verarbeitet — unabhängig davon, wo im HTML sie stehen.

---

### 6.1 Das `asset-pos`-Attribut

Füge `asset-pos="POSITION"` direkt an ein HTML-Tag. Der Scanner erkennt es,
entnimmt den Tag aus seiner aktuellen Position und fügt ihn an der richtigen
Stelle ein — als wäre er per `I::insertCssFile()` oder `I::insertJsFile()`
eingetragen worden.

#### CSS-Datei einbinden

```html
<link rel="stylesheet" href="/modules/my_module/assets/frontend.css" asset-pos="head_late">
```

Äquivalenter PHP-Aufruf: `I::insertCssFile('/modules/my_module/assets/frontend.css')`

#### Inline-CSS

```html
<style asset-pos="head_early">
  :root {
    --primary-color: #3d7fbc;
    --font-base: 'Inter', sans-serif;
  }
</style>
```

Ohne `asset-pos` würde ein `<style>`-Block im Body als valide gelten und nicht
verschoben. Mit `asset-pos` landet er sauber im `<head>`.

#### JS-Datei einbinden

```html
<script src="/modules/my_module/assets/frontend.js" asset-pos="body_late"></script>
```

Der Tag bekommt automatisch `defer="defer"` — genau wie bei `I::insertJsFile()`.

#### Inline-JS

```html
<script asset-pos="body_late">
  document.addEventListener('DOMContentLoaded', function () {
    initMyWidget({ color: '#3d7fbc', speed: 400 });
  });
</script>
```

> **Wichtig:** `<script>`-Tags **ohne** `asset-pos` werden vom Scanner grundsätzlich
> nicht angefasst. Inline-Scripts die sofort ausgeführt werden sollen bleiben genau
> dort wo sie stehen. Nur explizit markierte Tags werden verschoben.

#### Mehrere Tags — jeder bekommt sein eigenes `asset-pos`

Auch wenn ein Embed-Code aus mehreren zusammengehörenden Tags besteht, braucht
es keine Block-Syntax. Jeder Tag bekommt einfach sein eigenes `asset-pos`:

```html
<!-- Google Analytics — beide Tags einzeln markiert -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"
        asset-pos="head_late"></script>
<script asset-pos="head_late">
  window.dataLayer = window.dataLayer || [];
  function gtag(){ dataLayer.push(arguments); }
  gtag('js', new Date());
  gtag('config', 'G-XXXXXXXXXX');
</script>
```

Das System verarbeitet jeden Tag unabhängig und fügt beide an die richtige
Position ein — in der Reihenfolge in der sie im Dokument stehen.

#### Alle unterstützten Tag-Typen

| Tag                             | Mit `asset-pos` | Ohne `asset-pos`                        |
| ------------------------------- | --------------- | --------------------------------------- |
| `<link rel="stylesheet">`       | Zur Position    | Automatisch nach `head_late` verschoben |
| `<style>...</style>`            | Zur Position    | Bleibt im Body                          |
| `<script src="...">`            | Zur Position    | Bleibt im Body (kein defer)             |
| `<script>...</script>` (inline) | Zur Position    | Bleibt im Body, wird sofort ausgeführt  |
| `<meta ...>`                    | –               | Automatisch in den Head verschoben      |
| `<title>...</title>`            | –               | Ersetzt bestehenden `<title>` im Head   |

---

### 6.2 Mehrere Assets als Gruppe — `<asset-group>`

Wenn mehrere zusammengehörende Tags gemeinsam an eine Position verschoben werden
sollen, fasst `<asset-group>` sie in einem Container zusammen:

```html
<asset-group pos="body_late">
  <link rel="stylesheet" href="widget.css">
  <script src="widget-loader.js"></script>
  <script>initWidget({ id: 'main' });</script>
</asset-group>
```

Der Scanner verarbeitet jeden Kind-Tag einzeln, stellt ihn an die angegebene
Position und entfernt den Container vollständig aus dem Dokument.

#### Per-Tag-Override

Ein Kind-Tag kann die Gruppen-Position mit seinem eigenen `asset-pos`
überschreiben:

```html
<asset-group pos="body_late">
  <!-- CSS soll früher — override auf head_early -->
  <link rel="stylesheet" href="critical.css" asset-pos="head_early">

  <!-- JS bleibt bei body_late (Gruppen-Default) -->
  <script src="app.js"></script>

  <!-- Inline-Script ebenfalls body_late -->
  <script>bootstrap();</script>
</asset-group>
```

#### Anwendungsfälle

**Drittanbieter-Embed mit mehreren Tags** — kein `asset-pos` auf jedem einzelnen Tag nötig:

```html
<asset-group pos="head_late">
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){ dataLayer.push(arguments); }
    gtag('js', new Date());
    gtag('config', 'G-XXXXXXXXXX');
  </script>
</asset-group>
```

**Cookie-Banner als Self-contained HTML-Snippet:**

```html
<asset-group pos="body_early">
  <link rel="stylesheet" href="/templates/my_template/css/cookie-banner.css"
        asset-pos="head_late">

  <div id="cookie-banner" role="alertdialog">
    <p>Wir verwenden Cookies. <a href="/datenschutz">Mehr erfahren</a></p>
    <button id="cookie-accept">Akzeptieren</button>
  </div>

  <script asset-pos="body_last">
    document.getElementById('cookie-accept').addEventListener('click', function () {
      document.getElementById('cookie-banner').remove();
      document.cookie = 'cookies_ok=1; max-age=31536000; path=/';
    });
  </script>
</asset-group>
```

Das `<div>` (kein Asset-Tag) landet als HTML-Block an `body_early`. CSS und Script
landen dank ihrer eigenen `asset-pos`-Attribute an ihren jeweiligen Positionen.

---

### 6.3 Die `<!--(MOVE)-->`-Block-Syntax *(deprecated)*

> **Nicht für neuen Code verwenden.** Wird nur noch aus Rückwärtskompatibilität
> unterstützt. Für alle neuen Fälle: `<asset-group>` (→ 6.2) oder `asset-pos`
> direkt am Tag (→ 6.1).

```html
<!--(MOVE) body_late -->
<script>...</script>
<!--(END)-->
```

Migration: Container durch `<asset-group pos="...">` ersetzen,
`<!--(MOVE)-->`/`<!--(END)-->`-Kommentare entfernen.

---

### 6.4 Praktische Szenarien

#### Szenario 1 — WYSIWYG-Editor (Redakteur fügt Embed-Code ein)

Ein Redakteur fügt folgenden Code direkt in TinyMCE ein:

```html
<!-- So kommt es aus dem Embed-Formular des Drittanbieters -->
<link rel="stylesheet" href="https://cdn.example.com/widget.css">
<div id="my-widget" data-id="12345"></div>
<script src="https://cdn.example.com/widget.js"></script>
```

Das Problem: `<link>` und `<script>` landen mitten im `<body>`. Mit einem kleinen
Eingriff des Redakteurs oder einer Anpassung im Modul-Template:

```html
<link rel="stylesheet" href="https://cdn.example.com/widget.css" asset-pos="head_late">
<div id="my-widget" data-id="12345"></div>
<script src="https://cdn.example.com/widget.js" asset-pos="body_late"></script>
```

Ergebnis: CSS landet im `<head>`, Script landet vor `</body>` mit `defer` —
sauber getrennt, ohne dass der Redakteur PHP verstehen muss.

---

#### Szenario 2 — Twig-Template ohne PHP-Aufrufe

Ein Modul rendert seine Ausgabe über Twig. In der `.twig`-Datei kann man kein
`I::insertCssFile()` aufrufen — aber man kann `asset-pos` direkt ins HTML schreiben:

```twig
{# modules/my_module/twig/view.twig #}

<link rel="stylesheet" href="{{ WB_URL }}/modules/my_module/assets/frontend.css" asset-pos="head_late">

<div class="my-module">
    {% for item in items %}
        <article>{{ item.title }}</article>
    {% endfor %}
</div>

<script src="{{ WB_URL }}/modules/my_module/assets/frontend.js" asset-pos="body_late"></script>
```

Der Scanner verarbeitet das fertige HTML nach dem Rendering — Twig-Syntax ist
dann längst aufgelöst.

> **Besser:** Wenn das Modul eine `initialize_fe.php` hat, Assets dort per PHP
> eintragen. Das ist sauberer. `asset-pos` im Twig ist sinnvoll wenn das Modul
> nur in bestimmten Fällen geladen wird und die Assets konditional erscheinen sollen.

---

#### Szenario 3 — Konditionaler Asset-Load direkt im HTML

Ein Template-Block wird nur auf bestimmten Seiten ausgegeben. Die Assets sollen
nur dann geladen werden:

```html
<!-- Dieser Block erscheint nur auf der Kontaktseite -->
<div class="contact-form">
  <form>...</form>
</div>

<style asset-pos="head_late">
  .contact-form { max-width: 640px; margin: 0 auto; }
  .contact-form input { width: 100%; margin-bottom: 1rem; }
</style>

<script asset-pos="body_late">
  document.querySelector('.contact-form').addEventListener('submit', function (e) {
    e.preventDefault();
    // Formular-Validierung
  });
</script>
```

Weil der Block nur im HTML erscheint wenn die Seite ihn ausgibt, werden CSS und
JS automatisch nur auf dieser Seite geladen — ohne PHP-Logik.

---

#### Szenario 4 — Statischer HTML-Snippet (include)

Ein Template bindet eine reine HTML-Datei ein (z. B. ein Cookie-Banner).
Jeder Tag bekommt sein eigenes `asset-pos` — keine Block-Syntax nötig:

```html
<!-- templates/my_template/partials/cookie-banner.html -->

<link rel="stylesheet" href="/templates/my_template/css/cookie-banner.css"
      asset-pos="head_late">

<div id="cookie-banner" role="alertdialog" aria-modal="true" asset-pos="body_early">
  <p>Wir verwenden Cookies. <a href="/datenschutz">Mehr erfahren</a></p>
  <button id="cookie-accept">Akzeptieren</button>
</div>

<script asset-pos="body_late">
  document.getElementById('cookie-accept').addEventListener('click', function () {
    document.getElementById('cookie-banner').remove();
    document.cookie = 'cookies_ok=1; max-age=31536000; path=/';
  });
</script>
```

Die HTML-Datei trägt ihre eigenen Assets selbst ein — jeder Tag landet genau
dort wo er hingehört, kein PHP nötig, kein Eingriff ins Template.

---

### 6.5 Kurzreferenz

| Ziel                        | Methode                                                    |
| --------------------------- | ---------------------------------------------------------- |
| CSS-Datei einbinden         | `<link href="..." asset-pos="head_late">`                  |
| Inline-CSS einfügen         | `<style asset-pos="head_late">...</style>`                 |
| JS-Datei einbinden          | `<script src="..." asset-pos="body_late">`                 |
| Inline-JS einfügen          | `<script asset-pos="body_late">...</script>`               |
| Mehrere Tags gruppieren     | `<asset-group pos="body_late">...</asset-group>`           |
| Per-Tag-Override in Gruppe  | `asset-pos="..."` direkt am Kind-Tag                       |
| Meta-Tag setzen             | `<meta name="..." content="...">` (kein `asset-pos` nötig) |
| Block-Syntax *(deprecated)* | `<!--(MOVE) POSITION -->...<!--(END)-->` — nur legacy      |

---

## 7. Dateikonvention — vollständige Referenz

Für jedes Modul und jeden Kontext gibt es drei Dateitypen:

| Suffix                  | Typ                              | Verhalten                                       |
| ----------------------- | -------------------------------- | ----------------------------------------------- |
| `frontend.css`          | **Basis**                        | Wird geladen, außer eine Custom-Datei existiert |
| `frontend_custom.css`   | **Custom**                       | Ersetzt die Basis-Datei vollständig             |
| `frontend_override.css` | **Override**                     | Wird additiv **nach** Basis oder Custom geladen |
| `frontend.override.css` | **Legacy Custom** *(deprecated)* | Verhält sich wie `_custom`                      |

Dieselbe Logik gilt für alle Dateiarten:

```file-tree
frontend.css              backend.css
frontend.js               backend.js
frontend_body.js          backend_body.js

frontend_custom.css       backend_custom.css
frontend_custom.js        backend_custom.js
frontend_body_custom.js   backend_body_custom.js

frontend_override.css     backend_override.css
frontend_override.js      backend_override.js
frontend_body_override.js backend_body_override.js
```

**Ablageorte** (beide werden unterstützt, `assets/` wird bevorzugt):

```file-tree
    modules/mein_modul/assets/frontend.css   // bevorzugt (aufgeräumter)
    modules/mein_modul/frontend.css          // legacy (weiterhin unterstützt)
```

**Priorität** (erste gefundene Datei gewinnt):

```
1. assets/frontend_custom.css     ← Custom in assets/
2. frontend_custom.css            ← Custom in root
3. assets/frontend.override.css   ← Legacy Custom in assets/ (deprecated)
4. frontend.override.css          ← Legacy Custom in root   (deprecated)
5. assets/frontend.css            ← Basis in assets/
6. frontend.css                   ← Basis in root
```

Danach, unabhängig davon welche Basis/Custom-Datei gewählt wurde:

```
7. assets/frontend_override.css   ← Override in assets/ (additiv)
8. frontend_override.css          ← Override in root    (additiv)
```

**Typische Anwendungsfälle:**

```
# Modul liefert Basis-Styles, Site-Betreiber möchte sie komplett ersetzen:
modules/news_img/frontend_custom.css

# Modul liefert Basis-Styles, Site-Betreiber möchte nur wenige Anpassungen:
modules/news_img/frontend_override.css

# Modul liefert neues Layout, altes Layout bleibt als Fallback:
modules/news_img/assets/frontend.css     ← neues Layout
modules/news_img/frontend.css            ← nie erreicht, weil assets/ Priorität hat
```

---

## 8. Cheat Sheets

### 8.1 Injection Points

| Position      | Anker im HTML   | Standard für                        |
| ------------- | --------------- | ----------------------------------- |
| `head_top`    | Nach `<head>`   | DNS-Prefetch, allererste Ressourcen |
| `head_early`  | Nach `</title>` | Kritisches CSS, Preloads            |
| `head_middle` | Vor `</head>`   | Meta-Tags, Titel                    |
| `head_late`   | Vor `</head>`   | **CSS-Standard**                    |
| `head_last`   | Vor `</head>`   | Letztes CSS/JS im Head              |
| `body_top`    | Nach `<body>`   | Tag Manager noscript o. Ä.          |
| `body_early`  | Nach `<body>`   | Frühes Body-HTML                    |
| `body_late`   | Vor `</body>`   | **JS-Standard** (mit defer)         |
| `body_last`   | Vor `</body>`   | Absolut letztes JS                  |

**Kurzformen** (werden automatisch aufgelöst):

| Kurzform   | Wird zu                              |
| ---------- | ------------------------------------ |
| `'head'`   | `head_late`                          |
| `'body'`   | `body_late`                          |
| `'early'`  | `head_early`                         |
| `'middle'` | `head_middle`                        |
| `'late'`   | `head_late` (CSS) / `body_late` (JS) |

---

### 8.2 I:: Methoden

```php
// Dateien
I::insertCssFile($url, $position = 'head_late', $attrs = [], $id = '')
I::insertJsFile($url,  $position = 'body_late', $attrs = [], $id = '')

// Bundles
I::insertCssBundle([$url, ...], $identifier, $position = 'head_late')
I::insertJsBundle( [$url, ...], $identifier, $position = 'body_late')

// Inline Code
I::insertCssCode($code, $position = 'head_late', $id = '')
I::insertJsCode( $code, $position = 'body_late', $id = '')

// HTML
I::insertHtmlCode($html, $position = 'body_early', $id = '')

// Head-Elemente
I::insertMeta($nameOrTag, $contentOrAction = 'replace', $position = 'head_middle')
I::insertMetaTag(['name' => '...', 'content' => '...'])   // Legacy Array-Syntax
I::insertTitle($text)

// Verwaltung
I::addUrlToken('{TOKEN}', $url)
I::remove($type, $id)          // Eintrag aus Queue entfernen
I::clearCache()                // Bundle-Cache leeren
I::process(string &$content)   // Manuelle Ausführung (normalerweise automatisch)

// Legacy / Compat
I::insertCssBundle([...], $id)   // @deprecated → insertCssBundle()
I::insertJsBundle([...],  $id)   // @deprecated → insertJsBundle()
I::doFilter($content)              // → gibt String zurück (OutputFilter-Kompatibilität)
I::addPlaceholdersToDom($html)     // → entfernt alte <!--(PH)--> Marker
I::resetTitle()
I::delJs($id)
I::delCss($id)
```

---

### 8.3 Dateikonvention

```file-tree
modules/{name}/
├── assets/                          ← empfohlen
│   ├── frontend.css                 Frontend CSS   (Basis)
│   ├── frontend_custom.css          Frontend CSS   (ersetzt Basis)
│   ├── frontend_override.css        Frontend CSS   (additiv nach Basis/Custom)
│   ├── frontend.js                  Frontend JS    (Basis, head)
│   ├── frontend_custom.js           Frontend JS    (ersetzt Basis)
│   ├── frontend_override.js         Frontend JS    (additiv)
│   ├── frontend_body.js             Frontend JS    (Basis, body)
│   ├── frontend_body_custom.js      Frontend JS    (ersetzt Body-Basis)
│   ├── frontend_body_override.js    Frontend JS    (additiv, body)
│   ├── backend.css                  Backend CSS    (Basis)
│   ├── backend_custom.css           Backend CSS    (ersetzt Basis)
│   ├── backend_override.css         Backend CSS    (additiv)
│   ├── backend.js                   Backend JS     (Basis, head)
│   ├── backend_custom.js            Backend JS     (ersetzt Basis)
│   ├── backend_override.js          Backend JS     (additiv)
│   ├── backend_body.js              Backend JS     (Basis, body)
│   ├── backend_body_custom.js       Backend JS     (ersetzt Body-Basis)
│   └── backend_body_override.js     Backend JS     (additiv, body)
└── (dieselben Dateien direkt im Root des Moduls) ← legacy, weiterhin unterstützt
```

---

### 8.4 Alte → Neue Positions-Namen

Wer aus älterem WBCE-Code migriert, findet hier die Entsprechungen:

| Alte Position                | Neue Position | Hinweis                         |
| ---------------------------- | ------------- | ------------------------------- |
| `HEAD TOP+`                  | `head_top`    |                                 |
| `HEAD TOP-`                  | `head_early`  |                                 |
| `HEAD BTM+`                  | `head_late`   | Standard für CSS                |
| `HEAD BTM-`                  | `head_last`   |                                 |
| `HEAD`                       | `head_late`   |                                 |
| `CSS HEAD TOP+`              | `head_top`    |                                 |
| `CSS HEAD TOP-`              | `head_early`  |                                 |
| `CSS HEAD BTM+`              | `head_late`   |                                 |
| `CSS HEAD BTM-`              | `head_last`   |                                 |
| `JS HEAD TOP+`               | `head_top`    |                                 |
| `JS HEAD TOP-`               | `head_early`  |                                 |
| `JS HEAD BTM+`               | `head_late`   |                                 |
| `JS HEAD BTM-`               | `head_last`   |                                 |
| `CSS HEAD MODFILES`          | `head_late`   | Automatisch via Dateikonvention |
| `JS HEAD MODFILES`           | `head_late`   | Automatisch via Dateikonvention |
| `BODY TOP+`                  | `body_top`    |                                 |
| `BODY TOP-`                  | `body_early`  |                                 |
| `BODY BTM+`                  | `body_late`   | Standard für JS                 |
| `BODY BTM-`                  | `body_last`   |                                 |
| `BODY`                       | `body_late`   |                                 |
| `JS BODY TOP+`               | `body_top`    |                                 |
| `JS BODY TOP-`               | `body_early`  |                                 |
| `JS BODY BTM+`               | `body_late`   |                                 |
| `JS BODY BTM-`               | `body_last`   |                                 |
| `JS BODY MODFILES`           | `body_late`   | Automatisch via Dateikonvention |
| `HTML BODY TOP+`             | `body_top`    |                                 |
| `HTML BODY TOP-`             | `body_early`  |                                 |
| `HTML BODY BTM+`             | `body_late`   |                                 |
| `HTML BODY BTM-`             | `body_last`   |                                 |
| `HEAD+` *(Meta)*             | `head_middle` |                                 |
| `KEY+` *(Keywords-Meta)*     | `head_middle` |                                 |
| `DESC+` *(Description-Meta)* | `head_middle` |                                 |

**Legacy `<!--(MOVE)-->`-Syntax** *(deprecated, wird noch unterstützt):*

```html
<!-- Alt — wird noch verarbeitet, aber nicht mehr empfohlen -->
<!--(MOVE) JS BODY BTM- -->
<script>...</script>
<!--(END)-->

<!-- Neu — direkt am Tag -->
<script asset-pos="body_last">...</script>

<!-- Oder per PHP -->
<?php I::insertJsCode('...', 'body_last'); ?>
```
