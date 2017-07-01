var suchbegriff = '';
var qtimer;
var headerboxh;

$( document ).ready(function() {	 
	 $( "#nav a" ).focus(function() {
		$( "#nav li.lev0" ).removeClass('tabselected');
		$(this).parents('li.lev0').addClass('tabselected');
	});
	get_template_vars();
	//delete_cookie_permission(); //Testing
	check_cookie_permission ();
})



$(window).scroll(function() {   
	ww = document.body.clientWidth;
	if (ww < 960) {return 0;}		
	//if  ( $("body").hasClass("isstartpage") ) {return 0;}
	
    var scl = $(window).scrollTop();
	if (scl < 100) {  
		headerboxh = $("#headerbox").height();
		
	}
		

 	if (scl >= headerboxh + 30) {  //Hoehe + Menue-Balken
        $("body").addClass("noheader");
		$("#headerbox_replace").height(headerboxh+'px');
    } else {
        $("body").removeClass("noheader");
		$("#headerbox_replace").height(0);
    }
	
    if (scl >= headerboxh + 70) {
        $("body").addClass("reduced");
    } else {
        $("body").removeClass("reduced");
    }
});




function get_template_vars() {
	headerboxh = $("#headerbox").height();
}

function initsuggestion(q) {
	suchbegriff = q;
	window.clearTimeout(qtimer);
	qtimer = window.setTimeout(suchen, 100);
}

function suchen() {	
	$.ajax({
		type: "POST",
		url: qsURL,
		data: {'q':suchbegriff}
	})
	.done(function( msg ) {		
		$('#suggestbox').html(msg);				
	});
}


//-------------------------------------------------------------

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

//-------------------------------------------------------------
$( document ).ready(function() {
	findprevnext();
})

function findprevnext() {
	
	var avorher = '/';
	var aprev = '/';
	var anext = '';
	var count = 0;
		
	if ($("body").hasClass('isstartpage')) { //is Startpage
		anext = $("#nav li a:eq(1)" ).attr('href');
		if (typeof(anext) != "undefined") {showprevnext('', anext ); }		
		return false;
	}
	
	
	$("#nav li a").each(function () {
		var a = $(this).attr('href');
		var p = parseInt( $(this).data('pid') );
		
		
		if (anext == 'next') {
			anext = a; 
			showprevnext(aprev, anext ); 
			anext = 'break';
			return false;
		}
		
		if ( $(this).hasClass('menu-current') ) {
			//console.log( "a current: " + a );
			aprev = avorher;
			anext = 'next';			
		}
		
		var o = menulinks.indexOf(p); //jump over Menulinks
		if (o == -1) {avorher = a;}
		
		count ++;
	});
	console.log(count);
	if (anext == 'break') {return false;}
	if (count == 0) {return false;}
	
	//Nichts gefunden?
	anext = ''; //kein Link
	//anext = '/'; //oder Startseite
	showprevnext(aprev, anext );
	
}	
function showprevnext(aprev, anext ) {
	if (anext != '') {an = '<a class="anext" href="'+anext+'"><span style="display:none;">next</span></a>';} else {an = '';}
	if (aprev != '') {an += '<a class="aprev" href="'+aprev+'"><span style="display:none;">prev</span></a>';}	
	$("#aprevnext").html(an);
	$("#aprevnext2").html(an);	
}

function gototop() {
	$('html, body').animate({ scrollTop: ($('body').offset().top)}, 'slow');
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

/*======================================================
mobile
======================================================*/

var toggleWidth = 960;
var menuopen = false;
//var ua = navigator.userAgent; event = (ua.match(/iPad/i)) ? "touchstart" : "click";


$( document ).ready(function() {
	ww = document.body.clientWidth;
		
	$(".toggleMenu").click(function(e) {
		e.preventDefault();
		if (menuopen == false) {
			var nav =  $("#nav").html();
			nav += '<form action="'+WB_URL+'/search/index.php" method="get"><input type="text" name="string" placeholder="search" class="searchstring" /><input type="image" class="submitbutton" src="'+TEMPLATE_DIR+'/img/searchbutton.png" alt="Start"></form>';
			$("#nav2").html(nav) ;
			
			$("#nav2close").show(100)
			var scl = $(window).scrollTop();
			var o = $('.mobileheader').height();			
			scl = scl+o+'px';
			$("#nav2").css('top',scl);
				
			initmobilemenu();
			menuopen = true;
		} else {			
			$("#nav2close").hide(100);
			$("#nav2").html('') ;
			menuopen = false;
		}
	});
	
	
	$(window).bind('resize orientationchange', function() {
		ww = document.body.clientWidth;
		if (menuopen == true) { initmobilemenu(); }
	});
		
});


function initmobilemenu() {

	if (ww < toggleWidth) {	
		
		$("#nav2 li a.menu-expand").unbind('click').bind('click', function(e) {			
			
			if ($(this).parent("li").hasClass("is_opened") ) {return true;} //wenn schon geklickt worden, also:class is_opened link ausführen
			
			e.preventDefault();
			$(this).toggleClass("is_opened");
			$(this).parent("li").toggleClass("is_opened");
			//$(this).parent("li").find( "ul:first" ).show( "slow");
			
		});
	} 	
}


function opensidebar() {
	var opts = { direction: 'right' };
	
	$('#sidebar').toggle('slide', opts, 500);
	$('#opensidebarswitch').toggle('slide', opts, 100);
	//console.log( "done!" );
}

