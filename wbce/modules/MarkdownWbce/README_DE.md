# MarkdownWbce

Das Kernmodul, das WBCE für Markdown nutzt: Rendering, Bearbeitung und
Verlinkung zu `.md`-Dateien an beliebigen Stellen der Website — kein
spezialisiertes Add-on, sondern ein grundlegender Dienst, auf dem andere
Module aufbauen.

Es stellt bereit:

- **`ParsedownWbce`** — core-weit registriert über `initialize.php` (sowohl
  Frontend als auch Backend), sodass jedes Modul Markdown nach HTML rendern
  kann, ohne sich darum kümmern zu müssen, ob der eigene Viewer dieses
  Moduls jemals geöffnet wird.
- **`reader.php`** — ein Popup-Viewer/Editor für beliebige `.md`-Dateien
  unter `WB_PATH`. Automatische Erkennung von Sprachvarianten
  (`README_DE.md` usw.), ein Inhaltsverzeichnis, syntaxhervorgehobene
  Code-Blöcke und visuelle `` ```file-tree ``-Diagramme.
- **Bearbeitung**, betrieben von [PlainMDE](../../include/PlainMDE/) — dem
  gleichen leichtgewichtigen Markdown-Editor, der im gesamten Backend
  verwendet wird — abgesichert durch die `MarkdownWbce_tool`-AdminTool-
  Berechtigung (oder volles Admin-Recht), getrennt vom Lesezugriff, der
  lediglich eine Anmeldung voraussetzt.
- **`MdReaderLink`** — der flüssige Builder, den andere Module nutzen, um
  auf ihre eigene Dokumentation zu verlinken, verfügbar in FE+BE über
  `initialize.php`.

Siehe [DOCS.md](DOCS.md) für die vollständige `MdReaderLink`-Nutzungs-
referenz (oder [DOCS_DE.md](DOCS_DE.md) für die deutsche Version) — wie man
eine einzelne Datei, mehrere Dateien als Tabs oder ein ganzes Verzeichnis
über ein `md_reader.json`-Manifest verlinkt.

## Lizenz

GNU/GPL v2 — Copyright (c) 2026 Christian M. Stefan
([wbEasy.de](https://www.wbeasy.de)), passend zur Lizenz des WBCE-Cores.

Die einzige Ausnahme ist die mitgelieferte [Parsedown](Parsedown/README.md)-
Bibliothek unter `Parsedown/` (MIT, Copyright (c) 2013-2018 Emanuil Rusev)
— MIT hat keine Copyleft-Anforderung, daher kann sie hier koexistieren,
ohne den eigenen Code dieses Moduls unter MIT oder die Originaldateien von
Parsedown unter GPL zu stellen; lediglich der originale Lizenzhinweis muss
erhalten bleiben, siehe
[Parsedown/LICENSE.txt](Parsedown/LICENSE.txt). `ParsedownWbce` (die eigene
Unterklasse dieses Moduls, die GFM-Task-Listen-Checkboxen und den
`` ```file-tree ``-Blocktyp hinzufügt) ist GPL2 wie der restliche Code
dieses Moduls.

## Geschichte

Ursprünglich ausgeliefert als `MarkdownReader`: ein schreibgeschützter
Popup-Viewer für die eigene `README.md` eines Moduls, mit `MdrLink` als
öffentlicher Verlinkungs-API. Umbenannt in `MarkdownWbce` und in die oben
beschriebene Rolle hineingewachsen — Bearbeitung über PlainMDE,
berechtigungsgesteuerte Schreibzugriffe, automatische Erkennung von
Sprachvarianten und ein Sprachumschalter, `` ```file-tree ``-Rendering,
Syntaxhervorhebung sowie ein vollständiges Hell-/Dunkel-Theme.
`MdrLink`/`MdrHelper` wurden unterwegs in `MdReaderLink`/`MdReaderHelper`
umbenannt. Siehe [CHANGELOG.md](CHANGELOG.md) für die detaillierte
Versionshistorie.

## Autor

Christian M. Stefan ([wbEasy.de](https://www.wbeasy.de))