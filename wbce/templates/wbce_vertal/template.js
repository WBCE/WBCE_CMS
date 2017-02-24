$( document ).ready(function() { 
	 $( "#nav a" ).focus(function() {
		$( "#nav li.menu-expand" ).removeClass('tabselected');
		$(this).parents('li.menu-expand').addClass('tabselected');
	});
    initmobilemenu();
	
	//delete_cookie_permission(); //Testing
	check_cookie_permission ();		
});

$( window ).resize(function() {
	initmobilemenu();
});


function gototop() {
	$('html, body').animate({ scrollTop: ($('body').offset().top)}, 'slow');
}

function showloginbox() {
	var url = TEMPLATE_DIR+'/inc/login.load.php';
	var redirect_url = window.location.href;
	
	if(document.getElementById("login-box").style.display == 'none') {	
		$( "#login-box" ).load(url, function() {
			$('#redirect_url').val(redirect_url);			
			document.getElementById("login-box").style.display = 'block';
		});	
	} else {
		document.getElementById("login-box").style.display = 'none';
	}			
}

function showmenu() {

	if (typeof $ == 'undefined') {  
		obj = document.getElementById("leftbox");
		mm = obj.style.display;		
		if ( mm == 'block') { obj.style.display = 'none'; } else { obj.style.display = 'block'; }
		
	} else {
		$( "#nav .isopened" ).removeClass( "isopened" ); //alle li und a 
		
		var effect = 'slide';  // Set the effect type		
		var opts = { direction: 'left' }; // Set the options for the effect type chosen		
		var duration = 500; // Set the duration (default: 400 milliseconds)
	
		$('#leftbox').toggle(effect, opts, duration);	
	}
}



function initmobilemenu() {
	//vorab mal alle entfernen
	$( "#nav .menu-expand" ).removeClass( "isopened" ); //alle li und a 
	
	ms = $("#menucheck").css('display');
	if ( ms != 'block') {	
		$( "a.lev0.menu-expand" ).bind( "click", function() { //an jedes a.menu-expand hängen 
			
			if ($(this).hasClass("isopened") ) {return true;} //wenn schon geklickt worden, also:class isopened link ausführen
			
			$( "#nav .isopened" ).removeClass( "isopened" ); //alle li und a 
			//also noch keine class isopened?: Annhängen	
				
			 $(this).addClass('isopened');  
			 $(this).parent("li.menu-expand").addClass('isopened');  
			// alert( "User clicked on 'foo.'" );
			 return false;
		});
	}  else {
		$( "a.lev0.menu-expand" ).unbind( "click"); 
	
	}
}


/*======================================================
Cookie Permission
======================================================*/
function check_cookie_permission () {
	if (typeof cookie_permission_url == 'undefined' || cookie_permission_url == '') {
		return;
	}

	if (localStorage['cookie_permission'] && localStorage['cookie_permission'] == 'accepted') {
		//OK, do nothing
	} else {
		$( "body" ).append('<div id="cookie_permission"></div>');
		$( "#cookie_permission" ).load(cookie_permission_url, function() {
			$( "#cookie_permission" ).addClass('visible');
		});
	
	}
}

function accept_cookie_permission() {
	localStorage.setItem('cookie_permission', 'accepted');
	$('#cookie_permission').hide('500');
}

function delete_cookie_permission() {
	localStorage.setItem('cookie_permission', '');
}

