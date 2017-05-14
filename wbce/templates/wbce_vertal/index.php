<?php
//Das ist ein einzeiliger PHP-Kommentar. Er beginnt mit //
/*Das ist ein mehrzeiliger PHP-Kommentar, Er beginnt mit /* und endet mit  */

//Diese Kommentare helfen dir im folgenden, ein modernes Template zu verstehen.




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
	if ($wb->ami_group_member('1')) {$template_edit_link = true;}
	
	$refreshstring = '?rs='.time(); //forces refresh
} 




//============================================================================================================
//Der folgende Bereich ist zu 99% bei allen modernen Templates gleich. 
//Du wirst hier bis fast zum Ende des <head> nichts aendern muessen
//============================================================================================================

//So kannst du feststellen, ob die Seite die Startseite ist und dann die Ausgabe anders machen:
$isstartpage = false;
if ( !isset($page_id) ) { $isstartpage = true; }

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
$mainmenu = show_menu2(1, SM2_ROOT, SM2_ALL, SM2_ALL|SM2_BUFFER, '<li class="[class] lev[level]"><a href="[url]" target="[target]" class="lev[level] [class]" data-pid=[page_id]><span>[menu_title]</span></a>', '</li>', '<ul class="ullev[level]">', '</ul>', false, false);

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
//Hier verwenden wir zb den Flexlider.
?>
<link rel="stylesheet" href="<?php echo TEMPLATE_DIR; ?>/FlexSlider/flexslider.css" type="text/css" media="screen" />


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
<a style="display:none;" href="#beginContent">go to content</a>
<div id="mobiletop">
	<a tabindex="-1" id="menuswitch" onclick="showmenu();return false;" href="#"><img src="<?php echo TEMPLATE_DIR; ?>/img/mobilemenu.png" alt="Mobile Menu" /></a>
	<a tabindex="-1" class="mobilelogo" href="<?php echo WB_URL; ?>"><img src="<?php echo TEMPLATE_DIR; ?>/img/logo.png" alt="to Homepage" /></a>
	<div class="clearer"></div>
</div>
<div class="mainwrapper">
<div id="leftbox">
	<div class="menubox">
	<a tabindex="-1" class="logo" href="<?php echo WB_URL; ?>"><img src="<?php echo TEMPLATE_DIR; ?>/img/logo.png" alt="go to homepage" /></a>
	<div role="navigation" id="nav">
		<?php echo $mainmenu; ?>
	</div><!--end nav-->
	<?php 
		//Das Suche-Feld laden wir einfach per include, wenn die Suche eingeschaltet ist:
		if (SHOW_SEARCH) { include 'inc/search.inc.php'; }
		
		//Das gleiche mit dem Login-Bereich. Dieser ist normalerweise ausgeschaltet. Hier wird er mit AJAX nachgeladen. 
		if(FRONTEND_LOGIN) { echo '<div id="showlogin"><a href="#" onclick="showloginbox(); return false;"><img src="'.TEMPLATE_DIR.'/img/key.png" alt="K" /></a><div id="login-box" style="display:none"></div></div><!--LOGIN_URL, LOGOUT_URL,FORGOT_URL-->';} 
		
		//und optional machen wir einen Edit-Schalter fuer den Admin:
		if ($template_edit_link == true) { echo '<a tabindex="-1" class="template_edit_link" href="'.ADMIN_URL.'/pages/modify.php?page_id='.PAGE_ID.'" target="_blank"></a>'; } 
	?>	
	</div><!-- end menubox -->
	</div><!-- end leftbox -->
	
	
	<div class="mainbox">
	<div class="header" role="banner">	
	<?php
	/*jetzt wirds knifflig ;-)
	Regel 1: Wenn $contentblock[10] Inhalt hat: diesen ausgeben. 	
	Ansonsten: Wenn das die Startseite ist, laden wir den Flexslider
	Ansonsten: Einfach einen Bereich mit einem Bild
	*/
	if ($contentblock[10] != '') {
			echo '<div class="widetop">'.$contentblock[10].'</div>';
	} else {
		if ($isstartpage) {
			include('flexslider.php');
		} else {
			echo '<div id="static_headerbox"><img src="'.TEMPLATE_DIR.'/img/static_header.jpg" alt="" /></div>';	
		}	
	}
	//War doch gar nicht so schwer ;-)	
	
	?>	
	<div id="menucheck"></div>
	</div><!-- // header -->
	<div id="beginContent"></div>
	<?php  
	//Block 3 Wird oft fuer Teaser, Videos oder anderes verwendet. Ist daher breit und ohne Abstand:
	if ($contentblock[3] != '') { ?>
		<div class="widetop"><div class="contentinner"><?php echo $contentblock[3]; ?></div></div><!-- //widetop --> 
	<?php } ?>
	
	
	<div class="contentbox"><?php 
	//Und jetzt die 2 Hauptbloecke
	//Hier gilt: Wenn Block 2 leer ist, ist Block 1 breiter.
	//Das legen wir einfach mit einer class fest:
	if ($contentblock[2] == '') {$mainblockclass = 'contentwide'; } else {$mainblockclass = 'contentnarrow'; }
	?>
	<div role="main" class="content <?php echo $mainblockclass; ?>"><div class="contentinner"><?php echo $contentblock[1]; ?></div></div><!-- //content -->
	
	<?php
	//und jetzt die sidebar
	//diese wird nur ausgegeben, wenn sie Inhalt hat:
	if ($contentblock[2] != '') { ?>
	<div role="complementary" class="sidebar"><div class="contentinner"><?php echo $contentblock[2]; ?></div></div><!-- //sidebar -->
	<?php } ?>
	
	</div><!-- //contentbox -->
	<?php
	//und danach Block 4, dieser ist meistens ganz unten und in ganzer Breite:
	//Auch dieser wird nur ausgegeben, wenn er Inhalt hat:
	//Zur Abwechslung geben wir ihn komplett mit echo aus:
	if ($contentblock[4] != '') {
		echo '<div class="widebottom"><div class="contentinner">'.$contentblock[4].'</div></div><!-- //widebottom -->'; 
	}
	
	
	//Jetzt kommen wir zum Footer:
	?>
	<div class="clearer"></div>
	<div role="contentinfo" class="footer">
	<a id="gototopswitch" href="#" onclick="gototop();return false;"><img src="<?php echo TEMPLATE_DIR;?>/img/up.png" alt="up" title="Go to top"></a>
	<div class="contentinner">
	<?php page_footer();  ?>	
	</div></div><!-- //footer -->	
	</div><!-- //mainbox -->
	<div class="clearer"></div>
	</div><!-- //mainwrapper -->
		
	<?php
	
	//Ganz am Ende laden wir noch die Javascript Dateien fuer Module
	if (function_exists('register_frontend_modfiles_body')) { register_frontend_modfiles_body(); } 
	
	//und zum Ende noch das Javascript fuer cookie_permission und das Template.
	?>
	<script>
		var cookie_permission_url = "<?php echo TEMPLATE_DIR?>/inc/cookie_permission.php?lang=<?php echo LANGUAGE?>";
	</script>	
	<script type="text/javascript" src="<?php echo TEMPLATE_DIR; ?>/template.js?<?php echo $refreshstring; ?>"></script>
	
	<?php 
	//Und das ist der Farbwaehler. Du kannst das loeschen, wenn du die Farben fixiert hast.
	if ($template_edit_link == true) {include 'colorset/colorpicker.inc.php';} 
	?>	
	
</body>
</html>