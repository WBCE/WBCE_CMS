<?php
/**
 * WBCE Updater – Lokale Konfiguration (Vorlage)
 *
 * Um eigene Einstellungen zu hinterlegen:
 * 1. Diese Datei kopieren und die Kopie "user_config.php" nennen
 * 2. Die gewünschten Werte in user_config.php eintragen
 *
 * user_config.php wird NIEMALS automatisch erstellt oder überschrieben.
 * Da sie manuell angelegt wird, ist sie nie in einem Release-ZIP enthalten
 * und überlebt daher alle WBCE- und Modul-Updates automatisch.
 *
 * Diese Datei (user_config.default.php) ist immer im Release-ZIP enthalten
 * und wird bei Updates auf den aktuellen Stand gebracht – eigene Einstellungen
 * daher ausschließlich in user_config.php eintragen, nie hier.
 *
 * @category    module
 * @package     wbce_updater
 */
defined('WB_PATH') or die("This file can't be accessed directly!");

// ============================================================================
// EIGENE UPDATE-QUELLE
// ============================================================================
// Vollständige HTTPS-URL zum eigenen Update-ZIP-Paket.
// Das ZIP muss denselben Aufbau haben wie das offizielle WBCE-Release-Paket
// (also einen "wbce"-Ordner enthalten oder direkt die WBCE-Dateien).
// Leer lassen um ausschließlich die Standard-Quelle (GitHub) zu nutzen.
//
// Beispiel: 'https://example.com/updates/wbce_custom_build.zip'
$wbce_updater_custom_source_url = '';

// ============================================================================
// UPDATE-SPERRE
// ============================================================================
// Auf true setzen, um das Update-Tool für normale Administratoren zu sperren.
// In zukünftigen WBCE-Versionen wird dies durch das erweiterte
// Berechtigungssystem ersetzt und kann hier dann entfernt werden.
$wbce_updater_disabled = false;
