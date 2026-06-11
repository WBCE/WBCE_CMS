# WbeColoris

A WBCE-internal fork of [Coloris](https://github.com/mdbassit/Coloris) v0.25.0 — a lightweight, accessible colour picker.

WbeColoris extends the upstream library with features required by the WBCE admin backend: 
an explicit OK button, a dual-mode Reset/Clear button, per-instance configuration with default colours, 
and full built-in i18n for all 20 languages shipped with WBCE.

---

## Why a fork?

Upstream Coloris applies colour changes **live** — every interaction updates the bound field immediately.
WBCE admin tools require a confirmed workflow: the user selects a colour and explicitly clicks **OK** before 
the field value changes.
Additionally, WBCE modules need per-field default colours, descriptive reset actions, and translated UI labels 
without any server-side PHP infrastructure per module.

None of these features exist in upstream Coloris, and the changes are too WBCE-specific to contribute back.
The fork therefore stays internal under `include/wbeColoris/` and is maintained alongside WBCE.

---

## What's new compared to upstream Coloris

| Feature                          | Coloris   | WbeColoris                       |
| -------------------------------- | --------- | -------------------------------- |
| OK button (confirm selection)    | No        | Yes — `okButton: true`           |
| Reset to default colour          | No        | Yes — dual-mode Reset button     |
| Clear field (no colour)          | No        | Yes — dual-mode Clear button     |
| Per-instance configuration       | No        | Yes — `WbeColoris.setInstance()` |
| `defaultColor`                   | No        | Yes                              |
| `defaultColorLabel` in hex input | No        | Yes                              |
| Built-in i18n (20 languages)     | No        | Yes — `wbeColoris.i18n.js`       |
| WBCE admin theme CSS overrides   | No        | Yes — `wbeColoris.admin.css`     |
| Global API name                  | `Coloris` | `WbeColoris`                     |

### OK button

When `okButton: true` is set, colour changes are **not** applied to the bound field until the user clicks `✓ OK`. The picker remains open for further adjustments. Clicking outside or pressing Escape closes without saving.

### Dual-mode Reset/Clear button

The Reset button behaves differently depending on whether a `defaultColor` is configured for the field:

- **With `defaultColor`** — shows `↩`, tooltip: *"Reset to default"*. Resets the field to `defaultColor` and optionally shows `defaultColorLabel` in the hex input. The button is **hidden** while the value already equals the default (nothing to reset).
- **Without `defaultColor`** — shows `×`, tooltip: *"Clear field value"*. Clears the field and closes the picker. The button is **hidden** while the field is empty.

### Per-instance configuration

`WbeColoris.setInstance(selector, options)` stores a configuration for one or more specific fields. The configuration is applied automatically when that field's picker opens and removed when it closes, without affecting the global settings.

---

## Files

| File                   | Purpose                                                                          |
| ---------------------- | -------------------------------------------------------------------------------- |
| `wbeColoris.js`        | Core library (include on every page that uses WbeColoris)                        |
| `wbeColoris.i18n.js`   | Built-in translations — load **after** `wbeColoris.js`                          |
| `wbeColoris.css`       | Picker styles + OK/Reset button styles                                           |
| `wbeColoris.admin.css` | Overrides for WBCE admin themes — load **after** `wbeColoris.css` on admin pages |

---

## Basic usage

### 1. Include the files

```html
<link rel="stylesheet" href="/include/wbeColoris/wbeColoris.css">
<!-- On admin pages only: -->
<link rel="stylesheet" href="/include/wbeColoris/wbeColoris.admin.css">

<script src="/include/wbeColoris/wbeColoris.js"></script>
<script src="/include/wbeColoris/wbeColoris.i18n.js"></script>
```

`wbeColoris.i18n.js` auto-detects the language from `window.LANGUAGE` (set by WBCE in every admin `<head>` as `var LANGUAGE = "de";`) and applies translations immediately on DOM ready.

### 2. Mark your inputs

Add `data-wbecoloris` to any `<input type="text">`:

```html
<input type="text" id="my_color" name="my_color" data-wbecoloris value="">
```

### 3. Global configuration

Call `WbeColoris({...})` once to set options for all picker instances:

```js
WbeColoris({
    alpha:     false,       // disable alpha/opacity slider
    format:    'hex',       // output format: 'hex' | 'rgb' | 'hsl' | 'auto'
    themeMode: 'light',     // 'light' | 'dark' | 'auto'
    okButton:  true,        // enable OK-button workflow
    onChange:  function (color, input) {
        console.log('confirmed:', color, input);
    }
});
```

---

## Per-instance configuration

```js
WbeColoris.setInstance('#my_color', {
    defaultColor:      '#6d28d9',
    swatches: ['#6d28d9', '#2563eb', '#16a34a', '#ea580c', '#dc2626']
});
```

`setInstance` accepts all global options plus:

| Option              | Type       | Description                                                                                                                        |
| ------------------- | ---------- | -----------------------------------------------------------------------------------------------------------------------------------|
| `defaultColor`      | `string`   | Hex colour used as the default/fallback. Enables the `↩` reset button.                                                             |
| `defaultColorLabel` | `string`   | Label shown in the hex input when the field is at its default (e.g. `"Default"`). Overrides the i18n value for this instance only. |
| `swatches`          | `string[]` | Array of hex colours shown as quick-pick swatches.                                                                                 |

---

## i18n

### Automatic language detection

When `wbeColoris.i18n.js` is loaded, it reads `window.LANGUAGE` and calls `WbeColoris.setLang()` automatically.
WBCE sets `var LANGUAGE = "de";` (lowercase) in every admin `<head>` — the i18n module handles the case conversion internally.

No configuration needed for the standard WBCE setup.

### Supported languages

20 languages — matching the full set of languages shipped with WBCE:

`EN` `DE` `NL` `FR` `DA` `NO` `PL` `PT` `RU` `SK` `BG` `CS` `ES` `ET` `FI` `GR` `HU` `IT` `LV` `CA`

Aliases: `DE_AT` → `DE`, `DE_CH` → `DE`

### Manual language switch

```js
WbeColoris.setLang('FR');   // apply French
WbeColoris.setLang('de');   // lowercase is accepted — applies German
```

### Accessing the language map

```js
// Full map of all loaded languages
console.log(WbeColoris.langs);       // { EN: {...}, DE: {...}, … }
console.log(WbeColoris.langs.DE);    // { okLabel, resetLabel, clearLabel, a11y: {…}, … }
```

### Overriding individual strings

**Globally** — call `WbeColoris({...})` after `wbeColoris.i18n.js` has loaded:

```js
// Override just the labels you need; all others stay from the active language
WbeColoris({
    okLabel:    'Apply',
    clearLabel: 'Remove',
    a11y: {
        reset: 'Restore brand colour'
    }
});
```

**Per instance** — pass overrides directly to `setInstance`:

```js
WbeColoris.setInstance('#brand_color', {
    defaultColor:      '#6d28d9',
    defaultColorLabel: 'Brand purple'   // overrides the i18n default label for this field only
});
```

### Translated string keys

The following strings are translated per language (and can be overridden):

| Key                 | Description                         | Universal value |
| ------------------- | ----------------------------------- | --------------- |
| `okLabel`           | OK button label                     | `✓ OK`          |
| `resetLabel`        | Reset button label (↩ mode)         | `↩`             |
| `clearFieldLabel`   | Clear button label (× mode)         | `×`             |
| `clearLabel`        | "Clear" button (Coloris built-in)   | translated      |
| `closeLabel`        | "Close" button (Coloris built-in)   | translated      |
| `defaultColorLabel` | Text shown in hex input at default  | translated      |
| `a11y.open`         | Screen reader: open picker          | translated      |
| `a11y.close`        | Screen reader: close picker         | translated      |
| `a11y.clear`        | Screen reader: clear colour         | translated      |
| `a11y.marker`       | Screen reader: gradient marker      | translated      |
| `a11y.hueSlider`    | Screen reader: hue slider           | translated      |
| `a11y.alphaSlider`  | Screen reader: opacity slider       | translated      |
| `a11y.input`        | Screen reader: colour value field   | translated      |
| `a11y.format`       | Screen reader: colour format        | translated      |
| `a11y.swatch`       | Screen reader: colour swatch        | translated      |
| `a11y.instruction`  | Screen reader: gradient instruction | translated      |
| `a11y.reset`        | Tooltip/SR: reset button            | translated      |
| `a11y.ok`           | Tooltip/SR: OK button               | translated      |
| `a11y.clearField`   | Tooltip/SR: clear button (× mode)   | translated      |

`okLabel`, `resetLabel`, and `clearFieldLabel` intentionally use universal symbols — they are the same across all languages. Override them only when your context specifically requires different text.

---

## Admin theme compatibility

WBCE admin themes (e.g. *argos_theme_reloaded*) apply global `button { min-width: 150px; background-color: … }` rules that break the picker's swatch button and the OK/Reset buttons.

Include `wbeColoris.admin.css` **after** `wbeColoris.css` on every admin page that uses WbeColoris to neutralise these overrides:

```html
<link rel="stylesheet" href="/include/wbeColoris/wbeColoris.css">
<link rel="stylesheet" href="/include/wbeColoris/wbeColoris.admin.css">
```

This file is not needed on front-end pages.

---

## Full API reference

### `WbeColoris(options)`

Set global configuration. Can be called multiple times; options are merged.

```js
WbeColoris({
    el:            '[data-wbecoloris]', // default selector
    parent:        null,                // mount picker inside a specific element
    theme:         'default',           // picker theme
    themeMode:     'light',             // 'light' | 'dark' | 'auto'
    rtl:           false,               // right-to-left
    format:        'hex',               // 'hex' | 'rgb' | 'hsl' | 'auto'
    formatToggle:  false,               // show format toggle button
    alpha:         true,                // show alpha/opacity slider
    forceAlpha:    false,               // always include alpha in output
    focusInput:    true,                // focus hex input on open
    selectInput:   false,               // select hex input text on open
    inline:        false,               // inline (non-floating) mode
    defaultColor:  '',                  // global fallback default colour
    swatches:      [],                  // global swatch list
    clearButton:   false,               // show Coloris built-in clear button
    clearLabel:    '…',                 // label for clear button (from i18n)
    closeButton:   false,               // show Coloris built-in close button
    closeLabel:    '…',                 // label for close button (from i18n)
    okButton:      false,               // enable WbeColoris OK button
    okLabel:       '✓ OK',              // OK button label
    resetLabel:    '↩',                 // Reset button label (↩ mode)
    clearFieldLabel: '×',               // Clear button label (× mode)
    a11y:          { … },               // accessibility / tooltip strings (see table above)
    onChange:      null,                // callback(color, inputEl) — fires when OK is clicked
    onClose:       null,                // callback(color, inputEl) — fires on picker close
});
```

### `WbeColoris.setInstance(selector, options)`

Store per-field options. Applied when that field's picker opens; global settings are restored on close.

```js
WbeColoris.setInstance('#brand_color', {
    defaultColor:      '#6d28d9',
    defaultColorLabel: 'Brand purple',
    swatches: ['#6d28d9', '#2563eb']
});
```

### `WbeColoris.removeInstance(selector)`

Remove a previously stored per-field configuration.

### `WbeColoris.setLang(code)`

Apply a language from the built-in set. Accepts upper- or lowercase codes (`'DE'`, `'de'`, `'DE_AT'`).

### `WbeColoris.langs`

Object containing all loaded language definitions, keyed by uppercase language code.

### `WbeColoris.ready(callback)`

Register a callback to run when the DOM is ready. Used internally by `wbeColoris.i18n.js`.

### `WbeColoris.close()`

Close the picker programmatically.

### `WbeColoris.wrap(parent)`

Wrap all matching elements in a `.clr-field` container (called automatically on init).

---

## License

WbeColoris is based on **Coloris** by Momo Bassit, released under the 
[MIT License](https://github.com/mdbassit/Coloris/blob/main/LICENSE).

The WBCE fork is likewise released under the **MIT License**.

```
MIT License

Copyright (c) 2021 Mohammed Bassit (upstream Coloris)
Copyright (c) 2026 Christian M. Stefan (WbeColoris fork and extensions for WBCE CMS 1.7.0)

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
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
```

---

## Credits

**Upstream library**
[Coloris](https://github.com/mdbassit/Coloris) by [Mohammed Bassit](https://github.com/mdbassit) — MIT License.

**WBCE fork**
Ported and extended for WBCE by Christian M. Stefan for the [WBCE Project](https://wbce-cms.org).

The fork was created to adapt Coloris as closely as possible to WBCE's admin UI requirements:

- A confirmed OK-button workflow instead of live field updates
- A context-sensitive Reset/Clear button with dual behaviour depending on whether a default colour exists
- Per-instance configuration to support multiple colour pickers on a single admin page, each with its own defaults and swatches
- Built-in translations for all 20 WBCE admin languages, auto-applied from `window.LANGUAGE` without any PHP infrastructure
- CSS overrides ensuring correct rendering inside WBCE admin themes

The global API identifier was changed from `Coloris` to `WbeColoris` and the CSS selector 
from `[data-coloris]` to `[data-wbecoloris]` to avoid collisions if both libraries are ever present on the same page.
