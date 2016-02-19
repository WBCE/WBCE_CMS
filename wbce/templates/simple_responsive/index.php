<?php
// no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));
?><!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
  <?php
$includefile = WB_PATH . '/modules/wbstats/count.php';
if (file_exists($includefile)) {
    include_once $includefile;
}
if (function_exists('simplepagehead')) {
    simplepagehead();
} else {
    ?>
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
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<!-- Mobile viewport -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
  <link rel="shortcut icon" href="images/favicon.ico"  type="image/x-icon">
<!-- CSS-->
<!-- Google web fonts. You can get your own bundle at http://www.google.com/fonts. Don't forget to update the CSS accordingly!-->

  <link rel="stylesheet" href="<?php echo TEMPLATE_DIR;?>/css/normalize.css">
  <link rel="stylesheet" href="<?php echo TEMPLATE_DIR;?>/js/flexslider/flexslider.css">
  <link rel="stylesheet" href="<?php echo TEMPLATE_DIR;?>/css/basic-style.css">
<!-- end CSS-->
<!-- JS-->
  <script src="<?php echo TEMPLATE_DIR;?>/js/libs/modernizr-2.6.2.min.js"></script>
<!-- end JS-->
</head>

<body id="home">

<!-- header area -->
    <header class="wrapper clearfix">

        <div id="banner">
        	<div id="logo"><a href=""><img src="<?php echo TEMPLATE_DIR;?>/images/basic-logo.png" alt="logo"></a></div>
        </div>

        <!-- main navigation -->
        <nav id="topnav" role="navigation">
        <div class="menu-toggle">Menu</div>
          <?php
show_menu2(
    $aMenu = 1,
    $aStart = SM2_ROOT,
    $aMaxLevel = SM2_ALL,
    $aOptions = SM2_ALL,
    $aItemOpen = '<li[if(class==menu-current){ class="current"}]>[a][menu_title]</a>',
    $aItemClose = '</li>',
    $aMenuOpen = '<ul>',
    $aMenuClose = '</ul>',
    $aTopItemOpen = false,
    $aTopMenuOpen = '<ul class="srt-menu" id="menu-main-navigation">'
);
?>
		</nav><!-- #topnav -->
    </header><!-- end header -->


<section id="page-header" class="clearfix">
<!-- responsive FlexSlider image slideshow -->
  <div class="wrapper">
	<h1><?php echo page_title();?></h1>
  </div>
</section>

<!-- main content area -->
<div class="wrapper" id="main">
<!-- content area -->
	<section id="content">
    <?php echo page_content(1);?>
    </section><!-- #end content area -->

    <!-- sidebar -->
    <aside>
    <?php echo page_content(2);?>
    </aside><!-- #end sidebar -->

  </div><!-- #end div #main .wrapper -->



<!-- footer area -->
<footer>
	<div id="colophon" class="wrapper clearfix">
    	<?php echo page_footer();?>
    </div>

    <!--You can NOT remove this attribution statement from any page, unless you get the permission from prowebdesign.ro--><div id="attribution" class="wrapper clearfix" style="color:#666; font-size:11px;">Site built with <a href="http://www.prowebdesign.ro/simple-responsive-template/" target="_blank" title="Simple Responsive Template is a free software by www.prowebdesign.ro" style="color:#777;">Simple Responsive Template</a></div><!--end attribution-->

</footer><!-- #end footer area -->


<!-- jQuery -->
<script>window.jQuery || document.write('<script src="<?php echo TEMPLATE_DIR;?>/js/libs/jquery-1.9.0.min.js">\x3C/script>')</script>
<script defer src="<?php echo TEMPLATE_DIR;?>/js/flexslider/jquery.flexslider-min.js"></script>
<!-- fire ups - read this file!  -->
<script src="<?php echo TEMPLATE_DIR;?>/js/main.js"></script>
<?php if (file_exists(WB_PATH . '/.htaccess')) {?>
	[[ShortURL]]
<?php }
?>
</body>
</html>
