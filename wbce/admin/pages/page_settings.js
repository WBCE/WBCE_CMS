function toggle_viewers() {
	if(document.settings.visibility.value == 'private' || document.settings.visibility.value == 'registered') {
		document.getElementById('allowed_viewers').style.display = 'block';
	} else {
		document.getElementById('allowed_viewers').style.display = 'none';
	}
}
var lastselectedindex = new Array();

function disabled_hack_for_ie(sel) {
	var sels = document.getElementsByTagName("select");
	var i;
	var sel_num_in_doc = 0;
	for (i = 0; i <sels.length; i++) {
		if (sel == sels[i]) {
			sel_num_in_doc = i;
		}
	}
	// never true for browsers that support option.disabled
	if (sel.options[sel.selectedIndex].disabled) {
		sel.selectedIndex = lastselectedindex[sel_num_in_doc];
	} else {
		lastselectedindex[sel_num_in_doc] = sel.selectedIndex;
	}
	return true;
}