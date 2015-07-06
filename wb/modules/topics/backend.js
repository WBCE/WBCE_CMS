innerh = 0.9*window.innerHeight;
innerw = 0.8*window.innerWidth;
if (innerh < 600) innerh = 600;
if (innerw < 900) innerw = 900;

function changepic(wie) {
	alert(wie);
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
	document.getElementById(what).style.display="block";
	if (what != 'getfromtable' && document.getElementById("getfromtable")) document.getElementById("getfromtable").style.display="none";
	if (what != 'presetstable' && document.getElementById("presetstable")) document.getElementById("presetstable").style.display="none";
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

function changepresets(thefile) {
	
	if (!thelanguage) {thelanguage = "en";}
	
	if( !document.createElement ) {
 		alert('No createElement, sorry');
  		return;
 	}
	fn = 'presets-'+thelanguage+'/'+thefile+'.js';	
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
function openpicturepreviews() {	
	closepictureupload ();
	//document.getElementById('whattodo').style.display = "block";
	document.getElementById('picturechooser').style.display = "block";
	document.getElementById('picturechooser').style.height = innerh+"px";
	document.getElementById('picturechooser').style.width = innerw+"px";
	document.getElementById('picturechooser').innerHTML = "<h2>wird geladen</h2>";	
	getpicturepreviews();	
}

function closepicturepreviews() {
	document.getElementById('choosertable').style.display = "none";
}


//Ajax Script based on http://www.degraeve.com/reference/simple-ajax-example.php

function getpicturepreviews() {
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
	//alert(qstr);
    return qstr;
}

function showpicturepreviews(str){
    //document.getElementById("suggestbox").innerHTML = '<div class="ajax">'+str+'</div>';
	document.getElementById('picturechooser').innerHTML = str;
	document.getElementById('choosertable').style.display = "block";
	document.getElementById('choosertable').style.width = innerw+"px";
	//document.getElementById('choosertable').style.height = innerh+"px";
	
}


function OBSOLETE_choosethispicture(picfile) {

	modify = document.getElementById('todocheckbox').checked;	
	document.getElementById('todocheckbox').checked = false;
	
	if (modify) {		
		document.getElementById('picturechooser').innerHTML = '<iframe src="picupload/uploadview.php?section_id='+section_id+'&page_id='+page_id+'&fn='+picfile+'" frameborder="0" class="" style="width:'+(innerw-30)+'px; height:'+(innerh-50)+'px;" scrolling="auto"></iframe>';	
		document.getElementById('whattodo').style.display = "none";
	} else {	
		document.images['topicpic'].style.display = "block";
		document.getElementById('choosertable').style.display = "none";
		if (picfile!=0) {
			document.images['topicpic'].src = topicpicloc + picfile +'?time=' + new Date().getTime();	
			document.getElementById('picture').value=picfile;
		}
	}
}



function choosethispicture(picfile) {
	document.images['topicpic'].style.display = "block";
	document.getElementById('choosertable').style.display = "none";
	if (picfile!=0) {
		document.images['topicpic'].src = topicpicloc + picfile +'?time=' + new Date().getTime();	
		document.getElementById('picture').value=picfile;
	}
}

function additionalpicture(topic_id, picpath) {
	alert(picpath);
	pics = document.getElementById('additionalpictures').innerHTML;
	//pics += '<a href="javascript:openpicturemodify(\'topic'.$topic_id.'/'.$pic.'\');"><img src="'.$morepics_url.$pic.'" title="'.$pic.'" alt="'.$pic.'" /></a>
	
	pics += '<img src="'+picpath+'" />';
	document.getElementById('additionalpictures').innerHTML = pics;
	
	
alert(picfile);
}


function copythistopic() {
	document.getElementById('copytopic').value = 1;
	document.modify.submit();
}

function openpicturemodify(picfile) {
	closepictureupload ()
	//picfile = document.getElementById('picture').value;
	if (!picfile) {
		picfile = document.modify.picture.value;		
		if (picfile.substr(0,7) == 'http://') { alert('not possible'); return 0; }
	}
	
	//openpicturepreviews();
	document.getElementById('choosertable').style.display = "block";
	//document.getElementById('picturechooser').style.display = "block";
	document.getElementById('picturechooser').style.height = innerh+"px";
	document.getElementById('picturechooser').style.width = innerw+"px";
	
	//getpicturepreviews();
	//document.getElementById('picturechooser').innerHTML = 'HALLO';
	document.getElementById('picturechooser').innerHTML = '<iframe src="picupload/uploadview.php?section_id='+section_id+'&page_id='+page_id+'&topic_id='+topic_id+'&fn='+picfile+'" frameborder="0" class="" XXstyle="width:'+(innerw-00)+'px; height:'+(innerh-00)+'px;" scrolling="no"></iframe>';
		
	//alert(picfile);
}


function OBSOLETE_showuploader() {
	document.getElementById('picturechooser').innerHTML = '<iframe src="picupload/uploader.php?section_id='+section_id+'&page_id='+page_id+'" frameborder="0" class="" style="width:'+(innerw-30)+'px; height:'+(innerh-50)+'px;" scrolling="auto"></iframe>';
}

function showtabarea(nr) {
	i=0;
	while (i < 7) {
		i++;
		if (i == nr) {			
			document.getElementById('tabarea'+i).style.display = "block";
			document.getElementById('linktabarea'+i).style.borderBottom = "0";
			document.getElementById('linktabarea'+i).style.backgroundColor = "#fff";			
		} else {
		
			document.getElementById('tabarea'+i).style.display = "none";
			if (document.getElementById('linktabarea'+i)) {
				document.getElementById('linktabarea'+i).style.borderBottom = "1px solid #666";			
				document.getElementById('linktabarea'+i).style.backgroundColor = "transparent";
			}
		}
	}
}