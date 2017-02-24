<?php
if(!defined('WB_URL')) { header('Location: ../index.php'); exit(0); }

//Dies hier wird nur geladen, wenn die Suche eingeschaltet ist
?>
<div role="search" id="search_box">			
	<form name="search" id="search" action="<?php echo WB_URL; ?>/search/index.php" method="get">
	<input type="hidden" name="referrer" value="<?php echo defined('REFERRER_ID')?REFERRER_ID:PAGE_ID;?>" />
	<label><span style="display:none;"><?php echo $TEXT['SEARCH']; ?></span>
	<input type="text" value="<?php if (isset($_GET['string'])) {echo htmlspecialchars($_GET['string']);} else {echo $TEXT['SEARCH']; } ?>" id="searchstring" name="string" class="searchstring" onfocus="if (this.value=='<?php echo $TEXT['SEARCH']; ?>') {this.value='';}" onkeyup="initsuggestion(this.value);"/>
	</label>
	<input type="image"  class="submitbutton" src="<?php echo TEMPLATE_DIR; ?>/img/searchbutton.png" alt="Start" />
	</form><div id="suggestbox"></div>
</div><!-- end searchbox -->