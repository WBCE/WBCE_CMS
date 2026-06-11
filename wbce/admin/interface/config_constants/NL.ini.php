;<?php die(); ?>
; ═══════════════════════════════════════════════════════════════════════════════
; WBCE CMS — Bestandsgebaseerde Configuratieconstanten
; Bestand: var/config_constants.ini.php
; ═══════════════════════════════════════════════════════════════════════════════
;
; Dit bestand vervangt de vroegere praktijk om website-specifieke constanten
; rechtstreeks in config.php te definiëren. Tot WBCE 1.6.x werden schakelaars
; zoals TEMPLATE_SWITCHER of WB_DEBUG vaak daar geplaatst — met het risico
; deze instellingen bij een update te verliezen.
;
; Hier gedefinieerde constanten zijn updateveilig. Het systeem leest dit bestand
; bij het opstarten en stelt alle actieve vermeldingen beschikbaar als
; PHP-constanten door de hele applicatie
; (gelijkwaardig aan define('KEY', value) in config.php).
;
; SYNTAX:
;   KEY = value       <- actief
;   ; KEY = value     <- uitgecommentarieerd / uitgeschakeld
;
; Ondersteunde waardetypen:
;   Booleaans  :  true / false
;   Gehele get.:  42
;   Decimalen  :  3.14
;   Tekst      :  Hallo Wereld   (aanhalingstekens alleen nodig bij ; = # of ")
;
; Sleutels moeten in HOOFDLETTERS geschreven worden (A–Z, 0–9, _)
; en beginnen met een letter. Voorbeeld: MIJN_CONSTANTE = true
;
; ─────────────────────────────────────────────────────────────────────────────
; BEKENDE CONSTANTEN  (uitgecommentarieerd — verwijder het puntkomma om te activeren)
; ─────────────────────────────────────────────────────────────────────────────
;
; TEMPLATE_SWITCHER
; Sinds WBCE 1.5.0: Maakt het wisselen van het frontend-template mogelijk via
; de URL-parameter ?template=templatenaam voor de duur van de sessie.
; Standaard: false (switcher inactief)
;
; TEMPLATE_SWITCHER = true
;
; ─────────────────────────────────────────────────────────────────────────────
;
; SHOW_UPDATE_INFO
; Onderdrukt de melding over nieuwe WBCE-versies op het dashboard.
; Ook vereist als de cURL-extensie niet beschikbaar is op de server.
;
; SHOW_UPDATE_INFO = false
;
; ─────────────────────────────────────────────────────────────────────────────
;
; SM2_CORRECT_MENU_LINKS
; Toont in de navigatie en sitemap (Sitemap-module) voor menu-linkvermeldingen
; de werkelijke doel-URL in plaats van de accessfile-link.
;
; SM2_CORRECT_MENU_LINKS = true
;
; ─────────────────────────────────────────────────────────────────────────────
;
; MENU_LINK_TRANSFORMER
; Maakt het mogelijk gewone pagina's om te zetten naar menulinks (en terug)
; via de weergave "Secties beheren" in de beheerdersomgeving.
;
; MENU_LINK_TRANSFORMER = true
;
; ─────────────────────────────────────────────────────────────────────────────
;
; NO_SESSION_COOKIE
; Onderdrukt het frontend-sessiecookie volledig.
; WAARSCHUWING: Ernstige beperkingen — alleen geschikt voor statische informatiesites!
;   - Niet geschikt voor meertalige websites (omleidingsfouten)
;   - Frontend-registratie en -aanmelding niet mogelijk
;   - Geen captcha-ondersteuning in formulieren
;   - Alleen miniform-formulieren worden verwerkt; mpform/form-invoer wordt
;     noch opgeslagen noch verzonden
;   - Beheerders kunnen de onderhoudsmodus niet omzeilen
;
; NO_SESSION_COOKIE = true
;
; ─────────────────────────────────────────────────────────────────────────────
; EIGEN CONSTANTEN — voer hier uw eigen vermeldingen in
; ─────────────────────────────────────────────────────────────────────────────

