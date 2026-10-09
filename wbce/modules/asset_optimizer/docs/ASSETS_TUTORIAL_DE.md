# AssetQueue — Tutorial

Dieses Tutorial zeigt, wie CSS- und JavaScript-Dateien seit WBCE 1.7.0
eingebunden werden können, und was jeder Aufruf konkret in den HTML-Output
schreibt.

> **Für wen ist das?** Für Leute, die Templates oder Module bauen und dabei
> Code schreiben. Wenn du nur das **Asset-Optimizer-Tool** im Backend bedienen
> willst (Schalter für Minifizierung und Cache-Busting), lies stattdessen den
> Tab [**Anleitung**](?tool=asset_optimizer&tab=docs&doc=guide) (`TOOL_GUIDE.md`)
> — dort kommt kein Code vor.

---

## Begriffe in einem Satz

| Begriff       | Bedeutung                                                                              |
| ------------- | -------------------------------------------------------------------------------------- |
| HTTP-Request  | Eine einzelne Anfrage des Browsers an den Server, um eine Datei zu holen.              |
| CDN           | Ein fremder Server, der eine Datei ausliefert (z. B. `code.jquery.com`).               |
| minifizieren  | Leerraum und Kommentare aus einer Datei entfernen; der Inhalt bleibt gleich.           |
| Bundle        | Mehrere Quelldateien, zu einer ausgelieferten Datei zusammengefasst.                   |
| `defer`       | HTML-Attribut: Skript lädt parallel, wird erst nach dem Parsen der Seite ausgeführt.   |
| `async`       | HTML-Attribut: Skript lädt parallel und wird ausgeführt, sobald es da ist.             |
| Cache-Busting | Ein `?<zahl>` an der URL, das sich bei jeder Dateiänderung ändert — erzwingt Neuladen. |
| `mtime`       | Die „modification time", der Änderungszeitstempel einer Datei auf dem Server.          |

> **`insertCssFile()` und `I::insertCssFile()` sind dasselbe.** Die kurze Form
> ohne `I::` ist eine globale Abkürzung für den Aufruf auf der Klasse `I`. In
> PHP funktionieren beide; in Twig-Templates gibt es nur die kurze Form. Dieses
> Tutorial schreibt die kurze Form — außer bei `insertCssBundle()` /
> `insertJsBundle()`, die es nur als `I::`-Aufruf gibt.

---

## Warum das Queue-System benutzen?

Das bisherige Vorgehen in Templates (und Modulen) ist:

```php
<link rel="stylesheet" href="<?=TEMPLATE_DIR; ?>/styles.css">
<script src="<?=TEMPLATE_DIR; ?>/mymod/frontend.js"></script>
```

Das funktioniert — hat aber mehrere Probleme:

**1. Doppelte Einbindungen.** Wenn zwei Module dasselbe jQuery einbinden, landet es zweimal
im HTML. Das Queue-System erkennt gleiche Dateien und überspringt den zweiten Eintrag automatisch.

**2. Unkontrollierte Reihenfolge.** Ein `echo` landet genau dort im Output, wo es im PHP-Code
steht — oft mitten im Body, manchmal zu früh, manchmal zu spät für Dependencies.

**3. Kein Bundling.** Zehn `<link>`-Tags bedeuten zehn HTTP-Requests. Besonders auf HTTP/1.1
macht das einen messbaren Unterschied. Auch auf HTTP/2 lohnt sich Bundling für oft gecachte
Bundles (ein Cache-Miss statt zehn).

**4. Kein automatisches Cache-Busting.** Nach einem Update läuft der Browser-Cache weiter
mit der alten Datei, bis der Benutzer manuell löscht — oder bis `?v=2` händisch ergänzt wird.

> **Das Queue-System löst alle vier Probleme:** Aufrufe werden gesammelt, sortiert, an die
richtige Position im Dokument injiziert, (optional) gebündelt und minifiziert, und automatisch
mit Cache-Busting-Parametern versehen.

---

## insertCssFile() / insertJsFile()

Die Grundfunktionen. In PHP oder im Bootstrap eines Templates:

```php
insertCssFile('{TEMPLATE}/styles.css');
insertJsFile('{MODULES}/mymod/frontend.js');
```

