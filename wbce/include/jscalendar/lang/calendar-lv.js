// ** I18N

// Calendar LV language
// Author: Juris Valdovskis, <juris@dc.lv>
// Encoding: cp1257
// Distributed under the same terms as the calendar itself.

// For translators: please use UTF-8 if possible.  We strongly believe that
// Unicode is the answer to a real internationalized world.  Also please
// include your contact information in the header, as can be seen above.

// full day names
Calendar._DN = new Array
("Sv\u0113tdiena",
 "Pirmdiena",
 "Otrdiena",
 "Tre\u0161diena",
 "Ceturdiena",
 "Piektdiena",
 "Sestdiena",
 "Sv\u0113tdiena");

// Please note that the following array of short day names (and the same goes
// for short month names, _SMN) isn't absolutely necessary.  We give it here
// for exemplification on how one can customize the short day names, but if
// they are simply the first N letters of the full name you can simply say:
//
//   Calendar._SDN_len = N; // short day name length
//   Calendar._SMN_len = N; // short month name length
//
// If N = 3 then this is not needed either since we assume a value of 3 if not
// present, to be compatible with translation files that were written before
// this feature.

// short day names
Calendar._SDN = new Array
("Sv",
 "Pr",
 "Ot",
 "Tr",
 "Ce",
 "Pk",
 "Se",
 "Sv");

// full month names
Calendar._MN = new Array
("Janv\u0101ris",
 "Febru\u0101ris",
 "Marts",
 "Apr\u012blis",
 "Maijs",
 "J\u016bnijs",
 "J\u016blijs",
 "Augusts",
 "Septembris",
 "Oktobris",
 "Novembris",
 "Decembris");

// short month names
Calendar._SMN = new Array
("Jan",
 "Feb",
 "Mar",
 "Apr",
 "Mai",
 "J\u016bn",
 "J\u016bl",
 "Aug",
 "Sep",
 "Okt",
 "Nov",
 "Dec");

// tooltips
Calendar._TT = {};
Calendar._TT["INFO"] = "Par kalend\u0101ru";

Calendar._TT["ABOUT"] =
"DHTML Date/Time Selector\n" +
"(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" + // don't translate this this ;-)
"For latest version visit: http://www.dynarch.com/projects/calendar/\n" +
"Distributed under GNU LGPL.  See http://gnu.org/licenses/lgpl.html for details." +
"\n\n" +
"Datuma izv\u0113le:\n" +
"- Izmanto \xab, \xbb pogas, lai izv\u0113l\u0113tos gadu\n" +
"- Izmanto " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + "pogas, lai izv\u0113l\u0113tos m\u0113nesi\n" +
"- Turi nospiestu peles pogu uz jebkuru no augst\u0101k min\u0113taj\u0101m pog\u0101m, lai pa\u0101trin\u0101tu izv\u0113li.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Laika izv\u0113le:\n" +
"- Uzklik\u0161\u0137ini uz jebkuru no laika da\u013c\u0101m, lai palielin\u0101tu to\n" +
"- vai Shift-klik\u0161\u0137is, lai samazin\u0101tu to\n" +
"- vai noklik\u0161\u0137ini un velc uz attiec\u012bgo virzienu lai main\u012btu \u0101tr\u0101k.";

Calendar._TT["PREV_YEAR"] = "Iepr. gads (turi izv\u0113lnei)";
Calendar._TT["PREV_MONTH"] = "Iepr. m\u0113nesis (turi izv\u0113lnei)";
Calendar._TT["GO_TODAY"] = "\u0160odien";
Calendar._TT["NEXT_MONTH"] = "N\u0101ko\u0161ais m\u0113nesis (turi izv\u0113lnei)";
Calendar._TT["NEXT_YEAR"] = "N\u0101ko\u0161ais gads (turi izv\u0113lnei)";
Calendar._TT["SEL_DATE"] = "Izv\u0113lies datumu";
Calendar._TT["DRAG_TO_MOVE"] = "Velc, lai p\u0101rvietotu";
Calendar._TT["PART_TODAY"] = " (\u0161odien)";

// the following is to inform that "%s" is to be the first day of week
// %s will be replaced with the day name.
Calendar._TT["DAY_FIRST"] = "Att\u0113lot %s k\u0101 pirmo";

// This may be locale-dependent.  It specifies the week-end days, as an array
// of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
Calendar._TT["WEEKEND"] = "1,7";

Calendar._TT["CLOSE"] = "Aizv\u0113rt";
Calendar._TT["TODAY"] = "\u0160odien";
Calendar._TT["TIME_PART"] = "(Shift-)Klik\u0161\u0137is vai p\u0101rvieto, lai main\u012btu";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%d-%m-%Y";
Calendar._TT["TT_DATE_FORMAT"] = "%a, %e %b";

Calendar._TT["WK"] = "wk";
Calendar._TT["TIME"] = "Laiks:";
