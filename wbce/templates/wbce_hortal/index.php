<?php
//Das ist ein einzeiliger PHP-Kommentar. Er beginnt mit //
/*Das ist ein mehrzeiliger PHP-Kommentar, Er beginnt mit /* und endet mit  */

//Diese Kommentare helfen dir im folgenden, ein modernes Template zu verstehen.

/* 
Der erste Teil enthält sämtliche PHP definitionen die dann im Zweiten Teil in das 
in das eigentliche Template eingefügt werden. 
*/

// Datei vor direktem Zugriff schützen 
if(!defined('WB_URL')) { header('Location: ../index.php'); die();}


//============================================================================================================
// Hier einige Stellen an denen du Variablen einstellen kannst

// Name des Home buttons
$homename = 'Home';

// Sonderbehandlung für Block 2 auf Mobilgeräten
$block2mobile = 3; //0: simply hide on mobiles. 1: Move to bottom. 2: Move to Top, 3: Show Switch to open

// Soll der Browser den Cache nutzen oder nicht. 
// Durch das Anhängen eines GET parameters mit aktuellem Zeistempel wird der Browser überlistet. 
$refreshstring = ''; // ''=> Browser caches JS, CSS ...//  '?rs='.time()=> no cache 

// Seiten editieren Link anzeigen
$template_edit_link = false;


//============================================================================================================

//============================================================================================================
// Hier kommt jetzt der PHP Teil 

// info.php laden , wird immer mal gebraucht 
require_once __DIR__.'/info.php'; //Wir laden die info.php

//So kannst du feststellen, ob die Seite die Startseite ist und dann die Ausgabe anders machen:
$isstartpage = false;
if ( !isset($page_id) ) { $isstartpage = true;}

// Als admin angemeldet oder nicht ?
$isAdmin=false;
if ($wb->is_authenticated() AND $wb->ami_group_member('1')) $isAdmin=true;

// Der Admin möchte immer das Aktuellste sehen
if ($isAdmin) $refreshstring ='?rs='.time();

// Der Admin darf immer die Seite editieren.
if ($isAdmin) $template_edit_link = true;

// Generate vistor statistic if module is installed 
if (file_exists(WB_PATH.'/modules/wbstats/count.php')) { 
    include (WB_PATH.'/modules/wbstats/count.php'); 
} 

// Menue: 
// Fuer das Menue ist showmenu2 zustaendig.
// Du kannst das auch direkt dort aufrufen, wo es gebraucht wird.
// Aber hier speichern wir es gleich in eine Variable $mainmenu, damit wir es spaeter griffbereit haben:
// Hier ist sehr viel angegeben, oft kommst du mit weniger aus:
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

// Breadcrumb Navigation
//Breadcrumbs: Diese zeigen wir NICHT auf der Startseite:
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
            
// if we have subdirectories and aren't on start page , generate a sidebar menu 
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
            
            

//Bloecke
//In der info.php des Templates koennen beliebige Inhaltsbloecke angegeben sein.
//Ueblich ist aber eine bestimmte Aufteilung. Weiter unten geben wir diese Bloecke aus, und das Layout aendert sich, je nachdem, ob die Bloecke auch Inhalt haben.

//Auch die Bloecke laden wir gleich hier in eine Variable $contentblock (Array), das hat Vorteile:


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


//Dieser Code ist noetig fuer die Blaettern-Schalter:
//Alle Menu-Link Seiten heraussuchen und beim Blaettern ueperspringen. Sonst bleibt man an ihnen haengen.
$theq = "SELECT page_id FROM ".TABLE_PREFIX."sections WHERE module = 'menu_link'";
$query = $database->query($theq);
$num = $query->numRows();
$menulinks = array();
if ($num > 0) {
    while ($row = $query->fetchRow()) {
        $page_id = (int) $row['page_id'];
        $menulinks[] = $page_id;
    }
}
$implodelinks=implode(',', $menulinks);



// Jetzt haben wir alles, was wir fuer die Ausgabe brauchen.
//============================================================================================================


//============================================================================================================
//Der folgende Bereich ist zu 99% bei allen modernen Templates (nahezu gleich) gleich. 
//Du wirst hier bis fast zum Ende des <head> nichts aendern muessen
//============================================================================================================


