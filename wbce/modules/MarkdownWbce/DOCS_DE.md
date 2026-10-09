# MdReaderLink — Dokumentation

`MdReaderLink` ist im gesamten WBCE-Backend verfügbar sobald das Modul
`MarkdownWbce` installiert ist. Kein `require`, kein `include` nötig.

---

## Varianten im Überblick

| Variante | Methode | Wann verwenden |
|----------|---------|----------------|
| A | `MdReaderLink::file()` | Eine einzelne Datei — der Normalfall |
| B | `MdReaderLink::docs()` | Mehrere Dateien als Tabs |
| D | `MdReaderLink::dir()` | Verzeichnis mit `md_reader.json` |

---

## Variante A — einzelne Datei

Der häufigste Fall. Pfad relativ zu `WB_PATH` oder absolut — beides funktioniert.

```php
// Relativ zu WB_PATH
echo MdReaderLink::file('/modules/my_mod/docs/README.md')
            ->title('Mein Modul')
            ->linkHtml('Dokumentation');

// Absoluter Pfad — wird automatisch umgerechnet
echo MdReaderLink::file(WB_PATH . '/modules/my_mod/docs/README.md')
            ->linkHtml('Dokumentation');

// Als Button statt Link
echo MdReaderLink::file('/modules/my_mod/docs/README.md')
            ->title('Mein Modul')
            ->buttonHtml('Dokumentation öffnen');

// Nur die URL — für eigene <a>-Tags
$url = MdReaderLink::file('/modules/my_mod/docs/README.md')->url();
```

---

## Variante B — mehrere Dateien

Mehrere Dokumente erscheinen als Tabs im Reader-Header.

```php
echo MdReaderLink::docs([
        ['path' => '/modules/my_mod/docs/README.md',    'label' => 'Übersicht'],
        ['path' => '/modules/my_mod/docs/CHANGELOG.md', 'label' => 'Changelog'],
        ['path' => '/modules/my_mod/docs/API.md',       'label' => 'API'],
    ])
    ->title('Mein Modul – Dokumentation')
    ->linkHtml('Dokumentation');
```

---

## Variante D — Verzeichnis

Nur den Ordnerpfad übergeben. Der Reader liest `md_reader.json` automatisch.

```php
echo MdReaderLink::dir('/modules/my_mod/docs/')
            ->linkHtml('Dokumentation');
```

### md_reader.json — einfach (eine Datei)

```json
{
    "title": "Mein Modul"
}
```

Kein `docs`-Array → Reader lädt `README.md` im selben Ordner.

### md_reader.json — mehrere Dateien

```json
{
    "title": "Mein Modul",
    "docs": [
        { "file": "README.md",    "label": "Übersicht" },
        { "file": "CHANGELOG.md", "label": "Changelog" },
        { "file": "API.md",       "label": "API" }
    ]
}
```

---

## Ausgabe-Methoden

```php
// <a>-Tag mit Popup-JS
->linkHtml('Linktext')
->linkHtml('Linktext', 'meine-css-klasse')
->linkHtml('Linktext', 'btn btn-sm', 'data-foo="bar"')

// <button>-Tag mit Popup-JS
->buttonHtml('Buttontext')
->buttonHtml('Buttontext', 'meine-css-klasse')

// Nur die URL — kein HTML
->url()
```

---

## Popup-Größe anpassen

Standard ist 1100 × 820 px. Kann pro Link überschrieben werden:

```php
echo MdReaderLink::file('/modules/my_mod/docs/README.md')
            ->popupSize(900, 700)
            ->linkHtml('Dokumentation');
```

---

## Sprachversionen

Der Reader erkennt Sprachvarianten automatisch — kein zusätzlicher Code nötig.

Liegt neben `README.md` eine Datei `README_DE.md`, wird diese bei aktiver
Sprache `DE` automatisch bevorzugt. Im Reader-Header erscheinen Flaggen-Icons
für alle verfügbaren Sprachversionen.

```file-tree
docs/
 ├── README.md        ← Fallback / Englisch
 ├── README_DE.md     ← Deutsch
 ├── README_NL.md     ← Französisch
 // weitere Sprachen... 
 └── README_FR.md     ← Französisch
```

Flaggen-SVGs müssen unter `WB_PATH/languages/` liegen:
`DE.svg`, `EN.svg`, `FR.svg` usw.

---

## Baumdiagramme — `file-tree` und `page-tree`

Zwei Fenced-Block-Sprachen werden als visueller Baum statt als Code gerendert,
über `layout/filetree.js` (derselbe Renderer wie im TipTap-Editor — ein Baum
sieht im Editor, im Reader und im Frontend identisch aus):

    ```file-tree      Icons aus der Dateiendung, Ordner enden auf "/"
    ```page-tree      Seiten-Icons plus WBCEs Sichtbarkeits-Zustände

Die Verbindungszeichen (`│ ├ └ ─`) bestimmen die Einrückungstiefe. Im
**file**-Baum beginnt der Kommentar beim ersten Leerzeichen nach `name.ext`, im
**page**-Baum ausschließlich bei `//`, `#` oder `/*` — Seitentitel enthalten
Leerzeichen.

### Marker

| Marker | Wo | Wirkung |
|---------------------------------------------------------|------------|-------------------------------------------------|
| `{public}` `{hidden}` `{private}` `{registered}` `{none}` | page-tree | setzt das Zeilen-Icon auf diesen Status |
| `{menulink}` oder 🔗 | beide | setzt ein Link-Icon **genau an dieser Stelle** |

Eine Seite ohne Sichtbarkeits-Marker wird als `public` dargestellt. Eine Zeile,
die andere gruppiert (oberste Ebene oder Name mit `/`), behält das Gruppen-Icon,
sofern sie nicht selbst einen Marker trägt. Marker erscheinen nie als Text.

```page-tree
EN {menulink}
│   ├── Home
│   ├── Blog {hidden}          // nicht im Menü
│   └── Über Mich {private}
```

---

## Vollständiges Beispiel

```php
// In einem Modul-Backend, z.B. in pages.php oder modify.php:

$helpLink = MdReaderLink::file(WB_PATH . '/modules/catalogue_hub/docs/README.md')
                   ->title('CatalogueHub – Hilfe')
                   ->popupSize(1100, 840)
                   ->linkHtml('? Hilfe', 'btn-help');

echo '<div class="module-toolbar">' . $helpLink . '</div>';
```

---

## Sicherheitsmodell (Lesen/Schreiben)

`reader.php` und `ajax_save_doc.php` verlangen immer eine gültige Backend-
`Admin`-Session — auch wenn der Link von einer Frontend-Seite aus geöffnet
wird (Cookie-basiert, nicht URL-Pfad-basiert; ein eingeloggter Redakteur, der
die Live-Seite browst, sieht denselben Reader wie im Backend).

| Aktion | Prüfung |
|---|---|
| Lesen | gültige `Admin`-Session (jeder eingeloggte Backend-User) |
| Schreiben/Überschreiben | `$admin->isAdmin()` **oder** `MarkdownWbce_tool`-Permission (Access-Verwaltung → Gruppen → AdminTools, gleiches Muster wie `admin/admintools/tool.php`) |

Zusätzliche Härtung in `MdReaderHelper::safePath()`: nur `.md`-Dateien, nur
innerhalb `WB_PATH`, und `ajax_save_doc.php` überschreibt ausschließlich
bereits existierende Dateien (kein Anlegen neuer Dateien über den Editor).
Jeder Save wird nach `var/markdown_wbce/save.log` protokolliert (JSON-Lines,
wer/wann/welcher Pfad).
