// ** I18N

// Calendar SK language
// Author: Peter Valach (pvalach@gmx.net)
// Encoding: utf-8
// Last update: 2003/10/29
// Distributed under the same terms as the calendar itself.

// full day names
Calendar._DN = new Array
("Nede\u00c4\u013ea",
 "Pondelok",
 "Utorok",
 "Streda",
 "\u0139\u00a0tvrtok",
 "Piatok",
 "Sobota",
 "Nede\u00c4\u013ea");

// short day names
Calendar._SDN = new Array
("Ned",
 "Pon",
 "Uto",
 "Str",
 "\u0139\u00a0tv",
 "Pia",
 "Sob",
 "Ned");

// full month names
Calendar._MN = new Array
("Janu\u0102\u02c7r",
 "Febru\u0102\u02c7r",
 "Marec",
 "Apr\u0102\u00adl",
 "M\u0102\u02c7j",
 "J\u0102\u015fn",
 "J\u0102\u015fl",
 "August",
 "September",
 "Okt\u0102\u0142ber",
 "November",
 "December");

// short month names
Calendar._SMN = new Array
("Jan",
 "Feb",
 "Mar",
 "Apr",
 "M\u0102\u02c7j",
 "J\u0102\u015fn",
 "J\u0102\u015fl",
 "Aug",
 "Sep",
 "Okt",
 "Nov",
 "Dec");

// tooltips
Calendar._TT = {};
Calendar._TT["INFO"] = "O kalend\u0102\u02c7ri";

Calendar._TT["ABOUT"] =
"DHTML Date/Time Selector\n" +
"(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" +
"Posledn\u0102\u015f verziu n\u0102\u02c7jdete na: http://www.dynarch.com/projects/calendar/\n" +
"Distribuovan\u0102\u00a9 pod GNU LGPL.  Vi\u00c4\u0179 http://gnu.org/licenses/lgpl.html pre detaily." +
"\n\n" +
"V\u0102\u02ddber d\u0102\u02c7tumu:\n" +
"- Pou\u0139\u013eite tla\u00c4\u0164idl\u0102\u02c7 \xab, \xbb pre v\u0102\u02ddber roku\n" +
"- Pou\u0139\u013eite tla\u00c4\u0164idl\u0102\u02c7 " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " pre v\u0102\u02ddber mesiaca\n" +
"- Ak ktor\u0102\u00a9ko\u00c4\u013evek z t\u0102\u02ddchto tla\u00c4\u0164idiel podr\u0139\u013e\u0102\u00adte dlh\u0139\u02c7ie, zobraz\u0102\u00ad sa r\u0102\u02ddchly v\u0102\u02ddber.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"V\u0102\u02ddber \u00c4\u0164asu:\n" +
"- Kliknutie na niektor\u0102\u015f polo\u0139\u013eku \u00c4\u0164asu ju zv\u0102\u02dd\u0139\u02c7i\n" +
"- Shift-klik ju zn\u0102\u00ad\u0139\u013ei\n" +
"- Ak podr\u0139\u013e\u0102\u00adte tla\u00c4\u0164\u0102\u00adtko stla\u00c4\u0164en\u0102\u00a9, pos\u0102\u015fvan\u0102\u00adm men\u0102\u00adte hodnotu.";

Calendar._TT["PREV_YEAR"] = "Predo\u0139\u02c7l\u0102\u02dd rok (podr\u0139\u013ete pre menu)";
Calendar._TT["PREV_MONTH"] = "Predo\u0139\u02c7l\u0102\u02dd mesiac (podr\u0139\u013ete pre menu)";
Calendar._TT["GO_TODAY"] = "Prejs\u0139\u0104 na dne\u0139\u02c7ok";
Calendar._TT["NEXT_MONTH"] = "Nasl. mesiac (podr\u0139\u013ete pre menu)";
Calendar._TT["NEXT_YEAR"] = "Nasl. rok (podr\u0139\u013ete pre menu)";
Calendar._TT["SEL_DATE"] = "Zvo\u00c4\u013ete d\u0102\u02c7tum";
Calendar._TT["DRAG_TO_MOVE"] = "Podr\u0139\u013ean\u0102\u00adm tla\u00c4\u0164\u0102\u00adtka zmen\u0102\u00adte polohu";
Calendar._TT["PART_TODAY"] = " (dnes)";
Calendar._TT["MON_FIRST"] = "Zobrazi\u0139\u0104 pondelok ako prv\u0102\u02dd";
Calendar._TT["SUN_FIRST"] = "Zobrazi\u0139\u0104 nede\u00c4\u013eu ako prv\u0102\u015f";
Calendar._TT["CLOSE"] = "Zavrie\u0139\u0104";
Calendar._TT["TODAY"] = "Dnes";
Calendar._TT["TIME_PART"] = "(Shift-)klik/\u0139\u0104ahanie zmen\u0102\u00ad hodnotu";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "$d. %m. %Y";
Calendar._TT["TT_DATE_FORMAT"] = "%a, %e. %b";

Calendar._TT["WK"] = "t\u0102\u02dd\u0139\u013e";

Calendar._TT["DAY_FIRST"] = "Display %s first";
Calendar._TT["WEEKEND"] = "0,6";
Calendar._TT["TIME"] = "Time:";

