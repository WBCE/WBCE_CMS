show_menu2, version 4.9
=======================
Ist ein Code-Snippet für das CMS Website Baker. Es stellt einen kompletten 
Ersatz für die eingebaute Menüfuntionalität zur Verfügung. Alle, für die 
Erzeugung des Menüs erforderlichen Daten, werden durch eine einzige 
Datenbankabfrage erzeugt. Durch umfangreiche Anpassungsmöglichkeiten des 
erzeugten HTML-Code können alle möglichen Menüarten (Listen, Breadcrumbs, 
Sitemaps, usw.) erzeugt werden.

---
Deutsche Übersetzung von BerndJM. Dies ist eine weitgehend direkte Übersetzung 
des englischen Originals. Bei Übersetzungs- oder Interpretationsfehlern, bitte 
eine Email an bjm@wwnw.de.
---



INSTALLATION
============
1. Die aktuelle Version von http://code.jellycan.com/show_menu2/ herunterladen.
2. In das Admin-Backend der Website Baker Installation einlogen.
3. Erweiterungen -> Module aufrufen.
4. Wenn bereits eine frühere Version von show_menu2 installiert ist, diese über
   "Modul deinstallieren" auswählen und deinstallieren.
5. Im Abschnitt "Modul installieren" das im Schritt 1 heruntergeladene zip-File
   auswählen und installieren.

   
   
BENUTZUNG VON SHOW_MENU2
========================
Um show_menu2 zu benutzen muss das verwendete Template an den Stellen 
modifiziert werden, an denen das Menü erscheinen soll. Bitte beachten: Wenn alte 
Menüaufrufe ersetzt werden, müssen unbedingt auch die entsprechenden neuen 
Parameter verwendet werden die show_menu2 benötigt.

In den meisten Fällen genügt bereits der Standardaufruf ohne weitere Parameter 
von show_menu2. In diesem Fall werden die Vorgabewerte verwendet, dies erzeugt 
ein Menü das die aktuelle Seite und die Unterseiten der aktuellen Seite anzeigt:
    
    show_menu2();
    
Bitte beachten: der Aufruf von show_menu2 ist PHP und muss normalerweise in PHP-
Codezeichen eingeschlossen werden (ausser der Aufruf erfolgt bereits innerhalb 
von PHP Code):

    <?php show_menu2(); ?>

Dieses Vorgabe Menü erzeugt bereits ein komplettes Menü auf Listenbasis mit 
etlichen Klassen, die eine leichte Formatierung mittels CSS ermöglichen. Es wird 
z.B. die Klasse "menu-current" zu dem <li> tag des aktuellen Menüpunktes 
hinzugefügt. Zusätzlich erhält jeder Menüpunkt der Unterpunkte enthält die 
Klasse "menu-expand". Das erlaubt es sehr differenzierte CSS Regeln für die 
einzelnen Menüpunkte aufzustellen.
Zum Beispiel:

    li.menu-expand  { font-weight: bold; }
    li.menu-current { background: red; }

Im Abschnitt "HTML-Ausgabe" findet sich eine detaillierte Beschreibung welche 
Klassen welchem Element zugeordnet werden. Durch die Verwendung von 
verschiedenen Parametern bei dem show_menu2 Funktionsaufruf lassen sich auch 
recht umfangreiche und unterschiedliche Menüstrukturen erzeugen. Um 
beispielsweise nur Menüpunkte aus der obersten Ebene der Menüstruktur 
darzustellen, könnte man folgenden Aufruf verwenden:

    show_menu2(0, SM2_ROOT, SM2_START);
    
Oder um beispielsweise bis zu zwei Unterebenen der aktuellen Seite anzuzeigen:

    show_menu2(0, SM2_CURR+1, SM2_CURR+2);

Es gibt jede Menge Möglichkeiten, um die unterschiedlichsten Menüstrukturen zu 
erzeugen. Zahlreiche Beispiele dazu findet man auf der Demo-Website: http://
code.jellycan.com/sm2test/



HÄUFIGE FRAGEN
==============

