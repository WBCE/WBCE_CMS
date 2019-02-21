<?php 
// prevent this template file from being accessed directly
defined('WB_PATH') or die("Cannot access this file directly"); 
I::insertCssFile(get_url_from_path(__DIR__).'/forms.css', 'HEAD BTM+');
?>

<h1><?=$TEXT['SUCCESS']; ?></h1>

<div class="alert alert-success">
<p><?=$TEXT['REGISTER_USER_ACTIVATED']; ?></p>
</div>


