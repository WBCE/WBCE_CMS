# wbePwGen

Password strength meter, generator and confirm-field feedback.  
Vanilla JS — no dependencies.

Designed by **Christian M. Stefan** to meet the requirements of CMS environments
such as WebsiteBaker and WBCE CMS, where password inputs appear throughout the system
and a consistent, lightweight solution is needed across every context.

**License:** [GNU General Public License v2](https://www.gnu.org/licenses/gpl-2.0.html)  
**Author:** Christian M. Stefan (https://www.wbEasy.de)

---

## Files

| File             | Purpose                                              |
| ---------------- | ---------------------------------------------------- |
| `wbePwGen.js`    | Main script                                          |
| `wbePwGen.css`   | Styles                                               |
| `i18n.php`       | Localised strings (22 languages) — WBCE/PHP projects |
| `wbce_setup.php` | Enqueues assets via WBCE's `I::` asset manager       |

---

## Basic HTML setup

```html
<link rel="stylesheet" href="wbePwGen.css" />

<!-- Password field -->
<label for="pw">Password</label>
<input id="pw" type="password" autocomplete="new-password" />
<div id="pw-wrap" class="wpg-wrap"></div>

<!-- Confirm field (optional) -->
<label for="pw2">Confirm password</label>
<input id="pw2" type="password" autocomplete="new-password" />

<script src="wbePwGen.js"></script>
<script>
  WbePwGen.attach('pw', 'pw-wrap', {
    confirmId: 'pw2'
  });
</script>
```

**Rules:**
- The `<div class="wpg-wrap">` must come directly after the password input.
- The confirm input can be anywhere — it is referenced by ID only.
- The confirm feedback line is injected automatically after the confirm input; no placeholder div needed.

---

## WBCE / PHP setup

```php
// Enqueue assets + get localised labels
require_once WB_PATH . '/include/wbePwGen/wbce_setup.php';
// $wpg_labels is now available
```

```html
<input id="pw" type="password" name="pw" />
<div id="pw-wrap" class="wpg-wrap"></div>
<input id="pw2" type="password" name="pw2" />

<script>
  WbePwGen.attach('pw', 'pw-wrap', <?= json_encode(
      array_merge($wpg_labels, ['confirmId' => 'pw2'])
  ) ?>);
</script>
```

`i18n.php` resolves the language automatically from WBCE's `LANGUAGE` constant, falling back to `$_GET['lang']`, `$_SESSION['default_language']`, then English.

---

## Options

All options are optional.

```js
WbePwGen.attach('input-id', 'container-id', {

  // Lengths
  minLength: 12,          // minimum required password length
  genLength: 12,          // length of generated passwords

  // Generator button
  showGenerate:   true,
  generateLabel:  '↺',                // button face
  generateTitle:  'Generate password', // tooltip / aria-label

  // Eye toggle
  showToggle:      true,
  toggleShowLabel: '👁',
  toggleHideLabel: '👁',

  // Copy-to-clipboard button (visible only when password is revealed)
  showCopy:     true,
  copyLabel:    '📋',   // button face
  copiedLabel:  '✓',    // shown for 1.5 s after a successful copy
  copyTitle:    'Copy to clipboard', // tooltip / aria-label

  // Strength level labels (5 entries, weakest → strongest)
  levels: ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'],

  // Nudges shown while length < minLength
  // {n} is replaced with minLength
  // Four entries cover 0–25 %, 25–50 %, 50–75 %, 75–100 % of minLength
  nudges: [
    'Enter at least {n} characters',
    'Keep going — try mixing letters & numbers',
    'Almost there — add special characters',
    'Just a bit more…'
  ],

  // Quality hints shown once minLength is reached
  messages: {
    allSameClass: 'Weak — mix letters, numbers and special characters',
    noUpper:      'Add an uppercase letter to strengthen',
    noLower:      'Add a lowercase letter to strengthen',
    noNumber:     'Add a number to strengthen',
    noSpecial:    'Add a special character',
    good:         'Good password',
    strong:       'Strong password ✓'
  },

  // Invalid character messages
  // {chars} is replaced with the offending characters
  invalidMsg:       'Invalid character: {chars}',
  invalidMsgPlural: 'Invalid characters: {chars}',

  // Human-readable names for specific control characters
  charNames: {
    '\t': 'tab',
    '\n': 'newline'
  },

  // Confirm field
  confirmId:      'pw2',           // ID of the confirm input
  confirmMatch:   'Passwords match ✓',
  confirmNoMatch: 'Passwords do not match'

});
```

---

## Allowed characters

All printable ASCII characters are accepted (space `0x20` through tilde `0x7E`), including spaces.  
Only control characters (codes 0–31 and 127) trigger an error message.

Spaces count silently toward length and score but are never generated automatically — they can be typed manually.

---

## Strength bar

A single continuous bar fills left to right and changes colour smoothly:

| Fill  | Colour      | Level label |
| ----- | ----------- | ----------- |
| 20 %  | Red         | Very Weak   |
| 40 %  | Orange      | Weak        |
| 60 %  | Yellow      | Fair        |
| 80 %  | Light green | Good        |
| 100 % | Green       | Strong      |

---

## CSS customisation

All widget elements use the `wpg-` prefix. Key selectors:

```css
.wpg-wrap          /* widget container */
.wpg-track         /* grey bar background */
.wpg-fill          /* coloured bar fill */
.wpg-label         /* level label (right of bar) */
.wpg-hint          /* flowing hint / nudge line */
.wpg-warn          /* invalid character warning */
.wpg-confirm       /* confirm match / no-match line */
.wpg-input-row     /* flex row wrapping input + buttons */
.wpg-btn           /* shared style for eye + generate + copy */
.wpg-eye           /* eye toggle button */
.wpg-gen           /* generate button */
.wpg-copy          /* copy-to-clipboard button */
.wpg-copy.wpg-copied  /* flash state after successful copy */
```
