# Third-party notices for PlainMDE

PlainMDE's own code (everything under `src/`, `css/`, `*.php`) is original
work, licensed under the MIT license in [LICENSE](LICENSE). This file
documents where that work came from conceptually, and lists the licenses of
the third-party code and assets vendored alongside it.

## Design lineage

PlainMDE is a ground-up rewrite — it does not include, bundle, or transpile
any code from [EasyMDE](https://github.com/Ionaru/easy-markdown-editor) or
[SimpleMDE](https://github.com/sparksuite/simplemde-markdown-editor). But it
started life as a WBCE-specific EasyMDE fork, and its toolbar action set
(bold/italic/strikethrough/heading/quote/lists/link/image/table/horizontal-
rule/preview/side-by-side/fullscreen), the overall editor+preview+toolbar
layout, and several UX conventions (e.g. Ctrl-based shortcuts, the
preview/side-by-side/fullscreen toggle trio) were carried over from that
lineage by design, not reinvented independently. That lineage is:

- **SimpleMDE** — Sparksuite, Inc. — MIT License, Copyright (c) 2015 Sparksuite, Inc.
- **EasyMDE** — Ionaru and contributors (fork of SimpleMDE) — MIT License,
  Copyright (c) 2015 Sparksuite, Inc.; Copyright (c) 2017 Jeroen Akkerman.
  https://github.com/Ionaru/easy-markdown-editor

Both are MIT-licensed; the full upstream license text is reproduced above
verbatim since it's the origin of the design this project builds on, even
though no source from either package ships in this folder.

## Vendored third-party code

### CodeMirror (`vendor/codemirror-addons/`)

`mode/markdown/markdown.js`, `mode/gfm/gfm.js`, and
`addon/display/autorefresh.js` are unmodified files from the
[CodeMirror 5](https://codemirror.net/5/) distribution, copyright Marijn
Haverbeke and others, MIT License. Each file carries its own license header
comment. The canonical license text for the CodeMirror copy this project
already vendors elsewhere is at
`modules/CodeMirror_Config/codemirror/LICENSE`.

### Tabler Icons (`icons.php`)

The toolbar icons are hand-picked outline glyphs from
[Tabler Icons](https://tabler.io/icons), MIT License, Copyright (c)
2020-present Paweł Kuna. The SVG markup was adapted to match this project's
icon convention (added `class="icon icon-tabler ..."`, added a
background-normalizer `<path>`) but the glyphs themselves are unchanged.

```
The MIT License (MIT)

Copyright (c) 2020-present Paweł Kuna

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

```
The MIT License (MIT)

Copyright (c) 2015 Sparksuite, Inc.
Copyright (c) 2017 Jeroen Akkerman.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```