**Was im `<head>` landet:**

```html
<link rel="stylesheet" href="/wbce/templates/mytheme/styles.css?1720000000">
```

**Was vor `</body>` landet:**

```html
<script src="/wbce/modules/mymod/frontend.js?1720000000"></script>
```

CSS landet standardmäßig in `head_late`, JS in `body_late`.

### Mit Position

```php
insertCssFile('{TEMPLATE}/critical.css', 'head_early');
insertJsFile('{TEMPLATE}/init.js', 'head_late');
```

### Mit HTML-Attributen

```php
// Nur für Druck
insertCssFile('{TEMPLATE}/print.css', 'head_late', ['media' => 'print']);

// Defer — lädt parallel, führt nach dem Parsen aus
insertJsFile('{MODULES}/mymod/heavy.js', 'body_late', ['defer' => true]);

// Async — lädt und führt sofort aus, sobald verfügbar
insertJsFile('{MODULES}/mymod/tracker.js', 'body_late', ['async' => true]);

// ES-Modul
insertJsFile('{TEMPLATE}/app.js', 'body_late', ['type' => 'module']);
```

**HTML-Output für die Beispiele:**

```html
<link rel="stylesheet" href="…/print.css?…" media="print">
<script src="…/heavy.js?…" defer></script>
<script src="…/tracker.js?…" async></script>
<script src="…/app.js?…" type="module"></script>
```

### Mit ID (Dedup-Schlüssel)

```php
insertJsFile(WB_URL . '/include/jquery/jquery-min.js', 'head_early', [], 'jquery');
```

Eine ID verhindert Doppeleinbindungen auch wenn dieselbe Datei unter verschiedenen URLs
angefordert wird. Wer jQuery später nochmals einbindet (gleiche ID), wird ignoriert.

---

## Token-System

Statt absoluter URLs werden Token-Platzhalter verwendet, die zur Laufzeit aufgelöst werden:

| Token             | Löst auf                       |
| ----------------- | ------------------------------ |
| `{TEMPLATE}`      | URL des aktiven Templates      |
| `{MODULES}`       | `WB_URL/modules`               |
| `{MODULES_URL}`   | wie `{MODULES}`, alternativ    |
| `{TEMPLATES_URL}` | `WB_URL/templates`             |
| `{WB_URL}`        | Root-URL der WBCE-Installation |
| `{ADMIN_URL}`     | URL des Admin-Bereichs         |
| `{MEDIA_URL}`     | URL des Media-Verzeichnisses   |

Vorteil: Kein hartcodiertes `WB_URL . '/modules/...'`, keine `TEMPLATE_DIR`-Verkettungen.
Installationsunabhängig und testbar.

### Eigene Tokens registrieren

```php
I::addUrlToken('{MYMOD}', WB_URL . '/modules/my_module');

insertCssFile('{MYMOD}/assets/backend.css');
insertJsFile('{MYMOD}/assets/backend.js');
```

Sinnvoll in `index.php` oder `initialize_fe.php` eines Moduls — einmal registrieren,
überall verwenden.

---

## insertCssCode() / insertJsCode()

Inline-Code direkt in den Head oder Body schreiben — ohne `<style>`- oder `<script>`-Wrapper,
den fügt die Queue selbst hinzu.

```php
insertCssCode(':root { --primary: #3a7bd5; --gap: 1.5rem; }');
insertJsCode('window.SITE_LANG = "' . LANGUAGE . '";');
```

**HTML-Output:**

```html
<style>
:root { --primary: #3a7bd5; --gap: 1.5rem; }
</style>

<script>
window.SITE_LANG = "DE";
</script>
```

Mit Position — CSS-Variablen möglichst früh, damit sie beim Parsen schon da sind:

```php
insertCssCode(':root { --primary: #3a7bd5 }', 'head_early');
```

Mit ID — verhindert Doppelausgabe wenn dasselbe Code-Snippet von mehreren Stellen
eingefügt werden könnte:

```php
insertCssCode('.sr-only { position: absolute; … }', 'head_late', 'sr-only-helper');
```

---

## insertHtmlCode()

Beliebige HTML-Blöcke an einer Queue-Position einschleusen.

