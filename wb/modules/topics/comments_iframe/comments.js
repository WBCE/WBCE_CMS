dorestoreit();


function validateForm() { 	
	erc = 0;
	err = errorArr[0];
	n = document.comment.thenome.value;
	if (n.length < 3) {erc ++; err += '\n - '+errorArr[1];} else {
			dostoreit('thenome',n);
		}
	
	m = document.comment.themoil.value;
	//if (m != '') { //Wenn vorhanden, dann muss gültig sein
		p1=m.indexOf('@');
		p2=m.indexOf('.');
		if (emailsettings > 1) { 
			if ((p1 * p2) < 6) {erc ++; err += '\n - eMa'+'il';} 
		} 
		dostoreit('themoil',m);
	//}
	
	w = document.comment.thesote.value;
	if (w.length > 10) {dostoreit('thesote',w);	}
	
	
	c = document.comment.c0mment.value;
	ct = c.length+1;
	//alert (ct);	
	if (ct < 10)  {erc ++; err += '\n - '+errorArr[2];} else {dostoreit('c0mment'+topic_id,c);	}
	
	
	try {
	if ( document.comment.captcha) {
		c = document.comment.captcha.value;
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
	if (localStorage['thenome']) {document.comment.thenome.value = localStorage['thenome'];} 
	if (localStorage['themoil']) {document.comment.themoil.value = localStorage['themoil']; }
	if (localStorage['thesote']) {document.comment.thesote.value = localStorage['thesote']; }
	//if (localStorage['c0mment'+topic_id]) {document.comment.c0mment.value = localStorage['c0mment'+topic_id]; }
	
}