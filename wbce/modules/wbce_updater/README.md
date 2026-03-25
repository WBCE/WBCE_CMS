# WBCE Updater

Ein intelligenter Update-Assistent für WBCE CMS mit PHP-Kompatibilitätsprüfung, Sicherheitsfeatures und verbesserter Benutzerführung.

**Version:** 1.0.0
**Kompatibel mit:** WBCE 1.4.x | 1.5.x | 1.6.x

## Features

### 🎯 Intelligente Update-Verwaltung
- **Empfohlene Updates**: Zeigt das höchste Patch-Update als empfohlenes Update an
- **Update-Risiko-Level**: Farbcodierte Badges (Grün=Patch, Gelb=Minor, Rot=Major)
- **Versteckte Updates**: Automatisches Ausblenden von Zwischenversionen mit Anzeige-Option
- **GitHub Integration**: Direkte Anbindung an WBCE GitHub Releases
- **Automatische ZIP-Umpackung**: Intelligente Verarbeitung von GitHub Release-Formaten

### 🔒 Sicherheit
- **CSRF-Schutz**: FTAN-Token-Validierung mit Session-Fallback für WBCE 1.4.x
- **SQL Injection Schutz**: TABLE_PREFIX-Validierung und Escaping
- **XSS-Schutz**: Konsequentes HTML-Escaping aller Ausgaben
- **ZIP-Slip Schutz**: Path-Traversal-Prävention beim Entpacken
- **HTTPS-Erzwingung**: Nur verschlüsselte Verbindungen zu GitHub
- **Domain-Whitelist**: Downloads nur von github.com erlaubt
- **SSL-Verifikation**: Zertifikatsprüfung für externe Requests
- **SHA256 Checksummen**: Optionale Validierung (siehe Konfiguration)

### ✅ PHP-Kompatibilitätsprüfung
- **Dynamische Prüfung**: Lädt Requirements von wbce.org/media mit Fallback auf lokale Datei
- **Pre-Update-Warnung**: Warnt vor inkompatiblen PHP-Versionen (nicht blockierend)
- **EOL-Warnungen**: Hinweise für veraltete PHP-Versionen
- **Visuelle Badges**: Kompatibilitätsstatus direkt in der Update-Liste
- **1-Stunden-Cache**: Optimierte Performance

### 💻 Benutzeroberfläche
- **Loading Spinner**: Visuelles Feedback während Downloads/Uploads
- **Farbcodierte Updates**: Patch (Grün), Minor (Gelb), Major (Rot)
- **Backup-Warnung**: Prominente Erinnerung mit Backup Plus Integration
- **Wartungsmodus**: Optionale Aktivierung während des Updates
- **Detaillierte Changelogs**: Release-Notes direkt im Interface
- **Responsive Design**: Optimiert für Desktop und Mobile

### 📦 Update-Optionen
1. **Automatischer Download**: Direkt von GitHub mit einem Klick
2. **Manueller Upload**: Eigene ZIP-Dateien hochladen mit Checksummen-Anzeige
3. **Wartungsmodus**: Optional aktivierbar während des Updates
4. **Backup-Integration**: Direkter Link zu Backup Plus (falls installiert)

## Checksummen-Validierung

Die SHA256-Checksummen-Validierung ist **standardmäßig deaktiviert**, da aktuell noch keine offiziellen Checksummen-Dateien für WBCE Releases verfügbar sind.

### Aktivierung (wenn Checksummen verfügbar)

In `config_defaults.php`:
```php
define('WBCE_UPDATER_VERIFY_CHECKSUMS', true);
```

Siehe `CHECKSUMS.md` für Details zur Checksummen-Verifikation.

## Installation

1. **Download**: Code von GitHub als ZIP herunterladen
2. **Installation**: Im WBCE Backend unter "Addons" → "Module" installieren
3. **Zugriff**: Über "Admin-Tools" → "WBCE Update-Assistent" aufrufen

## Upgrade

Ein Upgrade wird automatisch erkannt und durchgeführt:
- Cache wird automatisch geleert
- Konfiguration bleibt erhalten
- Kompatibel mit allen WBCE-Versionen (1.4.x - 1.6.x)

## Konfiguration

Zentrale Konfiguration in `config_defaults.php`:

