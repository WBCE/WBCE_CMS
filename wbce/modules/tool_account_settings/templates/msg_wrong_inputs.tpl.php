<?php 
// prevent this template file from being accessed directly
defined('WB_PATH') or die("Cannot access this file directly"); 
I::insertCssFile(get_url_from_path(__DIR__).'/forms.css', 'HEAD BTM+');
?>

<h1><?=$TOOL_TXT['HEADING_ERROR']; ?></h1>

<div class="alert alert-warning">
<p><?=$TOOL_TXT['GENERIC_ERROR_MESSAGE']; ?></p>
<p><?=$TOOL_TXT['CONTACT_ADMINISTRATOR']; ?></p>
</div>

