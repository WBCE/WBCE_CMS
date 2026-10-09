# TinyMCE for WBCE

A GNU/GPL v2 integration of the [TinyMCE](https://www.tiny.cloud/) rich-text
editor for WBCE CMS. Self-hosted — no cloud account, no API key.

## Configurator

The module ships an admin tool (**Admin-Tools → TinyMCE**) to configure the
editor without touching code:

- **Toolbar** — drag-and-drop layout over up to three rows, plus a compact
  single-row variant for narrow editors.
- **Editor** — height, menu/status bar, UI skin, word count.
- **Typography & formats** — font families, sizes, paragraph formats, custom styles.
- **Colours**, **version history**, **paste-image handling** (incl. WebP),
  **link options**, and which **editor.css** layer loads into the editing area.

Settings are grouped into named **presets** (complete editor profiles). One is
the default; a module may request a specific preset per field, and an editor
can keep a personal "only me" configuration. Presets export/import as JSON. A
separate, lightweight profile list covers inline (FEE) editing.

## Requirements

WBCE 1.7.0+, PHP 8.1+.

## Languages

English (base), German, Dutch. To add a language, copy `languages/EN.php` and
translate the values.

## License

GNU GPL v2. TinyMCE and the bundled plugins/libraries are GPL-compatible; see
`tinymce/license.md`. Version history is in `CHANGELOG.md`.

## Contributors

- Slugger & Claude KI
- Christian M. Stefan