Q:  Ich bin kein Programmierer. Gibt es keine einfachere Dokumentation? 
A:  Nein, denn dies hier ist bereits die einfache Dokumentation.


Q:  Wie kann ich ein sogenanntes Drop-Down Menü erstellen?
A:  Dies hat nichts mit show_menu2 zu tun. Um ein Drop-Down Menü zu erzeugen muß
    lediglich der CSS-Code des jeweiligen Templates angepaßt werden. Die nötigen 
    Anpassungen findet man z.B. im "allcss2" Template aus dem WB Addon     
    Repository -> http://addons.websitebaker.org/pages/templates.php


Q:  Warum verschwindet das Menü nachdem ich in einer mehrsprachigen WB-Site die 
    Suchfunktion benutzt habe?
A:  Im verwendeten Template fehlen die notwendigen Zeilen:

    1.  Im WB Admin Backend: Optionen -> Erweiterte Optionen anzeigen ->        
        Suchoptionen -> Kopfzeile - hier direkt nach dem öffnenden <form> tag 
        folgende Zeile einfügen:
       
        <input type="hidden" name="referrer" value="[REFERRER_ID]" />

    2.  In der index.php des verwendeten Templates folgende Zeile unmittelbar   
        nach dem öffnenden <form> tag der Suche einfügen:

        <input type="hidden" name="referrer" value="<?php echo defined('REFERRER_ID')?REFERRER_ID:PAGE_ID;?>" />


Q:  Mehrsprachig? Das klingt toll. Wie macht man das?
A:  http://help.websitebaker.org/pages/de/advanced-doku/designer-guide/mehrsprachige-webseiten.php


Q:  Jedesmal wenn eine Seite aufgerufen wird, erzeugt SM2 folgende Warnmeldung:
    "show_menu2 error: $aOptions is invalid. No flags from group 1 supplied!"
A:  Der Funktion wurden die falschen Werte oder eine falsche Anzahl an 
    Werten übergeben.
    Siehe den Abschnitt PARAMETER für die korrekten Flag Werte die dem 
    $aOptions Parameter zu übergeben sind.    


