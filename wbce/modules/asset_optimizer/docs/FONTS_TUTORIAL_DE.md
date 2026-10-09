# FontCache — Tutorial

Dieses Tutorial zeigt, wie `insertWebFont()` und `insertFont()` verwendet werden,
und was sie konkret in den HTML-Output schreiben.

> **Für wen ist das?** Für Leute, die Templates oder Module bauen. Das
> Asset-Optimizer-Tool im Backend hat keinen Schalter für Schriften — Fonts
> werden immer im Template-Code eingebunden. Wer nur wissen will, was im Tab
> *Cache & Status* unter „Selbst gehostete Web-Fonts" steht: das ist der
> Zwischenspeicher, den die hier beschriebenen Aufrufe füllen; „Font-Cache
> leeren" ist jederzeit gefahrlos.

---

## Begriffe in einem Satz

| Begriff              | Bedeutung                                                                                                                                 |
| -------------------- | ----------------------------------------------------------------------------------------------------------------------------------------- |
| Web-Font             | Eine Schriftart, die als Datei mit der Seite geladen wird, statt vom Gerät zu kommen.                                                     |
| `.woff2`             | Das heute übliche, stark komprimierte Dateiformat für Web-Fonts.                                                                          |
| `@font-face`         | Die CSS-Regel, die einer Schrift einen Namen gibt und sagt, welche Datei dazugehört.                                                      |
| Google-Fonts-Problem | Lädt man Google Fonts direkt vom Google-Server, geht die IP jedes Besuchers an Google (USA) — ohne Einwilligung rechtlich heikel (DSGVO). |
| FOUT                 | „Flash of Unstyled Text" — der kurze Moment, in dem Text in der Ersatzschrift erscheint, bis der Web-Font da ist.                         |
| Variable Font        | Eine einzige Datei, die alle Strichstärken einer Familie enthält (statt je eine Datei pro Gewicht).                                       |
| Alias                | Ein selbst gewählter Ersatzname für eine Schrift, damit das Template-CSS gleich bleibt, egal welche Schrift dahintersteckt.               |

`FontCache` lädt Web-Fonts einmalig auf deinen eigenen Server herunter, schreibt
die `@font-face`-CSS auf lokale Pfade um und liefert danach alles von der
eigenen Domain aus — das löst das Google-Fonts-Problem und die externe
Abhängigkeit auf einmal.

---

## Das Problem mit CDN-Web-Fonts

Das übliche Vorgehen in Templates sieht so aus:

```html
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
```

Oder in PHP:

```php
echo '<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">';
```

Das funktioniert — hat aber drei Probleme:

**1. DSGVO.** Beim ersten Laden der Seite baut der Browser eine Verbindung zu `fonts.googleapis.com` auf.
Dabei wird die IP-Adresse des Besuchers an Google-Server in den USA übermittelt. Das ist ohne
explizite Einwilligung rechtlich problematisch.

**2. Externe Abhängigkeit.** Ist der CDN nicht erreichbar (Ausfall, Firewall, langsame Verbindung),
lädt der Font nicht — und die Seite erscheint in der Fallback-Schrift.

**3. Kein Queue-System.** Ein `echo` landet irgendwo im Template-Output, unkontrolliert.
Das WBCE-Queue-System (`I::`) kann diesen Tag nicht erfassen, sortieren oder doppelte
Einbindungen verhindern.

---

## insertWebFont() — Font vom CDN, lokal gecacht

### Einfachste Verwendung

```php
insertWebFont('https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap');
```

**Was beim ersten Seitenaufruf passiert:**

1. `FontCache` lädt die CSS-Datei von Google Fonts herunter
2. Jeden `@font-face`-Block parst FontCache einzeln — `font-family` und `font-weight` werden ausgelesen
3. Jede referenzierte `.woff2`-Datei wird heruntergeladen und unter einem **stabilen, lesbaren Namen** gespeichert
4. Alle `url(https://fonts.gstatic.com/…)` in der CSS werden auf lokale Pfade umgeschrieben
5. CSS + Font-Dateien werden atomar in `cache/fonts/` gespeichert

**Was danach (und bei allen weiteren Aufrufen) in den `<head>` geschrieben wird:**

```html
<link rel="stylesheet" href="/cache/fonts/a3f8b2c1d4e5f6a7b8c9d0e1.css">
```

Die gecachte CSS-Datei enthält:

