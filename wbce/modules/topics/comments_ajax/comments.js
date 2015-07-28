dorestoreit();


// http://stackoverflow.com/questions/5004233/jquery-ajax-post-example-with-php

// variable to hold request
var request;
// bind to the submit event of our form
//$("#c_mment_form").submit(function(event){

function submitform() {
    // abort any pending request
    if (request) {
        request.abort();
    }
	if (validateForm() != true) return false;
	
	
    // setup some local variables
   //var $form = $(this);
	var $form = $("#c_mment_form");
    // let's select and cache all the fields
    var $inputs = $form.find("input, select, button, textarea, hidden");
    // serialize the data in the form
    var serializedData = $form.serialize();

    // let's disable the inputs for the duration of the ajax request
    // Note: we disable elements AFTER the form data has been serialized.
    // Disabled form elements will not be serialized.
    $inputs.prop("disabled", true);

    // fire off the request to /form.php
    request = $.ajax({
        url: MOD_URL+"/comments_ajax/submit.php",
        type: "post",
        data: serializedData
    });

    // callback handler that will be called on success
    request.done(function (response, textStatus, jqXHR){
		
		document.getElementById('c_mment_area').innerHTML = response;
		
        // log a message to the console
        console.log("Hooray, it worked!" + response);
    });

    // callback handler that will be called on failure
    request.fail(function (jqXHR, textStatus, errorThrown){
        // log the error to the console
        console.error(
            "The following error occured: "+
            textStatus, errorThrown
        );
    });

    // callback handler that will be called regardless
    // if the request failed or succeeded
    request.always(function () {
        // reenable the inputs
        $inputs.prop("disabled", false);
    });

    // prevent default posting of form
    event.preventDefault();
}




function validateForm() { 	
	erc = 0;
	err = errorArr[0];
	n = document.c_mment_form.thenome.value;
	if (n.length < 3) {erc ++; err += '\n - '+errorArr[1];} else {
			dostoreit('thenome',n);
		}
	
	m = document.c_mment_form.themoil.value;
	//if (m != '') { //Wenn vorhanden, dann muss gültig sein
		p1=m.indexOf('@');
		p2=m.indexOf('.');
		if (emailsettings > 1) { 
			if ((p1 * p2) < 6) {erc ++; err += '\n - eMa'+'il';} 
		} 
		dostoreit('themoil',m);
	//}
	
	w = document.c_mment_form.thesote.value;
	if (w.length > 10) {dostoreit('thesote',w);	}
	
	
	c = document.c_mment_form.c0mment.value;
	ct = c.length+1;
	//alert (ct);	
	if (ct < 10)  {erc ++; err += '\n - '+errorArr[2];} else {dostoreit('c0mment'+topic_id,c);	}
	
	
	try {
	if ( document.c_mment_form.captcha) {
		c = document.c_mment_form.captcha.value;
		if (c=='') {erc ++; err += '\n - '+errorArr[3];}	
	}
	} finally {}
	
	
	if (erc > 0) {
		alert(err);
		return false;
		//document.returnValue = false; //(err == '');
	} else {
	return true;
	//document.returnValue = true;
	}

}

function dostoreit(k,v) {
 	console.log("Speichern: k:" + k + ' v: ' + v);
	localStorage.setItem(k, v);
}

function dorestoreit() {
	if (localStorage['thenome']) {document.c_mment_form.thenome.value = localStorage['thenome'];} 
	if (localStorage['themoil']) {document.c_mment_form.themoil.value = localStorage['themoil']; }
	if (localStorage['thesote']) {document.c_mment_form.thesote.value = localStorage['thesote']; }
	if (localStorage['c0mment'+topic_id]) {document.c_mment_form.c0mment.value = localStorage['c0mment'+topic_id]; }
	
}