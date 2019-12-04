$(function(){
	$('.bar, .vbar, .expand').poshytip({
		className: 'tip-twitter',
		showTimeout: 1,
		alignTo: 'cursor',
		offsetX: 15,
		alignX: 'left',
		alignY: 'center',
		followCursor: true,
		fade: true,
		slide: false
	});
	$('.expandunder').poshytip({
		className: 'tip-twitter',
		alignTo: 'target',
		showTimeout: 1,
		offsetX: 15,
		alignX: 'center',
		alignY: 'bottom',
		fade: true,
		slide: false
	});
	$(".bar").each(function() { $.data(this, "realHeight", $(this).height()); }).css({ height: "1px" }).each(function() { $(this).animate({ height: $(this).data("realHeight") }, 1000); });
	
	$('.pop').on('click', function(e) {
		e.preventDefault();
		var sec = '#'+$(this).data('sec');
		$(this).simplePopup({ width: '1200px', type: "html", htmlSelector: sec ,afterOpen: function() {
			$('.simple-popup-content .expand').each(function (index, value){ $(this).html(value.title); value.title='';});
        } });
	});
}); 
function OnBeforeUnLoad () { document.getElementById('loading').style.display = 'block'; }
window.onbeforeunload = OnBeforeUnLoad;
function GetCurrentPage() { 
	if (!document.getElementsByTagName) return;
	var anchors = document.getElementsByTagName("a");
	var thisPage = location.href;
	for (var i=0; i<anchors.length; i++) {
		var anchor = anchors[i];
		thisHREF = anchor.getAttribute("href");
		if (thisHREF == thisPage || location.protocol + "//" + location.hostname + thisHREF == thisPage) {
			anchor.id = "current";
			return;
		}
	}
} 
window.onload = GetCurrentPage;


// https://github.com/dinoqqq/simple-popup
!function(t){"use strict";t.fn.simplePopup=function(n){function e(){return o(),v=i(),k=r(),a(),g}function o(){if("auto"!==w.type&&"data"!==w.type&&"html"!==w.type)throw new Error('simplePopup: Type must me "auto", "data" or "html"');if(w.backdrop>1||w.backdrop<0)throw new Error('simplePopup: Please enter a "backdrop" value <= 1 of >= 0');if(w.fadeInDuration<0||Number(w.fadeInDuration)!==w.fadeInDuration)throw new Error('simplePopup: Please enter a "fadeInDuration" number >= 0');if(w.fadeOutDuration<0||Number(w.fadeOutDuration)!==w.fadeOutDuration)throw new Error('simplePopup: Please enter a "fadeOutDuration" number >= 0')}function i(){if("html"===w.type)return"html";if("data"===w.type)return"data";if("auto"===w.type){if(g.data("content"))return"data";if(t(w.htmlSelector).length)return"html";throw new Error('simplePopup: could not determine type for "type: auto"')}return!1}function r(){if("html"===v){if(!w.htmlSelector)throw new Error('simplePopup: for "type: html" the "htmlSelector" option must point to your popup html');if(!t(w.htmlSelector).length)throw new Error('simplePopup: the "htmlSelector": "'+w.htmlSelector+'" was not found');return t(w.htmlSelector).html()}if("data"===v){if(k=g.data("content"),!k)throw new Error('simplePopup: for "type: data" the "data-content" attribute can not be empty');return k}return!1}function a(){w.backdrop&&l(),w.escapeKey&&m(),p()}function p(){var n=t("<div/>",{class:"simple-popup-content",html:k}),e=t("<div/>",{id:"simple-popup",class:"hide-it"});if(w.inlineCss&&(n.css("width",w.width),n.css("height",w.height),n.css("background",w.background)),u(e),w.closeCross){var o=t("<div/>",{class:"closemodal"});c(o),n.append(o)}e.append(n),w.beforeOpen(e),t("body").append(e),setTimeout(function(){var n=t("#simple-popup");w.inlineCss&&(n=b(n,w.fadeInTimingFunction),n=y(n,w.fadeInDuration)),n.removeClass("hide-it")});var i=setInterval(function(){"1"===t("#simple-popup").css("opacity")&&(clearInterval(i),w.afterOpen(e))},100)}function s(){var n=t("#simple-popup");w.beforeClose(n),w.inlineCss&&(n=b(n,w.fadeOutTimingFunction),n=y(n,w.fadeOutDuration)),t("#simple-popup").addClass("hide-it");var e=setInterval(function(){"0"===t("#simple-popup").css("opacity")&&(t("#simple-popup").remove(),clearInterval(e),w.afterClose())},100);w.backdrop&&d(),w.escapeKey&&h()}function u(n){t(n).on("click",function(n){"simple-popup"===t(n.target).prop("id")&&s()})}function c(n){t(n).on("click",function(t){s()})}function l(){f()}function d(){var n=t("#simple-popup-backdrop");w.inlineCss&&(n=b(n,w.fadeOutTimingFunction),n=y(n,w.fadeOutDuration)),n.addClass("hide-it");var e=setInterval(function(){"0"===t("#simple-popup-backdrop").css("opacity")&&(t("#simple-popup-backdrop").remove(),clearInterval(e))},100)}function f(){var n=t("<div/>",{class:"simple-popup-backdrop-content"}),e=t("<div/>",{id:"simple-popup-backdrop",class:"hide-it"});w.inlineCss&&(n.css("opacity",w.backdrop),n.css("background",w.backdropBackground)),e.append(n),t("body").append(e),setTimeout(function(){var n=t("#simple-popup-backdrop");w.inlineCss&&(n=b(n,w.fadeInTimingFunction),n=y(n,w.fadeInDuration)),n.removeClass("hide-it")})}function m(){t(document).on("keyup.escapeKey",function(t){27===t.keyCode&&s()})}function h(){t(document).unbind("keyup.escapeKey")}function b(t,n){return t.css("-webkit-transition-timing-function",n),t.css("-moz-transition-timing-function",n),t.css("-ms-transition-timing-function",n),t.css("-o-transition-timing-function",n),t.css("transition-timing-function",n),t}function y(t,n){return t.css("-webkit-transition-duration",n+"s"),t.css("-moz-transition-duration",n+"s"),t.css("-ms-transition-duration",n+"s"),t.css("-o-transition-duration",n+"s"),t.css("transition-duration",n+"s"),t}var k,v,g=this,w=t.extend({type:"auto",htmlSelector:null,width:"600px",height:"auto",background:"#fff",backdrop:.7,backdropBackground:"#000",inlineCss:!0,escapeKey:!0,closeCross:!0,fadeInDuration:.3,fadeInTimingFunction:"ease",fadeOutDuration:.3,fadeOutimingFunction:"ease",beforeOpen:function(){},afterOpen:function(){},beforeClose:function(){},afterClose:function(){}},n);this.selector;return e()}}(jQuery);