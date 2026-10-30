//:Randomly display one WYSIWYG section from a given list
//:Use [[ShowRandomWysiwyg?section=10,12,15,20]] possible Delimiters: [ ,;:|-+#/ ]
global $database, $wb;
$content = '';
// get_section_content() is only loaded in the frontend (index.php)
if (isset($section) && function_exists('get_section_content')
    && preg_match('/^[0-9]+(?:\s*[\,\|\-\;\:\+\#\/]\s*[0-9]+\s*)*$/', $section)) {
    // split and sanitize arguments, pick one at random
    $aSections = preg_split('/[\s\,\|\-\;\:\+\#\/]+/', $section);
    $iSection  = (int) $aSections[array_rand($aSections)];
    $aSection  = get_section_array($iSection);
    // only WYSIWYG sections the visitor may see: page visibility, viewing
    // groups/users and the section's publication period; pages with
    // visibility 'none' serve as content stores and are allowed
    if (!empty($aSection) && $aSection['module'] === 'wysiwyg') {
        $aPage = null;
        $rPage = $database->query(
            'SELECT `page_id`, `visibility`, `viewing_groups`, `viewing_users` FROM `{TP}pages` WHERE `page_id` = '.(int) $aSection['page_id']
        );
        if ($rPage) {
            $aPage = $rPage->fetchRow(MYSQLI_ASSOC);
        }
        $iNow   = time();
        $iStart = (int) $aSection['publ_start'];
        $iEnd   = (int) $aSection['publ_end'];
        if (!empty($aPage) && ($aPage['visibility'] === 'none' || $wb->page_is_visible($aPage))
            && ($iStart === 0 || $iNow >= $iStart) && ($iEnd === 0 || $iNow <= $iEnd)) {
            $content = get_section_content($iSection);
        }
    }
}
return $content;
