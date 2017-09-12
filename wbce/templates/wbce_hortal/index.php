<?php
/**
    @file /templates/wbce_hortal/index.php
    @brief Die Index Datei enthält das eigentliche (PHP) Template 
    
    Die include.php enthält sämtliche PHP definitionen die dann in der index.php (Also genau hier!!!) in das 
    in das eigentliche Template eingefügt werden. 
    
*/
//Das ist ein einzeiliger PHP-Kommentar. Er beginnt mit //
/*Das ist ein mehrzeiliger PHP-Kommentar, Er beginnt mit /* und endet mit  */
//Diese Kommentare helfen dir im folgenden, das Template zu verstehen.


//============================================================================================================
// Ab hier beginnt das eigentliche Template. 
//============================================================================================================


?><!DOCTYPE html>
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
                    <?php if (isset($breadcrumbs)) { echo $breadcrumbs; } ?>
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