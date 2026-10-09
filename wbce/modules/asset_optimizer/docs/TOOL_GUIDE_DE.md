# Asset Optimizer — Anleitung

Diese Anleitung erklärt das Tool **Asset Optimizer** in einfacher Sprache.
Du brauchst dafür keine Programmierkenntnisse. Wenn du selbst Templates oder
Module entwickelst und die Funktionen im Code aufrufen willst, lies stattdessen
den Tab [**Assets im Template**](?tool=asset_optimizer&tab=docs&doc=embed)
(`ASSETS_TUTORIAL.md`) oben.

---

## Was macht dieses Tool?

Jede Webseite besteht aus vielen kleinen Hilfsdateien: Style-Dateien, die das
Aussehen bestimmen, und Skript-Dateien, die Dinge beweglich machen. Ein Browser
muss jede dieser Dateien einzeln vom Server holen. Je mehr Dateien und je größer
sie sind, desto länger dauert der Seitenaufbau.

Der **Asset Optimizer** macht diese Dateien kleiner (er entfernt Leerzeichen und
Kommentare, die nur für Menschen da sind) und sorgt dafür, dass Besucher nach
einer Änderung sofort die neue Fassung bekommen statt einer veralteten aus ihrem
Zwischenspeicher.

Du stellst hier ein paar Schalter, klickst **Speichern**, und WBCE erledigt den
Rest bei jedem Seitenaufruf automatisch.

