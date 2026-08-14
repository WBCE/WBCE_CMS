# wbSeoTool — Roadmap

Ideas for future extensions, roughly in order of how disruptive they are to
build. None of this is scheduled — this file just keeps the options from
getting lost between sessions.

## Größerer Umbau (neue Spalten/eigene Settings nötig)

Diese Punkte brauchen mehr als das bestehende Inline-Edit-Muster — entweder
eine neue Spalte in `{TP}pages`, ein neues optionales Custom-Feld (analog zu
`REWRITE_URL` heute schon), oder Abstimmung mit einem anderen Modul.

- **Meta Robots** (noindex/nofollow) als weiteres optionales Custom-Feld,
  gleiches Muster wie `REWRITE_URL`.
- **Canonical URL**.
- **Open-Graph-Felder** (`og:title`/`og:description`) für Social-Previews.
- **Sitemap-Integration** — es gibt im System schon ein separates
  "Sitemap"-Modul; Priority/Changefreq direkt hier im Baum mitpflegen wäre
  naheliegend, braucht aber Absprache mit dessen Datenmodell.

## Bereits umgesetzt (zur Einordnung)

- Doppelklick-editierbarer Menü-Titel (optional, Settings-Toggle).
- Autogrow für alle Textfelder.
- Duplikat-Warnung für `page_title`/`description` (baumweit, live).
- Status-Punkte (leer/zu kurz/optimal/zu lang) für `page_title`/`description`,
  auch bei eingeklapptem Baum-Knoten sichtbar.

## Hinweis

- Die aktuellen Zeichen-Schwellenwerte (Title 30/50/60, Description 90/150/160)
  sind eine Annahme auf Basis aktueller SEO-Praxis — Google rendert Titel/
  Description pixelbasiert, nicht zeichenbasiert, und die faktischen Grenzen
  verschieben sich gelegentlich. Diese Werte sollten irgendwann gegen aktuelle
  Google-SERP-Daten geprüft und ggf. angepasst werden.
