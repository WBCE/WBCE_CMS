<?php defined('WB_PATH') or header("Location: ../index.php", true, 301); ?>
<!-- [subject]Ihre Anmeldedaten für {{LOGIN_WEBSITE_TITLE}}[/subject] -->
Hallo {{LOGIN_DISPLAY_NAME}},

Ihre Registrierung auf {{LOGIN_WEBSITE_TITLE}} ist abgeschlossen und Ihre Anmeldedaten lauten:

Benutzername: {{LOGIN_NAME}}
Passwort: {{LOGIN_PASSWORD}}

Hier geht es zum Log-In:
<a href="{{LOGIN_URL}}">{{LOGIN_URL}}</a>


Bei Fragen oder Problemen wenden Sie sich bitte an den Support: <a href="mailto:{{SUPPORT_EMAIL}}">{{SUPPORT_EMAIL}}</a>

-----------------------------------------------
Diese E-Mail wurde automatisch erstellt.
-----------------------------------------------