```php
// Preconnect-Hint
insertHtmlCode('<link rel="preconnect" href="https://api.example.com">', 'head_early');

// Structured Data (JSON-LD)
insertHtmlCode('<script type="application/ld+json">' . json_encode($schema) . '</script>', 'head_late');

// Kein-JS-Fallback
insertHtmlCode('<noscript><style>.js-only { display:none }</style></noscript>', 'head_late');
```

**HTML-Output (Beispiel Structured Data):**

```html
<script type="application/ld+json">{"@context":"https://schema.org","@type":"Article",...}</script>
```

---

## loadPlugin()

Ein Plugin-Verzeichnis mit einer einzigen Zeile einbinden. Die `plugin.json` im Verzeichnis
deklariert, welche Dateien geladen werden sollen und ob es Dependencies gibt.

```php
loadPlugin('include/wbeSelect');
```

**`include/wbeSelect/plugin.json`:**

```json
{
    "css": ["wbeSelect.css"],
    "js":  ["wbeSelect.js"]
}
```

**HTML-Output:**

```html
<link rel="stylesheet" href="/wbce/include/wbeSelect/wbeSelect.css?1720000000">
<script src="/wbce/include/wbeSelect/wbeSelect.js?1720000000"></script>
```

### Mit Dependencies

```json
{
    "css": ["datepicker.css"],
    "js":  ["datepicker.js"],
    "require": ["include/jquery-slim"]
}
```

`require`-Einträge werden zuerst geladen (depth-first), Deduplication greift auch hier —
wird `include/jquery-slim` von zwei Plugins angefordert, landet es nur einmal im HTML.

### JS-Dateien mit expliziter Position

Wenn ein Plugin sowohl ein Polyfill (muss in den Head) als auch den Hauptcode (Body) mitbringt:

```json
{
    "css": ["wbeSelect.css"],
    "js":  {
        "head_early": ["wbeSelect-polyfill.js"],
        "body_late":  ["wbeSelect.js"]
    }
}
```

### Position überschreiben

```php
// CSS in head_early statt head_late
loadPlugin('include/wbeSelect', 'head_early');

// CSS head_late, JS in head_early
loadPlugin('include/wbeSelect', 'head_late', 'head_early');
```

---

## insertCssBundle() / insertJsBundle()

Mehrere Dateien zu einer einzigen gecachten Datei zusammenfassen — ein HTTP-Request
statt vieler.

```php
I::insertCssBundle([
    '{TEMPLATE}/styles.css',
    '{TEMPLATE}/cookie-consent.css',
    '{MODULES}/ckeditor/frontend.css',
    '{MODULES}/mod_multilingual/frontend.css',
], 'dolce-piano-main');
```

**Was im `<head>` landet:**

```html
<link rel="stylesheet" href="/wbce/cache/assets/combined_dolce-piano-main.css?1720000000">
```

Statt vier Requests: einer. Die kombinierte Datei wird beim ersten Aufruf gebaut und
danach aus dem Cache serviert. Ändert sich eine Quelldatei, wird der Cache automatisch
invalidiert (via mtime-Vergleich).

### Warum auch Modul-CSS bundeln?

Module wie `ckeditor` oder `mod_multilingual` legen ihre `frontend.css` im Modulordner ab
und registrieren sie über `register_frontend_modfiles('css')`. Das läuft durch die Queue,
aber als einzelne Datei. Wer diese Dateien ins Bundle aufnimmt, hat sie unter Kontrolle —
Minifizierung, Reihenfolge und Bundling inklusive.

### Wichtig: Reihenfolge

Das Bundle muss **vor** `register_frontend_modfiles('css')` registriert werden.
Die Queue erkennt bereits gebündelte Dateien und überspringt sie bei der
Einzel-Registrierung — aber nur, wenn das Bundle zuerst da ist.

```php
// Richtig ✓
I::insertCssBundle([
    '{TEMPLATE}/styles.css',
    '{MODULES}/ckeditor/frontend.css',
], 'mein-bundle');
register_frontend_modfiles('css');   // ckeditor wird übersprungen — schon im Bundle

// Falsch ✗
register_frontend_modfiles('css');   // ckeditor landet einzeln in der Queue
I::insertCssBundle([
    '{TEMPLATE}/styles.css',
    '{MODULES}/ckeditor/frontend.css',  // zu spät — ckeditor ist schon drin
], 'mein-bundle');
// Ergebnis: ckeditor erscheint doppelt
```

