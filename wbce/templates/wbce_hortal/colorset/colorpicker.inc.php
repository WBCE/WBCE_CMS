<?php
//Tags and Default Values

$colorArr = array();
$colorArr[0] = array('body','444444');
$colorArr[1] = array('h1, h2','1b868b');
$colorArr[2] = array('h3-6, strong','24b1b9');
$colorArr[3] = array('a','a80004');
$colorArr[4] = array('a:hover','ff171d');

$colorArr[5] = array('menu','22a9b0');
$colorArr[6] = array('menu hover','006871');
$colorArr[7] = array('menu active','b81418');

$colorArr[8] = array('light bg','eff6f6');

?>

<a id="colorpickericon" onclick="show_colorpicker(); return false" ></a>
<div id="colorpicker" >
<div id="colorpicker-info">
<p><b>Info:</b><br/>
Die Einstellung werden <b><i>auf diesem Computer</i></b> gespeichert (AutoSave), nur <b>du</b> siehst die ge&auml;nderten Farben.<br/>
RESET Local: L&ouml;scht die lokal gespeicherten &Auml;nderungen.</p>
<p>SAVE Server: Die aktuellen Einstellungen werden ver&ouml;ffentlicht.
</p>

</div>
<p>
<a href="#" id="show_colorpickerinfo" onclick="show_colorpickerinfo(); return false;" ><img style="height:18px;" src="<?php echo TEMPLATE_DIR; ?>/img/info.png" alt="Info"></a>&nbsp;&nbsp;&nbsp;
<a href="#" id="close_colorpickericon" onclick="show_colorpicker(); return false;" ><img style="height:18px;" src="<?php echo TEMPLATE_DIR; ?>/img/close.png" alt="close"></a>
</p>

<?php
$f = 0; $css_paramArr = array();
foreach ($colorArr as $cArr) {
	echo '<div class="pickerfield" style="">&nbsp;'.$cArr[0].'<br/>
	<input id="colorset_f'.$f.'" name="colorset_f'.$f.'"  type="color"  value="#'.$cArr[1].'" onchange="showcolorchanges();" />
	</div>';
	$css_paramArr[] = $cArr[1];
	$f++;

}

$css_param = implode(',',$css_paramArr);
$css_paramOrig = $css_param;

//database dummy:
$p =  __DIR__.'/param.txt';
if (file_exists($p)) {
	$css_param =  file_get_contents ($p);
	$css_paramArr = explode(',',$css_param);
	if (count($css_paramArr) < 7) $css_param = $css_paramOrig;
	
}
	



?>

<a class="colpick-button" href="#" onclick="toogle_inputs(); return false;">Toggle Type</a>
<!-- a class="colpick-button" href="#" onclick="savecolorchanges(); return false;"><b>SAVE</b><br/>Local</a -->
<a class="colpick-button" href="#" onclick="resetcolorchanges(); return false;"><b>RESET</b><br/>Local</a>
<p>&nbsp;</p>
<a class="colpick-button" href="#" onclick="submitcolorchanges(); return false;"><b>SAVE</b><br/>Server</a>



</div>
<script>
var css_param = '<?php echo $css_param; ?>';
var css_paramOrig = '<?php echo $css_paramOrig; ?>';
var TEMPLATE_DIR = '<?php echo TEMPLATE_DIR; ?>';
</script>
<script type="text/javascript" src="<?php echo TEMPLATE_DIR; ?>/colorset/colorpicker.js<?php echo $refreshstring; ?>"></script>
