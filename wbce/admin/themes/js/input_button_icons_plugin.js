/**
 * jQuery plugin to apply icons to input type submit buttons
 * Require:  jQuery 1.2.6
 * Author:   Christian M. Stefan (Stefanek)
 * License:  Dual licensed under the MIT and GPL licenses:
 *           http://www.opensource.org/licenses/mit-license.php
 *           http://www.gnu.org/licenses/gpl.html
 *           
 * Description:
 * This plugin will enframe all inputs with the class "button" into
 * a div container, generating the following code
 * 
 * BEFORE: 
 *   <input class="button ico-add" name="add" value="add">
 * 
 * AFTER:
 * <div class="button ico-add">
 *     <input name="add" value="add">
 * </div>
 * 
 * We need it in order to allow for icons in buttons also in input type buttons.
 * 
*/

$( document ).ready(function() {
	if($("input.button").length > 0) { 
		;!(function ($) {
			// get ALL css selectors from element returned as string
			$.fn.getCssSelectors = function (callback) {
				var aSelecs = [];
				$.each(this, function (i, v) {
					var splitClassName = v.className.split(/\s+/);
					for (var j in splitClassName) {
						var className = splitClassName[j];
						if (-1 === aSelecs.indexOf(className)) aSelecs.push(className);
					}
				});
				if ('function' === typeof callback) 
					for (var i in aSelecs) callback(aSelecs[i]);
                                                return (aSelecs.join(' '));
			};
		})(jQuery);
	}		
	if($("input.button").length > 0) { 
		$("input.button").each(function( index ) {
			var sClasses = $(this).getCssSelectors();
			$(this).removeClass(sClasses).wrap('<div class="'+ sClasses +'" />');  
		});
	}
});