//:Display one defined WYSIWYG section
//:Use [[ShowWysiwyg?section=10]]
global $database, $section_id, $module;
	$content = '';
	$section = isset($section) ? intval($section) : 0;
	if ($section) {
		if (is_readable(WB_PATH.'/modules/wysiwyg/view.php')) {
		// if valid section is given and module wysiwyg is installed
			$iOldSectionId = intval($section_id); // save old SectionID
			$section_id = $section;
			ob_start(); // generate output by regulary wysiwyg module
			require(WB_PATH.'/modules/wysiwyg/view.php');
			$content = ob_get_clean();
            $section_id = $iOldSectionId; // restore old SectionId
		}
	}
return $content;