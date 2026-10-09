# MarkdownWbce

The core module WBCE uses for Markdown: rendering, editing, and linking to
`.md` files anywhere on the site — not a single-purpose add-on, a
foundational service other modules build on.

It provides:

- **`ParsedownWbce`** — registered core-wide via `initialize.php` (both
  frontend and backend), so any module can render Markdown to HTML without
  worrying whether this module's own viewer is ever opened.
- **`reader.php`** — a popup viewer/editor for any `.md` file under
  `WB_PATH`. Automatic language-variant resolution (`README_DE.md` etc.), a
  table of contents, syntax-highlighted code blocks, and visual
  `` ```file-tree `` diagrams.
- **Editing**, powered by [PlainMDE](../../include/PlainMDE/) — the same
  lightweight Markdown editor used across the backend — gated behind the
  `MarkdownWbce_tool` AdminTool permission (or full Admin), separate from
  read access, which only requires being logged in.
- **`MdReaderLink`** — the fluent builder other modules use to link to
  their own documentation, available FE+BE via `initialize.php`.

See [DOCS.md](DOCS.md) for the full `MdReaderLink` usage reference (or
[DOCS_DE.md](DOCS_DE.md) for the German version) — how to link a single
file, multiple files as tabs, or a whole directory via a
`md_reader.json` manifest.

## License

GNU/GPL v2 — Copyright (c) 2026 Christian M. Stefan
([wbEasy.de](https://www.wbeasy.de)), matching WBCE core's own license.

The one exception is the vendored [Parsedown](Parsedown/README.md) library
under `Parsedown/` (MIT, Copyright (c) 2013-2018 Emanuil Rusev) — MIT has
no copyleft requirement, so it coexists here without pulling this module's
own code under MIT or Parsedown's own files under GPL; only its original
license notice has to stay attached, see
[Parsedown/LICENSE.txt](Parsedown/LICENSE.txt). `ParsedownWbce` (this
module's own subclass, adding GFM task-list checkboxes and the
`` ```file-tree `` block type) is GPL2 like the rest of this module's code.

## History

Originally shipped as `MarkdownReader`: a read-only popup viewer for a
module's own `README.md`, with `MdrLink` as its public linking API.
Renamed to `MarkdownWbce` and grew into the role described above — editing
via PlainMDE, permission-gated writes, automatic language-variant
resolution and a language switcher, `` ```file-tree `` rendering, syntax
highlighting, and a full light/dark theme. `MdrLink`/`MdrHelper` were
renamed to `MdReaderLink`/`MdReaderHelper` along the way. See
[CHANGELOG.md](CHANGELOG.md) for the detailed version history.

## Author

Christian M. Stefan ([wbEasy.de](https://www.wbeasy.de))