> **Wichtig:** Das *Zusammenfassen* vieler Dateien zu einer einzigen (man nennt
> das „Bündeln") richtet dein Template ein, nicht dieses Tool. Was du hier
> einschaltest, ist das **Verkleinern** und das **Cache-Busting**. Beides wirkt
> auch auf bereits gebündelte Dateien.

---

## Kurzes Wörterbuch

Diese Wörter tauchen im Tool immer wieder auf:

| Wort            | Bedeutung in einem Satz                                                                         |
| --------------- | ----------------------------------------------------------------------------------------------- |
| Asset           | Eine Hilfsdatei der Seite: eine Style-Datei, eine Skript-Datei, eine Schriftart, ein Bild.      |
| CSS             | Die Sprache der Style-Dateien — sie bestimmt Farben, Abstände, Schriftgrößen.                   |
| JavaScript (JS) | Die Sprache der Skript-Dateien — sie macht Menüs, Slider, Formularprüfungen beweglich.          |
| minifizieren    | Eine Datei kleiner machen, indem Leerraum und Kommentare entfernt werden. Inhalt bleibt gleich. |
| Bundle          | Mehrere Dateien, die zu einer einzigen zusammengefasst wurden.                                  |
| Cache           | Ein Zwischenspeicher. Der Server legt fertige Dateien ab; der Browser merkt sich Geladenes.     |
| Cache-Busting   | Ein Trick, der den Browser zwingt, eine geänderte Datei neu zu laden statt der alten Kopie.     |
| Frontend        | Die öffentliche Website, die deine Besucher sehen.                                              |
| Backend         | Der Admin-Bereich, in dem du eingeloggt arbeitest.                                              |

---

## Schnellstart: „Ich will nur, dass meine Seite schneller lädt"

1. Öffne **Admin-Tools → Asset Optimizer**, Tab **Einstellungen**.
2. Schalte **CSS minifizieren** ein.
3. Schalte **JavaScript minifizieren** ein.
4. Schalte unter *Browser-Cache-Busting* den Schalter **Frontend** ein.
5. Klick **Einstellungen speichern**.
6. Ruf deine Website einmal ganz normal auf. Fertig.

Wechsle dann auf den Tab **Cache & Status**. Nach dem ersten Seitenaufruf steht
dort, wie viele Dateien gebaut wurden und wie viel Prozent gespart wurde.

Wenn danach etwas seltsam aussieht: siehe [Wenn etwas kaputt aussieht](#wenn-etwas-kaputt-aussieht).

---

## Der Tab „Einstellungen"

Oben steht ein Hinweis, wo die Werte gespeichert werden. Kurz gesagt: Die
Minifizierungs-Schalter landen in einer Konfigurationsdatei
(`var/config_constants.ini.php`), die zwei Cache-Busting-Schalter in der
Datenbank. Beides übersteht ein WBCE-Update. Du musst dich darum nicht kümmern —
außer der gelbe Kasten unten erscheint.

### Karte „Minifizierung"

#### CSS minifizieren

- **Was es bewirkt:** Entfernt aus jeder Style-Datei deiner Seite den Leerraum
  und die Kommentare und legt die kleinere Fassung im Cache ab. Dateien von
  fremden Servern (CDN) bleiben unberührt.
- **Empfehlung:** **An.** Das ist für so gut wie jede Seite sinnvoll.
- **Woran du die Wirkung merkst:** Auf dem Tab *Cache & Status* erscheinen
  Einträge unter „Minifizierte Dateien" bzw. „Bundles", und die Spalte „Gespart"
  zeigt einen Prozentwert.
- **Kann das etwas kaputt machen?** Bei CSS praktisch nie.

#### JavaScript minifizieren

- **Was es bewirkt:** Dasselbe für die Skript-Dateien.
- **Empfehlung:** **An** — aber die erste Seite danach kurz durchklicken
  (Menü, Slider, Formulare), weil JavaScript empfindlicher auf Verkleinerung
  reagiert als CSS.
- **Kann das etwas kaputt machen?** Selten. Wenn doch, hilft [Wenn etwas kaputt
  aussieht](#wenn-etwas-kaputt-aussieht).

#### .min-Suffix behalten

- **Was es bewirkt:** Ob die Cache-Dateien `...min.css` heißen oder nur `...css`.
- **Empfehlung:** **An lassen** (Standard). Reine Kosmetik im Dateinamen. Bei
  Änderung dieser Einstellung wird der Cache neu geschrieben.

#### Entwickler-Quellansicht

- **Was es bewirkt:** Solange **du** eingeloggt bist, schaltet dieser Schalter
  Verkleinerung und Bündelung *für dich* ab, damit du in den
  Browser-Entwicklertools die ursprünglichen Einzeldateien siehst. Alle anderen
  Besucher bekommen weiterhin die optimierte Fassung.
- **Empfehlung:** **Aus**, außer du suchst gerade einen Fehler. Es ist
  ungefährlich, den Schalter anzulassen — für die Öffentlichkeit ändert sich
  nichts.

### Karte „Browser-Cache-Busting"

Kurz erklärt: Ohne Cache-Busting behält der Browser eines Besuchers eine einmal
geladene Style- oder Skript-Datei oft tagelang — auch wenn du sie längst geändert
hast. Cache-Busting hängt die Änderungszeit an die Datei-Adresse an
(`main.css?1712345678`). Ändert sich die Datei, ändert sich die Adresse, und der
Browser holt automatisch die neue Fassung. Fremde CDN-Adressen bleiben unberührt.

#### Frontend

- **Was es bewirkt:** Cache-Busting auf der öffentlichen Website.
- **Empfehlung:** **An**, wenn du deine Seite gelegentlich pflegst. **Aus** nur,
  wenn sich seit Jahren nichts ändert und du das letzte bisschen Browser-Caching
  herausholen willst.

#### Backend

- **Was es bewirkt:** Cache-Busting für den Admin-Bereich, getrennt vom Frontend.
- **Wann du ihn ändern kannst:** nur solange **Frontend** aus ist. Solange
  Frontend-Cache-Busting an ist, ist auch dieser Schalter fest an — der
  Admin-Bereich hinkt der öffentlichen Seite nie hinterher.
- **Wofür er da ist:** ihn anzuschalten, während **Frontend** aus ist, hält den
  Admin-Bereich frisch (dort wird ständig an CSS/JS gearbeitet), während die
  öffentliche Seite statisch bleibt.

### Karte „Erweitert"

#### Cache-Verzeichnis

- **Was es bewirkt:** Der Ordner, in den die gebauten Dateien geschrieben werden.
  Angezeigt wird der aktuelle Pfad (Standard: `cache/assets/` im WBCE-Ordner).
- **Ändern:** Klick auf **Abweichenden Ordner festlegen**, dann einen absoluten
  Pfad eintippen. Feld leeren und speichern führt zurück zum Standard. Nur nötig,
  wenn dein Hoster dir einen Pfad vorgibt — er muss vom Webserver beschreibbar
  und über das Web erreichbar sein.

#### Admin-Konsolenfehler

- **Was es bewirkt:** Wenn an, meldet die Asset-Pipeline ihre eigenen technischen
  Probleme angemeldeten Administratoren in der Browser-Konsole. Eigener Schalter
  — der globale Entwicklungsmodus schaltet ihn mit an, aber dafür musst du den
  nicht extra einschalten.
- **Empfehlung:** **Aus** auf einer Live-Seite. Nur zum Fehlersuchen einschalten.

### Der gelbe Kasten „… Konstante(n) in config.php definiert"

Erscheint dieser Kasten, ist einer der Werte fest in der Datei `config.php`
eingetragen. Ein Wert dort **gewinnt immer** — gegenüber der Konfigurationsdatei
und der Datenbank —, lässt sich hier **nicht** umstellen (der Schalter ist
ausgegraut) und kann bei einem Update verloren gehen.

**Was tun:** Die genannte Zeile aus `config.php` heraus und nach
`var/config_constants.ini.php` verschieben. Danach verwaltet dieses Tool sie
ganz normal. Wenn du dir das nicht selbst zutraust, gib die Meldung an die
Person weiter, die deinen Server betreut — es ist eine Zwei-Minuten-Aufgabe.

---

## Der Tab „Cache & Status"

Hier siehst du, was gerade im Cache liegt. Du musst hier nichts einstellen — es
ist eine Übersicht plus zwei Aufräum-Knöpfe.

### Die Zahlenleiste oben

| Feld             | Bedeutung                                                           |
| ---------------- | ------------------------------------------------------------------- |
| Dateien im Cache | Wie viele gebaute Dateien gerade abgelegt sind.                     |
| Original         | Zusammengezählte Größe der Dateien, *bevor* sie verkleinert wurden. |
| optimiert        | Größe *nach* der Verkleinerung.                                     |
| gzip geschätzt   | Schätzung, wie klein die Dateien beim Übertragen tatsächlich sind.  |
| gespart          | Der Unterschied in Prozent — die eigentliche Ersparnis.             |
| letzter Build    | Wann zuletzt eine Cache-Datei gebaut wurde.                         |

Beispiel: Original 240 KB → optimiert 180 KB → gespart 25 %. Übertragen wird
dank gzip oft nur ein Bruchteil davon.

### „Bundles" und „Minifizierte Dateien"

Zwei Listen. **Bundles** sind zu einer Datei zusammengefasste Gruppen (vom
Template eingerichtet). **Minifizierte Dateien** sind einzeln verkleinerte
Dateien.

Klick eine Bundle-Zeile an: Sie klappt auf und zeigt die Einzeldateien, aus
denen das Bundle gebaut wurde — jeweils mit Größe und Änderungszeit. Ist eine
Quelldatei inzwischen von der Festplatte gelöscht, steht sie **rot
durchgestrichen** mit dem Hinweis „Quelle gelöscht". Das ist ein Zeichen, dass
das Template oder ein Modul aufgeräumt wurde und das Bundle neu gebaut werden sollte
(einmal „Asset-Cache leeren", siehe unten).

### Die Knöpfe „Asset-Cache leeren" und „Font-Cache leeren"

- **Was sie tun:** Sie löschen die gebauten Dateien bzw. die selbst gehosteten
  Schriftarten aus dem Cache.
- **Ist das gefährlich?** **Nein.** Der nächste Seitenaufruf baut alles neu, was
  gebraucht wird. Der einzige Effekt: Der allererste Aufruf danach ist minimal
  langsamer.
- **Wann sinnvoll:** Nach einem Template- oder Modul-Update, wenn eine Änderung
  nicht durchschlägt, oder wenn eine Quelldatei rot durchgestrichen angezeigt
  wird.

### Karte „Systemprüfungen"

Eine Liste mit grünen und gelben Punkten.

- **Grün:** Alles in Ordnung, nichts zu tun.
- **Gelb:** Ein Hinweis — die Seite funktioniert, aber etwas ist nicht optimal.

Mögliche gelbe Meldungen und was zu tun ist:

| Meldung                                          | Was zu tun ist                                                                                                                                                                    |
| ------------------------------------------------ | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Asset-Cache-Verzeichnis ist *nicht* beschreibbar | Der Webserver darf im Cache-Ordner nichts anlegen. Dein Hoster muss die Schreibrechte setzen.                                                                                     |
| Font-Cache-Verzeichnis ist *nicht* beschreibbar  | Dasselbe für `cache/fonts/` — nur nötig, wenn du Web-Schriften selbst hostest.                                                                                                    |
| `matthiasmullie/minify` fehlt                    | Erscheint nur auf einer kaputten Installation — die Bibliothek gehört zum WBCE-Kern. Neu einspielen oder den Hoster fragen. Bis dahin verkleinert WBCE JavaScript nur vorsichtig. |
| cURL ist *nicht* verfügbar                       | Nur wichtig, wenn du Web-Schriften selbst hosten willst. Dein Hoster muss die PHP-Erweiterung cURL aktivieren.                                                                    |
| … Konstante(n) in config.php fixiert             | Siehe der gelbe Kasten im Tab *Einstellungen* — Zeile nach `var/config_constants.ini.php` verschieben.                                                                            |

---

## Wenn etwas kaputt aussieht

Nach dem Einschalten der Verkleinerung sieht selten mal etwas anders aus —
meistens liegt es an JavaScript. Geh der Reihe nach vor:

1. **Cache leeren.** Tab *Cache & Status* → „Asset-Cache leeren". Seite neu laden
   (am besten mit Strg+F5). Oft ist es damit erledigt.
2. **JavaScript-Minifizierung testweise aus.** Tab *Einstellungen* → „JavaScript
   minifizieren" aus, speichern, Cache leeren, Seite prüfen. Ist der Fehler weg,
   lag es daran.
3. **Entwickler-Quellansicht an.** Damit siehst du (als eingeloggter Admin) in
   den Browser-Entwicklertools die Originaldateien und die genaue Fehlermeldung.
   Gib diese Meldung an deine Entwicklerin oder deinen Entwickler weiter.
4. **Zurückschalten.** Alle Schalter, bei denen der Fehler auftrat, wieder aus,
   speichern, Cache leeren — die Seite ist sofort wieder wie vorher. Es geht
   nichts dauerhaft kaputt.

---

## Häufige Fragen

**Muss ich die Verkleinerung dauerhaft anlassen?**
Ja, das ist der Sinn — sie wirkt bei jedem Seitenaufruf. Aus solltest du sie nur
zum Fehlersuchen schalten.

**Verliere ich die Einstellungen bei einem WBCE-Update?**
Nein. Sie liegen in `var/config_constants.ini.php` und in der Datenbank, beides
updatesicher. Die einzige Ausnahme ist der Fall aus dem gelben Kasten (Wert steht
in `config.php`) — deshalb der Hinweis, ihn zu verschieben.

**Was ist mit Besuchern, die meine Seite schon im Browser-Cache haben?**
Solange Cache-Busting an ist, bekommen sie beim nächsten Besuch automatisch die
neue Fassung, sobald du etwas geändert hast.

**Brauche ich die Bibliothek `matthiasmullie/minify`?**
Sie ist schon da — sie wird mit WBCE ausgeliefert und erledigt die Verkleinerung.
Nur falls sie auf einer kaputten Installation fehlt, meldet das der Tab *Cache &
Status*, und WBCE verkleinert JavaScript so lange nur vorsichtig.

**Der „Asset-Cache leeren"-Knopf — kann ich den bedenkenlos drücken?**
Ja, jederzeit. Schlimmstenfalls ist der erste Seitenaufruf danach einen
Sekundenbruchteil langsamer.

---

*Für Entwicklerinnen und Entwickler:* die weiteren Tabs oben —
[**Assets im Template**](?tool=asset_optimizer&tab=docs&doc=embed)
(`ASSETS_TUTORIAL.md`, Assets aus Template und Modul laden),
[**Web-Fonts**](?tool=asset_optimizer&tab=docs&doc=fonts)
(`FONTS_TUTORIAL.md`, Schriften selbst hosten) und
[**Referenz**](?tool=asset_optimizer&tab=docs&doc=ref)
(`ASSETS_REFERENCE.md`, vollständiges Nachschlagewerk).
