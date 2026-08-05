# show_menu2, Version 4.16.0

Ein Code-Snippet für das WBCE CMS. Alle Menüdaten werden mit einer einzigen Datenbankabfrage abgerufen, und alle Arten von Menüstilen (Listen, Breadcrumbs, Sitemaps) können mit umfassender Anpassung des resultierenden HTMLs generiert werden.

---

## Installation

Es ist keine Installation erforderlich, da es sich bei dem Modul um ein integriertes Core-Modul handelt.

---

## Verwendung von `show_menu()` (und `show_menu2()`)



Die PHP-Dateien des Templates müssen so angepasst werden, dass `show_menu()` dort aufgerufen wird, wo das Menü angezeigt werden soll. 


> **Hinweis:** Seit der Version 4.16.0 kann wieder der Aufruf `show_menu()` verwendet werden – Es gab schon früher eine show_menu() Funktion, aber diese ist nun jedoch ein direkter Alias für `show_menu2()` mit identischer Signatur. Beide Namen können austauschbar verwendet werden; `show_menu()` ist lediglich die kürzere Schreibweise.

Passend dazu hat jede `SM_*`-Konstante (`SM_ROOT`, `SM_TRIM` usw.) automatisch eine äquivalente `SM2_*`-Variante – beide Präfixe sind austauschbar, z. B. `SM_TRIM|SM_USE_ARIA == SM2_TRIM|SM2_USE_ARIA`.

> In diesem README Dokument werden wir durchgehend die neu eingeführte `show_menu()` Schreibweise verwenden.

## Styling
In manchen Fällen ist das von `show_menu()` generierte Standardmenü bereits alles, was Sie benötigen. Dieses Menü zeigt die aktuelle Seite und die untergeordneten Seiten der aktuellen Seite an. Es wird durch einen einfachen Aufruf von `show_menu()` ohne Parameter generiert:

```php
show_menu();
```

Beachten Sie, dass der Aufruf von `show_menu()` PHP-Code ist, sodass Sie ihn in der Regel in PHP-Code-Tags einschließen müssen, damit er ausgeführt wird:

```php
<?php show_menu(); ?>
```

Dieses Standardmenü generiert ein vollständiges listenbasiertes Menü mit vielen Klassen, die ein CSS-Styling ermöglichen. Das aktuelle Menüelement erhält beispielsweise die Klasse `menu-current` im `<li>`-Tag. Zusätzlich erhält jedes Menüelement mit einem Untermenü die Klasse `menu-expand` im `<li>`-Tag. Dadurch können Sie CSS-Regeln erstellen, um diese Menüelemente unterschiedlich zu gestalten:

```css
li.menu-expand  { font-weight: bold; }
li.menu-current { background: red; }
```

