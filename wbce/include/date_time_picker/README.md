# WBCE Date & Time Picker
## WBCE Adaptation of `flatpicker` introducing a DateRange Selection

A lightweight, fully localised **Date**, **Time**, and **Date-Time-Range** picker for the
[WBCE CMS](https://wbce.org) admin interface, built on top of
[flatpickr](https://flatpickr.js.org/).

---

## Overview

This component was developed specifically for use with **WBCE Page Sections**, providing
a polished user experience for the publication start and end date fields. It ships with a
custom WBCE plugin — `wbeRangePlugin` — that connects the two date fields as an
independent start/end range:

- Either field can be left empty without affecting the other.
- When both dates are set the calendar highlights the range between them.
- The start date is displayed in **green**, the end date in **red**, so users can
  immediately tell which field they are editing.
- Manual keyboard input is supported in both fields (`allowInput`).
- Mobile native pickers are suppressed in favour of the consistent flatpickr UI
  (`disableMobile`).

The same setup file can be used by any WBCE module that needs a **plain date picker**,
a **date+time picker**, or the full **start/end range picker**.

---

## Files

| File                           | Purpose                                                |
|--------------------------------|--------------------------------------------------------|
| `flatpickr.min.js`             | flatpickr core library (minified)                      |
| `flatpickr.locales.js`         | All 22 WBCE languages bundled in one file              |
| `flatpickr.wbeRange.js`        | WBCE range plugin (`wbeRangePlugin`)                   |
| `flatpickr.wbeRange.min.css`   | flatpickr stylesheet + WBCE range colours              |
| `wbce_setup.php`               | WBCE integration setup — include this in your module   |
| `index.php`                    | Direct-access guard                                    |

---

## Usage in WBCE Modules

### 1. Include the setup file

Include `wbce_setup.php` early in your module's backend file. The setup file
automatically enqueues the CSS and JS assets via WBCE's asset pipeline
(`I::insertCssFile` / `I::insertJsFile`) — assets are loaded only once per page
regardless of how many module instances are present.

```php
// Plain date picker (no time)
require_once WB_PATH . '/include/date_time_picker/wbce_setup.php';

// Date + time picker
$fp_use_time = true;                // set BEFORE the require
require_once WB_PATH . '/include/date_time_picker/wbce_setup.php';
```

After the include the following PHP variables are available:

| Variable           | Example value              | Description                                         |
|--------------------|----------------------------|-----------------------------------------------------|
| `$fp_locale_key`   | `'de'`, `'default'`        | flatpickr locale key for the active language        |
| `$fp_dateFormat`   | `'d.m.Y'`, `'d.m.Y H:i'`   | flatpickr / PHP-style date format                   |
| `$fp_php_format`   | same as above              | Use with PHP `date()` to format stored timestamps   |
| `$fp_use_time`     | `true` / `false`           | Whether time picking is enabled                     |

---

### 2a. Plain date (or date+time) picker

Initialise flatpickr directly on your input field. The locale object is always
available at `window.flatpickr.l10ns['<?php echo $fp_locale_key; ?>']` after the
assets are loaded.

```html
<input type="text" id="my_date_<?php echo $section_id; ?>"
       name="my_date" value="<?php echo $stored_value; ?>">

<script>
flatpickr(document.getElementById('my_date_<?php echo $section_id; ?>'), {
    locale:        window.flatpickr.l10ns['<?php echo $fp_locale_key; ?>'] || {},
    dateFormat:    '<?php echo $fp_dateFormat; ?>',
    allowInput:    true,
    disableMobile: true<?php if ($fp_use_time): ?>,
    enableTime:    true,
    time_24hr:     true<?php endif; ?>
});
</script>
```

---

### 2b. Start / end range picker (wbeRangePlugin)

The `wbeRangePlugin` manages both fields from a single call. Each field remains
fully independent — either can be empty without forcing the other.

```html
<input type="text" id="start_date_<?php echo $section_id; ?>"
       name="start_date" value="<?php echo $start_value; ?>">
<input type="text" id="end_date_<?php echo $section_id; ?>"
       name="end_date"   value="<?php echo $end_value; ?>">

<script>
var fpStartId      = 'start_date_<?php echo $section_id; ?>';
var fpEndId        = 'end_date_<?php echo $section_id; ?>';
var fpTriggerStart = '';   // id of an optional calendar-open button for start
var fpTriggerEnd   = '';   // id of an optional calendar-open button for end
var fpLocaleKey    = '<?php echo $fp_locale_key; ?>';
var fpDateFormat   = '<?php echo $fp_dateFormat; ?>';
var fpUseTime      = <?php echo $fp_use_time ? 'true' : 'false'; ?>;
</script>
<script src="<?php echo ADMIN_URL; ?>/pages/page_calendar.js"></script>
```

`page_calendar.js` reads the variables above and initialises both pickers using
`wbeRangePlugin`. After initialisation:

- `startInput._flatpickr` — the start flatpickr instance
- `endInput._flatpickr` — the end flatpickr instance

Both instances expose the standard flatpickr API (`.clear()`, `.setDate()`, etc.).

---

## Supported Languages

BG · CA · CS · DA · **DE** · **EN** · ES · ET · FI · FR · GR · HU · IT · LV ·
**NL** · NO · PL · PT · RU · SK · SV · TR

The locale is resolved automatically from WBCE's active `LANGUAGE` constant. No
additional configuration is needed.

---

## Credits

### flatpickr

**Original project:** Gregory Doucette (chmln) and contributors  
**Repository:** <https://github.com/flatpickr/flatpickr>  
**Version bundled:** 4.6.13

### WBCE integration

Adopted for the WBCE Project by Christian M. Stefan as a modern replacement for 
the legacy jscalendar component. Includes the custom `wbeRangePlugin` for 
independent start/end date handling in WBCE Page Sections.

**WBCE Project:** <https://wbce.org> 
**wbEasy.de:** <https://wbEasy.de> Christian M. Stefan

---

## License

**flatpickr** is released under the [MIT License](https://github.com/flatpickr/flatpickr/blob/master/LICENSE.md).

The WBCE integration code (`wbce_setup.php`, `flatpickr.wbeRange.js`, `flatpickr.locales.js`)
is released under the **GNU General Public License v2** (or any later version),
consistent with the WBCE CMS licence.

```
This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE. See the GNU General Public License for more details.
```