### JS-Bundle

```php
I::insertJsBundle([
    '{TEMPLATE}/vendor/alpine.js',
    '{TEMPLATE}/js/main.js',
    '{TEMPLATE}/js/cookieconsent.js',
], 'dolce-piano-scripts');
```

**Was vor `</body>` landet:**

```html
<script src="/wbce/cache/assets/combined_dolce-piano-scripts.js?1720000000"></script>
```

### Mit Position

```php
// Bundle explizit in den Head (z.B. für kritisches JS)
I::insertJsBundle(['{TEMPLATE}/js/critical.js'], 'critical', 'head_early');
```

### Remote-Dateien im Bundle

CDN-URLs werden automatisch herausgefiltert und einzeln nach dem Bundle-Tag geladen:

```php
I::insertCssBundle([
    '{TEMPLATE}/styles.css',
    'https://cdn.example.com/lib.css',   // wird einzeln geladen, nicht gebündelt
], 'mein-bundle');
```

**HTML-Output:**

```html
<link rel="stylesheet" href="/wbce/cache/assets/combined_mein-bundle.css?…">
<link rel="stylesheet" href="https://cdn.example.com/lib.css">
```

---

## Rezept: Alles vom Template aus bündeln

Ein Template kann sämtliche CSS- und JS-Dateien der Seite in je einem einzigen
Request zusammenfassen — die eigenen Template-Dateien zusammen mit den
`frontend.css`/`frontend.js`-Dateien ausgewählter Module. Der richtige Ort dafür
ist **`templates/mein_template/index.php`**, ganz oben, bevor das Template HTML
ausgibt.

### CSS

```php
<?php
// templates/mein_template/index.php

I::insertCssBundle([
    // 1. Template-eigene Dateien — in der gewünschten Kaskaden-Reihenfolge
    '{TEMPLATE}/css/reset.css',
    '{TEMPLATE}/css/base.css',
    '{TEMPLATE}/css/layout.css',
    '{TEMPLATE}/css/components.css',

    // 2. Modul-Styles, die das Template kennt und mitbündeln möchte
    '{MODULES}/news_img/assets/frontend.css',
    '{MODULES}/topics/assets/frontend.css',
], 'mein-template-css');
```

Wenn `register_frontend_modfiles()` später die aktiven Module durchläuft,
erkennt es die schon gebündelten Dateien und überspringt sie. Module, die
**nicht** im Bundle stehen, werden ganz normal einzeln geladen — du listest nur
die auf, die du selbst kontrollieren willst.

> **Welche Datei bündeln — `frontend.css` oder `frontend_custom.css`?** Das
> Bundle enthält exakt die Datei, die du angibst. Bringt ein Modul eine
> `frontend_custom.css` mit und du bündelst `frontend.css`, sind das **zwei
> verschiedene Dateien** und beide werden geladen. Trage im Zweifel die
> Custom-Datei ein. `frontend_override.css` wird immer zusätzlich nach dem
> Bundle geladen — das ist Absicht.

### JavaScript — zwei Strategien für jQuery

**Strategie A — jQuery vom CDN (empfohlen für öffentliche Seiten).** jQuery lädt
einzeln und synchron im Head; der Browser hat es oft schon im Cache. Das eigene
Bundle kommt mit `defer` danach und darf `$` voraussetzen.

```php
I::insertJsFile(
    'https://code.jquery.com/jquery-3.7.1.min.js',
    'head_late',
    ['integrity' => 'sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=',
     'crossorigin' => 'anonymous']
);

I::insertJsBundle([
    '{TEMPLATE}/js/navigation.js',
    '{TEMPLATE}/js/slider.js',
    '{MODULES}/news_img/assets/frontend.js',
], 'mein-template-js');
```

**Strategie B — jQuery lokal im Bundle (Intranet, offline).** jQuery kommt als
**erste** Datei ins Bundle, danach die Plugins, danach der eigene Code.