```css
/* @font-face Blöcke mit lokalen Pfaden statt Google-URLs */
@font-face {
  font-family: 'Inter';
  font-style: normal;
  font-weight: 400;
  font-display: swap;
  src: url(/cache/fonts/Inter_400.woff2) format('woff2');
}
@font-face {
  font-family: 'Inter';
  font-style: normal;
  font-weight: 700;
  font-display: swap;
  src: url(/cache/fonts/Inter_700.woff2) format('woff2');
}
```

Die Font-Dateien heißen `Inter_400.woff2` und `Inter_700.woff2` — abgeleitet direkt aus
`font-family` und `font-weight` im jeweiligen `@font-face`-Block, **ohne Hash-Suffix**.
Der Name ist auf jeder Installation gleich und voraussagbar.

Kein Request geht mehr an Google. Alle Font-Dateien liegen auf dem eigenen Server.

> **Tipp: SourceFont hinter einem Alias erkennen.**
> Wenn ein Template `'MainFont'` als Alias verwendet, sieht man in den Browser-DevTools
> (Tab *Network* oder *Sources*) oder direkt im `cache/fonts/`-Verzeichnis, welcher Font
> tatsächlich dahintersteckt — ohne das Template öffnen zu müssen:

```css
font-family: 'MainFont';
src: url(/cache/fonts/Inter_400.woff2)  /*  Quelle ist Inter, Weight 400 */
```

> Das erleichtert das Debugging und macht Abhängigkeiten transparent — auch für jemanden,
> der das Template nicht kennt.

---

### Mit Font-Alias

Ein Alias hat zwei Effekte:

1. **CSS-Dateiname** — die gecachte CSS-Datei heißt `{alias}.css` statt einem Hash.
   Voraussagbar, direkt referenzierbar aus `editor.css` oder anderen statischen Dateien.
2. **font-family im Output** — Nur der Alias-Name erscheint; der Original-Name (`Inter`) wird unterdrückt.
   Die Umbenennung geschieht direkt in der CSS-Datei — kein separater `<style>`-Block im HTML.

