/*
 * Charcounter textarea for Jeditable
 *
 * Copyright (c) 2008 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 * 
 * Depends on Charcounter jQuery plugin by Tom Deater
 *   http://www.tomdeater.com/jquery/character_counter/
 *
 * Project home:
 *   http://www.appelsiini.net/projects/jeditable
 *
 * Revision: $Id: jquery.jeditable.autogrow.js 344 2008-03-24 16:02:11Z tuupola $
 *
 */
 

 
 
$.editable.addInputType('charcounter', {
    element : function(settings, original) {        
        var textarea = $('<textarea />');
        if (settings.rows) {
            textarea.attr('rows', settings.rows);
        } else {
            textarea.height(settings.height);
        }
        if (settings.cols) {
            textarea.attr('cols', settings.cols);
        } else {
            textarea.width(settings.width);
        }
		
        $(this).append(textarea);
        return(textarea);
    },
    plugin : function(settings, original) {
        $('textarea', this).characterCounter(settings.charcounter);
    }
});

/*
 * 	Show Counter Plugin - jQuery plugin
 * 	Dynamic character count for text areas and input fields
 *	written by Stefek (Christian M. Stefan)
 *
 *	Copyright (c) 2013 Christian M. Stefan (Stefek[at]designThings[dot]de)
 *	Dual licensed under the MIT (MIT-LICENSE.txt)
 *	and GPL (GPL-LICENSE.txt) licenses.
 *
 *
 */

 (function($) {

	$.fn.showCounter = function(options)
	{			
		// default configuration properties
		var defaults = {	
			cssOptimum: 'cssOptimum',
			cssExceeded: 'cssExceeded',
			counterText: '<span><strong>[%2]</strong><br /> <small>(%1 characters left )</small></span>'
		}; 
		var options = $.extend(defaults, options); 
		this.each(function() {  
			var iId = $(this).attr('rel');
			var iCount = $(this).text().length;
			var sClass = '';			
			if(iCount > options.minimum){
				sClass = options.cssOptimum;
			}
			if(iCount > options.optimum){
				sClass = options.cssExceeded;
			}
			var available = options.optimum - iCount;
			var output = options.counterText.replace(/%1/, available).replace(/%2/, iCount);
			$(this).next().append(output).addClass(sClass);			
		});	  
	};
})(jQuery); 

/*
 * 	Character Count Plugin - jQuery plugin
 * 	Dynamic character count for text areas and input fields
 *	written by Alen Grakalic	
 *	http://cssglobe.com/post/7161/jquery-plugin-simplest-twitterlike-dynamic-character-count-for-textareas
 *
 *	Copyright (c) 2009 Alen Grakalic (http://cssglobe.com)
 *	Dual licensed under the MIT (MIT-LICENSE.txt)
 *	and GPL (GPL-LICENSE.txt) licenses.
 *
 *	Built for jQuery library
 *	http://jquery.com
 *
 */
  /**
  * This plugin was reworked by
  * Stefek (Christian M. Stefan)
  * to fit the needs of the functionality
  *
  */
(function($) {

	$.fn.characterCounter = function(options)
	{	  
		// default configuration properties
		var defaults = {	
			css: 'counter',
			counterElement: 'span',
			cssOptimum: 'cssOptimum',
			cssExceeded: 'cssExceeded',
			counterText: '<span><strong>[%2]</strong><br /> <small>(%1 characters left )</small></span>'			
		}; 	
		var options = $.extend(defaults, options); 	
			
		function calculate(obj, element){
			var iCount = $(obj).val().length;
			var available = options.optimum - iCount;
			
			if(iCount >= options.minimum){
				element.removeClass(options.cssExceeded);
				element.addClass(options.cssOptimum);
			}else{
				element.removeClass(options.cssOptimum);
			}
			if(iCount > options.optimum){
				element.removeClass(options.cssOptimum);
				element.addClass(options.cssExceeded);
			}
			
			
			element.html(options.counterText.replace(/%1/, available).replace(/%2/, iCount));			
		};
				
		this.each(function() {  
			var sHtmlString = options.counterText;			
			var iId = $(this).parent().parent().attr('rel');
			var element = $('#'+iId+' td.showCounter');
			element.append(sHtmlString);
			
			// calculate and update as you go
			calculate(this, element);
			$(this).keyup(function(){calculate(this, element)});
			$(this).change(function(){calculate(this, element)});
		});
	};

})(jQuery); 