Q:  How do I use a different class/picture/color/widget for each entry in a menu?
A:  Use the [page_id] format string in the $aItemOpen string. Create a unique 
    class or id for each menu item, then reference that item in your CSS or Javascript
    to do whatever you want.
    
    To add a unique class for each menu item (or similar):
    
        "<li><a href="[url]" target="[target]" class="[class] p[page_id]">[menu_title]</a>"

        ... creating menu items like ...
    
        <li><a href="/pages/foo/bar.php" target="_top" class="menu-top p45">Top Menu</a>

        Reference this in your CSS like:
        
        a.p45 { color: red; }
    
    To add a unique ID for each menu item (or similar):
    
        "<li><a id="p[page_id]" href="[url]" target="[target]" class="[class]">[menu_title]</a>"
    
        ... creating menu items like ...
    
        <li><a id="p45" href="/pages/foo/bar.php" target="_top" class="menu-top">Top Menu</a>

        Reference this in your CSS like:
        
        a#p45 { color: red; }
        
        Note that the ID can only be used if that menu is generated and displayed one time
        only on the page (because HTML ID's must be unique within a page). 
    
    
FUNKTION
========

Der komplette Aufruf und die Vorgabe Parameterwerte für show_menu2 sind wie folgt:

    show_menu2(
        $aMenu          = 0,
        $aStart         = SM2_ROOT,
        $aMaxLevel      = SM2_CURR+1,
        $aOptions       = SM2_TRIM,
        $aItemOpen      = '[li][a][menu_title]</a>',
        $aItemClose     = '</li>',
        $aMenuOpen      = '[ul]',
        $aMenuClose     = '</ul>',
        $aTopItemOpen   = false,
        $aTopMenuOpen   = false
        )

Im Abschnitt "Parameter" findet sich eine detaillierte Beschreibung jedes 
einzelnen Parameters.
Jeder Parameter muß absolut korrekt verwendet werden. Folgende Regeln können 
dabei helfen:

    $aMenu = 0 ist in den meisten Anwendungsfällen der beste Wert.
    
    $aStart muß entweder eine page ID oder ein Wert der mit "SM2_" beginnt sein.
    
    $aMaxLevel kann nur Werte erhalten, die mit "SM2_" beginnen.
    
    $aOptions bis auf einige wenige Spezialfälle sind hier nur Werte die mit 
    "SM2_" beginnen zulässig.
    
    Alle weiteren Parameter enthalten die (HTML)Tags die die Ausgabe des Menüs 
    steuern.
    
    Ab $aItemOpen kann jedem Parameter der Wert false übergeben werden um den 
    jeweiligen Vorgabewert zu erhalten.

    Dies kann beispielsweise verwendet werden um eine nummerierte Liste zu 
    erzeugen, während für die einzelnen Menüpunkte trotzdem die Vorgabewerte 
    Verwendung finden:

        show_menu2(0, SM2_ROOT, SM2_ALL, SM2_ALL, false, false, '<ol>', '</ol>');

Bitte beachten: bis einschliesslich $aOptions müssen alle Parameter explizit übergeben werden!


HTML-AUSGABE
============
Die HTML-Ausgabe hängt wesentlich davon ab, welche Parameter an die Funktion übergeben werden. 
Unabhängig davon werden nachfolgende Klassen grundsätzlich für jedes Menü verwendet, wobei 
einzelne Menüpunkte, wenn es erforderlich ist, auch mehrere Klassen erhalten können.

    KLASSE          ZUORDNUNG
    ------------    -------------------------------------------------------
    menu-top        Nur der erste Menüpunkt.
    menu-parent     Jeder Hauptmenüpunkt.
    menu-current    Nur der Menüpunkt der aktuellen Seite.
    menu-sibling    Alle "Geschwister" der aktuellen Seite.
    menu-child      Jedes Untermenü der aktuellen Seite.
    menu-expand     Jedes Menü das Untermenüs hat.
    menu-first      Der erste Punkt eines jeden Menüs oder Untermenüs.
    menu-last       Der letzte Punkt eines jeden Menüs oder Untermenüs.

    Folgende Klassen werden nur hinzugefügt, wenn das SM2_NUMCLASS Flag gesetzt 
    ist:

    menu-N          Jeder Menüpunkt, wobei das N für die ABSOLUTE Menütiefe, 
                    beginnend bei 0, des jeweiligen Menüpunktes steht.
                    Die oberste Ebene ist also immer menu-0, die nächste
                    Ebene menu-1 usw.
    menu-child-N    Jedes Untermenü der aktuellen Seiten, wobei das N für die
                    RELATIVE Tiefe des Untermenüs, beginnend bei 0, steht.
        
Beispiel einer HTML-Ausgabe:

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
      <li class="menu-2 menu-expand menu-current menu-first">  ...      ** CURRENT PAGE **
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


PARAMETER
=========
$aMenu      
    Nummer des Menüs. Diese ist nützlich um mehrere Menüs auf einer Seite zu    
    verwenden.
    Menü Nummer 0 ist das Vorgabemenü der aktuellen Seite, SM2_ALLMENU gibt alle
    im System verwendeten Menüs zurück.

$aStart  
    Gibt an, ab welcher Ebene die Erzeugung des Menüs beginnen soll. In den     
    meisten Fällen wird dies die oberste Ebene des anzuzeigenden Menüs sein. Es 
    kann einer der folgenden Werte verwendet werden:

        SM2_ROOT+N  Beginnt N Ebenen unterhalb der obersten Ebene, z.B.:
                    SM2_ROOT      Beginnt auf der obersten Ebene
                    SM2_ROOT+1    Beginnt eine Ebene unterhalb der obersten Ebene
                    SM2_ROOT+2    Beginnt zwei Ebenen unterhalb der obersten Ebene

        SM2_CURR+N  Beginnt N Ebenen unterhalb der aktuellen Ebene, z.B.:
                    SM2_CURR      Beginnt auf der aktuellen Ebene. Alle Geschwister
                                  der aktuellen Ebene
                    SM2_CURR+1    Beginnt eine Ebene unterhalb der aktuellen Ebene
                                  mit allen Unterebenen

        page_id     Verwendet die Seite mit der angegebenen page id als Elternelement.
                    Alle Untermenüs dieser Seite werden angezeigt. 
                    (Die page id kann ermittelt werden, wenn man die Seite im 
                    Admin-Backend editiert, sie steht dann in der Adresszeile des      
                    Browsers: http://SITE/admin/pages/modify.php?page_id=35

$aMaxLevel   
    Die maximale Anzahl der Ebenen die angezeigt werden. Die Anzeige
    beginnt ab der in $aStart festgelegten Ebene, bis hin zu der hier 
    festgelegten Ebene.
        
        SM2_ALL      Keine Beschränkung, alle Ebenen werden angezeigt

        SM2_CURR+N   Zeigt immer die aktuelle Seite + N Ebenen. 
                     SM2_CURR      Aktuelle Ebene (keine Unterebene)
                     SM2_CURR+3    Alle übergeordneten + aktuelle + 3 Unterebenen

        SM2_START+N  Beginnt immer auf der Startebene + N Ebenen.
                     Die Ebenen werden unabhängig davon angezeigt,egal 
                     auf welcher Ebene sich die aktuelle Seite befindet.
                     SM2_START     Eine einzelne Ebene ab der Startebene.
                     SM2_START+1   Startebene + eine Ebene darunter.

        SM2_MAX+N    Zeigt höchstens N Ebenen ab der Startebene.
                     Ebenen unterhalb der aktuellen Ebene werden nicht angezeigt.
                     SM2_MAX       Nur die Startebene (gleiche Wirkung wie SM2_START)
                     SM2_MAX+1     Die Startebene und eine Ebene darunter.

$aOptions   
    Spezielle Flags für verschiedene Menügenerierungs Optionen. Sie können mittels
    einer ODER Verknüpfung (|) miteinander kombiniert werden. Um beispielsweise
    sowohl TRIM als auch PRETTY zu definieren, verwendet man: (SM2_TRIM | SM2_PRETTY).

    GROUP 1
    -------
    Aus dieser Gruppe muss stets genau ein Flag angegeben werden. Diese Flags
    bestimmen auf welche Weise die Geschwisterelemente im Menübaum in der
    Ausgabe unterdrückt werden.
    
    SM2_ALL         Zeigt alle Zweige des Menübaums
                        A-1 -> B-1 
                            -> B-2 -> C-1
                                   -> C-2 (CURRENT)
                                          -> D-1
                                          -> D-2
                                   -> C-3
                        A-2 -> B-3
                            -> B-4

    SM2_TRIM        Zeigt alle Geschwistermenüs der Seite im aktuellen Pfad.
                    Alle Untermenüs von Elemnten die sich nicht im Pfad befinden
                    werden entfernt.
                        A-1 -> B-1 
                            -> B-2 -> C-1
                                   -> C-2 (CURRENT)
                                          -> D-1
                                          -> D-2
                                   -> C-3
                        A-2 

    SM2_CRUMB       Zeigt den Breadcrumb Pfad des Menüs an, also den aktuellen
                    Menüpunkt sowie alle Menüpunkte die dorthin führen.
                        A-1 -> B-2 -> C-2 (CURRENT)

    SM2_SIBLING     Wie SM2_TRIM, es werden aber nur Geschwistermenüs der aktuellen
                    Seite angezeigt. Alle anderen Punkte werden unterdrückt.
                        A-1 -> B-2 -> C-1
                                   -> C-2 (CURRENT)
                                          -> D-1
                                          -> D-2
                                   -> C-3

    GROUP 2
    -------
    Diese Flags sind optional, sie können in beliebiger Anzahl kombiniert werden.

    SM2_NUMCLASS    Fügt die nummerierten Menüklassen "menu-N" und 
                    "menu-child-N hinzu.
        
    SM2_ALLINFO     Lädt alle Felder aus der Seitentabelle der Datenbank.
                    Dies verursacht einen ziemlich hohen Speicherverbauch und sollte
                    deshalb nur mit Bedacht verwendet werden.
                    Dadurch werden z.B. die Keywords, die Seitenbeschreibung sowie
                    all die anderen Informationen verfügbar, die normalerweise nicht
                    geladen werden.
                    Bitte beachten: dieses Flag muss beim ERSTEN Aufruf von schow_menu2
                    für die jeweilige Menü ID verwendet werden, oder in Verbindung
                    mit SM2_NOCACHE, sonst zeigt es keine Wirkung.
    
    SM2_NOCACHE     Die aus der Datenbank gelesenen Daten werden bei erneutem Aufruf von
                    show_menu2 nicht wiederverwendet sondern erneut aus der Datenbank 
                    gelesen.
    
    SM2_PRETTY      Bringt die HTML-Ausgabe des Menüs mittels Leerzeichen und
                    Zeilenumbrüchen in eine gut lesbare Form. Das ist besonders 
                    nützlich beim Debuggen der Menüausgabe.
    
    SM2_BUFFER      Gibt den HTML-Code nicht direkt aus, sondern speichert ihn intern
                    zwischen und gibt ihn als kompletten String aus.
    
    SM2_CURRTREE    Schliesst alle anderen Toplevelmenüs von der Betrachtung aus.
                    Es werden nur Menüpunkte des aktuellen Menüzweiges dargestellt.
                    Dieses Flag kann bei Bedarf mit jedem Flag aus der Gruppe 1
                    kombiniert werden.
    
    SM2_ESCAPE      Wendet htmlspecialchars auf den Menüstring an.
                    Dies kann bei älteren Websitebaker Installationen erforderlich
                    sein um eine valide HTML Ausgabe zu erzeugen.
                        
    SM2_SHOWHIDDEN  Hidden pages are usually hidden all of the time, including 
                    when they are active (i.e. current page or a parent page).
                    Use private pages for time when you want pages to be
                    hidden except when active. However for compatibility with
                    release 4.8, supply this flag to enable hidden pages to
                    become visible when they are active.

    SM2_XHTML_STRICT	Stellt die XHTML-Kompatibilität der Links sicher indem
					in per [a] oder [ac] formatierten Links die Targetangabe
					entfernt und das Argument title="[page_titel]" eingefügt
					wird. Bei manuell zusammengestellten Links ist der Designer
					selbst für die XHTML-Konformität zuständig.

	SM2_NO_TITLE	Unterdrückt die Ausgabe des Inhaltes des Title-Attributes
					bei [a] oder [ac] formatierten links. Im XHTML-Strikt Modus
                    wird 'title' mit einen &nbsp; ausgegeben.

    Für diesen Parameter gibt es auch einen erweiterten Modus, bei dem die Optionen
    als assoziatives Array übergeben werden. Näheres dazu im Abschnitt ERWEITERTE
    OPTIONEN. Für die meisten Anwendungsfälle wird dies jedoch NICHT benötigt.
    
$aItemOpen
    Dies legt den Formatstring fest, mit dem jeder einzelne Menüeintrag begonnen
    wird. Für den allerersten Menüeintrag kann mittels $aTopItemOpen ein anderer
    Formatstring definiert werden.
    Wenn dieser Parameter auf false gesetzt wird, wird der Vorgabe Formatstring
    '[li][a][menu_title]</a>' verwendet um die Kompatibilität zur Website Baker
    Standardfunktion show_menu() zu gewährleisten.
    Da die Formatierung mittels CSS-Klassen oftmals einfacher ist, wenn sie auf den 
    <a> tag angewendet werden, empfiehlt es sich hier folgenden Formatstring zu
    verwenden: '<li>[ac][menu_title]</a>'.
    
    Dieser Parameter kann auch als Instanz eine Formatierungklasse für das Menü
    verwendet werden. Die nähere Beschreibung dazu findet sich im Abschnitt FORMATTER. 
    Wenn hier ein Formatter angegeben wird, werden alle Argumente
    nach $aItemOpen ignoriert.

$aItemClose
    Dieser String schließt jeden Menüpunkt ab. 
    Bitte beachten: dies ist kein Formatstring und es werden keine Schlüsselworte
    ersetzt!
    Wenn dieser Parameter auf false gesetzt ist, wird die Vorgabe '</li>' verwendet.
    
$aMenuOpen
    Mit diesem Formatstring wird eine Liste von Menüeinträgen geöffnet. Für das erste
    Menü kann mittels $aTopMenuOpen ein davon abweichender Formatstring definiert
    werden.
    Wenn dieser Parameter auf false gesetzt ist wird der Vorgabewert '[ul]'
    verwendet.
    
$aMenuClose
    Dieser String schließt jedes Menü ab. 
    Bitte beachten: dies ist kein Formatstring und es werden keine Schlüsselworte
    ersetzt!
    Wenn dieser Parameter auf false gesetzt ist, wird die Vorgabe '</ul>' verwendet.

$aTopItemOpen
    Der Formatstring für den allerersten Menüpunkt. Wenn dieser Parameter auf false
    gesetzt wird, wird der selbe Formatstring wie bei $aItemOpen verwendet.

$aTopMenuOpen 
    Der Formatstring für das erste Menü. Wenn dieser Parameter auf false
    gesetzt wird, wird der selbe Formatstring wie bei $aMenuOpen verwendet.

    

ERWEITERTE OPTIONEN
===================
Der Parameter $aOptions kann auf zweierlei Arten verwendet werden. Zum einen, wie oben 
im Abschnitt PARAMETER beschrieben, diese Art sollte für die allermeisten 
Anwendungsfälle ausreichen. Um allerdings in speziellen Fällen die Sonderoptionen 
ansprechen zu können, müssen die erforderlichen Werte als assoziatives Array 
bereitgestellt werden.
Bitte beachten: Die SM2_* Flags sind auch hierbei erforderlich und müßen als 'flags' 
übergeben werden.

    'flags'     **ZWINGEND ERFORDELICH** Dies sind die Flags die oben im Abschnitt 
                PARAMETER unter $aOptions beschrieben wurden.

    'notrim'    Hiermit wird eine Anzahl von Ebenen festegelegt, die relativ bezogen 
                auf die in $aStart festgelegte Menüebene, immer angezeigt werden. Dies 
                bewirkt, daß für diese Ebenen das SM2_TRIM Flag ignoriert wird.

Um dieses Array zu verwenden, empfiehlt es sich es erst anzulegen und dann den 
$aOptions parameter mit dem angelegten Array zu beliefern:

    $options = array('flags' => (SM2_TRIM|...), 'notrim' => 1);
    show_menu2(0, SM2_ROOT, SM2_CURR+1, $options);
    
    
    
FORMAT STRINGS
==============
Die folgenden Tags können in den Formatstrings für $aItemOpen und $aMenuOpen
verwendet werden und sollen durch den entsprechenden Text ersetzt werden.

[a]             <a> tag ohne Klasse:   '<a href="[url]" target="[target]">'
[ac]            <a> tag mit Klasse:    '<a href="[url]" target="[target]" class="[class]">'
[li]            <li> tag mit Klasse:   '<li class="[class]">'
[ul]            <ul> tag mit Klasse:   '<ul class="[class]">'
[class]         Liste der Klassen für diese Seite
[menu_title]    Text des Menütitel 
                (HTML entity escaped ausser das SM2_NOESCAPE Flag ist gesetzt)
[menu_icon_0]	die URL zu einer Bilddatei mit normal - Darstellung (ab WB2.9.0)
[menu_icon_1]	die URL zu einer Bilddatei mit active/hover - Darstellung (ab WB2.9.0)
[page_title]    text des Seitentitel 
                (HTML entity escaped ausser das SM2_NOESCAPE Flag ist gesetzt)
[page_icon]		die URL zu einer seitenbezogenen Bilddatei (ab WB2.9.0)
[tooltip]       Tooltip-Text, der normal im title-Attribut der Links ausgegeben wird
[url]           die URL der Seiten für den <a> tag
[target]        das Seitenziel für den <a> tag
[page_id]       die Page ID des aktuellen Menüpunktes.
[parent]        die Page ID des übergeordneten Menüpunktes.
[level]         die Seitenebene, 
                dies ist die gleiche Zahl die im "menu-N" CSS tag verwendet wird.
[sib]           Anzahl der Geschwister des aktuellen Menüpunktes.
[sibCount]      Anzahl aller Geschwister in diesem Menü.
[if]            Bedingung (Details hierzu im Abschnitt "Bedingte Formatierung')

Folgende tags sind NUR verfügbar, wenn das SM2_ALLINFO Flag gesetzt ist.

[description]   Seitenbeschreibung
[keywords]      Schlüsselworte der Seite



BEDINGTE FORMATIERUNG
=====================
Die Anweisung für eine bedingte Formatierung kann eine der folgenden Formen haben:

    [if(A){B}]
    [if(A){B}else{C}]
    
    A   Die Bedingung. Details dazu, siehe unten.
    
    B   Der Ausdruck der verwendet wird, wenn die Bedingung erfüllt ist.
        Dies kann ein beliebiger String sein, der jedoch nicht das Zeichen '}'
        enthalten darf. Er kann jeden beliebigen Formatstring aus dem Abschnitt
        'Format Strings' enthalten, jedoch keinen weiteren Bedingungstest (da das 
        Zeichen '}' nicht erlaubt ist).
    
    C   Der Ausdruck der verwendet wird, wenn die Bedingung nicht erfüllt ist.
        Dies kann ein beliebiger String sein, der jedoch nicht das Zeichen '}'
        enthalten darf. Er kann jeden beliebigen Formatstring aus dem Abschnitt
        'Format Strings' enthalten, jedoch keinen weiteren Bedingungstest (da das 
        Zeichen '}' nicht erlaubt ist).

Die Bedingung ist eine Kombination von einem oder mehreren boolschen Vergleichen.
Wenn mehr als ein Vergleich erforderlich ist, so muss dieser mit den anderen Vergleichen
mittels || (boolsches oder - OR) oder && (boolsches und - AND) verknüpft werden.    

Ein einzelner Vergleich besteht aus dem linken Operanden, dem Operator und dem rechten 
Operanden.
z.B. X == Y  - hierbei ist X der linke Operand, == der Operator und Y der rechte Operand.
    
    Linker Operand. Muss eines der folgende Schlüsselworte sein:
        class       Überprüfung ob diese Klasse existiert. Es sind nur die
                    "==" and "!=" Operatoren erlaubt. In diesem Fall haben die Operatoren
                    die Bedeutung von "enthält" bzw. "enthält nicht" an Stelle von
                    "ist gleich" bzw. "ist nicht gleich"
        level       Überprüfung der Seitenebene.
        sib         Überprüfung der Geschwisteranzahl der aktuellen Seite.
        sibCount    Überprüfung der Geamtanzahl der Geschwister im aktuellen Menü.
        id          Überprüfung der page id.
		target		Überprüfung der Target-Angabe
    
    Operator. Muss einer der folgenden sein:
        <           Kleiner als
        <=          Kleiner oder gleich als
        ==          Gleich
        !=          Nicht gleich
        >=          Grössr oder gleich als
        >           Grösser als
    
    Rechter Operand. Die Art dieses Operanden hängt von dem, für den linken Operanden
                     verwendeten Schlüsselwort ab.
        class       einer der "menu-*" Klassennamen wie sie im Abschnitt "Ausgabe"
                    spezifiziert sind.
        level       Überprüfung der Seitenebene gegen folgende Werte:
                        <number>  die absolute Seitenebene
                        root      die oberste Seitenebene
                        granny    die Seitenebene über der übergeordneten Seitenebene
                        parent    die übergeordnete Seitenebene
                        current   die aktuelle Seitenebene
                        child     die untergeornete Seitenebene
        id          Überprüfung der page id gegen folgende Werte:
                        <number>  die absolute page id
                        parent    die übergeordnete page id
                        current   die aktuelle page id
        sib         Eine positive Integerzahl, oder "sibCount" um die Anzahl der
                    Geschwister in diesem Menü zu überprüfen
        sibCount    Eine positive Integerzahl
		target		Ein String, der eine mögliche Targetangabe darstellt
        
Folgende Beispiele ergeben "wahr" und der Ausdruck {exp} wird ausgeführt, wenn zutrifft:
    
    [if(class==menu-expand){exp}]   hat ein Untermenü
    [if(class==menu-first){exp}]    ist der erste Eintrag in einem Menü
    [if(class!=menu-first){exp}]    ist NICHT der erste Eintrag in einem Menü
    [if(class==menu-last){exp}]     ist der letzte Eintrag in einem Menü
    [if(level==0){exp}]             befindet sich auf der obersten Ebene
    [if(level>0){exp}]              befindet sich NICHT auf der obersten Ebene
    [if(sib==2){exp}]               ist der zweite Eintrag in einem Menü
    [if(sibCount>1){exp}]           ist in einem Menü mit mehr als einem Eintrag
    [if(sibCount!=2){exp}]          ist in einem Menü, das nicht genau 2 Einträge hat
    [if(level>parent){exp}]         ist in einem Geschwistermenü oder dem Untermenü eines      
                                    Geschwistermenüs
    [if(id==parent){exp}]           ist der übergeordnete Punkt der aktuellen id
	[if(target==_self){exp}]		im Target-Attribut ist der String '_self' enthalten

Wenn eine sonst-Klausel (else) hinzugefügt wird, so wird diese in allen anderen Fällen 
ausgeführt.
Zum Beispiel wird "foo" immer dann ausgeführt, wenn die if Überprüfung falsch ergibt, also:

    [if(sib==2){exp}else{foo}]      ist NICHT der zweite Eintrag im Menü
    [if(sibCount>2){exp}else{foo}]  ist NICHT in einem Menü mit mehr als zwei Einträgen

Bei mehrfach Vergleichen wird der Ausdruck "exp" nur ausgeführt, wenn:

    [if(sib == 1 || sib > 3){exp}]  ist der erste Eintrag ODER ist der vierte oder höhere   
    Eintrag im Menü    
               
    [if(id == current && class == menu-expand){exp}  ist der aktuelle Eintrag UND hat 
    Untermenüs
        
Bitte beachten:
Alle Überprüfungen werden in der Reihenfolge ausgeführt, in der sie notiert sind, denn:
* es findet keine Überprüfung auf evtl. Schleifen statt (alle Überprüfungen werden immer ausgeführt)
* Überprüfungen werden nicht gruppiert (eine Klammerung von Überprüfungen wird nicht unterstützt)
* sowohl || als auch && haben die gleiche Wertigkeit



FORMATTER
=========
Achtung: dies ist ein fortgeschrittenes und äusserst selten benötigtes Feature!

Mit umfangreichen Kenntnissen in der PHP Programmierung ist es möglich den vordefinierten
Formatierer von show_menu2 mit einem eigenen zu ersetzen.
In der include.php von show_menu2 sieht man wie der Formatierer geschreiben werden muss.
Die API, die verwendet werden muss, sieht wie folgt aus:

(Anmerkung des Übersetzers: Kommentare sind nicht übersetzt, wer sich so weit vorwagt, sollte
damit keine Probleme haben ;-)

class SM2_Formatter
{
    // called once before any menu is processed to allow object initialization
    function initialize() { }
    
    // called to open the menu list
    function startList($aPage, $aUrl) { }
    
    // called to open the menu item
    function startItem($aPage, $aUrl, $aCurrSib, $aSibCount) { }
    
    // called to close the menu item
    function finishItem() { }
    
    // called to close the menu list
    function finishList() { }
    
    // called once after all menu has been processed to allow object finalization
    function finalize() { }
    
    // called once after finalize() if the SM2_NOOUTPUT flag is used
    function getOutput() { }
};
