# captcha_control — Captcha Integration Guide

> WBCE CMS 1.7.0+ · Captcha type: **ALTCHA** (proof-of-work, self-hosted)  
> `call_captcha()` and `Captcha::verify()` are available on every page load —  
> no `require_once` needed in new modules.

---

## How It Works

WBCE uses **ALTCHA** — a self-hosted, privacy-friendly proof-of-work captcha.  
The visitor's browser solves a small SHA-256 puzzle in the background; no data  
is sent to any third party. The widget is invisible or shows a minimal checkbox.

`captcha_control/initialize.php` runs on every page load and registers:
- The `Captcha` class (via `WbAuto::AddFile`)
- The `call_captcha()` helper function

Both are available everywhere without any `require_once`.

---

## Quick Start

### 1. Render in the form

```php
// Outputs the ALTCHA widget (+ honeypot if ASP is enabled in settings).
// Place it inside your <form> element, before the submit button.
call_captcha();
```

```html
<form method="post">
    <!-- your fields -->
    <?php call_captcha(); ?>
    <button type="submit">Send</button>
</form>
```

### 2. Verify on POST

```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Captcha::verify($_POST['captcha'] ?? '')) {
        // Captcha failed — show error, do not process form
    }
    // … process form
}
```

That's it. `Captcha::verify()` handles both the ALTCHA cryptographic proof  
and the honeypot check (if ASP is enabled) in one call.

---

## Respecting the `ENABLED_CAPTCHA` Setting

Users can disable the captcha in the CMS backend under  
**Tools → Captcha Control**. Always check the setting before rendering:

```php
$captchaEnabled = defined('ENABLED_CAPTCHA') && ENABLED_CAPTCHA
               && !(defined('NO_SESSION_COOKIE') && NO_SESSION_COOKIE);

if ($captchaEnabled) {
    call_captcha();
}
```

And on verification:

```php
if ($captchaEnabled) {
    if (!Captcha::verify($_POST['captcha'] ?? '')) {
        // error
    }
}
```

---

## Full Example — Contact Form

```php
<?php
// view.php (PageType module) or any frontend form handler

$error   = '';
$success = false;

$captchaEnabled = defined('ENABLED_CAPTCHA') && ENABLED_CAPTCHA
               && !(defined('NO_SESSION_COOKIE') && NO_SESSION_COOKIE);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Captcha check first
    if ($captchaEnabled && !Captcha::verify($_POST['captcha'] ?? '')) {
        $error = L_('MESSAGE:MOD_FORM_INCORRECT_CAPTCHA');
    }

    // 2. Your validation
    $message = trim($_POST['message'] ?? '');
    if ($error === '' && $message === '') {
        $error = 'Please enter a message.';
    }

    // 3. Process if valid
    if ($error === '') {
        // … send email, save to DB, etc.
        $success = true;
    }
}
?>

<?php if ($success): ?>
    <p>Thank you!</p>
<?php else: ?>
    <?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
    <form method="post">
        <textarea name="message"></textarea>
        <?php if ($captchaEnabled): ?>
            <?php call_captcha(); ?>
        <?php endif; ?>
        <button type="submit">Send</button>
    </form>
<?php endif; ?>
```

---

## Multiple Captchas on One Page

If a page has more than one form with a captcha, use the `$sec_id` parameter  
to keep the session tokens separate:

```php
// Form 1
call_captcha('all', '', 'contact');

// Form 2
call_captcha('all', '', 'newsletter');
```

Pass the same `$sec_id` to `verify()`:

```php
Captcha::verify($_POST['captcha'] ?? '', 'contact');
Captcha::verify($_POST['captcha'] ?? '', 'newsletter');
```

---

## `call_captcha()` Parameters

```php
call_captcha(
    string  $action        = 'all',    // see table below
    string  $style         = '',       // unused, kept for compatibility
    string  $sec_id        = '',       // suffix for multiple captchas per page
    ?string $type_override = null      // ignored — only ALTCHA is available
);
```

| `$action` value             | Output                                                           |
|-----------------------------|------------------------------------------------------------------|
| `'all'`                     | Full ALTCHA widget (default)                                     |
| `'widget'`                  | Same as `'all'` — no table wrapper                               |
| `'input'`                   | Only the hidden sync `<input>` + JS listener — no visible widget |
| `'text'`                    | Nothing — ALTCHA has its own built-in label                      |
| `'image'`, `'image_iframe'` | Treated as `'all'` (legacy action names)                         |

---

## Honeypot (Advanced Spam Protection)

When **ASP (Advanced Spam Protection)** is enabled in settings, `call_captcha()`  
automatically renders an invisible honeypot field alongside the ALTCHA widget.  
`Captcha::verify()` checks it automatically — no extra code needed.

The honeypot traps bots that fill in every field; real users never see it.

---

## Legacy: `require_once` the old shim

Older modules may have this line:

```php
require_once WB_PATH . '/include/captcha/captcha.php';
```

**Do not use this pattern in new modules.** `call_captcha()` and `Captcha` are  
already available without it. The shim file exists only for backward compatibility  
with existing modules and **must not be deleted** — `require_once` on a missing  
file causes a fatal error regardless of whether the function is already defined.

When updating an existing module, simply remove the `require_once` line.  
Nothing else needs to change.

---

## Settings Reference

All settings are managed via **Tools → Captcha Control** and stored in `{TP}settings`.

| Constant           | Type   | Default  | Description                  |
|--------------------|--------|----------|------------------------------|
| `ENABLED_CAPTCHA`  | bool   | `true`   | Master on/off switch         |
| `ENABLED_ASP`      | bool   | `true`   | Honeypot extra layer         |
| `CAPTCHA_TYPE`     | string | `altcha` | Only valid value is `altcha` |

---

*WBCE CMS — https://wbce.org — GNU GPL2*
