/**
 * WebsiteBaker CMS AdminTool: wbSeoTool
 *
 * backend_body.js
 * This file provides needed javascript for use with addonMonitor
 * 
 * 
 * @platform    CMS WebsiteBaker 2.8.x
 * @package     wbSeoTool
 * @author      Christian M. Stefan (Stefek)
 * @copyright   Christian M. Stefan
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 */


/**
	Set default vars
*/
var AJAX = MODULE_URL + '/ajax/' ;


/**
	Language Strings
	// you will need to pass over those vars from within the php file(s) to make the string multilanguage
	// if not, default values will be used	
*/
var TXT_CLOSE = TXT_CLOSE ? TXT_CLOSE : "close"; 
	
$(document).ready(function() {	

	// HANDLE PAGE TREE TOGGLE AND ITS COOKIES		 
	$.insert( AJAX + "/jquery.cookie.js");
	$("#pageTree li.page").each(function() { 
		var closeThis = ($.cookie(this.id) == 'x') ? true : false; // the string "x" represents closed nodes
		if(true == closeThis){ 
			$("#"+this.id).removeClass('pt_expanded pt_collapsed').addClass('pt_collapsed'); 
			// $('#output').append('selected: '+this.id+'		-> '+closeThis+' <br />');
		}
	});
	$(".pt_expander").unbind("click").on('click', $(this).parent().parent().parent(), function(e) {
		e.preventDefault();
		var sThisItem = $(this).parent().parent().parent();				
		$.cookie(sThisItem.attr("id"), sThisItem.hasClass("pt_collapsed") ? '' : 'x');	
		var sNewClass = sThisItem.hasClass('pt_collapsed') ? 'pt_expanded' : 'pt_collapsed';	
		sThisItem.removeClass("pt_expanded pt_collapsed");
		sThisItem.addClass(sNewClass);
	});		
	
	
	// jEditable	
	var sDefaultCounterText = '<span><strong>[%2]</strong><br /> <small>(%1 characters left )</small></span>';
	var sCounterText = (window.sCounterText != undefined) ?  window.sCounterText : sDefaultCounterText;
	var sIndicator = '<img src="'+ MODULE_URL +'/css/indicator.gif">';
	
	// Load jQuery plugins
	jQuery.insert(AJAX + '/jquery.jeditable.js');
	jQuery.insert(AJAX + '/jquery.jeditable.charcounter.js');
	
	// TITLE COUNTER 
	aTitleCounter = {
			optimum : iTitleCount_optimum,	
			minimum : iTitleCount_minimum,
			counterText: sCounterText
		};
	if(iTitleCount_use == 1){
		$('.edit_title').editable(AJAX + '/save.php', {	
			indicator   : sIndicator,
			type        : "charcounter",
			tooltip     : CLICK2EDIT,
			submit      : 'OK',
			onblur		: 'ignore',
			placeholder : "",
			charcounter : aTitleCounter
		}).showCounter(aTitleCounter);
	}else{
		$('.edit_title').editable(AJAX + '/save.php', {	
			indicator   : sIndicator,
			type        : "textarea",
			tooltip     : CLICK2EDIT,
			submit      : 'OK',
			onblur		: 'ignore',
			placeholder : ""
		});	
	}
	
	// DESCRIPTION COUNTER 
	aDescriptionCounter = {
			optimum : iDescriptionCount_optimum,	
			minimum : iDescriptionCount_minimum,
			counterText: sCounterText
	};
	
	if(iDescriptionCount_use == 1){
		$('.edit_descr').editable(AJAX + '/save.php', {
			indicator   : sIndicator,
			type        : "charcounter",
			submit      : 'OK',
			onblur		: 'ignore',
			tooltip     : CLICK2EDIT,
			placeholder : "",
			charcounter : aDescriptionCounter
		}).showCounter(aDescriptionCounter);
	}else{
		$('.edit_descr').editable(AJAX + '/save.php', {
			indicator   : sIndicator,
			type        : "textarea",
			submit      : 'OK',
			onblur		: 'ignore',
			tooltip     : CLICK2EDIT,
			placeholder : ""
		});
	}	
	
	$('.edit_area, .edit_url').editable(AJAX + '/save.php', {
		indicator   : sIndicator,
		type        : "textarea",
		submit      : 'OK',
		onblur		: 'ignore',
		tooltip     : CLICK2EDIT,
		placeholder : ""
	});
	
	// TOGGLE VISIBILITY OF FIELDS BASED ON COOKIE SETTING
	$("#checkboxes input").each(function() { 
		var closeThis = ($.cookie(this.id) == 'x') ? true : false; // the string "x" represents closed nodes
		if(true == closeThis){ 
			$("#"+this.id).attr('checked', true);
		}
	});
	
	// CLICK TOGGLE Elements
	var aElements = [
		'display_title', 'display_description',	'display_keywords', 'display_url',
	];
	
	$.each(aElements, function(index, value) {
		var ELEMENT = $('#' + value +'');
		ELEMENT.click(function() {
		   $.cookie(ELEMENT.attr("id"), this.checked ? 'x' : ''); // write to cookie
		   $('.' + value +'').toggle(this.checked);
		}).triggerHandler('click');
		
	});	
});	
