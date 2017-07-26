/*	#################################################################################

	JQuery Module: Sticky Elements
	Version: 2.1.0
	Development: Yetiie
	Author: Yetiie
	
	Gebrauch: Das jQuery kann ohne jede Einschränkung in jeder Form
	genutzt werden jedoch ohne jegliche Garantie in jeder Form oder 
	für jeden Eisatzzweck durch den Author. Der Author übernimmt keinerlei
	Haftung für mögliche Schäden durch den Gebrauch jedweder Art.
	
	Use: The Jquery ist free to use without any restrictions in any way 
	but without any warranty in any form or any case by the author. Any
	liability of the author is excluded for any possible damage which could 
	be rised by use in any form.
	
	
 *	################################################################################# */

$.fn.stickyElements = function(options) {

	// Vorgabewerte
	options = $.extend({

		// Method to trigger an element as sticky. Possible Values:
		// visibility (Standard): triggers (and checks) the state if element is visible (open/closed) --> easy trigger open/closed boxes)
		// class: triggers a class which marks the state of the element 
		stickyMethod: 'visibility',

		// ONLY if stickyMethod = class
		// name of class for the state of the element (i.e. boxOpen, colorBlue, ...)
		stickyFormatClass: '',

		// Page Modus
		// singlePage (Standard): stickyElements works on the specific page on a domain
		// domainWide: stickyElements is sticky for same elements on different pages off the domain
		pageModus: 'singlePage'

	}, options);

	// Prevent Condition
	// prevent from running in method class when no stickyFormatClass is defined
	if(  (options.stickyMethod == 'class')  &&   (options.stickyFormatClass == '')  ) {
		// do nothting
	}else{
		// execute the jq module for this call
		// ... ... ... code below:

		// Cookie: Name bilden
		// mit Identifyer --> mehrere Aufrufe auf einer Seite
		var cookieIdentifier = $(this).selector;
		cookieIdentifier = escape(cookieIdentifier);
		cookieIdentifier = cookieIdentifier.replace('.', '_');
		var cookieName = 'stElem__' + cookieIdentifier;

		// IDs für die zu beobachtenden Elemente (= Selector) Speichern 
		// --> Verfügbarkeit in nachfolgender Funktion $(window).bind(... function...)
		var $selectedElements = $(this);

		// Bestehenden Cookie auslesen
		var cookieValue = '';
		var cookieGetString = '';
		cookieGetString = document.cookie;
		if( cookieGetString.length != 0 ) {
			cookieGetString = unescape(cookieGetString);
			var arrCookie = cookieGetString.match( new RegExp(cookieName + '=([^;]*)', 'g') )
			if(  arrCookie != null  ){
				cookieValue=RegExp.$1;
			}	
		}	

		// Letzten Zustand der Boxen rekonstruieren
		if(cookieValue.length != 0){

			// Array erstellen: IDs der offenen Boxen 

			// letztes Delimiter-Zeichen (hier: #) entfernen da sonst Extra-Array-Element
			cookieValue = cookieValue.substring(0,cookieValue.length-1);
			var arrayElementValues = cookieValue.split('#');

			// Je Cookie-ID: Jedes offene Fenster wiederherstellen
			$.each(arrayElementValues, function(index, idValueString ) {

				//ID bereitstellen
				var regExpId = /\[id:(.*?)\]/;
				var cookieVal = idValueString.match(regExpId);
				var elementID = cookieVal[1];

				// CSS-Wert bereitstellen
				var regExpId = /\[value:(.*?)\]/;
				var cookieVal = idValueString.match(regExpId);
				var cssProperty = cookieVal[1];

				// Werte Zuweisen

				// method: visibility --> get value for display from string an set it to element
				if(options.stickyMethod == 'visibility'){

					$selectedElements.eq(elementID).css('display', cssProperty);

				// method: class --> get Information for stickyFormatClass from cookie and (if class is defined) set it to element
				}else if(options.stickyMethod == 'class'){
					if(cssProperty != 'noClass'){
						$selectedElements.eq(elementID).addClass(options.stickyFormatClass);
					}
				}

			});
		}

		// Beim Neuladen oder Verlassen der Seite
		// --> Zustände der Boxen (offen/geschlossen) ermitteln 	
		$(window).bind('beforeunload', function(){

            var BoxenIdValueString = '';
			$selectedElements.each(function(){

                // method: visibility --> check if element is open/closed (by visibility) and store it to cookie string
				if(options.stickyMethod == 'visibility'){

                    var elementId = $($selectedElements).index( this );
					var elementSichtbar = $(this).is(':visible');
					if(elementSichtbar){
						BoxenIdValueString = BoxenIdValueString + '[id:' + elementId + '][value:block]' + '#';
					}else{
						BoxenIdValueString = BoxenIdValueString + '[id:' + elementId + '][value:none]' + '#';
					}		

				// method: class --> check it element has the given class and  store it to cookie string
				}else if(options.stickyMethod == 'class'){

					var elementId = $($selectedElements).index( this );
					var elementStickyClassExists = $(this).hasClass(options.stickyFormatClass);
					if(elementStickyClassExists){
						BoxenIdValueString = BoxenIdValueString + '[id:' + elementId + '][value:' + options.stickyFormatClass + ']' + '#';
					}else{
						BoxenIdValueString = BoxenIdValueString + '[id:' + elementId + '][value:noClass]' + '#';
					}		
				}
			});

			// set cookie
			var cookieSetString = escape(BoxenIdValueString);

			// 
			if(	options.pageModus == 'domainWide'){

				// pageModus=domainWide --> set cookie to the the domain
				var myDomain = document.domain;

				// Special for working on local webserver-installation width local host
				//=> google chrome
				//=> don't write name of domain, write '' instead
				(myDomain == 'localhost') ? myDomain = '' : myDomain = myDomain;
				document.cookie = cookieName + '=' + cookieSetString + ';domain=' + myDomain + ';path=/';

			}else{

				// Every other case: standard
				// pageModus=singlePage --> set cookie to the specific page of the domain
				document.cookie = cookieName + '=' + cookieSetString;

			}

		});

    } // END prevent condition --> do not run on method class when stickyFormatClass not defined
};