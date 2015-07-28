// ** I18N
Calendar._DN = new Array
("Duminic\u0103",
 "Luni",
 "Mar\u0163i",
 "Miercuri",
 "Joi",
 "Vineri",
 "S\u00e2mb\u0103t\u0103",
 "Duminic\u0103");
Calendar._SDN_len = 2;
Calendar._MN = new Array
("Ianuarie",
 "Februarie",
 "Martie",
 "Aprilie",
 "Mai",
 "Iunie",
 "Iulie",
 "August",
 "Septembrie",
 "Octombrie",
 "Noiembrie",
 "Decembrie");

// tooltips
Calendar._TT = {};

Calendar._TT["INFO"] = "Despre calendar";

Calendar._TT["ABOUT"] =
"DHTML Date/Time Selector\n" +
"(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" + // don't translate this this ;-)
"Pentru ultima versiune vizita\u0163i: http://www.dynarch.com/projects/calendar/\n" +
"Distribuit sub GNU LGPL.  See http://gnu.org/licenses/lgpl.html for details." +
"\n\n" +
"Selec\u0163ia datei:\n" +
"- Folosi\u0163i butoanele \xab, \xbb pentru a selecta anul\n" +
"- Folosi\u0163i butoanele " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " pentru a selecta luna\n" +
"- Tine\u0163i butonul mouse-ului ap\u0103sat pentru selec\u0163ie mai rapid\u0103.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Selec\u0163ia orei:\n" +
"- Click pe ora sau minut pentru a m\u0103ri valoarea cu 1\n" +
"- Sau Shift-Click pentru a mic\u015fora valoarea cu 1\n" +
"- Sau Click \u015fi drag pentru a selecta mai repede.";

Calendar._TT["PREV_YEAR"] = "Anul precedent (lung pt menu)";
Calendar._TT["PREV_MONTH"] = "Luna precedent\u0103 (lung pt menu)";
Calendar._TT["GO_TODAY"] = "Data de azi";
Calendar._TT["NEXT_MONTH"] = "Luna urm\u0103toare (lung pt menu)";
Calendar._TT["NEXT_YEAR"] = "Anul urm\u0103tor (lung pt menu)";
Calendar._TT["SEL_DATE"] = "Selecteaz\u0103 data";
Calendar._TT["DRAG_TO_MOVE"] = "Trage pentru a mi\u015fca";
Calendar._TT["PART_TODAY"] = " (ast\u0103zi)";
Calendar._TT["DAY_FIRST"] = "Afi\u015feaz\u0103 %s prima zi";
Calendar._TT["WEEKEND"] = "0,6";
Calendar._TT["CLOSE"] = "\u00cenchide";
Calendar._TT["TODAY"] = "Ast\u0103zi";
Calendar._TT["TIME_PART"] = "(Shift-)Click sau drag pentru a selecta";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%d-%m-%Y";
Calendar._TT["TT_DATE_FORMAT"] = "%A, %d %B";

Calendar._TT["WK"] = "spt";
Calendar._TT["TIME"] = "Ora:";
