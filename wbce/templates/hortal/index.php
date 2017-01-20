<?php
//Das ist ein einzeiliger PHP-Kommentar. Er beginnt mit //
/*Das ist ein mehrzeiliger PHP-Kommentar, Er beginnt mit /* und endet mit  */

//Diese Kommentare helfen dir im folgenden, ein modernes Template zu verstehen.

//*******************************************************************************************************
//some vaiabled you might edit:
$homename = 'Home';
$block2mobile = 3; //0: simply hide on mobiles. 1: Move to bottom. 2: Move to Top, 3: Show Switch to open
//*******************************************************************************************************

//Must be: Prevent from direct access:
if(!defined('WB_URL')) {
	header('Location: ../index.php');
	exit(0);
}

//Gleich hier kannst du festlegen, dass der SuperAdmin einen Edit-Schalter im Template bekommt.
//Du kannst das auch erweitern - oder weglassen.
$refreshstring = '?rs='.time(); //forces refresh
$template_edit_link = false;
if ($wb->is_authenticated()) {
	$user_id = (int) $wb->get_user_id();
	if ($user_id === 1) {$template_edit_link = true;}
	//So koennte dann der Edit-Link aussehen:
	//echo '<a class="template_edit_link" href="'.ADMIN_URL.'/pages/modify.php?page_id='.PAGE_ID.'" target="_blank">&nbsp;</a>'; unset($user_id);}
	
	$refreshstring = '?rs='.time(); //force refresh of css and js
} 




//============================================================================================================
//Der folgende Bereich ist zu 99% bei allen modernen Templates (nehezu gleich) gleich. 
//Du wirst hier bis fast zum Ende des <head> nichts aendern muessen
//============================================================================================================

//So kannst du feststellen, ob die Seite die Startseite ist und dann die Ausgabe anders machen:
$isstartpage = false;
if ( !isset($page_id) ) { $isstartpage = true; }
if ( isset($template_id) AND $page_id==4)  { $isstartpage = true; } // wbce.at presentation, you can remove this line
?>
<!DOCTYPE html>
<html lang="<?php echo strtolower(LANGUAGE); ?>">
<head>
<?php if(function_exists('simplepagehead')) {
	simplepagehead('/', 1, 0, 0); 
} else { ?>
<title><?php page_title(); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php if(defined('DEFAULT_CHARSET')) { echo DEFAULT_CHARSET; } else { echo 'utf-8'; }?>" />
<meta name="description" content="<?php page_description(); ?>" />
<meta name="keywords" content="<?php page_keywords(); ?>" />
<?php }

//Hier wird alles JS/CSS dazugeladen, was Module und Templates brauchen. Das ist ganz wichtig:
if(function_exists('register_frontend_modfiles')) {
	register_frontend_modfiles('css');
	register_frontend_modfiles('jquery');
	register_frontend_modfiles('js');
} ?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="<?php echo TEMPLATE_DIR; ?>/editor.css<?php echo $refreshstring; ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo TEMPLATE_DIR; ?>/template.css<?php echo $refreshstring; ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo TEMPLATE_DIR; ?>/colorset/colorset.php<?php echo $refreshstring; ?>" rel="stylesheet" type="text/css" />

<?php
// Generate vistor statistic if module is installed 
if (file_exists(WB_PATH.'/modules/wbstats/count.php')) { 
	include (WB_PATH.'/modules/wbstats/count.php'); 
} 




//============================================================================================================

//Menue: 
//Fuer das Menue ist showmenu2 zustaendig.
//Du kannst das auch direkt dort aufrufen, wo es gebraucht wird.
//Aber hier speichern wir es gleich in eine Variable $mainmenu, damit wir es spaeter griffbereit haben:
//Hier ist sehr viel angegeben, oft kommst du mit weniger aus:
$mainmenu = show_menu2(1, SM2_ROOT, SM2_ALL, SM2_ALL|SM2_BUFFER, '<li class="[class] lev[level]"><a href="[url]" target="[target]" class="lev[level] [class]" data-pid=[page_id]><span>[menu_title]</span></a>', '</li>', '<ul>', '</ul>', false, false);

