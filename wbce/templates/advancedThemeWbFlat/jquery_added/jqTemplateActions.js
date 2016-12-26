// added code for jQuery

$(document).ready( function() {


	// make sidebar state sticky --> call plugin sticky elements
	// call this first --> otherwise sidebar could be open for a moment
	$('.stickySidebarElement').stickyElements({
		stickyMethod: 'class',
		stickyFormatClass: 'closedsidebar',
		pageModus: 'domainWide'
	});  // ENDE make sidebar state sticky



	
	// toggle-action for sidebar
	$('#sidebararea_togglebutton').click(function() {
	
		if(  $(this).parent().hasClass('closedsidebar')  ){
			// close sidebar
			$('#pagetopmenu').removeClass('closedsidebar', 1000);
			$('#mainarea').removeClass('closedsidebar', 1000);
			$('#sidebararea').removeClass('closedsidebar', 1000);
			$('#mainmenu').removeClass('closedsidebar', 1000);
			$('#mainmenu ul').removeClass('closedsidebar', 1000, function(){
				$('#userbox').removeClass('closedsidebar', 800);
				$('#systeminfo').removeClass('closedsidebar', 800);
			});
		}else{
			// sidebar is open --> close sidebar
			$('#userbox').addClass('closedsidebar', 800);
			$('#systeminfo').addClass('closedsidebar', 800);
			$('#sidebararea').addClass('closedsidebar', 1000);
			$('#mainmenu ul').addClass('closedsidebar', 1000);
			$('#mainmenu').addClass('closedsidebar', 1000);
			$('#pagetopmenu').addClass('closedsidebar', 1000);
			$('#mainarea').addClass('closedsidebar', 1000);
			sidebararea_close_switch = 0;
		}
		
	});  // ENDE script toggle action for sidebar
	
	


	// pagetopmenu --> check if link is active link and add active class to parent <li>
	var pageurl = window.location.pathname,
	urlRegExp = new RegExp(pageurl.replace(/\/$/,'') + "$");
	$('#pagetopmenu a').each( function(){
		if (urlRegExp.test(this.href.replace(/\/$/,''))) {
			$(this).parent().addClass('current');
		}
	});
	

	// enable special stylings for file input fields
	$("input[type=file]").nicefileinput({
		label : '' 
	}); 

	

	// special for hard coded page pages --> add class 'page_titel' to first head of page
	
	var page_url = document.URL;
	var page_url_searchstring = ADMIN_URL + '/pages/index';

	if(page_url.indexOf(page_url_searchstring) != -1){
	   
	  $('h2').first().addClass('id_pages_addpages page_titel');
	  
	} // ENDE special add class page_titel to page pages	


	
	
	// special for themeboxes --> remove commas after links to format links as buttons
	$('.themebox .tb_content').each(function(){
	  
		var tb_replacestring = $(this).html();
		tb_replacestring = tb_replacestring.replace(/<\/a>\, /g, '</a>');
		$(this).html(tb_replacestring);
	  
	}); // ENDE special remove commas from themeboxes


	
	
	// Specials for separate module-pages admintools --> remove » from text
	$('td.content > h4').each(function(){
	  
		var tb_replacestring = $(this).html();
		tb_replacestring = tb_replacestring.replace(/<\/a>&nbsp;»/g, '</a> &nbsp;');
		$(this).html(tb_replacestring);
	  
	}); // ENDE special remove » from text
	
	
	// Specials for separate module-pages admintools --> deactivate link in head
	// just unbind click-handler for the link (Note: do not remove link, cause css designs link-tag)
	//$('td.content > h4 a').click(function () {return false;});
	// ENDE special deactivate link
	

	
	
	// toggle-action for toggleboxarea 
	
	$('#toggelboxarea_togglebutton').click(function() {
		if(  $(this).parent().parent().hasClass('toggelboxarea_action_open')   ){
			$('#toggleboxarea').removeClass('toggelboxarea_action_open', 800, 'easeInOutQuad');
		}else{
			$('#toggleboxarea').addClass('toggelboxarea_action_open', 800, 'easeInOutQuad');
		}
	});

	$(document).keyup(function(e) {
		if (e.keyCode == 27) { // esc keycode
				$('#toggleboxarea').removeClass('toggelboxarea_action_open', 800, 'easeInOutQuad');
		}
	});
	
	$(window).bind('beforeunload', function(){
		$('#toggleboxarea').removeClass('toggelboxarea_action_open', 600, 'easeInOutQuad');
	});
	
	// ENDE toggle action for togglebox area


	
	// focus effect for file-input-fields
	// NOTE: based on jQuery NiceFileInput to style this fields
	// ad style to button on focus
	$('input.NFI-current').focus(function(){
		$(this).parent().css('color', 'rgba(255,255,255,0.65)');
	});
	// remove style from button on focus
	$('input.NFI-current').blur(function(){
		$(this).parent().css('color', '');
	});


	
	
	// show admin version (= version of backend theme)
	// call information from separate php-file and show version number
	$('#admincheck a').click(function(e){
		
		e.preventDefault();   // deactivate link
		
		// url to load file with admin version to show
		var loadUrl = THEME_URL + '/jquery_added/plugins/phpSystemVars/themeVersion.php';
		
		// replace admincheck-link with information from file (admin version)
		$(this).css({ opacity: 1 }).animate({opacity: 0}, 'slow');
		$("#admincheck").load(loadUrl, function(){
			$(this).css({ opacity: 0 }).animate({opacity: 1}, 'slow');
		}); 
	});
	
		
	
	
}); // ENDE document.ready

	
