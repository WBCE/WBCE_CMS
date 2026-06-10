# config_constants templates

Language-specific templates for `var/config_constants.ini.php`.

On first boot after a fresh install or upgrade, `Settings::initFileBasedSettings()`
copies the template matching the installation's `DEFAULT_LANGUAGE` into `var/`.
If no match is found, `EN.ini.php` is used as a fallback.

## Available templates

| File | Language |
|------|----------|
| `EN.ini.php` | English (fallback) |
| `DE.ini.php` | German |
| `NL.ini.php` | Dutch |

## Adding a language

Create `XX.ini.php` where `XX` is the two-letter language code (e.g. `FR`).
The file will be picked up automatically for installations with that default language.

---

# config_constants Templates

Sprachspezifische Vorlagen für `var/config_constants.ini.php`.

Beim ersten Start nach einer Neuinstallation oder einem Upgrade kopiert
`Settings::initFileBasedSettings()` die zur `DEFAULT_LANGUAGE` passende Vorlage
in das `var/`-Verzeichnis. Wird keine passende Datei gefunden, dient `EN.ini.php`
als Fallback.

## Verfügbare Vorlagen

| Datei | Sprache |
|-------|---------|
| `EN.ini.php` | Englisch (Fallback) |
| `DE.ini.php` | Deutsch |
| `NL.ini.php` | Niederländisch |

## Eine Sprache hinzufügen

Erstelle eine Datei `XX.ini.php`, wobei `XX` dem zweistelligen Sprachkürzel entspricht
(z. B. `FR`). Die Datei wird automatisch für Installationen mit dieser Standardsprache
verwendet.

---

# config_constants sjablonen

Taalspecifieke sjablonen voor `var/config_constants.ini.php`.

Bij de eerste opstart na een nieuwe installatie of upgrade kopieert
`Settings::initFileBasedSettings()` het sjabloon dat overeenkomt met de
`DEFAULT_LANGUAGE` van de installatie naar `var/`.
Als er geen overeenkomst wordt gevonden, wordt `EN.ini.php` als terugvaloptie gebruikt.

## Beschikbare sjablonen

| Bestand | Taal |
|---------|------|
| `EN.ini.php` | Engels (terugvaloptie) |
| `DE.ini.php` | Duits |
| `NL.ini.php` | Nederlands |

## Een taal toevoegen

Maak een bestand `XX.ini.php` aan waarbij `XX` de tweeletterige taalcode is (bijv. `FR`).
Het bestand wordt automatisch gebruikt voor installaties met die standaardtaal.
