## Allgemein

News with images (kurz: NWI) ermöglicht das Erstellen von News-Seiten bzw. -Beiträgen und bietet u.a. folgende Funktionen:

*   Beitragsbild
*   integrierte Bildergalerie (Masonry oder Fotorama)
*   optionaler 2\. Inhaltsbereich
*   Sortieren von Beiträgen mit Drag & Drop
*   Verschieben/Kopieren von Beiträgen zwischen Gruppen und Abschnitten
*   Import von Beiträgen und Einstellungen aus dem Modul "Topics" sowie "News" ("klassisches Newsmodul" bis Version 3.5.12)

## Download

Das Modul ist ab WBCE CMS 1.4 ein Coremodul und standardmäßig installiert. Darüber hinaus ist der Download im [WBCE CMS Add-On Repository](https://addons.wbce.org) verfügbar.

## Lizenz

NWI steht unter der [GNU General Public License (GPL) v3.0](http://www.gnu.org/licenses/gpl-3.0.html).

## Systemvoraussetzungen

* WBCE v1.4.2 oder neuer
* **PHP 7.1** oder neuer

## Installation/Update

1.  Sofern erforderlich, aktuelle Version aus dem [AOR](https://addons.wbce.org) herunterladen
2.  Wie jedes andere WBCE-Modul auch über Erweiterungen > Module installieren bzw. aktualisieren

## Beiträge erstellen und bearbeiten

### Loslegen und Schreiben

1.  Ggfs. eine neue Seite mit "News with Images" anlegen (Von der Funktionsweise ist es vorgehen, dass pro Seite nur ein "News with Images" Abschnitt vorhanden sein soll).
2.  Um einen neuen Beitrag zu erstellen, auf die Schaltfläche "Beitrag verfassen" klicken. Zum Bearbeiten des Inhalts eines vorhandenen Beitrags auf dessen Titel klicken.
3.  Überschrift und ggf. weitere Felder ausfüllen, ggf. Bilder auswählen. Die Funktion der Eingabefelder ist wohl selbsterklärend.
4.  Auf "Speichern" oder "Speichern und zurück" klicken
5.  Schritte 2\. - 4\. ein paar Mal wiederholen und sich das ganze im Frontend anschauen

Grundsätzlich kann NWI mit anderen Modulen auf einer Seite bzw. in einem Block kombiniert werden, es kann dann aber wie bei jedem Modul, das eigene Detailseiten generiert, zu Ergebnissen kommen, die nicht dem Erwarteten/Erwünschten entsprechen.

### Bilder im Beitrag

Für jeden Beitrag kann ein Beitragsbild hinterlegt werden, das auf der Übersichtsseite und ggfs. der Beitragsseite angezeigt wird. Darüber hinaus ist es möglich, beliebig viele Bilder zu einem Beitrag zu hinterlegen, die als Bildergalerie angezeigt werden. Die Galeriedarstellung erfolgt entweder als Fotorama-Galerie (Thumbnails, Bild über die gesamte Breite) oder als Masonry-Galerie (Bildermosaik).

Welches Galeriescript verwendet wird, wird für alle Beiträge in den Einstellungen des jeweiligen Abschnitts festgelegt.

Die Galeriebilder werden hochgeladen, sobald der Beitrag gespeichert wird, und können dann mit Bildunterschriften versehen, per Drag&Drop umsortiert oder auch wieder gelöscht werden.

Beim Upload von Dateien mit gleichen Namen wie bereits vorhandenen Bildern werden die vorhandenen Dateien nicht überschrieben, sondern bei den nachfolgenden Dateien wird eine fortlaufende Nummerierung ergänzt (bild.jpg, bild_1.jpg usw.)

Die Verwaltung der Bilder erfolgt nur über den Beitrag, nicht über die WBCE-Medienverwaltung, da NWI sonst nicht "weiß", wo welche Bilder hingehören/fehlen usw.

### Gruppen

Beiträge können Gruppen zugeordnet werden. Dies ermöglicht, themenspezifische Übersichtsseiten zu generieren. Diese können dann über die URL der NWI-Seite mit dem Parameter g?=GROUP_ID, also z.B. news.php?g=2 angesteuert werden.

Ein Beitrag kann immer nur einer Gruppe zugeordnet sein.

Einzelne oder mehrere Beiträge können von einer Gruppe in eine andere kopiert und verschoben werden.

### Stichworte

Diese Funktion steht nur zur Verfügung, wenn bei den Einstellungen der "Expertenmodus" aktiviert wurde und Stichworte angelegt wurden.

Beiträge können einem oder mehreren Stichworten zugeordnet werden. Diese Stichworte werden dann je nach Konfiguration im Frontend auf der Beitragsübersicht und/oder der Detailansicht angezeigt und sind jeweils mit der Übersicht aller Beiträge zu diesem Stichwort verlinkt.

Stichworte aus der Beitragsübersicht heraus zentral für alle Beiträge im Abschnitt zur Verfügung gestellt (Reiter "Stichworte") und können dann in der Beitrags-Detailansicht ausgewählt werden.

Globale Stichworte stehen in allen NWI-Abschnitten zur Verfügung, also auch auf anderen Seiten des Auftritts.

### 2\. Block

Diese Funktion steht nur zur Verfügung, wenn bei den Einstellungen der "Expertenmodus" aktiviert wurde und die Verwendung des 2\. Blocks aktiviert ist.

Sofern vom Template unterstützt, können Inhalte in einem zweiten Block (z.B. einer Randspalte) dargestellt werden. Dabei kann es sich entweder um bei den Einstellungen hinterlegte, wiederkehrende Inhalte handeln, beitragsspezifische Inhalte (Beitragsbild, Anreißertext o.ä.) oder direkt im Beitrag hinterlegte Texte, die im Eingabefeld für den 2\. Block eingetragen wurden.

### Importfunktion

So lange noch kein Beitrag im jeweiligen NWI-Abschnitt erstellt wurde, können Beiträge aus anderen NWI-Abschnitten, News 3.x sowie Topics automatisch importiert werden. Die Seiteneinstellungen werden mit übernommen. Beim Import von Topics-Beiträgen sind aber noch manuelle Nacharbeiten erforderlich, sofern bei Topics die "Additional Images"-Funktion genutzt wurde.

### Beiträge kopieren / verschieben

Aus der Beitragsübersicht im Backend heraus können einzelne, mehrere markierte oder alle (markierten) Beiträge innerhalb eines Abschnitts kopiert oder zwischen unterschiedlichen Abschnitten (auch auf unterschiedlichen Seiten) kopiert oder verschoben werden. Kopierte Beiträge sind stets zunächst im Frontend nicht sichtbar (Auswahl Aktiv: "nein").

### Beiträge löschen

Aus der Beitragsübersicht können einzelne, mehrere markierte oder alle (markierten) Beiträge gelöscht werden. Nach der Bestätigung der Rückfrage sind die betreffenden Beiträge unwiderruflich **VERNICHTET**, es gibt **keinen** Papierkorb!

## Einstellungen

### Expertenmodus

Wird der "Expertenmodus" aktiviert, so stehen zusätzliche Eingabefelder bei den Einstellungen (2\. Block), in der Beitragsübersicht (Stichworte) sowie Beitragsdetailansicht (Stichwortzuweisung, 2\. Block) zur Verfügung.

**Achtung:** Beim Wechsel zwischen aktiviertem und deaktivierten Expertenmodus erfolgt jeweils die Rückkehr zur Beitragsübersicht, andere Änderungen an den Einstellungen werden dabei **nicht** gespeichert.

### Übersichtsseite

*   **Sortierung**: Festlegung der Reihenfolge der Beiträge (Benutzerdefiniert = manuelle Festlegung, Beiträge erscheinen so, wie sie im Backend angeordnet werden; Startdatum / Ablaufdatum / eingetragen (=Erstelldatum) / Eintrags-ID: jeweils absteigend nach entsprechendem Kriterium)
*   **Nachrichten pro Seite**: Auswahl, wie viele Einträge (Teaserbild/Text) pro Seite angezeigt werden sollen
*   **Kopfzeile, Beitrag Schleife, Fußzeile**: HTML-Code zur Formatierung der Anzeige
*   **Vorschaubild Größe ändern auf** Breite/Höhe des Bildes in Pixeln. Bei Änderungen erfolgt **keine** automatische Neuberechnung, es ist also sinnvoll, sich im voraus Gedanken über die gewünschte Größe zu machen und dann den Wert nicht mehr zu ändern.  
    Das Beitragsbild steht **nur** in der angegebenen Auflösung zur Verfügung. Soll es in verschiedenen Größen (klein auf der Übersichtsseite, größer auf der Beitragsseite) verwendet werden, die Bildgröße auf den Wert für die größere Darstellung setzen und das Bild auf der Übersichtsseite per CSS verkleinern.

Erlaubte Platzhalter:

#### Kopfzeile/Fußzeile

*   [NEXT_PAGE_LINK] "Nächste Seite", verlinkt zur nächsten Seite (bei Aufteilung der Übersichtsseite auf mehrere Seiten),
*   [NEXT_LINK], "Nächste", s.o.,
*   [PREVIOUS_PAGE_LINK], "Vorherige Seite", s.o.,
*   [PREVIOUS_LINK],"Vorherige", s.o.,
*   [OUT_OF], [OF], "x von y",
*   [DISPLAY_PREVIOUS_NEXT_LINKS] "hidden" / "visible", je nach dem, ob Paginierung erforderlich ist
*   [BACK] URL der News-Übersichtsseite
*   [TEXT_BACK] "Zurück zur Übersicht"

    #### Beitrag Schleife

*   [PAGE_TITLE] Überschrift der Seite,
*   [GROUP_ID] ID der Gruppe, der der Beitrag zugeordnet ist, bei Beiträgen ohne Gruppe "0"
*   [GROUP_TITLE] Titel der Gruppe, der der Beitrag zugeordnet ist, bei Beiträgen ohne Gruppe "",
*   [GROUP_IMAGE] Bild (<img src.../>) der Gruppe, der der Beitrag zugeordnet ist, bei Beiträgen ohne Gruppe "",
*   [DISPLAY_GROUP] _inherit_ oder _none_,
*   [DISPLAY_IMAGE] _inherit_ oder _none_,
*   [TITLE] Titel (Überschrift) des Beitrags,
*   [IMAGE] Beitragsbild (<img src=... />),
*   [SHORT] Kurztext,
*   [LINK] Link zur Beitrags-Detailansicht,
*   [MODI_DATE] Datum der letzten Änderung des Beitrags,
*   [MODI_TIME] Zeitpunkt (Uhrzeit) der letzten Änderung des Beitrags,
*   [CREATED_DATE] Datum, wann der Beitrag erstellt wurde,
*   [CREATED_TIME] Uhrzeit, zu der der Beitrag erstellt wurde,
*   [PUBLISHED_DATE] Startdatum,
*   [PUBLISHED_TIME] Startuhrzeit,
*   [USER_ID] ID des Erstellers des Beitrags,
*   [USERNAME] Benutzername des Erstellers des Beitrags,
*   [DISPLAY_NAME] Anzeigename des Erstellers des Beitrags,
*   [EMAIL] Mailadresse des Erstellers des Beitrags,
*   [TEXT_READ_MORE] "Details anzeigen",
*   [SHOW_READ_MORE], _hidden_ oder _visible_,
*   [GROUP_IMAGE_URL] URL des Gruppen-Bildes,
*   [CONTENT_LONG] Langtext

### Beitragsansicht

*   **Nachrichten-Kopfzeile, -Inhalt, -Fußzeile, Block 2**: HTML-Code zur Formatierung der Anzeige

Erlaubte Platzhalter:

#### Nachrichten-Kopfzeile, Nachrichten-Fußzeile, Block 2

*   [PAGE_TITLE] Überschrift der Seite,
*   [GROUP_ID] ID der Gruppe, der der Beitrag zugeordnet ist, bei Beiträgen ohne Gruppe "0"
*   [GROUP_TITLE] Titel der Gruppe, der der Beitrag zugeordnet ist, bei Beiträgen ohne Gruppe "",
*   [GROUP_IMAGE] Bild (<img src.../>) der Gruppe, der der Beitrag zugeordnet ist, bei Beiträgen ohne Gruppe "",
*   [DISPLAY_GROUP] _inherit_ oder _none_,
*   [DISPLAY_IMAGE] _inherit_ oder _none_,
*   [TITLE] Titel (Überschrift) des Beitrags,
*   [IMAGE] Beitragsbild (<img src=... />),
*   [IMAGE_URL] URL des Beitragsbilds (https://example.com/media/.news_img/filename.jpg),
*   [CONTENT_SHORT] Kurztext,
*   [MODI_DATE] Datum der letzten Änderung des Beitrags,
*   [MODI_TIME] Zeitpunkt (Uhrzeit) der letzten Änderung des Beitrags,
*   [CREATED_DATE] Datum, wann der Beitrag erstellt wurde,
*   [CREATED_TIME] Uhrzeit, zu der der Beitrag erstellt wurde,
*   [PUBLISHED_DATE] Startdatum,
*   [PUBLISHED_TIME] Startuhrzeit,
*   [USER_ID] ID des Erstellers des Beitrags,
*   [USERNAME] Benutzername des Erstellers des Beitrags,
*   [DISPLAY_NAME] Anzeigename des Erstellers des Beitrags,
*   [EMAIL] Mailadresse des Erstellers des Beitrags

#### Nachrichten-Inhalt

*   [CONTENT] kompletter Beitragsinhalt (SHORT+LONG) (HTML)<,/li>
*   [IMAGES] Bilder / Galerie-HTML,
*   [IMAGE_URL] URL des Beitragsbilds (https://example.com/media/.news_img/filename.jpg),
*   [CONTENT_SHORT] Kurztext,
*   [CONTENT_LONG] Langtext

### Galerie-/Bild-Einstellungen

*   **Bildergalerie**: Auswahl des zu verwendenden Galeriescripts. Bitte beachten, dass eventuell vorgenommene individuelle Anpassungen am Galeriecode im Feld Nachrichten-Inhalt bei einer Änderung verloren gehen.
*   **Bild Schleife**: HTML-Code für die Darstellung eines einzelnen Bildes, muss zum jeweiligen Galeriescript passen
*   **Max. Bildgröße in Bytes**: Dateigröße pro Bilddatei, warum das jetzt in Bytes und nicht in lesbareren KB oder MB angegeben werden muss, weiß ich gerade nicht
*   **Galeriebilder / Thumbnailbilder Größe ändern auf Breite x Höhe**: genau selbige. Bei Änderungen erfolgt **keine** automatische Neuberechnung, es ist also sinnvoll, sich im voraus Gedanken über die gewünschte Größe zu machen und dann den Wert nicht mehr zu ändern.
*   **Beschneiden**: Siehe Erläuterung auf der Seite.

### 2\. Block

Optional kann ein 2\. Block angezeigt werden, sofern das Template dies unterstützt. In diesem wird dann entweder der hier hinterlegte Inhalt **oder** beim Beitrag hinterlegter Text angezeigt (beides gleichzeitig ist nicht vorgesehen). Das Eingabefeld wird nur angezeigt, wenn der 2\. Block aktiviert ist.