```php
I::insertJsBundle([
    '{TEMPLATE}/js/vendor/jquery-3.7.1.min.js',   // muss zuerst stehen
    '{TEMPLATE}/js/vendor/jquery.easing.min.js',
    '{TEMPLATE}/js/navigation.js',
    '{MODULES}/news_img/assets/frontend.js',
], 'mein-template-js');
```

| Kriterium               | A — CDN                           | B — Lokal                    |
| ----------------------- | --------------------------------- | ---------------------------- |
| jQuery im Browser-Cache | ja (CDN, hohe Trefferquote)       | nein (eigene Domain)         |
| externer Request        | ja                                | nein                         |
| Bundle-Größe            | kleiner (ohne jQuery)             | größer (mit jQuery)          |
| Ladereihenfolge         | explizit (Head sync + Body defer) | implizit (Array-Reihenfolge) |
| empfohlen für           | öffentliche Sites                 | Intranet / Offline           |

> **Reihenfolge zählt:** Das Bundle muss **vor** `register_frontend_modfiles()`
> registriert werden, sonst landet eine Modul-Datei einzeln in der Queue, bevor
> das Bundle sie „übernehmen" kann — und erscheint dann doppelt.

---

## insertWebFont() / insertFont()

Web-Fonts vom CDN werden lokal gecacht und DSGVO-konform eingebunden.

```php
// Google Fonts, Bunny Fonts, Fontshare, … — ein Aufruf
insertWebFont('https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap');

// Lokale .woff2-Datei
insertFont('{TEMPLATE}/fonts/MyFont.woff2', ['family' => 'MyFont', 'weight' => '400']);
```

→ Ausführliches Tutorial: Tab [**Web-Fonts**](?tool=asset_optimizer&tab=docs&doc=fonts) (`FONTS_TUTORIAL.md`)

---

## Twig-Funktionen

Im Twig-Template stehen folgende Funktionen zur Verfügung:

| Twig-Aufruf                                      | Entspricht PHP                      |
| ------------------------------------------------ | ----------------------------------- |
| `{{ loadPlugin('include/wbeSelect') }}`          | `loadPlugin(...)`                   |
| `{{ insertCssFile(TEMPLATE_DIR ~ '/x.css') }}`   | `insertCssFile(...)`                |
| `{{ insertJsFile(INCLUDE_URL ~ '/x.js') }}`      | `insertJsFile(...)`                 |
| `{{ insertCssCode('.foo { color:red }') }}`      | `insertCssCode(...)`                |
| `{{ insertJsCode('window.x = 1') }}`             | `insertJsCode(...)`                 |
| `{{ insertHtmlCode('<noscript>…</noscript>') }}` | `insertHtmlCode(...)`               |
| `{{ insertFile(url, pos, type) }}`               | `insertCssFile` oder `insertJsFile` |
| `{{ insertTitle('Meine Seite') }}`               | `I::insertTitle(...)`               |
| `{{ insertMeta('description', 'Mein Text') }}`   | `I::insertMeta(...)`                |

**Nicht verfügbar in Twig:**

| Funktion          | Grund                                                                |
| ----------------- | -------------------------------------------------------------------- |
| `insertCssBundle` | Muss vor dem Template-Render registriert sein (Reihenfolge-Garantie) |
| `insertJsBundle`  | Wie `insertCssBundle`                                                |
| `insertWebFont`   | Löst I/O aus — gehört ins Bootstrap, nicht in die View-Schicht       |
| `insertFont`      | Wie `insertWebFont`                                                  |
| `I::addUrlToken`  | Infrastruktur-Setup, gehört ins Bootstrap                            |

Bundles und Fonts immer in `initialize_fe.php` oder der Template-`index.php` registrieren,
bevor Twig rendert.

---

# Referenz

## Positions-Kurzübersicht

Jeder Aufruf akzeptiert eine optionale Position. Ohne Angabe: CSS landet in
`head_late`, JS in `body_late`.

