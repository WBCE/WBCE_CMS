/*Einstellung des Designs. Auswahl = 1-5.*/

var  design='1'; 

/*
########################################################################## 
 Ab hier nichts mehr ändern!! Don't change anything below this line!!
##########################################################################
*/


if(typeof head != "function"){
	/**
		Head JS		The only script in your <HEAD>
		Copyright	Tero Piirainen (tipiirai)
		License		MIT / http://bit.ly/mit-license
		Version		0.99
		
		http://headjs.com
	*/
(function(ac,G){function V(){}function ab(f,e){if(f){"object"===typeof f&&(f=[].slice.call(f));for(var h=0,g=f.length;h<g;h++){e.call(f,f[h],h)}}}function H(e,d){var f=Object.prototype.toString.call(d).slice(8,-1);return d!==G&&null!==d&&f===e}function X(b){return H("Function",b)}function aa(b){b=b||V;b._done||(b(),b._done=1)}function U(e){var d={};if("object"===typeof e){for(var f in e){e[f]&&(d={name:f,url:e[f]})}}else{d=e.split("/"),d=d[d.length-1],f=d.indexOf("?"),d={name:-1!==f?d.substring(0,f):d,url:e}}return(e=S[d.name])&&e.url===d.url?e:S[d.name]=d}function R(e){var e=e||S,d;for(d in e){if(e.hasOwnProperty(d)&&e[d].state!==O){return !1}}return !0}function N(e,d){d=d||V;e.state===O?d():e.state===o?ae.ready(e.name,d):e.state===c?e.onpreload.push(function(){N(e,d)}):(e.state=o,a(e,function(){e.state=O;d();ab(W[e.name],function(b){aa(b)});Y&&R()&&ab(W.ALL,function(b){aa(b)})}))}function a(f,e){var e=e||V,h;/\.css[^\.]*$/.test(f.url)?(h=ad.createElement("link"),h.type="text/"+(f.type||"css"),h.rel="stylesheet",h.href=f.url):(h=ad.createElement("script"),h.type="text/"+(f.type||"javascript"),h.src=f.url);h.onload=h.onreadystatechange=function(b){b=b||ac.event;if("load"===b.type||/loaded|complete/.test(h.readyState)&&(!ad.documentMode||9>ad.documentMode)){h.onload=h.onreadystatechange=h.onerror=null,e()}};h.onerror=function(){h.onload=h.onreadystatechange=h.onerror=null;e()};h.async=!1;h.defer=!1;var g=ad.head||ad.getElementsByTagName("head")[0];g.insertBefore(h,g.lastChild)}function Z(){ad.body?Y||(Y=!0,ab(T,function(b){aa(b)})):(ac.clearTimeout(ae.readyTimeout),ae.readyTimeout=ac.setTimeout(Z,50))}function L(){ad.addEventListener?(ad.removeEventListener("DOMContentLoaded",L,!1),Z()):"complete"===ad.readyState&&(ad.detachEvent("onreadystatechange",L),Z())}var ad=ac.document,T=[],Q=[],W={},S={},K="async" in ad.createElement("script")||"MozAppearance" in ad.documentElement.style||ac.opera,P,Y,M=ac.head_conf&&ac.head_conf.head||"head",ae=ac[M]=ac[M]||function(){ae.ready.apply(null,arguments)},c=1,o=3,O=4;ae.load=K?function(){var e=arguments,d=e[e.length-1],f={};X(d)||(d=null);ab(e,function(g,b){g!==d&&(g=U(g),f[g.name]=g,N(g,d&&b===e.length-2?function(){R(f)&&aa(d)}:null))});return ae}:function(){var e=arguments,d=[].slice.call(e,1),f=d[0];if(!P){return Q.push(function(){ae.load.apply(null,e)}),ae}f?(ab(d,function(h){if(!X(h)){var g=U(h);g.state===G&&(g.state=c,g.onpreload=[],a({url:g.url,type:"cache"},function(){g.state=2;ab(g.onpreload,function(b){b.call()})}))}}),N(U(e[0]),X(f)?f:function(){ae.load.apply(null,d)})):N(U(e[0]));return ae};ae.js=ae.load;ae.test=function(f,d,h,g){f="object"===typeof f?f:{test:f,success:d?H("Array",d)?d:[d]:!1,failure:h?H("Array",h)?h:[h]:!1,callback:g||V};(d=!!f.test)&&f.success?(f.success.push(f.callback),ae.load.apply(null,f.success)):!d&&f.failure?(f.failure.push(f.callback),ae.load.apply(null,f.failure)):g();return ae};ae.ready=function(e,d){if(e===ad){return Y?aa(d):T.push(d),ae}X(e)&&(d=e,e="ALL");if("string"!==typeof e||!X(d)){return ae}var f=S[e];if(f&&f.state===O||"ALL"===e&&R()&&Y){return aa(d),ae}(f=W[e])?f.push(d):W[e]=[d];return ae};ae.ready(ad,function(){R()&&ab(W.ALL,function(b){aa(b)});ae.feature&&ae.feature("domloaded",!0)});if("complete"===ad.readyState){Z()}else{if(ad.addEventListener){ad.addEventListener("DOMContentLoaded",L,!1),ac.addEventListener("load",Z,!1)}else{ad.attachEvent("onreadystatechange",L);ac.attachEvent("onload",Z);var J=!1;try{J=null==ac.frameElement&&ad.documentElement}catch(I){}J&&J.doScroll&&function af(){if(!Y){try{J.doScroll("left")}catch(b){ac.clearTimeout(ae.readyTimeout);ae.readyTimeout=ac.setTimeout(af,50);return}Z()}}()}}setTimeout(function(){P=!0;ab(Q,function(d){d()})},300)})(window);
}

