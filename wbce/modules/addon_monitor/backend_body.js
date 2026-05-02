/**
 * WebsiteBaker CMS AdminTool: addonMonitor
 *
 * This file provides the needed javascript for use with addonMonitor
 * and includes relevant jQuery Plugins
 * 
 * @platform    CMS WebsiteBaker 2.8.3
 * @package     addonMonitor
 * @author      Christian M. Stefan (Stefek)
 * @copyright   Christian M. Stefan
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 */
 
var TABLESORTER = TOOL_URL + '/plugins/jqueryTablesorter';
var COLLAPSER = TOOL_URL + '/plugins/jqueryCollapser';
var TXT_SHOW_MORE = "[+ show more]";
var TXT_HIDE = "[- show less]";

// Table Sorter & Filter Plugin
$.insert(TABLESORTER + '/jquery.tablesorter.min.js');
$.insert(TABLESORTER + '/tablesorter.js');
$.insert(TABLESORTER + '/tablesorter_filter.js');



$(document).ready(function() {	
	var TXT_SHOW_ALL = '[ + ]';
	var TXT_SHOW_LESS = '[ - ]';
	$('ul.using_sections').each(function(){	  
	  var LiN = $(this).find('li').length;	  
	  if( LiN > 4){    
		$('li', this).eq(3).nextAll().hide().addClass('toggable');
		$(this).append('<li class="show_all">'+ TXT_SHOW_ALL +'</li>');    
	  }	  
	});
	$('ul.using_sections').on('click','.show_all', function(){	  
	  if( $(this).hasClass('collapse') ){    
		$(this).text(TXT_SHOW_ALL).removeClass('collapse');    
	  }else{
		$(this).text(TXT_SHOW_LESS).addClass('collapse'); 
	  }	  
	  $(this).siblings('li.toggable').slideToggle(150);		
	}); 
		
	// HANDLE ADDON TYPE TOGGLE
	var aFunctionTypes = ["page", "tool", "snippet", "wysiwyg", "template", "theme"];
	jQuery.each(aFunctionTypes, function() {
		var string = this;
		$("#include_"+ string +"s").click(function() {
		   $("#htmlgrid tbody tr[rel='"+string+"']").toggle(this.checked);
		}).triggerHandler('click');	
	});
   
   // FILTERING ("search")
	$("#htmlgrid")
        .tablesorter({
			debug: false, 
			sortList: [[0,0]]
		})
        .tablesorterFilter({
			filterContainer: "#filter_titles",
			filterClearContainer: "#clear_titles",
			filterColumns: [1]
		}, 
		{ 
			filterContainer: "#filter_authors",
			filterClearContainer: "#clear_authors",
			filterColumns: [4]
		});
	$("#filter-addon-type").trigger("wysiwyg")
	
	// COLLAPSER
	if ($('div.collapser').length){
		$.insert(COLLAPSER + '/jquery.collapser.min.js');
		$('.collapser').collapser({		
			mode: 'lines',
			truncate: 4,
			speed: 'fast',
			showText: TXT_SHOW_MORE,
			hideText: TXT_HIDE,
		});	
	}	
}); 