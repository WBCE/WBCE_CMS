/* 
	calendar-cs-win.js
	language: Czech
	encoding: windows-1250
	author: Lubos Jerabek (xnet@seznam.cz)
	        Jan Uhlir (espinosa@centrum.cz)
*/

// ** I18N
Calendar._DN  = new Array('Ned\u011ble','Pond\u011bl\u00ed','\u00dater\u00fd','St\u0159eda','\u010ctvrtek','P\u00e1tek','Sobota','Ned\u011ble');
Calendar._SDN = new Array('Ne','Po','\u00dat','St','\u010ct','P\u00e1','So','Ne');
Calendar._MN  = new Array('Leden','\u00danor','B\u0159ezen','Duben','Kv\u011bten','\u010cerven','\u010cervenec','Srpen','Z\u00e1\u0159\u00ed','\u0158\u00edjen','Listopad','Prosinec');
Calendar._SMN = new Array('Led','\u00dano','B\u0159e','Dub','Kv\u011b','\u010crv','\u010cvc','Srp','Z\u00e1\u0159','\u0158\u00edj','Lis','Pro');

// tooltips
Calendar._TT = {};
Calendar._TT["INFO"] = "O komponent\u011b kalend\u00e1\u0159";
Calendar._TT["TOGGLE"] = "Zm\u011bna prvn\u00edho dne v t\u00fddnu";
Calendar._TT["PREV_YEAR"] = "P\u0159edchoz\u00ed rok (p\u0159idr\u017e pro menu)";
Calendar._TT["PREV_MONTH"] = "P\u0159edchoz\u00ed m\u011bs\u00edc (p\u0159idr\u017e pro menu)";
Calendar._TT["GO_TODAY"] = "Dne\u0161n\u00ed datum";
Calendar._TT["NEXT_MONTH"] = "Dal\u0161\u00ed m\u011bs\u00edc (p\u0159idr\u017e pro menu)";
Calendar._TT["NEXT_YEAR"] = "Dal\u0161\u00ed rok (p\u0159idr\u017e pro menu)";
Calendar._TT["SEL_DATE"] = "Vyber datum";
Calendar._TT["DRAG_TO_MOVE"] = "Chy\u0165 a t\u00e1hni, pro p\u0159esun";
Calendar._TT["PART_TODAY"] = " (dnes)";
Calendar._TT["MON_FIRST"] = "Uka\u017e jako prvn\u00ed Pond\u011bl\u00ed";
//Calendar._TT["SUN_FIRST"] = "Uka\u017e jako prvn\u00ed Ned\u011bli";

Calendar._TT["ABOUT"] =
"DHTML Date/Time Selector\n" +
"(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" + // don't translate this this ;-)
"For latest version visit: http://www.dynarch.com/projects/calendar/\n" +
"Distributed under GNU LGPL.  See http://gnu.org/licenses/lgpl.html for details." +
"\n\n" +
"V\u00fdb\u011br datumu:\n" +
"- Use the \xab, \xbb buttons to select year\n" +
"- Pou\u017eijte tla\u010d\u00edtka " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " k v\u00fdb\u011bru m\u011bs\u00edce\n" +
"- Podr\u017ete tla\u010d\u00edtko my\u0161i na jak\u00e9mkoliv z t\u011bch tla\u010d\u00edtek pro rychlej\u0161\u00ed v\u00fdb\u011br.";

Calendar._TT["ABOUT_TIME"] = "\n\n" +
"V\u00fdb\u011br \u010dasu:\n" +
"- Klikn\u011bte na jakoukoliv z \u010d\u00e1st\u00ed v\u00fdb\u011bru \u010dasu pro zv\u00fd\u0161en\u00ed.\n" +
"- nebo Shift-click pro sn\u00ed\u017een\u00ed\n" +
"- nebo klikn\u011bte a t\u00e1hn\u011bte pro rychlej\u0161\u00ed v\u00fdb\u011br.";

// the following is to inform that "%s" is to be the first day of week
// %s will be replaced with the day name.
Calendar._TT["DAY_FIRST"] = "Zobraz %s prvn\u00ed";

// This may be locale-dependent.  It specifies the week-end days, as an array
// of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
Calendar._TT["WEEKEND"] = "0,6";

Calendar._TT["CLOSE"] = "Zav\u0159\u00edt";
Calendar._TT["TODAY"] = "Dnes";
Calendar._TT["TIME_PART"] = "(Shift-)Klikni nebo t\u00e1hni pro zm\u011bnu hodnoty";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "d.m.yy";
Calendar._TT["TT_DATE_FORMAT"] = "%a, %b %e";

Calendar._TT["WK"] = "wk";
Calendar._TT["TIME"] = "\u010cas:";
