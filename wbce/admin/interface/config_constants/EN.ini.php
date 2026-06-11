;<?php die(); ?>
; ═══════════════════════════════════════════════════════════════════════════════
; WBCE CMS — File-Based Configuration Constants
; File: var/config_constants.ini.php
; ═══════════════════════════════════════════════════════════════════════════════
;
; This file replaces the earlier practice of defining site-specific constants
; directly inside config.php. Up to WBCE 1.6.x, switches like TEMPLATE_SWITCHER
; or WB_DEBUG were commonly placed there — at the risk of losing those entries
; on every update.
;
; Constants defined here are safe from updates. The system reads this file at
; startup and makes all active entries available as PHP constants throughout
; the application (equivalent to define('KEY', value) in config.php).
;
; SYNTAX:
;   KEY = value       <- active
;   ; KEY = value     <- commented out / disabled
;
; Supported value types:
;   Booleans  :  true / false
;   Integers  :  42
;   Floats    :  3.14
;   Strings   :  Hello World   (quotes only needed when value contains ; = # or ")
;
; Keys must be written in UPPER_SNAKE_CASE (A-Z, 0-9, _) and start with
; a letter. Example: MY_CONSTANT = true
;
; ─────────────────────────────────────────────────────────────────────────────
; KNOWN CONSTANTS  (commented out — remove the leading semicolon to activate)
; ─────────────────────────────────────────────────────────────────────────────
;
; TEMPLATE_SWITCHER
; Since WBCE 1.5.0: Enables switching the active frontend template via the
; URL parameter ?template=templatename for the duration of the session.
; Default: false (switcher inactive)
;
; TEMPLATE_SWITCHER = true
;
; ─────────────────────────────────────────────────────────────────────────────
;
; SHOW_UPDATE_INFO
; Suppresses the new-version notification on the dashboard.
; Also required when the cURL extension is not available on the server.
;
; SHOW_UPDATE_INFO = false
;
; ─────────────────────────────────────────────────────────────────────────────
;
; SM2_CORRECT_MENU_LINKS
; Makes the navigation and the sitemap (Sitemap module) display the actual
; target URL for menu-link entries instead of the accessfile link.
;
; SM2_CORRECT_MENU_LINKS = true
;
; ─────────────────────────────────────────────────────────────────────────────
;
; MENU_LINK_TRANSFORMER
; Allows converting normal pages into menu links (and back) via the
; "Manage Sections" view in the backend.
;
; MENU_LINK_TRANSFORMER = true
;
; ─────────────────────────────────────────────────────────────────────────────
;
; NO_SESSION_COOKIE
; Suppresses the frontend session cookie entirely.
; WARNING: Severe restrictions apply — suitable only for static information sites!
;   - Not suitable for multilingual websites (redirect errors)
;   - Frontend registration and login not possible
;   - No captcha support in forms
;   - Only miniform forms are processed; mpform/form input is neither
;     saved nor sent
;   - Administrators cannot bypass maintenance mode
;
; NO_SESSION_COOKIE = true
;
; ─────────────────────────────────────────────────────────────────────────────
; CUSTOM CONSTANTS — add your own entries below
; ─────────────────────────────────────────────────────────────────────────────

