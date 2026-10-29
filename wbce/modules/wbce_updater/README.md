# WBCE Updater

Update-Assistent für WBCE CMS: prüft GitHub auf neue Releases, lädt sie herunter (oder nimmt ein manuell hochgeladenes ZIP entgegen) und bereitet die Installation vor.

**Version:** 1.1.1
**Kompatibel mit:** WBCE 1.4.x – 1.6.x

## Features

- **Update-Erkennung**: GitHub-Releases mit Risiko-Badges (Patch/Minor/Major), empfohlenes Update wird hervorgehoben, Zwischenversionen ausblendbar
- **Zwei Update-Wege**: automatischer Download von GitHub oder manueller ZIP-Upload, jeweils mit Wartungsmodus-Option
- **Individuelles Admin-Verzeichnis**: umbenannte Admin-Ordner werden erkannt und im Update-Paket automatisch berücksichtigt
- **Template-Schutz**: das aktuell aktive Standardtemplate/-theme wird beim Update nicht überschrieben, falls lokal angepasst
- **Umgebungsprüfung**: klare Meldung statt rohem Fehler, wenn `ZipArchive` fehlt oder `allow_url_fopen` deaktiviert ist
- **PHP-Kompatibilitätsprüfung**: nicht-blockierende Warnung samt EOL-Hinweis vor dem Update
- **Sicherheit**: CSRF-Schutz, HTTPS-/Domain-Whitelist für Downloads, Path-Traversal-Schutz beim Entpacken, optionale SHA256-Checksummen-Validierung (siehe `CHECKSUMS.md`)

## Installation

1. ZIP im WBCE-Backend unter „Addons" → „Module" hochladen und installieren
2. Aufruf über „Admin-Tools" → „WBCE Update-Assistent"

Ein Update auf eine neuere Modulversion wird automatisch erkannt; Konfiguration und Cache bleiben erhalten.

## Konfiguration

Zentrale Einstellungen in `config_defaults.php`; dauerhafte, update-sichere Overrides gehören in `user_config.php` (aus `user_config.default.php` kopieren):

```php
define('WBCE_UPDATER_GITHUB_API', 'https://api.github.com/repos/WBCE/WBCE_CMS/releases');
define('WBCE_UPDATER_VERIFY_CHECKSUMS', false); // deaktiviert, bis offizielle Checksummen verfügbar sind
define('WBCE_UPDATER_MAX_UPLOAD_SIZE', 100 * 1024 * 1024); // 100 MB
```

## Systemanforderungen

- WBCE CMS 1.4.x – 1.6.x, PHP 7.2+ (je nach WBCE-Version)
- PHP-Erweiterung `ZipArchive` (zwingend); `allow_url_fopen` nur für den automatischen Download
- Schreibrechte auf `WB_PATH` und `/temp`

## Troubleshooting

- **Updates werden nicht angezeigt**: Internetverbindung zu `api.github.com` prüfen, Cache löschen (`/temp/.wbce_releases_cache.json`), Browser-Konsole auf JS-Fehler prüfen
- **Upload schlägt fehl**: `upload_max_filesize`/`post_max_size` in php.ini (min. 12 MB empfohlen) sowie Schreibrechte auf `WB_PATH` prüfen
- **PHP-Kompatibilitätswarnung**: blockiert das Update nicht — PHP-Version nach dem Update gemäß Anforderungen anpassen

## Entwicklung & Support

- **Repository**: https://github.com/Beachbone/wbce_updater
- **Issues**: https://github.com/Beachbone/wbce_updater/issues
- **Änderungshistorie**: siehe GitHub Releases
- **WBCE Forum**: https://forum.wbce.org/

## Lizenz

MIT License — WBCE Community
