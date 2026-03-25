# Checksum-Verifizierung

## Status

⚠️ **Aktuell deaktiviert** - Es gibt derzeit noch keine offizielle Checksummendatei für WBCE-Releases.

## Warum ist die Checksum-Verifizierung wichtig?

Checksums (SHA256-Hashes) gewährleisten die Integrität heruntergeladener Dateien:
- **Schutz vor Manipulation**: Erkennt, ob eine Datei nach der Veröffentlichung verändert wurde
- **Download-Validierung**: Stellt sicher, dass die Datei vollständig und korrekt heruntergeladen wurde
- **Man-in-the-Middle Schutz**: Verhindert, dass manipulierte Dateien bei unterbrochenen Downloads injiziert werden

## Aktivierung der Checksum-Verifizierung

Sobald offizielle Checksums verfügbar sind, können Sie die automatische Verifizierung aktivieren:

### 1. Konfiguration anpassen

Bearbeiten Sie die Datei `config_defaults.php`:

```php
// Checksum-Verifizierung aktivieren
if (!defined('WBCE_UPDATER_VERIFY_CHECKSUMS')) {
    define('WBCE_UPDATER_VERIFY_CHECKSUMS', true);  // Von false auf true ändern
}
```

### 2. Checksums-Quelle

Die Checksums werden automatisch von folgenden Quellen bezogen:

1. **GitHub API Digest-Feld** (bevorzugt)
   - Seit Juni 2025 stellt GitHub automatisch SHA256-Hashes für Release-Assets bereit
   - Format: `"digest": "sha256:HASH"`

2. **Externe Checksums-Datei** (fallback)
   - URL: `https://wbce.org/media/checksums.json`
   - Format: JSON mit `{"dateiname.zip": "sha256hash"}`

### 3. Testen

Nach der Aktivierung:

1. Führen Sie einen Test-Download durch
2. Die Checksum wird automatisch validiert
3. Bei Fehlanpassung wird der Download abgebrochen

## Manuelle Verifizierung

Bis die automatische Verifizierung aktiviert ist, sollten Sie Checksums manuell prüfen:

### Windows (PowerShell):
```powershell
Get-FileHash -Path "wbceup.zip" -Algorithm SHA256
```

### Linux/Mac:
```bash
sha256sum wbceup.zip
```

### Online:
Vergleichen Sie die Ausgabe mit der offiziellen Checksum aus:
- GitHub Release Notes
- WBCE.org Download-Seite
- Offizielle Announcements

## Format der Checksums-Datei

Die externe Checksums-Datei muss folgendes JSON-Format haben:

```json
{
  "WBCE_CMS-1.6.5.zip": "e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855",
  "WBCE_CMS-1.6.6.zip": "d4735e3a265e16eee03f59718b9b5d03019c07d8b6c51f90da3a666eec13ab35"
}
```

Oder als SHA256SUMS-Textdatei:

```
e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855  WBCE_CMS-1.6.5.zip
d4735e3a265e16eee03f59718b9b5d03019c07d8b6c51f90da3a666eec13ab35  WBCE_CMS-1.6.6.zip
```

## Sicherheitshinweis

⚠️ **Deaktivierte Checksum-Verifizierung reduziert die Sicherheit!**

Ohne Checksum-Verifizierung können Sie nicht sicherstellen, dass:
- Die Datei nicht während des Downloads beschädigt wurde
- Die Datei nicht von Dritten manipuliert wurde
- Sie die authentische WBCE-Version erhalten haben

**Empfehlung**: Aktivieren Sie die Checksum-Verifizierung, sobald offizielle Checksums verfügbar sind.
