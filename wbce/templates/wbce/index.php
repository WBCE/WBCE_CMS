<!DOCTYPE HTML>
<html lang="de-de" dir="ltr">
<head>
  <?php
$includefile = WB_PATH . '/modules/wbstats/count.php';
if (file_exists($includefile)) {
    include_once $includefile;
}
if (function_exists('simplepagehead')) {
    simplepagehead();
} else {?>
	<title><?php page_title();?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php if (defined('DEFAULT_CHARSET')) {echo DEFAULT_CHARSET;} else {echo 'utf-8';}
    ?>" />
	<meta name="description" content="<?php page_description();?>" />
	<meta name="keywords" content="<?php page_keywords();?>" />
<?php }
if (function_exists('register_frontend_modfiles')) {
    register_frontend_modfiles('css');
    register_frontend_modfiles('jquery');
    register_frontend_modfiles('js');
}
?>
	<link href='http://fonts.googleapis.com/css?family=Dosis:400,300,700' rel='stylesheet' type='text/css'>
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link href="<?php echo TEMPLATE_DIR;?>/fitgrid.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo TEMPLATE_DIR;?>/sm-core-css.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo TEMPLATE_DIR;?>/sm-clean.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo TEMPLATE_DIR;?>/wbce.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="<?php echo TEMPLATE_DIR;?>/jquery.smartmenus.js"></script>

	<script type="text/javascript">
	$(function() {
		$('#main-menu').smartmenus({
			mainMenuSubOffsetX: -1,
			subMenusSubOffsetX: 10,
			subMenusSubOffsetY: 0
		});
	});
	</script>

<?php
$headerbild_id = "";
$takeit = false;

if (PARENT == 0) {
    if (file_exists(WB_PATH . '/media/heroimages/hero_' . PAGE_ID . '.jpg')) {
        $headerbild_id = PAGE_ID;
        $takeit = true;
    }
} else {
    if (file_exists(WB_PATH . '/media/heroimages/hero_' . PARENT . '.jpg')) {
        $headerbild_id = PARENT;
        $takeit = true;
    }
    if (file_exists(WB_PATH . '/media/heroimages/hero_' . PAGE_ID . '.jpg')) {
        $headerbild_id = PAGE_ID;
        $takeit = true;
    }
}
if ($takeit) {
    ?>
<style type="text/css">
.hero {
	background-image:url(<?php echo WB_URL . '/media/heroimages/hero_' . $headerbild_id . '.jpg';?>);
}
</style>

<?php
echo "\n<!--WB_URL: " . WB_URL . " - PARENT: " . PARENT . " - PAGE_ID: " . PAGE_ID . " - Headerbild: " . $headerbild_id . " -->";
}
?>



</head>

<body>
<?php
ob_start();
page_content(1);
$main = ob_get_contents();
ob_end_clean();
ob_start();
page_content(2);
$fulltop = ob_get_contents();
ob_end_clean();
ob_start();
page_content(3);
$left = ob_get_contents();
ob_end_clean();
ob_start();
page_content(4);
$right = ob_get_contents();
ob_end_clean();
ob_start();
page_content(5);
$fullbottom = ob_get_contents();
ob_end_clean();
ob_start();
page_content(6);
$hero = ob_get_contents();
ob_end_clean();
$contentheadline = "";
if ($hero) {
    ?>
<div class="hero">
<?php } else {
    $contentheadline = "<h1>" . PAGE_TITLE . "</h1>";
}
?>
	<div class="navbg">
	<div class="center">
		<div class="row">
			<div class="fg12 nav">
			<a id="menu-button"><img src="<?php echo TEMPLATE_DIR;?>/menu.png" alt="" width="32" height="32" border="0" /></a>
			<?php
show_menu2(
    $aMenu = 1,
    $aStart = SM2_ROOT,
    $aMaxLevel = SM2_ALL,
    $aOptions = SM2_ALL,
    $aItemOpen = '[li][a][menu_title]</a>',
    $aItemClose = '</li>',
    $aMenuOpen = '<ul>',
    $aMenuClose = '</ul>',
    $aTopItemOpen = false,
    $aTopMenuOpen = '<ul id="main-menu" class="sm sm-clean collapsed">'
);?>
			</div>
		</div>
		</div>
		<div class="clearfix"></div>
		</div>
<?php if ($hero) {?>
		<div class="row">
			<div class="fg12 top">
				<?php echo "<h1>" . PAGE_TITLE . "</h1>";?>
			</div>
		</div>
		<div class="row">
			<?php echo $hero;?>
		</div>
		<div class="clearfix"></div>
	</div>
<?php }
?>
<div class="center">

<?php if ($fulltop) {?>
<div class="row">
	<div class="fg12">
		<?php echo $fulltop;?>
	</div>
</div>
<?php }
?>

<div class="row contents">
<?php if ($left && $main && $right) {?>
    <div class="fg4"><?php echo $left?></div>
    <div class="fg4"><?php echo $main?></div>
    <div class="fg4"><?php echo $right?></div>
<?php } elseif ($right && $main) {?>
    <div class="fg8">
	<?php echo $contentheadline;?>
	<?php echo $main?>
	</div>
    <div class="fg4"><?php echo $right?></div>
<?php } elseif ($left && $main) {?>
    <div class="fg4"><?php echo $left?></div>
    <div class="fg8">
	<?php echo $contentheadline;?>
	<?php echo $main?>
	</div>
<?php } elseif ($main) {?>
    <div class="fg12">
	<?php echo $contentheadline;?>
	<?php echo $main?>
	</div>
</div>
<?php }
if ($fullbottom) {?>
<div class="row">
<div class="fg12"><?php echo $fullbottom?></div>
</div>
<?php }
?>
<div class="row">
<div class="fg12 footer"><?php echo WEBSITE_FOOTER;?></div>
</div>


<script type="text/javascript">
$(function() {
  $('#menu-button').click(function() {
    var $this = $(this),
        $menu = $('#main-menu');
    if (!$this.hasClass('collapsed')) {
      $menu.addClass('collapsed');
      $this.addClass('collapsed');
    } else {
      $menu.removeClass('collapsed');
      $this.removeClass('collapsed');
    }
    return false;
  }).click();
});
</script>
<?php if (file_exists(WB_PATH . '/.htaccess')) {?>
	[[ShortURL]]
<?php }
?>
</body>
</html>
