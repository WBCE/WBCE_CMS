<?php
/**
    @file /templates/wbce_hortal/include.php
    @brief Die Include Datei sollte das Template spezifische PHP enthalten.
    
    Die include.php enthält PHP definitionen die dann in der index.php 
    in das eigentliche Template eingefügt werden.
*/

// Das ist ein einzeiliger PHP-Kommentar. Er beginnt mit //
/* Das ist ein mehrzeiliger PHP-Kommentar, Er beginnt mit /* und endet mit  */
// Diese Kommentare helfen dir im folgenden, das Template zu verstehen.

// Datei vor direktem Zugriff schützen.
if(!defined('WB_URL')) { header('Location: ../index.php'); die();}

//============================================================================================================
// Hier einige Stellen an denen du Variablen einstellen kannst

// Name des Home buttons:
$homename = 'Home';

// Sonderbehandlung für Block 2 auf Mobilgeräten:
$block2mobile = 3; //0: simply hide on mobiles. 1: Move to bottom. 2: Move to Top, 3: Show Switch to open

// Soll der Browser den Cache nutzen oder nicht.
// Durch das Anhängen eines GET parameters mit aktuellem Zeistempel wird der Browser überlistet:
$refreshstring = ''; // ''=> Browser caches JS, CSS ...//  '?rs='.time()=> no cache

// Seiten editieren Link anzeigen:
$template_edit_link = false;

//============================================================================================================
// Hier kommt jetzt der PHP Teil.

// info.php laden , wird immer mal gebraucht.
require_once __DIR__.'/info.php'; //Wir laden die info.php.

//So kannst du feststellen, ob die Seite die Startseite ist und dann die Ausgabe anders machen:
$isstartpage = false;
if ( !isset($page_id) ) { $isstartpage = true;}

// Als admin angemeldet oder nicht ?
$isAdmin=false;
if ($wb->is_authenticated() AND $wb->ami_group_member('1')) $isAdmin=true;

// Der Admin möchte immer das Aktuellste sehen.
if ($isAdmin) $refreshstring ='?rs='.time();

// Der Admin darf immer die Seite editieren.
if ($isAdmin) $template_edit_link = true;

// Visitor statistics einbinden, falls das Modul installiert ist.
if (file_exists(WB_PATH.'/modules/wbstats/count.php')) {
    include (WB_PATH.'/modules/wbstats/count.php');
}

// Menue:
// Fuer das Menue ist showmenu2 zustaendig.
// Du kannst das auch direkt dort aufrufen, wo es gebraucht wird.
// Aber hier speichern wir es gleich in eine Variable $mainmenu, damit wir es spaeter griffbereit haben.
// Hier ist sehr viel angegeben, oft kommst du mit weniger aus.
$mainmenu = show_menu2(
    1, 
    SM2_ROOT, 
    SM2_ALL, 
    SM2_ALL|SM2_BUFFER, 
    '<li class="[class] lev[level]"><a href="[url]" target="[target]" class="lev[level] [class]" data-pid=[page_id]><span>[menu_title]</span></a>', 
    '</li>', 
    '<ul>', 
    '</ul>', 
    false, 
    false
);

// Breadcrumb Navigation:
// Breadcrumbs, diese zeigen wir NICHT auf der Startseite.
$breadcrumbs="";
if ($isstartpage !== true) { 
    $breadcrumbs = show_menu2( 
        1, 
        SM2_ROOT, 
        SM2_ALL, 
        SM2_CRUMB|SM2_BUFFER, 
        '<span class="[class]">[a][menu_title]</a></span>', 
        '', 
        '', 
        '', 
        '<span><a href="'.WB_URL.'">'.$homename.'</a></span> <span class="[class]">[a][menu_title]</a></span>' 
    ); 
}
// Seitenleistenmenü:
// Wenn wir Unterverzeichnisse haben, und diese nicht auf der Startseite sind, wird ein Seitenleistenmenü generiert.
$menuside = '';
if (!$isstartpage) { 
    $menuside= show_menu2( 
        1, 
        SM2_ROOT+1, 
        SM2_CURR+5, 
        SM2_TRIM|SM2_BUFFER, 
        '<li ><a class="[class] lev[level]" href="[url]">[menu_title]</a>', 
        '</li>', 
        '<ul>', 
        '</ul>' 
    ); 
}

// Bloecke:
// In der info.php des Templates koennen beliebige Inhaltsbloecke angegeben sein.
// Ueblich ist aber eine bestimmte Aufteilung. Weiter unten geben wir diese Bloecke aus, und das Layout aendert sich, je nachdem, ob die Bloecke auch Inhalt haben.

// Auch die Bloecke laden wir gleich hier in eine Variable $contentblock (Array), das hat Vorteile:
foreach($block as $k=>$v){ //und haengen in einer Schleife alle an.
    if ($k == 99) {continue;}  //ausser Block 99, der ist fuer "Keine Ausgabe" reserviert.
    ob_start(); page_content($k); $contentblock[$k] = ob_get_clean();
}

// Manche Module koennen auch einen 2. Block ausgeben, der im ersten Block definiert wurde:
if(defined('MODULES_BLOCK2') AND MODULES_BLOCK2 != '') { 
    $contentblock[2] .= MODULES_BLOCK2; //der 2. Block wird einfach erweitert.
}
if(defined('TOPIC_BLOCK2') AND TOPIC_BLOCK2 != '') { 
    $contentblock[2] = TOPIC_BLOCK2; //Bei Topics sollte der 2. Block aber vollstaendig ersetzt werden.
}

// Jetzt haben wir alles, was wir fuer die Ausgabe brauchen.

//============================================================================================================
// Weiter gehts in der index.php