if(typeof loadcss != "function"){
	/**
		loadcss		silly and plain css loader whithout callback
		copyright	Norbert Heimsath (Heimsath.org)
		License		LGPL http://www.gnu.org/copyleft/lesser.html

		example 	loadcss(URL+"/modules/wysiwyg_tab/js/test.css");
	*/
	function loadcss(a){var b=document.createElement("link");b.setAttribute("rel","stylesheet");b.setAttribute("type","text/css");b.setAttribute("href",a);if(typeof b!="undefined"){document.getElementsByTagName("head")[0].appendChild(b)}};
}


if(typeof jQuery != "function"){
	head.js("//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js");
}


head.js(URL+"/modules/colorbox/js/jquery.colorbox-min.js")

loadcss(URL+"/modules/colorbox/"+design+"/colorbox.css");


head.ready(function() {

                    jQuery(".colorbox").colorbox({
                                                loop:false,
                                                maxWidth:"90%",
                                                maxHeight:"90%",
                                                opacity: "0.7",
                                                current: "Bild {current} von {total}"
                                                });
                    jQuery("a[rel='csingle']").colorbox({
                                                loop:false,
                                                opacity: "0.7",
                                                maxWidth:"90%",
                                                maxHeight:"90%",
                                                current: "Bild {current} von {total}"
                                                });                 
                    jQuery("a[rel='cfade']").colorbox({
                                                 loop:false,
                                                 transition:"fade",
                                                 opacity: "0.7",
                                                 maxWidth:"90%",
                                                 maxHeight:"90%",
                                                 current: "Bild {current} von {total}",
                                                 speed: 800
                                                });              		
                    jQuery("a[rel='cslide']").colorbox({
                                                 slideshow:true,
                                                 loop:true,
                                                 slideshowSpeed:6000,
                                                 slideshowAuto:true,
                                                 transition:"elastic",                                                
                                                 opacity: "0.7",
                                                 maxWidth:"90%",
                                                 maxHeight:"90%",
                                                 previous:"zur&uuml;ck",
                                                 next:"vor",
                                                 close:"schlie&szlig;en",
                                                 current: "Bild {current} von {total}",
                                                 slideshowStart: "Diashow starten",
                                                 slideshowStop: "Diashow anhalten"                    
                                                });
                    jQuery(".youtube").colorbox({
                                                loop:false,
                                                iframe:true,
                                                width:650,
                                                height:550
                                                });
                    jQuery(".iframe").colorbox({
                                                loop:true,
                                                width:"90%",
                                                height:"90%",
                                                iframe:true
                                                });
                    jQuery(".tp_editlink").colorbox({
                                                loop:false,
                                                width:"90%",
                                                height:"90%",
                                                iframe:true
                                                });
});