?>
<!DOCTYPE html>
<html lang="<?php echo strtolower(LANGUAGE); ?>">
<head>
<?php if(function_exists('simplepagehead')) {
	simplepagehead('/', 1, 0, 0); 
} else { ?>
    <!--(PH) TITLE+ --><title><?php page_title(); ?></title><!--(PH) TITLE- -->
    <!--(PH) META DESC+ --><meta name="description" content="<?php page_description(); ?>" /><!--(PH) META DESC- -->
    <!--(PH) META KEY+ --><meta name="keywords" content="<?php page_keywords(); ?>" /><!--(PH) META KEY- -->

    <!-- Weitere Meta definitionen die sonst vom simple pagehead erzeugt werden -->
    <!--(PH) META HEAD+ -->
<?php } ?>

<meta name="viewport" content="width=device-width, initial-scale=1">
<!--(PH) META HEAD- -->

<!--(PH) CSS HEAD TOP+ -->
    <!-- Hier wird das CSS dazugeladen, was Module und Templates brauchen.-->
    <?php register_frontend_modfiles('css');?>
<!--(PH) CSS HEAD TOP- -->

<!--(PH) JS HEAD TOP+ -->
    <!-- Hier wird das eingebaute Jquery dazugeladen -->
    <?php register_frontend_modfiles('jquery');?>
    <!-- Hier wird das JS dazugeladen, was Module und Templates brauchen. -->
    <?php register_frontend_modfiles('js');?>
<!--(PH) JS HEAD TOP- -->

<!--(PH) CSS HEAD BTM+ -->  
    <!--  CSS template spezifisch kann auch die vorherigen Definitionen überschreiben -->
    <link href="<?php echo TEMPLATE_DIR; ?>/editor.css<?php echo $refreshstring; ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo TEMPLATE_DIR; ?>/template.css<?php echo $refreshstring; ?>" rel="stylesheet" type="text/css" />

    <!-- CSS für den Colorpicker -->
    <link href="<?php echo TEMPLATE_DIR; ?>/colorset/colorset.php<?php echo $refreshstring; ?>" rel="stylesheet" type="text/css" />

    <!-- CSS für den Slider -->
    <link rel="stylesheet" href="<?php echo TEMPLATE_DIR; ?>/responsive-slider/responsiveslides.css" type="text/css" media="screen" />

    <!-- hier könnte noch weiteres CSS einfgefügt werden -->     
<!--(PH) CSS HEAD BTM- -->

<!--(PH) JS HEAD BTM+ -->
<!--(PH) JS HEAD BTM- -->
</head><?php 
/*============================================================================================================

Head ist zuende Jetzt kommen wir zum </body>. 
Im Body wird das meiste durch kurze Schnippsel direkt in den HTML-Code eingesetzt.

============================================================================================================ */
?><body class="body<?php echo $page_id; if ($isstartpage == true) {echo ' isstartpage'; } ?>">
<!--(PH) JS BODY TOP+ -->
    <script>
        // noetig fuer die Blaettern-Schalter siehe oben im PHP Teil
        var menulinks = ['<?php echo $implodelinks ?>'];

        //der folgende Code gibt den Link zu Quicksearch und zu cookie_permission.php an:
        var qsURL = "<?php echo TEMPLATE_DIR?>/quicksearch.php";
        var cookie_permission_url = "<?php echo TEMPLATE_DIR?>/inc/cookie_permission.php?lang=<?php echo LANGUAGE?>";
    </script>
<!--(PH) JS BODY TOP- -->

