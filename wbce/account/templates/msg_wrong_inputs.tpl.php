<?php 
// prevent this template file from being accessed directly
defined('WB_PATH') or die("Cannot access this file directly"); 
loadCssFile(WB_URL.'/account/templates/forms.css', 'HEAD TOP-');
?>

<h1><?=$TXT_ACCOUNT['HEADING_ERROR']; ?></h1>

<div class="alert alert-warning">
<p><?=$TXT_ACCOUNT['GENERIC_ERROR_MESSAGE']; ?></p>
<p><?=$TXT_ACCOUNT['CONTACT_ADMINISTRATOR']; ?></p>
</div>

