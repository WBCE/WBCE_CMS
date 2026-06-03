// Copyright 2006 Stepan Riha
// www.nonplus.net

JsAdmin.init_tool = function() {
	var instruction = document.getElementById('jsadmin_install');
	if(instruction) {
		instruction.style.display = 'none';
	}
	var form = document.getElementById('jsadmin_form');
	if(form) {
		form.style.display = '';
	}
};