> **Wichtig — ein Alias, eine Familie:**
> Ein Alias benennt **alle** `@font-face`-Blöcke in der CSS-Datei um.
> Bei einer URL mit mehreren Familien (`family=Anton&family=Poppins`) würden beide
> denselben Alias-Namen bekommen und wären nicht mehr unterscheidbar.
> **Faustregel: einen separaten `insertWebFont()`-Aufruf pro Schriftfamilie verwenden.**
> Siehe Abschnitt [Mehrere Familien mit Alias](#mehrere-familien-mit-alias).

```php
insertWebFont(
    'https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap',
    'mainfont'
);
```

**Was in `cache/fonts/` liegt:**

```file-tree
cache/
    fonts/
        mainfont.css     ← CSS mit lokalen Pfaden und bereits umbenannter font-family
        Inter_400.woff2
        Inter_700.woff2
```

**Was in `mainfont.css` steht:**

```css
@font-face {
  font-family: 'mainfont';
  font-style: normal;
  font-weight: 400;
  font-display: swap;
  src: url(/cache/fonts/Inter_400.woff2) format('woff2');
}
@font-face {
  font-family: 'mainfont';
  font-style: normal;
  font-weight: 700;
  font-display: swap;
  src: url(/cache/fonts/Inter_700.woff2) format('woff2');
}
```

**Was in den `<head>` geschrieben wird:**

```html
<link rel="stylesheet" href="/cache/fonts/mainfont.css">
```

Kein separater `<style>`-Block. Die CSS-Datei enthält die umbenannten Deklarationen direkt.
Die gecachten Font-Dateien sind dieselben wie ohne Alias — der Browser lädt sie nur einmal.
Im Template-CSS kann jetzt immer `'mainfont'` stehen, egal welcher Font tatsächlich dahintersteckt.

**Alias-Konvention:** lowercase, Leerzeichen und Sonderzeichen werden zu Underscores.
`"Template Script"` → `template_script` → `template_script.css`.
Gebräuchliche Kurzformen wie `inter`, `mainfont`, `heading_font` sind am lesbarsten.

**Font wechseln — ohne das Template-CSS anzufassen:**

```php
// Vorher: Inter
insertWebFont('https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap', 'mainfont');

// Nachher: Satoshi — nur diese Zeile ändern
insertWebFont('https://fonts.bunny.net/css?family=satoshi:400,700&display=swap', 'mainfont');
```

Die Font-Dateien heißen jetzt `Satoshi_400.woff2` und `Satoshi_700.woff2`, die CSS-Datei
bleibt `mainfont.css`. Das Template-CSS bleibt unverändert:

```css
body { font-family: 'mainfont', sans-serif; }
```

---

### editor.css — Font im TinyMCE-Backend

TinyMCE rendert seinen Inhalt in einem `<iframe>`. Um dort dieselbe Schrift wie auf der
Website zu zeigen, muss `editor.css` (die statische Stylesheet-Datei des Templates) den
Font eigenständig laden.

Mit einem Alias ist der CSS-Dateiname im Cache **voraussagbar** — man kann ihn direkt
in `editor.css` einbinden:

```php
// Template index.php:
insertWebFont(
    'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap',
    'inter'
);
```

```css
/* templates/mytheme/editor.css */
@import url('/cache/fonts/inter.css');

body {
    font-family: 'inter', sans-serif;
    font-size: 16px;
    line-height: 1.6;
    color: #1a1a1a;
}
```

`/cache/fonts/inter.css` enthält die `@font-face`-Blöcke mit lokalen Pfaden und ist nach
dem ersten Frontend-Aufruf immer vorhanden. TinyMCE lädt den Font damit direkt vom eigenen
Server — ohne externe Abhängigkeit, DSGVO-konform.

> **Hinweis:** Wenn die gecachte CSS-Datei noch nicht existiert (allererster Seitenaufruf,
> oder nach einem Cache-Clear), erscheint der Font im Editor kurz als Fallback-Schrift.
> Beim nächsten Laden ist die Datei da. Das ist kein Fehler — der `@import` zeigt einfach
> nichts, solange die Zieldatei fehlt.

---

### Font-CSS in ein Bundle einbinden

Wer einen HTTP-Request sparen will, kann die gecachte Font-CSS in ein kombiniertes
Stylesheet-Bundle aufnehmen. Dafür steht das Token `{CACHE}` zur Verfügung —
es löst sich zu `WB_URL . '/cache'` auf, analog zu `{TEMPLATE}` und `{MODULES}`:

```php
I::insertCssBundle([
    '{CACHE}/fonts/inter.css',
    '{TEMPLATE}/assets/css/main.css',
], 'mytheme');
```

`markCombinedSources()` entfernt dabei automatisch einen eventuell bereits
eingereihten einzelnen `<link>` auf `inter.css` — es gibt also keine doppelte
Einbindung, egal in welcher Reihenfolge Template und Module ihre Assets anmelden.

> **Abwägung:** Wenn `inter.css` Teil eines Bundles ist, existiert sie als
> eigenständige Datei im `cache/fonts/`-Verzeichnis weiterhin — `editor.css`
> kann sie nach wie vor per `@import` einbinden. Wer jedoch **ausschließlich**
> über das Bundle arbeitet und nie `@import url('/cache/fonts/inter.css')` in
> `editor.css` einträgt, hat im TinyMCE-Backend keinen Font. Beide Wege schließen
> sich nicht aus — die Datei bleibt auf Disk, der Browser kombiniert sie nur einmal.

---

### Beide Namen gleichzeitig

Wenn sowohl der Original-Name als auch der Alias verfügbar sein sollen
(z. B. weil ein Modul `'Inter'` hartkodiert verwendet):

```php
// Erster Aufruf: Original-Name 'Inter' verfügbar (kein Alias → Hash-basierte CSS-Datei)
insertWebFont('https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap');

// Zweiter Aufruf: Alias 'inter' verfügbar
// Kein zweiter Download — FontCache erkennt die URL und nutzt den Cache
insertWebFont('https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap', 'inter');
```

**Was in den `<head>` geschrieben wird:**

```html
<!-- Erster Aufruf: <link> auf die Hash-benannte gecachte CSS-Datei (font-family: 'Inter') -->
<link rel="stylesheet" href="/cache/fonts/a3f8b2c1d4e5f6a7b8c9d0e1.css">

<!-- Zweiter Aufruf: <link> auf inter.css (font-family: 'inter') — kein <style>-Block -->
<link rel="stylesheet" href="/cache/fonts/inter.css">
```

---

### Mehrere Familien mit Alias

Wenn ein Template mehrere Schriftfamilien mit Alias verwendet — z. B. eine Serifenlose für
den Fließtext und eine Display-Schrift für Überschriften — muss jede Familie einen eigenen
`insertWebFont()`-Aufruf mit eigenem Alias bekommen:

```php
// RICHTIG — ein Aufruf pro Familie
insertWebFont(
    'https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,700;1,400;1,700&display=swap',
    'poppins'
);
insertWebFont(
    'https://fonts.googleapis.com/css2?family=Anton&display=swap',
    'anton'
);
```

Ergebnis in `cache/fonts/`:

```file-tree
cache/
    fonts/
        poppins.css               ← font-family: 'poppins'
        anton.css                 ← font-family: 'anton'
        Poppins_400.woff2
        Poppins_400_italic.woff2
        Poppins_700.woff2
        Poppins_700_italic.woff2
        Anton_400.woff2
```

```css
/* editor.css */
@import url('../../cache/fonts/poppins.css');
@import url('../../cache/fonts/anton.css');

h1, h2, h3 { font-family: 'anton', sans-serif; }
body        { font-family: 'poppins', sans-serif; }
```

> **Warum nicht eine URL mit mehreren Familien?**
> Google Fonts erlaubt `family=Anton&family=Poppins` in einer URL — das ist praktisch,
> funktioniert aber nicht mit Alias. FontCache würde beide Familien auf denselben
> Alias-Namen umbenennen, sodass Anton und Poppins im CSS nicht mehr unterscheidbar wären.
> Eine URL mit mehreren Familien macht nur ohne Alias Sinn (dann wird der Dateiname gehasht).

---

### Bunny Fonts — gleiche Methode, anderer Host

Bunny Fonts ist ein DSGVO-konformer Drop-in-Ersatz für Google Fonts (europäischer CDN,
kein IP-Logging). Die Methode bleibt identisch:

```php
insertWebFont('https://fonts.bunny.net/css?family=inter:400,700&display=swap', 'MainFont');
```

`FontCache` erkennt `fonts.bunny.net` automatisch und verwendet den passenden User-Agent.
Die gecachten Dateien heißen auch hier `Inter_400.woff2` — abgeleitet aus dem
`font-family`-Wert in der zurückgelieferten CSS, nicht aus der URL.

---

### Variable Font vom CDN

```php
insertWebFont('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap', 'MainFont');
```

Google liefert für Variable Fonts einen `@font-face`-Block mit `font-weight: 100 900`.
Die gecachte Datei heißt entsprechend:

```file-tree
cache/
    fonts/
        Inter_100_900.woff2
```

---

### Admin-Force-Refresh

Als eingeloggter Admin: **CTRL+F5** auf der Frontend-Seite löscht den Cache für diesen
Font und lädt ihn beim nächsten Request neu herunter. Nützlich nach einer URL-Änderung
(z. B. neues Weight hinzugefügt).

Oder per Code (löscht den gesamten Font-Cache):

```php
I::clearFontCache();
```

Nach einem Cache-Clear werden beim nächsten Aufruf alle Dateien neu heruntergeladen —
mit denselben lesbaren Namen.

---

## Unterstützte Font-Provider

Alle Provider, deren CSS-URL an `insertWebFont()` übergeben werden kann.
Der Code-Aufruf ist bei allen gleich — nur die URL unterscheidet sich.

| Provider           | Link                           | Besonderheit                                                                                                 |
| ------------------ | ------------------------------ | ------------------------------------------------------------------------------------------------------------ |
| **Google Fonts**   | https://fonts.google.com       | UA-sensitiv; DSGVO-Problem ohne lokalen Cache → FontCache löst das                                           |
| **Bunny Fonts**    | https://fonts.bunny.net        | Drop-in-Ersatz für Google Fonts; EU-CDN, kein IP-Logging; UA-sensitiv                                        |
| **Fontshare**      | https://www.fontshare.com      | Indian Type Foundry; hochwertige Free Fonts; kein UA-Trick nötig                                             |
| **Fontsource**     | https://fontsource.org         | Alle Google Fonts + viele weitere, paketiert via jsDelivr; versionsgebunden                                  |
| **Font Awesome**   | https://fontawesome.com        | Icon-Font; technisch identisch zu Textfonts — gleiche `insertWebFont()`-Methode                              |
| **Material Icons** | https://fonts.google.com/icons | Läuft über dieselbe Google Fonts CDN; UA-sensitiv wie Google Fonts                                           |
| **Adobe Fonts**    | https://fonts.adobe.com        | Kommerziell (Creative Cloud); kein lokaler Download möglich — stattdessen `insertCssFile()` direkt verwenden |

### Fontsource — URL-Schema

Fontsource-Pakete werden über jsDelivr eingebunden. Es gibt zwei Varianten:

```php
// Alle Weights auf einmal (index.css)
insertWebFont('https://cdn.jsdelivr.net/npm/@fontsource/inter@5.0/index.css', 'MainFont');

// Nur ein bestimmtes Weight (kleinere CSS-Datei)
insertWebFont('https://cdn.jsdelivr.net/npm/@fontsource/inter@5.0/400.css', 'MainFont');

// Variable Font (ein File für alle Weights)
insertWebFont('https://cdn.jsdelivr.net/npm/@fontsource-variable/inter@5.0/index.css', 'MainFont');
```

Die Versionsnummer (`@5.0`) pinnt den Font auf einen bestimmten Stand —
der Font ändert sich nie unerwartet, auch wenn der Anbieter Updates einspielt.

### Fontshare — URL-Schema

```php
// Eine Familie
insertWebFont('https://api.fontshare.com/v2/css?f[]=satoshi@400,700&display=swap', 'MainFont');

// Mehrere Familien in einem Request
insertWebFont(
    'https://api.fontshare.com/v2/css?f[]=satoshi@400,700&f[]=cabinet-grotesk@500&display=swap',
    'MainFont'
);
```

Bekannte Fontshare-Fonts: Satoshi, Cabinet Grotesk, Clash Display, General Sans, Switzer, Chillax.

---

## insertFont() — Direkte Font-Dateien

Wenn Font-Dateien direkt im Template oder Modul liegen (keine externe CSS-Quelle),
generiert `insertFont()` das `@font-face`-CSS automatisch.

### Einfachste Form — @font-face mit Familie

```php
insertFont('{TEMPLATE}/fonts/inter.woff2', [
    'family'  => 'MainFont',
    'weight'  => '400',
    'display' => 'swap',
]);
```

**Was in den `<head>` geschrieben wird:**

```html
<style>
@font-face {
  font-family: 'MainFont';
  font-weight: 400;
  font-style: normal;
  font-display: swap;
  src: url(/templates/mytheme/fonts/inter.woff2) format('woff2');
}
</style>
```

Das Token `{TEMPLATE}` wird automatisch zum korrekten Pfad aufgelöst.
Cache-Busting (`?mtime`) wird — wenn `OPF_ASSETS_CACHE_BUSTING` aktiv ist — automatisch angehängt.

---

### Mehrere Weights

```php
insertFont('{TEMPLATE}/fonts/inter-400.woff2', ['family' => 'MainFont', 'weight' => '400']);
insertFont('{TEMPLATE}/fonts/inter-700.woff2', ['family' => 'MainFont', 'weight' => '700']);
```

**Was in den `<head>` geschrieben wird:**

```html
<style>
@font-face {
  font-family: 'MainFont';
  font-weight: 400;
  font-style: normal;
  font-display: swap;
  src: url(/templates/mytheme/fonts/inter-400.woff2) format('woff2');
}
</style>
<style>
@font-face {
  font-family: 'MainFont';
  font-weight: 700;
  font-style: normal;
  font-display: swap;
  src: url(/templates/mytheme/fonts/inter-700.woff2) format('woff2');
}
</style>
```

---

### Mehrere Formate (woff2 + woff Fallback)

```php
insertFont(
    [
        '{TEMPLATE}/fonts/inter.woff2' => 'woff2',
        '{TEMPLATE}/fonts/inter.woff'  => 'woff',
    ],
    ['family' => 'MainFont', 'weight' => '400']
);
```

**Was in den `<head>` geschrieben wird:**

```html
<style>
@font-face {
  font-family: 'MainFont';
  font-weight: 400;
  font-style: normal;
  font-display: swap;
  src: url(/templates/mytheme/fonts/inter.woff2) format('woff2'),
       url(/templates/mytheme/fonts/inter.woff) format('woff');
}
</style>
```

---

### Variable Font

Ein Variable Font deckt alle Weights mit einer einzigen Datei ab:

```php
insertFont('{TEMPLATE}/fonts/inter-variable.woff2', [
    'family'  => 'MainFont',
    'weight'  => '100 900',   // Range statt einzelnem Wert
    'display' => 'swap',
]);
```

**Was in den `<head>` geschrieben wird:**

```html
<style>
@font-face {
  font-family: 'MainFont';
  font-weight: 100 900;
  font-style: normal;
  font-display: swap;
  src: url(/templates/mytheme/fonts/inter-variable.woff2) format('woff2');
}
</style>
```

---

### Nur Preload — ohne @font-face

Wenn der Font über eine separate CSS-Datei eingebunden wird, aber der Browser ihn
trotzdem sofort vorladen soll (FOUT vermeiden):

```php
insertFont('{TEMPLATE}/fonts/inter-400.woff2');
// Kein 'family' → kein @font-face, nur Preload-Hint
```

**Was in den `<head>` geschrieben wird:**

```html
<link rel="preload" href="/templates/mytheme/fonts/inter-400.woff2"
      as="font" type="font/woff2" crossorigin>
```

---

### Preload + @font-face kombiniert

```php
insertFont('{TEMPLATE}/fonts/inter-400.woff2', [
    'family'  => 'MainFont',
    'weight'  => '400',
    'preload' => true,
]);
```

**Was in den `<head>` geschrieben wird:**

```html
<link rel="preload" href="/templates/mytheme/fonts/inter-400.woff2"
      as="font" type="font/woff2" crossorigin>
<style>
@font-face {
  font-family: 'MainFont';
  font-weight: 400;
  font-style: normal;
  font-display: swap;
  src: url(/templates/mytheme/fonts/inter-400.woff2) format('woff2');
}
</style>
```

---

## Typisches Template-Setup

```php
// functions.php oder template_preprocess im Template:

// Hauptschrift vom CDN, lokal gecacht, als 'inter' verfügbar
insertWebFont(
    'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap',
    'inter'
);

// Monospace-Schrift direkt aus dem Template
insertFont('{TEMPLATE}/fonts/fira-code.woff2', [
    'family'  => 'mono',
    'weight'  => '400',
    'display' => 'swap',
]);

// Optional: Font-CSS + Template-CSS in einem einzigen HTTP-Request
// {CACHE} löst sich zu WB_URL . '/cache' auf
I::insertCssBundle([
    '{CACHE}/fonts/inter.css',
    '{TEMPLATE}/assets/css/main.css',
], 'mytheme');
```

```css
/* style.css im Template — nie ändern, egal welcher Font dahintersteckt */
body         { font-family: 'inter', system-ui, sans-serif; }
code, pre    { font-family: 'mono', monospace; }
```

```css
/* editor.css im Template — TinyMCE-Backend.
   Statische CSS-Datei: {CACHE}-Token funktioniert hier nicht.
   Der Pfad wird direkt eingetragen. */
@import url('/cache/fonts/inter.css');

body { font-family: 'inter', system-ui, sans-serif; }
```

Nach dem ersten Seitenaufruf liegt in `cache/fonts/`:

```file-tree
cache/
    fonts/
        inter.css          ← font-family: 'inter', direkt aus editor.css referenzierbar
        Inter_400.woff2
        Inter_600.woff2
        Inter_700.woff2
```

---

## Anhang: Wie FontCache intern arbeitet (kannst du überspringen)

Alles ab hier erklärt die Mechanik hinter den Kulissen — den Dateinamen-Aufbau,
den User-Agent-Trick, die Alias-Erzeugung. Für die tägliche Arbeit reicht der
Teil davor.

### Download und Dateiname

`FontCache` wird von `AssetQueue` lazy erstellt — nur wenn `insertWebFont()` tatsächlich
aufgerufen wird. Alle Font-Dateien landen in `WB_PATH/cache/fonts/`.

Beim Download verarbeitet FontCache die CSS **block-by-block**: Für jeden `@font-face`-Block
liest es `font-family` und `font-weight` aus und baut daraus den Dateinamen:

```
font-family: 'Inter', font-weight: 400, font-style: normal  →  Inter_400.woff2
font-family: 'Inter', font-weight: 400, font-style: italic  →  Inter_400_italic.woff2
font-family: 'Inter', font-weight: 700, font-style: normal  →  Inter_700.woff2
font-family: 'Inter', font-weight: 700, font-style: italic  →  Inter_700_italic.woff2
font-family: 'Inter', font-weight: 100 900                  →  Inter_100_900.woff2   (Variable Font)
```

Die Namen sind **stabil und voraussagbar** — auf jeder Installation gleich.
`font-style: normal` bekommt kein Suffix; `italic` und `oblique` werden angehängt.
Wenn derselbe Dateiname durch mehrere `@font-face`-Blöcke entsteht (z. B. Google Fonts
liefert für Inter 400 normal mehrere Unicode-Range-Subsets), wird nur der erste Block
heruntergeladen; alle weiteren referenzieren dieselbe Datei. Für westeuropäische Sites
(Latin-Subset) ist das korrekt; TinyMCE benötigt ohnehin nur den Latin-Subset.

### Cache-Key, CSS-Dateiname und Deduplizierung

Der Cache-Key für die CSS-Datei hängt davon ab, ob ein Alias übergeben wurde:

| Aufruf                                   | CSS-Dateiname                                               |
| ---------------------------------------- | ----------------------------------------------------------- |
| `insertWebFont($url)`                    | `md5(url + format) + ".css"` — intern, nicht referenzierbar |
| `insertWebFont($url, 'inter')`           | `inter.css` — stabil, direkt referenzierbar                 |
| `insertWebFont($url, 'Template Script')` | `template_script.css`                                       |

Alias-Sanitierung: lowercase, jede Folge von Nicht-Alphanumerics → einzelner Unterstrich,
führende/abschließende Unterstriche entfernt. Ergebnis ist immer ein gültiger Dateiname.

### URL-Tokens in PHP-Aufrufen

In PHP-Funktionen (`insertCssBundle()`, `insertFont()`, `insertCssFile()`) werden
WBCE-URL-Tokens aufgelöst. Für den Font-Cache ist insbesondere `{CACHE}` relevant:

| Token        | Löst sich auf zu          |
| ------------ | ------------------------- |
| `{CACHE}`    | `WB_URL . '/cache'`       |
| `{TEMPLATE}` | URL des aktiven Templates |
| `{MODULES}`  | `WB_URL . '/modules'`     |

In **statischen CSS-Dateien** (z. B. `editor.css`) kennt der Browser diese Tokens nicht —
dort muss der Pfad direkt eingetragen werden:

```css
/* editor.css — direkt, kein Token */
@import url('/cache/fonts/inter.css');

/* PHP — Token wird von AssetQueue aufgelöst */
I::insertCssBundle(['{CACHE}/fonts/inter.css', ...], 'bundle');
```

Dieselbe URL mit demselben Format wird nie zweimal heruntergeladen — alle weiteren Aufrufe
treffen sofort den Cache.

Atomische Schreibvorgänge verhindern, dass ein gleichzeitiger Request eine halb
geschriebene Datei liest: erst in `datei.tmp.{PID}` schreiben, dann umbenennen.

### User-Agent-Trick (Google Fonts, Bunny Fonts)

Google Fonts und Bunny Fonts werten den HTTP User-Agent aus um das Format zu bestimmen,
das sie in der CSS zurückgeben. Ohne UA-Angabe liefern sie ttf statt woff2.

`FontCache` sendet einen format-spezifischen UA:

| Format          | User-Agent                             |
| --------------- | -------------------------------------- |
| `woff2`         | Firefox 40 UA (gibt woff2 zurück)      |
| `woff2-unicode` | Chrome 104 UA (Unicode-Ranges + woff2) |
| `woff`          | Firefox 27 UA                          |
| `ttf`           | Safari/Daum UA                         |

Alle anderen Provider (Fontshare, Fontsource, …) bekommen einen neutralen
`WBCE-CMS`-UA — sie liefern woff2 direkt ohne UA-Steuerung.

### Alias-Generierung

`buildAliasCss()` parst die gecachte CSS-Datei nach `@font-face`-Blöcken und
erzeugt eine Kopie, in der jedes `font-family`-Property durch den Alias-Namen ersetzt wird.
Die `src`-URLs — und damit die Dateinamen wie `Inter_400.woff2` — bleiben
identisch. Der Browser lädt jede Font-Datei daher nur einmal, auch wenn sie unter
zwei Namen registriert ist.

### Position im Queue-System

Alle Font-Einträge landen in `head_early` — dem frühestmöglichen Punkt im `<head>`,
noch vor regulären CSS-Dateien. Das minimiert FOUT (Flash of Unstyled Text), weil
der Browser die Font-Deklaration so früh wie möglich sieht.
