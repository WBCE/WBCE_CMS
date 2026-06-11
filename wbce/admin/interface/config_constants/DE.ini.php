;<?php die(); ?>
; ═══════════════════════════════════════════════════════════════════════════════
; WBCE CMS — Dateibasierte Konfigurationskonstanten
; Datei: var/config_constants.ini.php
; ═══════════════════════════════════════════════════════════════════════════════
;
; Diese Datei ersetzt die frühere Praxis, website-spezifische Konstanten direkt
; in der config.php einzutragen. Bis WBCE 1.6.x wurden Schalter wie
; TEMPLATE_SWITCHER oder WB_DEBUG häufig dort gesetzt — mit dem Risiko, diese
; Einträge bei einem Update zu verlieren.
;
; Hier definierte Konstanten sind updatesicher. Das System liest diese Datei
; beim Start und stellt alle aktiven Einträge als PHP-Konstanten bereit
; (gleichwertig zu define('KEY', value) in der config.php).
;
; SYNTAX:
;   KEY = value       <- aktiv
;   ; KEY = value     <- auskommentiert / deaktiviert
;
; Erlaubte Werttypen:
;   Wahrheitswerte :  true / false
;   Ganzzahlen     :  42
;   Dezimalzahlen  :  3.14
;   Texte          :  Hallo Welt   (Anführungszeichen nur bei ; = # oder " nötig)
;
; Schlüssel müssen in GROSSBUCHSTABEN geschrieben werden (A–Z, 0–9, _)
; und mit einem Buchstaben beginnen. Beispiel: MEINE_KONSTANTE = true
;
; ─────────────────────────────────────────────────────────────────────────────
; BEKANNTE KONSTANTEN  (auskommentiert — Semikolon entfernen zum Aktivieren)
; ─────────────────────────────────────────────────────────────────────────────
;
; TEMPLATE_SWITCHER
; Seit WBCE 1.5.0: Aktiviert den Wechsel des Frontend-Templates über den
; URL-Parameter ?template=templatename für die Dauer der Sitzung.
; Standardwert: false (Switcher inaktiv)
;
; TEMPLATE_SWITCHER = true
;
; ─────────────────────────────────────────────────────────────────────────────
;
; SHOW_UPDATE_INFO
; Unterdrückt den Hinweis auf neue WBCE-Versionen auf dem Dashboard.
; Auch erforderlich, wenn cURL auf dem Server nicht verfügbar ist.
;
; SHOW_UPDATE_INFO = false
;
; ─────────────────────────────────────────────────────────────────────────────
;
; SM2_CORRECT_MENU_LINKS
; Zeigt in der Navigation und der Sitemap (Sitemap-Modul) für Menülink-Einträge
; die tatsächliche Ziel-URL statt des Accessfile-Links an.
;
; SM2_CORRECT_MENU_LINKS = true
;
; ─────────────────────────────────────────────────────────────────────────────
;
; MENU_LINK_TRANSFORMER
; Erlaubt die Umwandlung von normalen Seiten in Menülinks (und zurück) über
; die Ansicht "Abschnitte verwalten" im Backend.
;
; MENU_LINK_TRANSFORMER = true
;
; ─────────────────────────────────────────────────────────────────────────────
;
; NO_SESSION_COOKIE
; Unterdrückt das Frontend-Session-Cookie vollständig.
; ACHTUNG: Starke Einschränkungen — nur für reine Informationswebseiten!
;   - Nicht geeignet für mehrsprachige Websites (Weiterleitungsfehler)
;   - Frontend-Registrierung und -Anmeldung nicht möglich
;   - Kein Captcha-Support in Formularen
;   - Nur miniform-Formulare werden verarbeitet; mpform/form-Eingaben
;     werden weder gespeichert noch versendet
;   - Administratoren können den Wartungsmodus nicht umgehen
;
; NO_SESSION_COOKIE = true
;
; ─────────────────────────────────────────────────────────────────────────────
; EIGENE KONSTANTEN — hier eintragen
; ─────────────────────────────────────────────────────────────────────────────