//============================================================================================================

//Bloecke
//In der info.php des Templates koennen beliebige Inhaltsbloecke angegeben sein.
//Ueblich ist aber eine bestimmte Aufteilung. Weiter unten geben wir diese Bloecke aus, und das Layout aendert sich, je nachdem, ob die Bloecke auch Inhalt haben.

//Auch die Bloecke laden wir gleich hier in eine Variable $contentblock (Array), das hat Vorteile:

require_once __DIR__.'/info.php'; //Wir laden die info.php
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



//============================================================================================================
//Weiteres:

//Manche Module definieren ein og:image. Das ist das Bild, das Facebook anzeigt, wenn du eine Seite dort verlinkst.
if(defined('OG_IMAGE') AND OG_IMAGE != '') { 	echo '
	<meta property="og:image" content="'.OG_IMAGE.'"/>
';}


//Am Ende kannst du noch das CSS und Javascript fuer Slider oder Aehnliches einsetzen.
//Hier verwenden wir zb den responsive-slider.
?>
<link rel="stylesheet" href="<?php echo TEMPLATE_DIR; ?>/responsive-slider/responsiveslides.css" type="text/css" media="screen" />


<?php 
/*============================================================================================================
Jetzt haben wir alles, was wir fuer die Ausgabe brauchen.
Weil manches davon noch im <head> ausgegeben werden soll, haben wir das alles VOR dem schliessenden </head> gemacht.

Jetzt kommen wir zum </body>. 
Im Body wird das meiste durch kurze Schnippsel direkt in den HTML-Code eingesetzt.

============================================================================================================ */
?>

</head>
<body class="body<?php echo $page_id; if ($isstartpage == true) {echo ' isstartpage'; } ?>">
<script>
<?php
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
echo '
var menulinks = ['.implode(',', $menulinks).'];
'

//der folgende Code gibt den Link zu Quicksearch und zu cookie_permission.php an:
?>

var qsURL = "<?php echo TEMPLATE_DIR?>/quicksearch.php";
var cookie_permission_url = "<?php echo TEMPLATE_DIR?>/inc/cookie_permission.php?lang=<?php echo LANGUAGE?>";
</script>


<div class="bodybox">	
	<a style="display:none;" href="#beginContent">go to content</a>
	<div class="mobileheader">
	<div id="aprevnext2" class="aprevnext"><span style="display:none;">Prev-Next</span></div>
	<a tabindex="-1" class="toggleMenu" href="#"><span style="display:none;">Mobile Menu</span></a>
	<a href="<?php echo WB_URL; ?>"><img id="mobile-logo" src="<?php echo TEMPLATE_DIR; ?>/img/logo-mobile.png" alt="Logo" /></a>
	</div>
	<div role="banner" id="headerbox"><div class="headertop">
	<div class="logobox"><a class="homelink" href="<?php echo WB_URL; ?>"><img src="<?php echo TEMPLATE_DIR; ?>/img/logo.png" alt="Homepage" /></a></div><!-- end logobox -->
	
	
	<?php
	/*jetzt wirds knifflig ;-)
	Regel 1: Wenn $contentblock[10] Inhalt hat: diesen ausgeben. 	
	Ansonsten: Wenn das die Startseite ist, laden wir den responsive-slider
	Ansonsten: Einfach einen Bereich mit einem Bild
	*/
	if ($contentblock[10] != '') {
			echo '<div id="topslider">'.$contentblock[10].'</div>';
	} else {
		if ($isstartpage) {
				echo '<div id="topslider">'; include('responsive-slider/responsive-slider.php'); echo '</div>';
		} else {
			echo '<div id="headerpic" style="background-image:url('.TEMPLATE_DIR.'/img/header.jpg)"><img  src="'.TEMPLATE_DIR.'/img/header.jpg" alt="" /></div><!-- end headerpic -->';
	
		}	
	}
	//War doch gar nicht so schwer ;-)
	?>
	</div>
	<div class="menubox">
	<?php
		//Das Suche-Feld laden wir einfach per include, wenn die Suche eingeschaltet ist:
		if (SHOW_SEARCH) { include 'inc/search.inc.php'; }
	?>
		<div role="navigation" id="nav">
					<?php 
					echo $mainmenu;
					?><div style="clear:both;"/></div>
		</div><!-- end nav --></div><!-- end menuebox -->
		<div style="clear:both;"></div>			
	</div><!-- end headerbox -->
	<div id="headerbox_replace"></div>
	
	<div class="leftbox"><?php
	
	
	//Die linke Box:
	//Wenn ein Untermenu vorhanden ist, wird dieses gezeignt, ansonst die Datei leftblock.php
	$menuside = '';
	if (!$isstartpage) {
		ob_start();  	
		show_menu2(1, SM2_ROOT+1, SM2_CURR+5, SM2_TRIM,  '<li ><a class="[class] lev[level]" href="[url]" class="[class] men">[menu_title]</a>', '</li>', '<ul>', '</ul>');
		$menuside=ob_get_contents();
		ob_end_clean(); 
	}
	if ($menuside != '') {
		echo '<div id="leftmenu">'.$menuside.'</div>';
	} else {
		echo '<div id="leftmenu"></div><div class="inner">'; include('leftblock.php'); echo '</div>';
	}	
	?>	
	</div><!--end leftbox-->
	
	
	<div class="mainbox contentbox">
		<div class="breadcrumbs"><div id="aprevnext" class="aprevnext"><span style="display:none;">Prev-Next</span></div><div class="innerbc">
		
		<?php 
		//Breadcrumbs: Diese zeigen wir NICHT auf der Startseite:
		if ($isstartpage == false) { //We dont show breadcrumb on the startpage				
			show_menu2(1, SM2_ROOT, SM2_ALL, SM2_CRUMB, '<span class="[class]">[a][menu_title]</a></span>', '', '', '', '<span><a href="'.WB_URL.'">'.$homename.'</a></span> <span class="[class]">[a][menu_title]</a></span>'); 
		}		
		?></div><div id="beginContent" style="clear:both;"></div></div><!-- end breadcrumbs -->
	
		<?php 
		if ($contentblock[3]!= '') {echo '<div class="widetop">'.$contentblock[3].'</div>';}
		
		
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
	<?php if ($contentblock[4] != '') { echo '<div class="widebottom">'.$contentblock[4].'</div>';} ?>
	<div class="clearcontent"></div>

</div><!-- end bodybox -->
<div class="footerbox">
<div class="left"><?php 
if(FRONTEND_LOGIN) { echo '<div id="showlogin"><a href="#" onclick="showloginbox(); return false;"><img src="'.TEMPLATE_DIR.'/img/key.png" alt="K" /></a><div id="login-box" style="display:none"></div></div>';} 
?><!--LOGIN_URL, LOGOUT_URL,FORGOT_URL-->
<?php

if ($template_edit_link == true) { 
	echo '<a class="template_edit_link" href="'.ADMIN_URL.'/pages/modify.php?page_id='.PAGE_ID.'" target="_blank">&nbsp;</a>';
} else {
	}
?>&nbsp;  
</div><!-- end footer left -->
<div role="contentinfo" class="center">
<a id="gototopswitch" href="#" onclick="gototop();return false;"><img src="<?php echo TEMPLATE_DIR;?>/img/up.png" alt="Go to top" title="Go to top"></a>
<?php page_footer(); 
if (LEVEL > 0 AND $page_id % 5 == 0) {echo '<div class="footercredits">Template by <a href="http://webdesign-grafik.at/templateinfo.php" target="_blank">webdesign-grafik.at</a></div>'; } 

?>
</div><!-- end footer center -->
</div><!-- end footer -->
<a href="#" id="nav2close" class="toggleMenu"><span style="display:none;">Mobiler Menu</span></a><div id="nav2"></div>
<script type="text/javascript" src="<?php echo TEMPLATE_DIR;?>/template.js"></script>
<?php if (function_exists('register_frontend_modfiles_body')) { register_frontend_modfiles_body(); } ?>
<?php if ($template_edit_link == true ) {include 'colorset/colorpicker.inc.php';} ?>
</body>
</html>