<!--(PH) HTML BODY TOP+ -->
<!--(PH) HTML BODY TOP- -->

    <div class="bodybox">	
        <a style="display:none;" href="#beginContent">go to content</a>
        <div class="mobileheader">
            <div id="aprevnext2" class="aprevnext">
                <span style="display:none;">Prev-Next</span>
            </div>
            <a tabindex="-1" class="toggleMenu" href="#">
                <span style="display:none;">Mobile Menu</span>
            </a>
            <a href="<?php echo WB_URL; ?>">
                <img id="mobile-logo" src="<?php echo TEMPLATE_DIR; ?>/img/logo-mobile.png" alt="Logo" />
            </a>
        </div><!-- mobileheader -->
        <div role="banner" id="headerbox">
            <div class="headertop">
                <div class="logobox">
                    <a class="homelink" href="<?php echo WB_URL; ?>">
                        <img src="<?php echo TEMPLATE_DIR; ?>/img/logo.png" alt="Homepage" />
                    </a>
                </div><!-- logobox -->
        
        
        <?php /*
        Regel : Wenn $contentblock[10] Inhalt hat: diesen ausgeben. 	
        Ansonsten: Wenn das die Startseite ist, laden wir den responsive-slider
        Ansonsten: Einfach einen Bereich mit einem Bild
        */ ?>
        <?php if ($contentblock[10] != '') :?>
            <div id="topslider"><?php echo $contentblock[10] ?></div>
        <?php else : ?>
            <?php if ($isstartpage) :?>
                <div id="topslider"><?php include('responsive-slider/responsive-slider.php'); ?></div>
            <?php else :?>
                <div id="headerpic" style="background-image:url(<?php echo TEMPLATE_DIR ?>/img/header.jpg)">
                    <img  src="<?php echo TEMPLATE_DIR ?>/img/header.jpg" alt="" />
                </div><!-- headerpic -->
            <?php endif;?>
        <?php endif; ?>
        
        
            </div>
            <div class="menubox">
                <!-- Das Suche-Feld laden wir einfach per include, wenn die Suche eingeschaltet ist: -->
                <?php if (SHOW_SEARCH) { include 'inc/search.inc.php'; } ?>
                
                <div role="navigation" id="nav">
                    <?php echo $mainmenu; ?>
                    <div style="clear:both;"></div>
                </div><!-- nav -->
            </div><!-- menuebox -->
            <div style="clear:both;"></div>		
        </div><!-- headerbox -->
        <div id="headerbox_replace"></div>
        
        <div class="leftbox">
        <!-- Die linke Box: Wenn ein Untermenu vorhanden ist, wird dieses gezeigt, ansonsten die Datei leftblock.php -->
        <?php if ($menuside != ''): ?>
            <div id="leftmenu"><?php echo $menuside ?></div>
        <?php else : ?> 
            <div id="leftmenu"></div>
            <div class="inner"><?php include('leftblock.php'); ?> 
            </div>
        <?php endif; ?>	
        </div><!--end leftbox-->
        
        <div class="mainbox contentbox">
            <div class="breadcrumbs">
                <div id="aprevnext" class="aprevnext">
                    <span style="display:none;">Prev-Next</span>
                </div>
                <div class="innerbc">        
                    <!-- breadcrum navigation -->
                    <?php echo $breadcrumbs ?>
                </div><!-- innerbc -->
                <div id="beginContent" style="clear:both;"></div>
            </div><!-- breadcrumbs -->
        
            <?php if ($contentblock[3]!= '') :?>
            <div class="widetop"><?php echo $contentblock[3] ?></div>
            <?php endif; ?>
            
            <?php
            //So, was machen wir mit dem 2. Block:
            //Do it simple, step by step:
            if ($contentblock[2] == '') {
                //No sidebar, so dont care:
                echo '<div role="main" class="maincontent contentwide">'.$contentblock[1].'</div>';
                
            } else {
                //There IS a sidebar:
                if ($block2mobile < 2) {
                    //Move the sidebar to bottom (after maincontent) or hide. hiding: class sidebar'.$block2mobile
                    echo '<div  role="main" class="maincontent contentnarrow">'.$contentblock[1].'</div>';
                    echo '<div class="sidebar sidebar'.$block2mobile.'" ><div role="complementary" class="inner">'.$contentblock[2].'</div><div style="clear:left; height:1px;"></div></div><!-- end sidebar -->';					
                }
            
                if ($block2mobile == 2) {
                    //Move the sidebar to top:
                    echo '<div class="sidebar sidebar'.$block2mobile.'"><div role="complementary" class="inner">'.$contentblock[2].'</div><div style="clear:left; height:1px;"></div></div><!-- end sidebar -->';	
                    echo '<div role="main" class="maincontent contentnarrow">'.$contentblock[1].'</div>';
                }
                
                if ($block2mobile == 3) {
                    //Show a switch
                    echo '<div role="main" class="maincontent contentnarrow">'.$contentblock[1].'</div>
                    <div id="sidebar" class="sidebar"><a id="closesidebarswitch" href="#" onclick="opensidebar();return false;"><img src="'.TEMPLATE_DIR.'/img/close.png" alt="Close Sidebar" title="Close Sidebar"></a><div role="complementary" class="inner">'.$contentblock[2].'</div><div style="clear:left; height:1px;"></div></div><!-- end sidebar -->
                    <a id="opensidebarswitch" href="#" onclick="opensidebar();return false;"><img src="'.TEMPLATE_DIR.'/img/opensidebar.png" alt="Open Sidebar" title="Open Sidebar"></a>';
                }		
            }			
            ?>
        
        </div><!-- end mainbox -->
        
        <?php if ($contentblock[4] != '') : ?>
        <div class="widebottom"><?php $contentblock[4] ?></div> 
        <?php endif; ?>
            
        <div class="clearcontent"></div>

    </div><!-- end bodybox -->

    <div class="footerbox">
        <div class="left">
        <!--LOGIN_URL, LOGOUT_URL,FORGOT_URL also die Loginbox anzeigen-->
        <?php if(FRONTEND_LOGIN) : ?>
            <div id="showlogin">
                <a href="#" onclick="showloginbox(); return false;">
                    <img src="<?php echo TEMPLATE_DIR ?>/img/key.png" alt="K" />
                </a>
                <!-- Die eigentliche Loginbox wird wohl durch das Javascript gefüllt -->
                <div id="login-box" style="display:none"></div>
            </div><!-- showlogin --> 
        <?php endif; ?>

        <!-- Kleiner Button für den editieren Link -->
        <?php if ($template_edit_link == true) : ?> 
            <a class="template_edit_link" href="<?php echo ADMIN_URL ?>/pages/modify.php?page_id=<?php echo PAGE_ID ?>" target="_blank">&nbsp;</a>
        <?php endif; ?> 

        </div><!-- left -->
        <div role="contentinfo" class="center">
            <a id="gototopswitch" href="#" onclick="gototop();return false;">
                <img src="<?php echo TEMPLATE_DIR;?>/img/up.png" alt="Go to top" title="Go to top">
            </a>
            <?php page_footer(); ?>
        </div><!-- center -->
    </div><!-- footer -->

    <!-- Mobiles Menu Schalter am Ende der Seite ?? -->
    <a href="#" id="nav2close" class="toggleMenu">
        <span style="display:none;">Mobiler Menu</span>
    </a>
    <div id="nav2"></div>


