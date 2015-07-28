function change_os(type) {
	if(type == 'linux') {
		document.getElementById('file_perms_box1').style.display = 'block';
		document.getElementById('file_perms_box2').style.display = 'block';
		document.getElementById('file_perms_box3').style.display = 'block';
	} else if(type == 'windows') {
		document.getElementById('file_perms_box1').style.display = 'none';
		document.getElementById('file_perms_box2').style.display = 'none';
		document.getElementById('file_perms_box3').style.display = 'none';
	}
}

function change_wbmailer(type) {
	if(type == 'smtp') {
		document.getElementById('row_wbmailer_smtp_settings').style.display = '';
		document.getElementById('row_wbmailer_smtp_host').style.display = '';
		document.getElementById('row_wbmailer_smtp_auth_mode').style.display = '';
		document.getElementById('row_wbmailer_smtp_username').style.display = '';
		document.getElementById('row_wbmailer_smtp_password').style.display = '';
	} else if(type == 'phpmail') {
		document.getElementById('row_wbmailer_smtp_settings').style.display = 'none';
		document.getElementById('row_wbmailer_smtp_host').style.display = 'none';
		document.getElementById('row_wbmailer_smtp_auth_mode').style.display = 'none';
		document.getElementById('row_wbmailer_smtp_username').style.display = 'none';
		document.getElementById('row_wbmailer_smtp_password').style.display = 'none';
	}
}
/*  */
function toggle_wbmailer_auth() {
		document.getElementById('row_wbmailer_smtp_username').style.display = '';
		document.getElementById('row_wbmailer_smtp_password').style.display = '';
		document.settings.wbmailer_smtp_auth.checked == true;
/*
	if( document.settings.wbmailer_smtp_auth.checked == true ) {
		document.getElementById('row_wbmailer_smtp_username').style.display = '';
		document.getElementById('row_wbmailer_smtp_password').style.display = '';
	} else {
		document.getElementById('row_wbmailer_smtp_username').style.display = 'none';
		document.getElementById('row_wbmailer_smtp_password').style.display = 'none';
	}
*/
}