Siehe den Abschnitt [HTML-Ausgabe](#html-ausgabe) für Details darüber, welche Klassen genau zu jedem Element hinzugefügt werden. Aufwendigere und unterschiedliche Menüstrukturen können durch die Übergabe verschiedener Parameter an den `show_menu()`-Funktionsaufruf erstellt werden.

Um beispielsweise nur Menüelemente der obersten Ebene des Menüs anzuzeigen, verwenden Sie:

```php
show_menu(0, SM_ROOT, SM_START);
```

Alternativ, um bis zu zwei Ebenen der Untermenüs der aktuellen Seite anzuzeigen:

```php
show_menu(0, SM_CURR+1, SM_CURR+2);
```

Es gibt noch viele weitere mögliche Menüs, die mit `show_menu()` generiert werden können. Weitere Beispiele finden Sie auf der Demonstrations-Website unter [https://sm2.wbce-cms.org/](https://sm2.wbce-cms.org/).

---

## Häufig gestellte Fragen

**F: Wie erstelle ich ein Dropdown-Menü?**  
A: Dies steht nicht in direktem Zusammenhang mit `show_menu()`. Im Grunde müssen Sie den CSS-Code des Templates ändern, um das Menü als Dropdown anzuzeigen.

**F: Warum verschwindet das Menü, nachdem ich auf meiner mehrsprachigen Website eine Suche durchführe?**  
A: In Ihrem Template fehlen einige erforderliche Zeilen:
1. Melden Sie sich in der Administration an und gehen Sie zu **Optionen -> Erweiterte Optionen anzeigen -> Sucheinstellungen -> Header-Code** und fügen Sie das folgende Eingabefeld nach dem öffnenden `<form>`-Tag ein:
   ```html
   <input type="hidden" name="referrer" value="[REFERRER_ID]" />
   ```
2. Fügen Sie in der `index.php` Ihres Templates das folgende Eingabefeld unmittelbar nach dem öffnenden Such-`<form>`-Tag ein:
   ```php
   <input type="hidden" name="referrer" value="<?php echo defined('REFERRER_ID')?REFERRER_ID:PAGE_ID;?>" />
   ```

**F: Mehrsprachig? Das klingt gut. Wie mache ich das?**  
A: Siehe [https://addons.wbce.org/pages/addons.php?do=item&item=3](https://addons.wbce.org/pages/addons.php?do=item&item=3).

**F: `show_menu()` erzeugt bei jedem Seitenzugriff eine Warnung: `"show_menu error: $aOptions is invalid. No flags from group 1 supplied!"`**  
A: Sie übergeben falsche Werte an die Funktion. Schauen Sie sich die Parameter, die Sie übergeben, genauer an. Siehe den Abschnitt [Parameter](#parameter) unten für die korrekten Flag-Werte, die für den Parameter `$aOptions` übergeben werden müssen.

**F: Wie verwende ich eine unterschiedliche Klasse / ein unterschiedliches Bild / eine unterschiedliche Farbe / ein unterschiedliches Widget für jeden Eintrag in einem Menü?**  
A: Verwenden Sie den Format-String `[page_id]` im String `$aItemOpen`. Erstellen Sie eine eindeutige Klasse oder ID für jedes Menüelement und verweisen Sie dann in Ihrem CSS oder JavaScript auf dieses Element, um das Gewünschte umzusetzen.

*So fügen Sie eine eindeutige Klasse für jedes Menüelement (oder ähnlich) hinzu:*
```php
"<li><a href="[url]" target="[target]" class="[class] p[page_id]">[menu_title]</a>"
```
*Erzeugt Menüelemente wie:*
```html
<li><a href="/pages/foo/bar.php" target="_top" class="menu-top p45">Hauptmenü</a>
```
*Verweisen Sie darauf in Ihrem CSS wie folgt:*
```css
a.p45 { color: red; }
```

*So fügen Sie eine eindeutige ID für jedes Menüelement (oder ähnlich) hinzu:*
```php
"<li><a id="p[page_id]" href="[url]" target="[target]" class="[class]">[menu_title]</a>"
```
*Erzeugt Menüelemente wie:*
```html
<li><a id="p45" href="/pages/foo/bar.php" target="_top" class="menu-top">Hauptmenü</a>
```
*Verweisen Sie darauf in Ihrem CSS wie folgt:*
```css
a#p45 { color: red; }
```
> **Hinweis:** Die ID kann nur verwendet werden, wenn das Menü **nur einmal** auf der Seite generiert und angezeigt wird (da HTML-IDs auf einer Seite eindeutig sein müssen).

---

## Funktion

Die vollständige Aufrufsignatur und die Standardparameterwerte für `show_menu()` lauten:

```php
show_menu(
    $aMenu         = 0,
    $aStart        = SM_ROOT,
    $aMaxLevel     = SM_CURR+1,
    $aOptions      = SM_TRIM,
    $aItemOpen     = '[li][a][menu_title]</a>',
    $aItemClose    = '</li>',
    $aMenuOpen     = '[ul]',
    $aMenuClose    = '</ul>',
    $aTopItemOpen  = false,
    $aTopMenuOpen  = false
)
```

Siehe den Abschnitt [Parameter](#parameter) für detaillierte Beschreibungen jedes Parameters. Stellen Sie sicher, dass Sie jeden Parameter korrekt verwenden:
* `$aMenu` wird für die meisten Benutzer `0` sein.
* `$aStart` muss entweder eine Seiten-ID oder ein Wert sein, der mit `SM_` beginnt.
* `$aMaxLevel` darf nur Werte enthalten, die mit `SM_` beginnen.
* `$aOptions` darf nur Werte enthalten, die mit `SM_` beginnen (außer Sie gehören zu einer sehr kleinen Minderheit von Benutzern).
* Alle anderen Parameter sind die HTML-Tag-Templates, die für Menüs und Menüelemente ausgegeben werden.

> **Hinweis:** Jeder Parameter ab `$aItemOpen` kann als `false` übergeben werden, um den Standardwert zu verwenden.

---

## HTML-Ausgabe

Das Menü wird je nach den an die Funktion übergebenen Parametern unterschiedlich ausgegeben. Im Allgemeinen werden jedoch die folgenden Klassen für jedes Menü verwendet. Beachten Sie, dass Elemente bei Relevanz mehrere Klassen besitzen können.

| Klasse | Angehängt an |
| :--- | :--- |
| `menu-top` | Nur das erste Menü-Tag |
| `menu-parent` | Jedes übergeordnete Menüelement der aktuellen Seite |
| `menu-current` | Nur das Menüelement für die aktuelle Seite |
| `menu-sibling` | Jedes Geschwisterelement der aktuellen Seite |
| `menu-child` | Jedes Untermenü der aktuellen Seite |
| `menu-expand` | Jedes Menüelement mit untergeordneten Elementen |
| `menu-first` | Erstes Element in jedem Menü oder Untermenü |
| `menu-last` | Letztes Element in jedem Menü oder Untermenü |

Die folgenden Klassen werden nur hinzugefügt, wenn das Flag `SM_NUMCLASS` verwendet wurde:

| Klasse | Angehängt an |
| :--- | :--- |
| `menu-N` | Jedes Menüelement. Das `N` wird durch die **absolute** Menütiefe des Elements ersetzt, beginnend bei 0. Das Menü der obersten Ebene ist immer `menu-0`, die nächste Ebene ist `menu-1` usw. |
| `menu-child-N` | Jedes Untermenü der aktuellen Seite. Das `N` wird durch die relative Tiefe des Untermenüs ersetzt, beginnend bei 0. |

### Strukturbeispiel

```html
<ul class="menu-top menu-0">
  <li class="menu-0 menu-first">  ... </li>
  <li class="menu-0 menu-expand menu-parent">  ...
  <ul class="menu-1">
    <li class="menu-1 menu-expand menu-first">  ...
    <ul class="menu-2">
      <li class="menu-2 menu-first">  ...
      <li class="menu-2 menu-last">  ...
    </ul>
    </li>
    <li class="menu-1 menu-expand menu-parent">  ...
    <ul class="menu-2">
      <li class="menu-2 menu-expand menu-current menu-first">  ...      <!-- AKTUELLE SEITE -->
      <ul class="menu-3">
        <li class="menu-3 menu-child menu-child-0 menu-first">  ...
        <ul class="menu-4">
          <li class="menu-4 menu-child menu-child-1 menu-first">  ... </li>
          <li class="menu-4 menu-child menu-child-1 menu-last">  ... </li>
        </ul>
        </li>
        <li class="menu-3 menu-child menu-child-0 menu-last">  ... </li>
      </ul>
      </li>
      <li class="menu-2 menu-sibling menu-last">  ... </li>
    </ul>
    </li>
    <li class="menu-1">  ... </li>
    <li class="menu-1 menu-expand menu-last">  ...
    <ul class="menu-2">
      <li class="menu-2 menu-first menu-last">  ... </li>
    </ul>
    </li>
  </ul>
  </li>
  <li class="menu-0 menu-last">  ... </li>
</ul>
```

---

## Parameter

### `$aMenu`
Nummer des zu verwendenden Menüs. Dies ist nützlich, wenn Sie mehrere Menüs verwenden. Die Übergabe einer Menünummer von `0` verwendet das Standardmenü für die aktuelle Seite. Die Übergabe von `SM_ALLMENU` gibt alle Menüs im System zurück.

### `$aStart`
Gibt an, wo die Menügenerierung beginnen soll. Dies ist meistens das übergeordnete Element des anzuzeigenden Menüs. Es muss einer der folgenden Werte sein:

* `SM_ROOT+N`: Beginne `N` Ebenen unterhalb der Wurzel (Root). Z. B.:
  * `SM_ROOT`: Beginnend beim Hauptmenü (Root)
  * `SM_ROOT+1`: Beginne 1 Ebene unterhalb der Wurzel
  * `SM_ROOT+2`: Beginne 2 Ebenen unterhalb der Wurzel
* `SM_CURR+N`: Beginne `N` Ebenen unterhalb der aktuellen Seitenebene. Z. B.:
  * `SM_CURR`: Beginnt auf der aktuellen Seitenebene (alle Geschwistermenüs zur aktuellen Seite)
  * `SM_CURR+1`: Beginnt 1 Ebene unterhalb der aktuellen Seite mit den untergeordneten Menüs
* `page_id`: Zeige unter Verwendung der spezifischen Seite als übergeordnetes Element an. Alle untergeordneten Menüs dieser Seite werden angezeigt. Die `page_id` ist beim Bearbeiten der Seite in der Administrationsoberfläche zu finden (in der URL enthalten, z. B. `http://SITE/admin/pages/modify.php?page_id=35`).

### `$aMaxLevel`
Maximale anzuzeigende Menüebene. Menüs werden von der Startebene bis zu dieser Ebene angezeigt:

* `SM_ALL`: Keine Begrenzung, alle Ebenen werden angezeigt.
* `SM_CURR+N`: Zeige immer bis zur aktuellen Seite + `N` Ebenen an:
  * `SM_CURR`: Aktuelle Ebene (keine untergeordneten Elemente)
  * `SM_CURR+3`: Alle übergeordneten + aktuelle Seite + 3 untergeordnete Ebenen
* `SM_START+N`: Zeige immer ab der Startebene + `N` Ebenen an, unabhängig davon, auf welcher Ebene sich die aktuelle Seite befindet:
  * `SM_START`: Einzelne Menüebene ab der Startebene
  * `SM_START+1`: Startebene und 1 Ebene darunter
* `SM_MAX+N`: Zeige höchstens `N` Ebenen ab der Startebene an. Ebenen werden nicht angezeigt, wenn sie unterhalb der aktuellen Ebene liegen:
  * `SM_MAX`: Nur Startebene (entspricht `SM_START`)
  * `SM_MAX+1`: Maximal Startebene und 1 Ebene darunter

### `$aOptions`
Gibt Flags für verschiedene Generierungsoptionen des Menüs an. Die Flags können mittels bitweisem OR (`|`) kombiniert werden. Beispiel: `(SM_TRIM | SM_PRETTY)`.

#### Gruppe 1 (Erforderlich)
**Es muss immer genau ein Flag aus dieser Gruppe angegeben werden.** Diese Flags beeinflussen, wie Geschwisterelemente im Baum aus der Ausgabe entfernt werden:

* `SM_ALL`: Zeige alle Zweige des Menübaums an.
  ```text
  A-1 -> B-1 
      -> B-2 -> C-1
             -> C-2 (AKTUELL)
                -> D-1
                -> D-2
             -> C-3
  A-2 -> B-3
      -> B-4
  ```
* `SM_TRIM`: Zeige alle Geschwistermenüs von Seiten auf dem aktuellen Pfad an. Alle Untermenüs von Elementen, die sich nicht auf dem Pfad befinden, werden entfernt.
  ```text
  A-1 -> B-1 
      -> B-2 -> C-1
             -> C-2 (AKTUELL)
                -> D-1
                -> D-2
             -> C-3
  A-2 
  ```
* `SM_CRUMB`: Zeige nur den Breadcrumb-Pfad an (das aktuelle Menü und alle seine übergeordneten Menüs).
  ```text
  A-1 -> B-2 -> C-2 (AKTUELL)
  ```
* `SM_SIBLING`: Entspricht `SM_TRIM`, jedoch werden nur Geschwistermenüs der aktuellen Seite angezeigt. Alle anderen Menüs werden gekürzt, um nur den Pfad anzuzeigen.
  ```text
  A-1 -> B-2 -> C-1
             -> C-2 (AKTUELL)
                -> D-1
                -> D-2
             -> C-3
  ```

#### Gruppe 2 (Optional)
Eine beliebige Anzahl dieser optionalen Flags kann kombiniert werden:

* `SM_NUMCLASS`: Fünge die nummerierten Menüklassen (`menu-N` und `menu-child-N`) hinzu.
* `SM_ALLINFO`: Lade alle Felder aus der Seitentabelle der Datenbank. Dies führt zu einer gewissen Speicherbelastung und wird nicht empfohlen, stellt jedoch Schlüsselwörter, Beschreibungen und andere Felder zur Verfügung. Diese Daten werden standardmäßig nicht geladen.  
  > **Hinweis:** Dieses Flag muss beim *ERSTEN* Aufruf von `show_menu()` für diese Menü-ID oder in Kombination mit `SM_NOCACHE` verwendet werden, andernfalls hat es keine Wirkung.
* `SM_NOCACHE`: Die aus der Datenbank gelesenen Daten zwischen Aufrufen von `show_menu()` nicht wiederverwenden oder speichern.
* `SM_PRETTY`: Formatiere das Menü-HTML mit Einrückungen und Zeilenumbrüchen zu Debugging-Zwecken ("Pretty Print").
* `SM_BUFFER`: Gieb das Menü-HTML nicht direkt aus, sondern puffere es intern und gib es als String von `show_menu()` zurück.
* `SM_CURRTREE`: Schließe alle anderen Menüs der obersten Ebene von der Berücksichtigung aus. Es werden nur Elemente im aktuellen Menübaum ausgegeben. Kann nach Bedarf mit jedem der Gruppe-1-Flags kombiniert werden.
* `SM_ESCAPE`: Rufe `htmlspecialchars` für die Menü-Strings auf. Dies kann bei älteren Installationen erforderlich sein. Durch das Maskieren der Rohdatenbank-Strings können Menüs HTML-Formatierungen enthalten, die andernfalls dazu führen würden, dass Seiten die Validierung nicht bestehen.
* `SM_SHOWHIDDEN`: Versteckte Seiten sind normalerweise immer ausgeblendet, auch wenn sie aktiv sind (d. h. aktuelle Seite oder eine übergeordnete Seite). Verwenden Sie private Seiten für Zeiten, in denen Seiten außer bei Aktivität verborgen bleiben sollen. Um jedoch die Kompatibilität mit Release 4.8 zu gewährleisten, übergeben Sie dieses Flag, damit versteckte Seiten sichtbar werden, wenn sie aktiv sind.
* `SM_XHTML_STRICT`: Von allen Links, die durch `[a]` oder `[ac]` erstellt werden, wird das `target`-Attribut entfernt, um die XHTML-Kompatibilität zu wahren.
* `SM_NO_TITLE`: Unterdrücke den Wert des `title`-Attributs bei Links, die durch `[a]` oder `[ac]` formatierte Links erstellt werden.
* `SM_EXTERNAL_MENULINKS`: Löse für ein "menu_link"-Menüelement (siehe Modul `menu_link`) die externe Ziel-URL direkt auf, wenn dieses Element mit "Extern" als Linktyp konfiguriert wurde. Ohne dieses Flag läuft das Anklicken des Elements zuerst über die Zugriffsdatei-Stummelseite des `menu_link`-Moduls, die dann weiterleitet (301/302) – mit dem Flag wird die externe URL direkt ins Menü eingefügt, ohne dass ein Weiterleitungsschritt erforderlich ist. Interne `menu_link`-Ziele werden unabhängig von diesem Flag IMMER automatisch aufgelöst. Ein `menu_link`-Eintrag kann auch als reiner Strukturknoten konfiguriert werden ("Nur Struktur, kein Link"): Das Element erhält dann `href="#"`, ist also nicht anklickbar, seine untergeordneten Elemente werden aber weiterhin normal angezeigt.
* `SM_USE_ARIA`: Befüllt den Platzhalter `[aria]` (und fügt ihn bei den integrierten Tags `[a]` / `[ac]` automatisch ein) mit ARIA-Attributen für Screenreader und andere assistive Technologien:
  * `aria-current="page"` auf dem aktuellen Menüelement
  * `aria-haspopup="true"` auf Elementen mit untergeordneten Seiten
  * `aria-expanded="true"/"false"` je nachdem, ob die untergeordneten Elemente in diesem speziellen Aufruf tatsächlich gerendert werden
  Ohne dieses Flag bleibt `[aria]` leer – keine Verhaltensänderung zu früher.

> Dieser Parameter bietet auch einen erweiterten Modus, in dem ein assoziatives Array von Optionen übergeben wird. Siehe Abschnitt [Erweiterte Optionen](#erweiterte-optionen) für Details.

### `$aItemOpen`
Format-String zur Erstellung jedes einzelnen Menüeintrags. Für den allerersten Eintrag kann ein anderer Format-String verwendet werden, indem ein abweichender Format-String für `$aTopItemOpen` übergeben wird. Wenn auf `false` gesetzt, wird der Standardwert `'[li][a][menu_title]</a>'` verwendet, um die Kompatibilität zu wahren. Beachten Sie jedoch, dass die CSS-Formatierung oft einfacher ist, wenn die Klassen zum `<a>`-Tag hinzugefügt werden. Verwenden Sie den Format-String `'<li>[ac][menu_title]</a>'` für diesen Tag-Stil.

Dieser Parameter kann auch als Instanz einer Formatierungsklasse für das Menü angegeben werden. Siehe Abschnitt [Formatter](#formatter) unten für Details zur API, die diese Klasse bereitstellen muss. Wenn ein Formatter übergeben wird, werden alle Argumente nach `$aItemOpen` ignoriert.

### `$aItemClose`
String zum Schließen jedes Elements (Standard: `'</li>'`). Beachten Sie, dass dies kein Format-String ist und keine Schlüsselwörter ersetzt werden. Wenn auf `false` gesetzt, wird der Standardwert verwendet.

### `$aMenuOpen`
Format-String zum Öffnen einer Liste von Menüeinträgen (Standard: `'[ul]'`). Für das allererste Menü kann ein anderer Format-String verwendet werden, indem ein abweichender Format-String für `$aTopMenuOpen` übergeben wird. Wenn auf `false` gesetzt, wird der Standardwert verwendet.

### `$aMenuClose`
String zum Schließen jedes Menüs (Standard: `'</ul>'`). Beachten Sie, dass dies kein Format-String ist und keine Schlüsselwörter ersetzt werden. Wenn auf `false` gesetzt, wird der Standardwert verwendet.

### `$aTopItemOpen`
Format-String für das erste Element. Wenn auf `false` gesetzt, wird dasselbe Format wie für `$aItemOpen` verwendet.

### `$aTopMenuOpen`
Format-String für das erste Menü. Wenn auf `false` gesetzt, wird dasselbe Format wie für `$aMenuOpen` verwendet.

---

## Erweiterte Optionen

Der Parameter `$aOptions` ist ein Parameter mit dualem Modus. Für die meisten Benutzer reichen die `SM_*`-Flags aus. Um jedoch auf die zusätzlichen Optionen zuzugreifen, muss er als assoziatives Array übergeben werden. Beachten Sie, dass die `SM_*`-Flags weiterhin erforderlich sind und unter dem Schlüssel `'flags'` übergeben werden müssen.

* `'flags'` (**ERFORDERLICH**): Dies sind die oben im Abschnitt PARAMETER beschriebenen Flags für den Parameter `$aOptions`.
* `'notrim'`: Geben Sie eine Anzahl von Ebenen relativ zur Menüebene von `$aStart` an, die immer angezeigt werden sollen. Dies führt dazu, dass das Flag `SM_TRIM` für diese Ebenen ignoriert wird.

Um eine dieser Optionen zusätzlich zu den Flags zu übergeben, sollte das Options-Array erstellt und als Parameter `$aOptions` übergeben werden:

```php
$options = array('flags' => (SM_TRIM | SM_PRETTY), 'notrim' => 1);
show_menu(0, SM_ROOT, SM_CURR+1, $options);
```

---

## Format-Strings

Die folgenden Tags können in den Format-Strings für `$aItemOpen` und `$aMenuOpen` enthalten sein und werden durch den entsprechenden Text ersetzt:

| Tag | Ersetzung |
| :--- | :--- |
| `[a]` | `<a>`-Tag (ohne Klasse): `<a href="[url]" target="[target]">` |
| `[ac]` | `<a>`-Tag inklusive Klasse: `<a href="[url]" target="[target]" class="[class]">` |
| `[li]` | `<li>`-Tag inklusive Klasse: `<li class="[class]">` |
| `[ul]` | `<ul>`-Tag inklusive Klasse: `<ul class="[class]">` |
| `[class]` | Liste der Klassen für diese Seite |
| `[menu_title]` | Menütitel-Text (HTML-Entity-maskiert, außer das Flag `SM_NOESCAPE` wird verwendet) |
| `[menu_icon_0]`| URL zu einem Bild für die normale Anzeige (Status Normal) |
| `[menu_icon_1]`| URL zu einem Bild für die Anzeige bei Aktivität/Hover (Status Aktiv/Hover) |
| `[page_title]` | Seitentitel-Text (HTML-Entity-maskiert, außer das Flag `SM_NOESCAPE` wird verwendet) |
| `[page_icon]`  | URL zu einem Bild bezüglich der aktuellen Seite |
| `[url]` | Seiten-URL für das `<a>`-Tag |
| `[target]` | Seitenziel (Target) für das `<a>`-Tag |
| `[page_id]` | Seiten-ID des aktuellen Menüelements |
| `[parent]` | Seiten-ID des übergeordneten Menüelements |
| `[level]` | Seitenebene, dieselbe Nummer, die für das CSS-Tag "menu-N" verwendet wird |
| `[sib]` | Aktuelle Geschwisternummer des Menüs |
| `[sibCount]` | Gesamtzahl der Geschwisterelemente in diesem Menü |
| `[aria]` | ARIA-Attribute für Screenreader, z. B. `aria-current="page"`. Wird nur befüllt, wenn das Flag `SM_USE_ARIA` verwendet wird (siehe Abschnitt PARAMETER), andernfalls leer. Bei `[a]`/`[ac]` automatisch eingefügt; in eigenen manuellen `<a>`-Tags platzieren Sie `[aria]` manuell dort, wo es hingehört, z. B.: `'<li><a href="[url]" [aria]>[menu_title]</a></li>'` |
| `[if]` | Bedingte Prüfung (siehe Abschnitt BEDINGTE FORMATIERUNG) |

### Verfügbar bei Verwendung des Flags `SM_ALLINFO`

| Tag | Ersetzung |
| :--- | :--- |
| `[description]` | Seitenbeschreibung (Description) |
| `[keywords]` | Seitenschlüsselwörter (Keywords) |

---

## Bedingte Formatierung

Die Anweisung zur bedingten Formatierung hat eine der folgenden Formen:

```text
[if(A){B}]
[if(A){B}else{C}]
```

* **A**: Bedingte Prüfung. Siehe unten für weitere Details.
* **B**: Ausdruck, der ausgegeben wird, wenn die if-Prüfung wahr (true) ist. Dies kann ein beliebiger String sein, der das Zeichen `}` NICHT enthält. Er kann jeden der im Abschnitt FORMAT-STRINGS beschriebenen Format-Strings enthalten, mit Ausnahme der bedingten Prüfung (da `}` nicht erlaubt ist).
* **C**: Ausdruck, der ausgegeben wird, wenn die if-Prüfung falsch (false) ist. Dies kann ein beliebiger String sein, der das Zeichen `}` NICHT enthält. Er kann jeden der im Abschnitt FORMAT-STRINGS beschriebenen Format-Strings enthalten, mit Ausnahme der bedingten Prüfung (da `}` nicht erlaubt ist).

Die bedingte Prüfung ist eine Kombination aus einem oder mehreren booleschen Tests. Wenn mehr als ein Test angegeben wird, muss er mit anderen Tests unter Verwendung von entweder `||` (boolesches OR) oder `&&` (boolesches AND) kombiniert werden.

Ein einzelner Test besteht aus dem linken Operanden, dem Operator und dem rechten Operanden (z. B. `X == Y`, wobei `X` der linke Operand, `==` der Operator und `Y` der rechte Operand ist).

### Linker Operand
Muss eines der folgenden Schlüsselwörter sein:
* `class`: Test auf Existenz einer der Klassen. Nur die Operatoren `==` und `!=` sind erlaubt. In diesem Fall haben diese Operatoren die Bedeutung von "enthält" statt "ist gleich".
* `level`: Test gegen die Seitenebene.
* `sib`: Test gegen die aktuelle Geschwisternummer der Seite.
* `sibCount`: Test gegen die Anzahl der Geschwisterelemente im Menü.
* `id`: Test gegen die Seiten-ID.
* `target`: Test gegen das Target-Attribut.

### Operator
Muss einer der folgenden sein:
* `<`: Kleiner als
* `<=`: Kleiner gleich
* `==`: Gleich
* `!=`: Nicht gleich
* `>=`: Größer gleich
* `>`: Größer als

### Rechter Operand
Der Typ dieses Operanden hängt vom Schlüsselwort ab, das für den linken Operanden verwendet wird:
* Für `class`: Einer der `"menu-*"`-Klassennamen wie im Abschnitt "HTML-AUSGABE" aufgelistet.
* Für `level`: Testet die Seitenebene gegen folgende Werte:
  * `<Nummer>`: Absolute Seitenebene
  * `root`: Die Hauptmenü-Ebene (Root)
  * `granny`: Die Großeltern-Seitenebene
  * `parent`: Die übergeordnete Seitenebene
  * `current`: Die aktuelle Seitenebene
  * `child`: Die untergeordnete Seitenebene
* Für `id`: Testet die Seiten-ID gegen folgende Werte:
  * `<Nummer>`: Absolute Seiten-ID
  * `parent`: Die übergeordnete Seiten-ID
  * `current`: Die aktuelle Seiten-ID
* Für `sib`: Eine positive ganze Zahl oder `sibCount`, um gegen die Anzahl der Geschwisterelemente in diesem Menü zu testen.
* Für `sibCount`: Eine positive ganze Zahl.
* Für `target`: Ein String, der ein mögliches Target enthält.

### Beispiele

Gültige Tests, bei denen der Ausdruck "exp" nur ausgegeben wird, wenn das Menüelement:

```text
[if(class==menu-expand){exp}]            Ein Untermenü hat
[if(class==menu-first){exp}]             Das erste Element in einem Menü ist
[if(class!=menu-first){exp}]             NICHT das erste Element in einem Menü ist
[if(class==menu-last){exp}]              Das letzte Element in einem Menü ist
[if(level==0){exp}]                      Sich auf der Hauptmenü-Ebene (Root) befindet
[if(level>0){exp}]                       Sich nicht auf der Hauptmenü-Ebene befindet
[if(sib==2){exp}]                        Das zweite Element in einem Menü ist
[if(sibCount>1){exp}]                    Sich in einem Menü mit mehr als 1 Eintrag befindet
[if(sibCount!=2){exp}]                   Sich in einem Menü befindet, das nicht genau 2 Einträge hat
[if(level>parent){exp}]                  Sich in einem Geschwistermenü oder Untermenü eines Geschwisterelements befindet
[if(id==parent){exp}]                    Das übergeordnete Element der aktuellen Seite ist
[if(target==_self){exp}]                 Wenn der Wert des Target-Attributs '_self' ist
```

Wenn eine else-Klausel hinzugefügt wurde, wird der Ausdruck für das else in allen anderen Fällen ausgegeben. Zum Beispiel wird der Ausdruck "foo" immer dann ausgegeben, wenn die if-Prüfung falsch ist:

```text
[if(sib==2){exp}else{foo}]               NICHT das zweite Element in einem Menü ist
[if(sibCount>2){exp}else{foo}]           NICHT in einem Menü mit mehr als 2 Einträgen ist
```

Bei mehreren Tests wird der Ausdruck "exp" nur ausgegeben, wenn das Menüelement:

```text
[if(sib == 1 || sib > 3){exp}]           [das 1. Element ist] ODER [das 4. oder ein höheres Element ist] im Menü
[if(id == current && class == menu-expand){exp}]  [das aktuelle Element ist] UND [untergeordnete Elemente hat]
```

> **Hinweis:** Alle Tests werden in der aufgelisteten Reihenfolge ausgewertet, da:
> * Es keine Kurzschluss-Auswertung (Short-Circuit Evaluation) gibt (alle einzelnen Tests werden immer ausgewertet)
> * Es keine Gruppierung von Tests gibt (d. h. keine Unterstützung für Klammern)
> * Sowohl `||` als auch `&&` auf derselben Prioritätsstufe behandelt werden

---

## Formatter

> **Hinweis:** Dies ist eine fortgeschrittene und selten benötigte Funktion!

Wenn Sie über umfangreiche PHP-Programmierkenntnisse verfügen, ist es möglich, den vordefinierten Menü-Formatter, den `show_menu()` verwendet, durch ein benutzerdefiniertes Modul zu ersetzen. Siehe die Datei `include.php` von `show_menu()` für ein Beispiel, wie der Menü-Formatter geschrieben werden muss. Die API, die er bereitstellen muss, lautet:

```php
class SM_Formatter
{
    // Wird einmal vor der Verarbeitung eines Menüs aufgerufen, um die Objektinitialisierung zu ermöglichen
    function initialize() { }
    
    // Wird aufgerufen, um die Menuliste zu öffnen
    function startList($aPage, $aUrl) { }
    
    // Wird aufgerufen, um das Menüelement zu öffnen
    function startItem($aPage, $aUrl, $aCurrSib, $aSibCount) { }
    
    // Wird aufgerufen, um das Menüelement zu schließen
    function finishItem() { }
    
    // Wird aufgerufen, um die Menüliste zu schließen
    function finishList() { }
    
    // Wird einmal nach der Verarbeitung aller Menüs aufgerufen, um die Objektfinalisierung zu ermöglichen
    function finalize() { }
    
    // Wird einmal nach finalize() aufgerufen, wenn das Flag SM_BUFFER verwendet wird
    function getOutput() { }
};
```