<!--(PH) HTML BODY BTM+ -->
<?php 
//Und das ist der Farbwaehler. Du kannst das loeschen, wenn du die Farben fixiert hast.
if ($template_edit_link == true) {include 'colorset/colorpicker.inc.php';} 
?>
<!--(PH) HTML BODY BTM- -->


<!--(PH) JS BODY BTM+ -->
    <!-- Template speciffic JS -->
    <script type="text/javascript" src="<?php echo TEMPLATE_DIR;?>/template.js"></script>
    
    <!-- register modfiles body , only JS may be here --> 
    <?php register_frontend_modfiles_body();?>
<!--(PH) JS BODY BTM- -->



<?php 
    // Manche Module definieren ein og:image. Das ist das Bild, das Facebook anzeigt, wenn du eine Seite dort verlinkst.
    // Erst hier am Ende sind alle Module durchgelaufen. Das Meta wird hier gesetzt , 
    // und der move_stuff Filter sorgt dafür, das es nach oben kommt. 
    // Eigentlich könnten die Module das jetzt selbst machen ohne die Konstante. 
    // Ich zweifle auch daran , das das im Head schon richtig funktioniert hat . 
    if(defined('OG_IMAGE') AND OG_IMAGE != '') { 
        echo '
<!--(MOVE) META HEAD- -->
<!-- Facebook image -->
<meta property="og:image" content="'.OG_IMAGE.'"/>
<!--(END)-->
        ';
    }
?>

</body>
</html>