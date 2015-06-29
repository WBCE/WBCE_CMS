//:Load the view.php from any other section-module
//:Use [[SectionPicker?sid=123]]
global $database, $wb, $TEXT, $DGTEXT;
$content = '';
$sid = isset($sid) ? intval($sid) : 0;
if( $sid ) {
	$sql  = 'SELECT `module` FROM `'.TABLE_PREFIX.'sections` ';
	$sql .= 'WHERE `section_id`='.$sid;
	if (($module = $database->get_one($sql))) {
		if (is_readable(WB_PATH.'/modules/'.$module.'/view.php')) {
			$_sFrontendCss = '/modules/'.$module.'/frontend.css';
			if(is_readable(WB_PATH.$_sFrontendCss)) {
				$_sSearch = preg_quote(WB_URL.'/modules/'.$module.'/frontend.css', '/');
				if(preg_match('/<link[^>]*?href\s*=\s*\"'.$_sSearch.'\".*?\/>/si', $wb_page_data)) {
					$_sFrontendCss = '';
				}else {
					$_sFrontendCss = '<link href="'.WB_URL.$_sFrontendCss.'" rel="stylesheet" type="text/css" media="screen" />';
				}
			} else { $_sFrontendCss = ''; }
			ob_start();
			$oldSid = $section_id; // save old sectionID
			$section_id = $sid;
			require(WB_PATH.'/modules/'.$module.'/view.php');
			$content = $_sFrontendCss.ob_get_clean();
			$section_id = $oldSid; // restore old sectionID
		}
	}
}
return $content;