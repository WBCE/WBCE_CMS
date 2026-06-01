# wbePwGen — Password Strength Widget

A lightweight, dependency-free password strength and validation widget for
**WBCE CMS**. Displays a 5-level strength bar, a per-criterion hint checklist,
and a warning when invalid characters are typed.

---

## Files

| File             | Purpose                                                 |
|------------------|---------------------------------------------------------|
| `wbePwGen.js`    | Plugin — exposes `WbePwGen.attach()`                    |
| `wbePwGen.css`   | Styles — strength bar, hints, warning box               |
| `i18n.php`       | Localised label array → `$wpg_labels`                   |
| `wbce_setup.php` | WBCE backend helper — enqueues assets + provides labels |
| `index.php`      | Direct-access guard                                     |

---

## Strength Scoring (1 – 5)

| Score | Label      | Criteria met                                     |
|-------|------------|--------------------------------------------------|
| 1     | Very Weak  | Invalid characters present, or only 1 criterion |
| 2     | Weak       | 2 criteria                                       |
| 3     | Fair       | 3 criteria                                       |
| 4     | Good       | 4 criteria                                       |
| 5     | Strong     | All 5 criteria                                   |

**Criteria** (each worth +1 point):

| Criterion      | Rule                                    |
|----------------|-----------------------------------------|
| Minimum length | ≥ 12 characters                         |
| Long password  | ≥ 16 characters                         |
| Mixed case     | Contains upper- AND lowercase letter    |
| Number         | Contains at least one digit             |
| Special char   | Contains one of: `_ - ! # * + @ $ & :` |

If any character outside the allowed set is typed, the score is forced to **1**
and a warning lists the invalid characters.

---

## Usage — WBCE Module (backend)

```php
// In your module's backend PHP file:
require_once INCLUDE_PATH . '/wbePwGen/wbce_setup.php';
// Assets are enqueued once via I:: — $wpg_labels is now available.
```

```html
<!-- In your module's template: -->
<input type="password" id="my_password" name="my_password" minlength="12">
<div id="my-pw-strength"></div>

<script>
WbePwGen.attach('my_password', 'my-pw-strength', <?php echo json_encode($wpg_labels); ?>);
</script>
```

The widget listens to the native `input` event. If you set the field value
programmatically, fire the event manually so the meter updates:

```javascript
document.getElementById('my_password').dispatchEvent(new Event('input'));
```

---

## Usage — Installer (or any non-WBCE page)

```php
// In index.php (before the template include):
require_once $wb_path . '/include/wbePwGen/i18n.php';
// $wpg_labels is now available.
```

```html
<!-- In <head>: -->
<link rel="stylesheet" href="../include/wbePwGen/wbePwGen.css">

<!-- Before </body>: -->
<script src="../include/wbePwGen/wbePwGen.js"></script>
<script>
WbePwGen.attach('admin_password', 'pw-strength-wrap', <?php echo json_encode($wpg_labels); ?>);
</script>
```

---

## JavaScript API

```javascript
// Attach widget to an input and return the instance
var instance = WbePwGen.attach(inputId, containerId, options);

// Manually trigger a re-evaluation (e.g. after programmatic value change)
instance.update();
```

### Options

| Option           | Type       | Default                      | Description                            |
|------------------|------------|------------------------------|----------------------------------------|
| `minLength`      | `number`   | `12`                         | Minimum length threshold for +1 point  |
| `levels`         | `string[]` | EN labels                    | Five strength label strings (1–5)      |
| `hints.length`   | `string`   | `'At least {n} characters'`  | `{n}` is replaced with `minLength`     |
| `hints.case`     | `string`   | `'Upper + lowercase …'`      | Mixed-case hint text                   |
| `hints.number`   | `string`   | `'Contains a number'`        | Number hint text                       |
| `hints.special`  | `string`   | `'Special char (…)'`         | Special-character hint text            |
| `warn.invalid`   | `string`   | `'Invalid characters:'`      | Label before the invalid-char list     |
| `warn.allowed`   | `string`   | `'Allowed:'`                 | Label before the allowed-chars display |
| `allowedDisplay` | `string`   | `'a–z A–Z 0–9 _ - …'`       | Human-readable allowed character set   |

---

## i18n

`i18n.php` ships with built-in translations for:
**EN · DE · NL · FR · ES · IT · PL · RU**

The language is resolved automatically from WBCE's `LANGUAGE` constant.
English is used as fallback for any unsupported language.

To add a language, extend the `$_wpg_strings` array in `i18n.php`.

---

## License

Released under the **GNU General Public License v2** (or any later version),
consistent with the WBCE CMS licence.
