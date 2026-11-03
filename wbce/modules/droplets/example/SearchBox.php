//:Create a searchbox
//:Creates a serachbox on the position of [[searchbox]]. Optional parameter "?msg=the search message"
global $TEXT;
$return_value = true;
if (!isset($msg)) {
    $msg='search this site..';
}
// droplet arguments arrive HTML-encoded; decode once, then encode for each
// context: HTML attribute, and JavaScript string inside an attribute
$sMsg     = html_entity_decode((string)$msg, ENT_QUOTES, 'UTF-8');
$sMsgAttr = htmlspecialchars($sMsg, ENT_QUOTES, 'UTF-8');
$sMsgJs   = htmlspecialchars(json_encode($sMsg, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');
$j = "onfocus=\"if(this.value==$sMsgJs){this.value='';this.style.color='#000';}else{this.select();}\"
        onblur=\"if(this.value==''){this.value=$sMsgJs;this.style.color='#b3b3b3';}\"";
if (SHOW_SEARCH) {
    $return_value  = '<div class="searchbox">';
    $return_value  .= '<form action="'.WB_URL.'/search/index'.PAGE_EXTENSION.'" method="get" name="search" class="searchform" id="search">';
    $return_value  .= '<input type="hidden" name="referrer" value="'.PAGE_ID.'" />';
    $return_value  .= '<input style="color:#b3b3b3;" type="text" name="string" size="25" class="textbox" value="'.$sMsgAttr.'" '.$j.'  />&nbsp;';
    $return_value  .= '</form>';
    $return_value  .= '</div>';
}
return $return_value;
