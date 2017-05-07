innerh = 0.9*window.innerHeight;
innerw = 0.8*window.innerWidth;
//if (innerh < 500) innerh = 500;
//if (innerw < 768) innerw = 768;

var lastopentab = 1;

$( document ).ready(function() {
	$( "body" ).append( '<div id="topics_chooser_overlay"><div class="topicpic_preview_close"><a class="topicpic_preview_closebox" href="javascript:choosethispicture(0);"></a></div><div id="topics_picturechooser"></div></div>');

	document.getElementById('topics_picturechooser').style.height = innerh+"px";
	document.getElementById('topics_chooser_overlay').style.width = innerw+"px";	
});	

function changepic(wie) {	
	if (wie == 1) {
		var bildname = document.modify.picture.value;		
		if (bildname.substr(0,7) != 'http://') {
			bildname = topicpicloc + bildname;
			document.getElementById('openpicturemodifytext').style.display="visible";
		} else { 
			document.getElementById('openpicturemodifytext').style.display="none";
		}
		document.images['topicpic'].src = bildname+'?time=' + new Date().getTime();		
	} else {
		var bildname = document.modify.picture.options[document.modify.picture.selectedIndex].value;
		o = 6 + bildname.indexOf(".gif") +  bildname.indexOf(".jpg")  +  bildname.indexOf(".png")+ bildname.indexOf(".GIF") +  bildname.indexOf(".JPG")  +  bildname.indexOf(".PNG");
		
		if (o > 0) {
			document.images['topicpic'].src = topicpicloc + bildname+'?time=' + new Date().getTime();
			document.images['topicpic'].style.display = "block";
		} else {
			document.images['topicpic'].style.display = "none";
		}
	}
}



/*--------- NewUploader ---------------*/
function showpictureupload (topic_id) {
	if (document.getElementById("pictureupload").style.display=="none") 	{
		document.getElementById("pictureupload").style.display="block";
		document.getElementById("pictureupload").innerHTML = '<iframe src="picupload/newuploader.php?page_id='+page_id+'&section_id='+section_id+'&topic_id='+topic_id+'" allowtransparency="true" scrolling="no" width="100%" height="40" border="0" name="newuploader" id="newuploader"></iframe>';
	} else {
		document.getElementById("pictureupload").style.display="none";
	}	
}

function closepictureupload () {
	document.getElementById("pictureupload").style.display="none";
	document.getElementById("pictureupload").innerHTML = '';
}

/*--------- additionalpictures ---------------*/
function reloadadditionalpictures () {
	document.getElementById("additionalpictures").innerHTML = '<iframe src="picupload/additional_pics.php?page_id='+page_id+'&section_id='+section_id+'&topic_id='+topic_id+'" allowtransparency="true" scrolling="auto" width="100%" height="200" border="0" name="additionalpicturesframe" id="additionalpicturesframe"></iframe>';
}

function showadditionalpictures () {
	if (document.getElementById("additionalpictures").style.display=="none") 	{
		document.getElementById("additionalpictures").style.display="block";
		document.getElementById("additionalpictures").innerHTML = '<iframe src="picupload/additional_pics.php?page_id='+page_id+'&section_id='+section_id+'&topic_id='+topic_id+'" allowtransparency="true" scrolling="auto" width="100%" height="200" border="0" name="additionalpicturesframe" id="additionalpicturesframe"></iframe>';
	} else {
		document.getElementById("additionalpictures").style.display="none";
	}	
}

function closeadditionalpictures () {
	document.getElementById("additionalpictures").style.display="none";
	document.getElementById("additionalpictures").innerHTML = '';
}

/*--------- END NewUploader ---------------*/

function makevisible(what) {
	document.getElementById("getfromtable").style.display="none";
	document.getElementById("presetstable").style.display="none";
	document.getElementById("assistant").style.display="none";
	document.getElementById(what).style.display="block";	
}


function topicfieldchanged() {
	var changed = 1 + parseInt(document.getElementById('topicchangedfields').value);
	document.getElementById('topicchangedfields').value = changed;
	//alert(document.getElementById('topicchangedfields').value);
}

function topictimefieldchanged (why,what) {
	if (why==1) { 
		if (what==3) { 
			document.getElementById("autoarchivetr").style.display=""; 
		} else {
			document.getElementById("autoarchivetr").style.display="none"; 
		}	
	 }
	 
	if (why==2) { 
		if (what>0) { 
			document.getElementById("autoarchive_sectionspan").style.display="inline"; 
		} else {
			document.getElementById("autoarchive_sectionspan").style.display="none"; 
		}	
	 }
}


function changesettings(sid) {

	if( !document.createElement ) {
 		alert('No createElement, sorry');
  		return;
 	}
	
	var script = document.createElement( 'script' );
	if ( script ) {
    	script.setAttribute( 'type', 'text/javascript' );
    	script.setAttribute( 'src', theurl + sid);
 		//alert(theurl + sid);
	
    	var head = document.getElementsByTagName( 'head' )[ 0 ];
    	if ( head ) {
     		head.appendChild( script );
    	}
   	}

}

function changepresets(thefile, what) {
	
	var thelanguage = "en";	
	
	if( !document.createElement ) {
 		alert('No createElement, sorry');
  		return;
 	}
	var d = new Date();
	var n = d.getMilliseconds();

	fn = 'presets-'+thelanguage+'/'+thefile+'.js?t='+n;
	if (what == 'as') {fn = 'assistant/'+thefile+'.js?t='+n;}
		
	if (thefile.substr(0,3) == '../') {fn = thefile; }
		
	if (script) { 
		head.Child( script ).setAttribute( 'src', fn ); 
	} else {
		var script = document.createElement( 'script' );
		if ( script ) {
			script.setAttribute( 'type', 'text/javascript' );
			script.setAttribute( 'src', fn );
	 
		
			var head = document.getElementsByTagName( 'head' )[ 0 ];
			if ( head ) {
				head.appendChild( script );
			}
		}
	}
}