```php
// GitHub API
define('WBCE_UPDATER_GITHUB_API', 'https://api.github.com/repos/WBCE/WBCE_CMS/releases');

// Requirements & Checksums URLs
define('WBCE_UPDATER_REQUIREMENTS_URL', 'https://wbce.org/media/wbce_php_requirements.json');
define('WBCE_UPDATER_CHECKSUMS_URL', 'https://wbce.org/media/checksums.json');

// Cache & Timeouts
define('WBCE_UPDATER_CACHE_DIR', WB_PATH . '/temp');
define('WBCE_UPDATER_CACHE_DURATION', 3600); // 1 Stunde
define('WBCE_UPDATER_HTTP_TIMEOUT', 30);

// Sicherheit
define('WBCE_UPDATER_VERIFY_CHECKSUMS', false); // Deaktiviert bis Checksummen verfügbar
define('WBCE_UPDATER_MAX_UPLOAD_SIZE', 50 * 1024 * 1024); // 50 MB
```

## Systemanforderungen

- **WBCE CMS**: 1.4.x, 1.5.x oder 1.6.x
- **PHP**: 7.2+ (abhängig von WBCE-Version)
- **PHP Extensions**:
  - ZipArchive (für ZIP-Verarbeitung)
  - cURL oder file_get_contents (für Downloads)
  - JSON (für API-Kommunikation)
- **Schreibrechte**: Auf WB_PATH und /temp Verzeichnis
- **Internet**: Zugriff auf api.github.com

## Update-Prozess

1. **Backup erstellen** (empfohlen mit Backup Plus)
2. **Updates prüfen** (automatisch beim Öffnen)
3. **PHP-Kompatibilität prüfen** (automatisch)
4. **Update auswählen** (empfohlen oder andere)
5. **Wartungsmodus aktivieren** (optional)
6. **Download & Installation** mit Live-Feedback
7. **WBCE Update-Script** wird automatisch aufgerufen

## Sicherheitshinweise

- Erstellen Sie **immer ein Backup** vor Updates
- Testen Sie Updates in einer **Testumgebung**
- Prüfen Sie die **PHP-Kompatibilität** vor dem Update
- Bei manuellem Upload: Verifizieren Sie die **Checksummen** manuell
- Aktivieren Sie den **Wartungsmodus** bei Live-Sites

## Troubleshooting

### Updates werden nicht angezeigt
- Prüfen Sie Internetverbindung zu api.github.com
- Cache manuell löschen: `/temp/.wbce_releases_cache.json`
- Browser-Konsole auf JavaScript-Fehler prüfen

### Upload schlägt fehl
- Prüfen Sie `upload_max_filesize` in php.ini (min. 12M empfohlen)
- Prüfen Sie `post_max_size` in php.ini
- Prüfen Sie Schreibrechte auf WB_PATH

### PHP-Kompatibilitätsprüfung zeigt Fehler
- Warnung ist **nicht blockierend** - Update kann trotzdem durchgeführt werden
- Nach Update PHP-Version anpassen gemäß Anforderungen
- Bei Unsicherheit: Erst WBCE aktualisieren, dann PHP-Version ändern

## Entwicklung & Beiträge

**Repository**: https://github.com/Beachbone/wbce_updater

Beiträge sind willkommen! Bitte beachten Sie:
- WBCE Coding Standards
- Kompatibilität mit WBCE 1.4.x - 1.6.x
- Sicherheits-Best-Practices (OWASP Top 10)
- Umfassende Tests auf allen WBCE-Versionen

## Changelog

### Version 1.0.0
- Loading Spinner für Downloads/Uploads
- Höchstes Patch-Update wird als empfohlen angezeigt
- Upload-Button UX verbessert
- Checksummen-Warnung entfernt (da deaktiviert)

### Version 0.9.14
- WBCE 1.4.x Kompatibilität (Session-Fallback)
- Sicherheitsfixes (CSRF, SQL Injection, XSS, ZIP-Slip)
- Zentrale Konfiguration
- Wartungsmodus-Integration

## Lizenz

MIT License

## Autoren

WBCE Community

## Support

- **WBCE Forum**: https://forum.wbce.org/
- **GitHub Issues**: https://github.com/Beachbone/wbce_updater/issues
- **Dokumentation**: Siehe CHECKSUMS.md für Checksummen-Details
