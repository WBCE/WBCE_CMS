//:Load the view.php from any other section-module
//:Use [[SectionPicker?sid=123]] or use [[SectionPicker?sid=123&anchor=MyAnchor]] if you want to have a anchor added before the section
$sid = isset($sid) ? intval($sid) : 0;
$anchor = isset($anchor) ? (string) $anchor : '';
return get_section_content($sid, false, $anchor);
