<?php
/**
 * Website Baker Theme
 * Additional content to include to the information center of the wb backend
 *
 * Part off: Website Baker theme advanced_theme_wb_flat
 * More information see: info.php in main theme folder
*/


if (!defined('WB_PATH')) {
    // include wb system data/functions
    include('../../../../config.php');
}

// --- check if logged in 
$bLoggedIn = (isset($_SESSION['USER_ID']) && is_numeric($_SESSION['USER_ID']));

// go on only forward if logged in
if ($bLoggedIn) {
	


	
	// ### IMPORTANT ########################################################################
	// 
	// NOTE: 
	// This file only generates the outer structure for the content file of the togglebox
	// This makes sure, that all included content has the same css base and size.
	//
	// ### IF YOU WANT TO CREATE CONTENT TO THE WB INFORMATION CENTER 
	// ### DON'T DO IT IN THIS FILE !!!
	// ### 
	// ### --> TO PREPARE CONTENT TO WB INFORMATION CENTER USE FILE informationcenter.content.php
	// ### --> in the same directory
	//
	// ######################################################################################
?>	



	<!DOCTYPE html>
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

		<head>

			<meta http-equiv="Content-Type" content="text/html; charset=<?php echo defined('DEFAULT_CHARSET') ? DEFAULT_CHARSET : 'utf-8'; ?>" />

			<!-- Load Google Fonts -->
			<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,700italic' rel='stylesheet' type='text/css'>

			
			<!-- Load FontAwesome -->
			<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" type="text/css" rel="stylesheet">

			<!-- prepare backend-css to include html -->
			<link href="<?php echo THEME_URL; ?>/theme.css" rel="stylesheet" type="text/css" media="screen" />
			<link href="<?php echo THEME_URL; ?>/contentinclude/informationcenter/informationcenter.css" rel="stylesheet" type="text/css" media="screen" />
				
			<!-- jQuery-lib (get lib from relative path of wb system folder) -->
			<script src="../../../../include/jquery/jquery-min.js" type="text/javascript"></script>

			
		</head>
		<body class="scroll-behaviour">

			<?php
			// Just include content to the body
			$wb_informationcenter_path = THEME_PATH . '/contentinclude/informationcenter/informationcenter.content.php';
			include($wb_informationcenter_path);
			?>

			
			
			
			<script>
				// fallback fontAwesome -- > load from own server if contact to maxcdn.bootstrapcdn.com fails
				// use also for developing on local stations if there is no contact to internet (= local installed webserver)
				(function($){
					var $span = $('<span class="fa" style="display:none"></span>').appendTo('body');
					if ($span.css('fontFamily') !== 'FontAwesome' ) {
						// Fallback Link
						$('head').append('<link href="../../webfonts/fontawesome_include/font-awesome.4.2.0.min.css" type="text/css" rel="stylesheet">');
					}
					$span.remove();
				})(jQuery);
			</script>
			

		</body>

	</html>



	
<?php }  // endif ($bLoggedIn) ?>