| Position      | Anker im Dokument      | Typischer Inhalt                                        |
| ------------- | ---------------------- | ------------------------------------------------------- |
| `head_top`    | direkt nach `<head>`   | Charset, Viewport                                       |
| `head_early`  | direkt nach `</title>` | kritisches CSS, CSS-Variablen, Preloads, Fonts          |
| `head_middle` | vor `</head>`          | Meta-Tags, OG-Tags                                      |
| `head_late`   | vor `</head>`          | Modul-CSS, Plugin-CSS — **Standard für CSS**            |
| `head_last`   | vor `</head>`          | Override-CSS, das nach allem anderen kommen soll        |
| `body_top`    | direkt nach `<body>`   | absolut erstes im Body                                  |
| `body_early`  | direkt nach `<body>`   | Feature-Detection, früh laufendes JS                    |
| `body_late`   | vor `</body>`          | Modul-JS, Plugin-JS — **Standard für JS** (mit `defer`) |
| `body_last`   | vor `</body>`          | Analytics, Tracking — immer zuletzt                     |

**Kurzformen:** `head` → `head_late`, `body` → `body_late`, `early` →
`head_early`, `middle` → `head_middle`.

→ Die vollständige Positions-Tabelle mit den Reihenfolge-Regeln und **allen
Alt-Namen aus WBCE 1.x** (`HEAD BTM+`, `BODY MODFILES`, …) steht im Tab
[**Referenz**](?tool=asset_optimizer&tab=docs&doc=ref) (`ASSETS_REFERENCE.md`),
Abschnitt 8.

---

## Anhang: Wie es intern funktioniert (kannst du überspringen)

Für die tägliche Arbeit musst du das nicht wissen — es erklärt nur, was die
Queue hinter den Kulissen tut.

### Was beim Bundling passiert

1. `I::insertCssBundle(['a.css', 'b.css'], 'mein-bundle')` wird aufgerufen.
2. Die Queue löst die Token auf und bestimmt die absoluten Pfade der Quelldateien.
3. Sie prüft, ob `cache/assets/combined_mein-bundle.css` existiert und ob alle
   Quelldateien unverändert sind (Vergleich der Änderungszeit, `mtime`).
4. Falls veraltet oder fehlend: Dateien werden geladen, optional minifiziert
   (mit `MatthiasMullie\Minify`, falls vorhanden), zusammengefügt und atomar
   in `cache/assets/` geschrieben.
5. Die Quelldateien werden intern als „schon gesehen" vermerkt — spätere
   `insertCssFile()`-Aufrufe für dieselben Dateien werden stillschweigend
   ignoriert.
6. Die Bundle-URL wird in die Queue eingetragen und beim Rendern als ein
   einzelner `<link>`-Tag ausgegeben.

**Minifizierung ohne Bundle:** Auch einzelne `insertCssFile()`- /
`insertJsFile()`-Aufrufe werden minifiziert, wenn `MINIFY_CSS` bzw. `MINIFY_JS`
aktiv ist. Die minifizierte Fassung landet in `cache/assets/` mit einem
lesbaren Namen (z. B. `wbcetik-css-main.min.css`).

### Cache-Busting im Detail

Wenn `OPF_ASSETS_CACHE_BUSTING` aktiv ist, hängt die Queue automatisch
`?{mtime}` an jede lokale Datei-URL:

```html
<link rel="stylesheet" href="/wbce/templates/mytheme/styles.css?1720000000">
```

Die Zahl ist die `filemtime()` der Datei auf dem Server. Nach einer Änderung
ändert sie sich — Browser und CDN verwerfen ihre alte Kopie automatisch. Kein
händisches `?v=2` mehr nötig. Externe URLs (CDN) bekommen kein `?mtime` — dort
hat der Server keinen Dateizugriff.

### Deduplizierung im Detail

Die Queue führt zwei Nachschlage-Listen:

- eine für exakt denselben URL-String (Token-Schreibweise)
- eine für den aufgelösten absoluten Dateipfad

Die zweite fängt Fälle wie diesen ab:

```php
I::insertCssFile('{MODULES}/ckeditor/frontend.css');
// … woanders im Code:
I::insertCssFile(WB_URL . '/modules/ckeditor/frontend.css');  // selbe Datei, andere URL
```

Beide zeigen auf denselben absoluten Pfad — der zweite Eintrag wird
übersprungen. Das gilt auch für Bundle-Quellen: Wer eine Datei ins Bundle
aufnimmt, kann sie danach bedenkenlos nochmals als Einzeldatei registrieren —
sie erscheint trotzdem nur einmal im HTML.
