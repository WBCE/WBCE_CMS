//:Create a searchbox
//:Creates a serachbox on the position of [[searchbox]]. Optional parameter "?msg=the search message"
global $TEXT;
$return_value = true;
if (!isset($msg)) $msg='search this site..';
$j = "onfocus=\"if(this.value=='$msg'){this.value='';this.style.color='#000';}else{this.select();}\"
        onblur=\"if(this.value==''){this.value='$msg';this.style.color='#b3b3b3';}\"";
if(SHOW_SEARCH) { 
	$return_value  = '<div class="searchbox">';
	$return_value  .= '<form action="'.WB_URL.'/search/index'.PAGE_EXTENSION.'" method="get" name="search" class="searchform" id="search">';
	$return_value  .= '<input style="color:#b3b3b3;" type="text" name="string" size="25" class="textbox" value="'.$msg.'" '.$j.'  />&nbsp;';
	$return_value  .= '</form>';
	$return_value  .= '</div>';
}
return $return_value;
