<?php ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php if(defined('DEFAULT_CHARSET')) { echo DEFAULT_CHARSET; } else { echo 'utf-8'; }?>" />
<meta http-equiv="content-language" content="de" />
<link href="<?php echo WB_URL.'/modules/'.$mod_dir; ?>/backend.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript"> 
var WB_URL = '<?php echo WB_URL; ?>';
var THEME_URL = '<?php echo THEME_URL; ?>';
var ADMIN_URL = '<?php echo ADMIN_URL; ?>';
</script>
<script src="<?php echo WB_URL; ?>/include/jquery/jquery-min.js" type="text/javascript"></script>
<script src="<?php echo WB_URL; ?>/include/jquery/jquery-insert.js" type="text/javascript"></script>
<!--[if lt IE 7]><script type="text/javascript" src="<?php echo WB_URL; ?>/include/jquery/plugins/jquery-pngFix.js"></script><![endif]-->

<script type="text/javascript" src="<?php echo WB_URL.'/modules/'.$mod_dir; ?>/backend.js"></script>
<script type="text/javascript"> 
function confirm_link(message, url) {
	if(confirm(message)) location.href = url;
}
</script>
</head>
<body class="freditbody"><div class="freditcontainer">