function selectDropdownOption(element,wert) {
	for (var i=0; i<element.options.length; i++) {
		if (element.options[i].value == wert) {
			element.options[i].selected = true;		
		} else	{
			element.options[i].selected = false;	
		}
	}
}

function selectRadioButtons (element,wert) {
	for  (var i=0; i<element.length; i++) {
		element[i].checked = false; 
		if ( element[i].value == wert) { element[i].checked = true;	}
	}	
}



//-----------------------------------------------------------------------------------
//Select Pictures
//-----------------------------------------------------------------------------------
function openpicturepreviews() {	
	closepictureupload ();	
	getpicturepreviews();
	$('#topics_chooser_overlay').addClass('visible');
		
}

function closepicturepreviews() {
	$('#topics_chooser_overlay').removeClass('visible');	
}


//Ajax Script based on http://www.degraeve.com/reference/simple-ajax-example.php

function getpicturepreviews() {

//old AJAX, coud be replaced...
    var xmlHttpReq = false;
    var self = this;
    // Mozilla/Safari
    if (window.XMLHttpRequest) {
        self.xmlHttpReq = new XMLHttpRequest();
    }
    // IE
    else if (window.ActiveXObject) {
        self.xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
    }
	
	strURL = "picupload/modify_topic.pictures.php";
    self.xmlHttpReq.open('POST', strURL, true);
    self.xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    self.xmlHttpReq.onreadystatechange = function() {
        if (self.xmlHttpReq.readyState == 4) {
            showpicturepreviews(self.xmlHttpReq.responseText);
        }
    }
    self.xmlHttpReq.send(getresults());
}

function getresults() {   
	qstr = 'section_id=' + section_id+'&page_id='+page_id; // NOTE: no '?' before querystring
    return qstr;
}

function showpicturepreviews(str){	
	document.getElementById('topics_picturechooser').innerHTML = str;	
}



function choosethispicture(picfile) {
	document.images['topicpic'].style.display = "block";
	$('#topics_chooser_overlay').removeClass('visible');
	
	if (picfile!=0) {
		document.images['topicpic'].src = topicpicloc + picfile +'?time=' + new Date().getTime();	
		document.getElementById('picture').value=picfile;
	} else {
		document.images['topicpic'].src = document.images['topicpic'].src +'?time=' + new Date().getTime();		
	}
}

function additionalpicture(topic_id, picpath) {
	pics = document.getElementById('additionalpictures').innerHTML;	
	pics += '<img src="'+picpath+'" />';
	document.getElementById('additionalpictures').innerHTML = pics;
}




function openpicturemodify(picfile) {
	closepictureupload ()
	//picfile = document.getElementById('picture').value;
	if (!picfile) {
		picfile = document.modify.picture.value;		
		if (picfile.substr(0,7) == 'http://') { alert('not possible'); return 0; }
	}	
	document.getElementById('topics_picturechooser').innerHTML = '<iframe src="picupload/uploadview.php?section_id='+section_id+'&page_id='+page_id+'&topic_id='+topic_id+'&fn='+picfile+'" frameborder="0" class="" XXstyle="width:'+(innerw-00)+'px; height:'+(innerh-00)+'px;" scrolling="no"></iframe>';
	$('#topics_chooser_overlay').addClass('visible');	
}




//----------------------------------------------------------------

function tp_changedmetafield(ths) {
	var v = ths.value;
	v = v.trim();
	v = v.replace(/(\r\n|\n|\r)/gm," ");
	v = v.replace(/\s+/g," ");
	if (ths.type = 'input') {
		ths.value = v;
	} else {
		ths.text = v;
	}
	//alert(v);
}

function copythistopic() {
	document.getElementById('copytopic').value = 1;
	document.modify.submit();
}

function showtabarea(nr) {
	if (nr==0) {
		if (localStorage['topics_lastopentab'+section_id]) {
			lastopentab = parseInt( localStorage['topics_lastopentab'+section_id]); 
			if (lastopentab > 0) {nr = lastopentab;}
		} 
	}
	if (nr==0) {nr=1;}
	
	i=0;
	while (i < 7) {
		i++;
		nar = '#linktabarea'+i;
		
		if (i == nr) {			
			document.getElementById('tabarea'+i).style.display = "block";			
			$(nar).addClass( "activeTab" );								
		} else {		
			document.getElementById('tabarea'+i).style.display = "none";			
			$(nar).removeClass( "activeTab" );
		}
	}	
	
	localStorage.setItem('topics_lastopentab'+section_id, nr);
	$('.settingsform input, .settingsform textarea').css({"background-color": "none"});
	
	//window.localStorage.clear(); //testing
}

/*
function showtabareajQuery(nr) {
	//Funktioniert leider nicht
	n = '#tabarea'+lastopentab;
	//$('.tabarea').css( "z-index", 1000 );
	$(n).css( "z-index", 1000 );
	$(n).fadeOut();
	
	n = '#tabarea'+nr;
	$(n).css( "z-index", 2000 );
	$(n).fadeIn();
		document.getElementById('tabarea'+nr).style.display = "block";
	console.log(n);
	
	lastopentab = nr;
}
*/

