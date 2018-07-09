<?php 
// prevent this template file from being accessed directly
defined('WB_PATH') or die("Cannot access this file directly"); 
loadCssFile(WB_URL.'/account/templates/forms.css', 'HEAD TOP+');
?>

<h1><?=$TEXT['SUCCESS']; ?></h1>
<div class="alert alert-success">
<p><?=$TEXT['REGISTER_THANKYOU']; ?></p>
<p><?=$TEXT['REGISTER_LOGIN_SENT_TO_USER']; ?></p>
<p><?=$TEXT['REGISTER_GENEREC_EMAIL_NOT_RECIEVED']; ?></p>
</div>


