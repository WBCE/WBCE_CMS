// ** I18N

// Calendar BG language
// Author: Mihai Bazon, <mihai_bazon@yahoo.com>
// Translator: Valentin Sheiretsky, <valio@valio.eu.org>
// Encoding: Windows-1251
// Distributed under the same terms as the calendar itself.

// For translators: please use UTF-8 if possible.  We strongly believe that
// Unicode is the answer to a real internationalized world.  Also please
// include your contact information in the header, as can be seen above.

// full day names
Calendar._DN = new Array
("\u041d\u0435\u0434\u0435\u043b\u044f",
 "\u041f\u043e\u043d\u0435\u0434\u0435\u043b\u043d\u0438\u043a",
 "\u0412\u0442\u043e\u0440\u043d\u0438\u043a",
 "\u0421\u0440\u044f\u0434\u0430",
 "\u0427\u0435\u0442\u0432\u044a\u0440\u0442\u044a\u043a",
 "\u041f\u0435\u0442\u044a\u043a",
 "\u0421\u044a\u0431\u043e\u0442\u0430",
 "\u041d\u0435\u0434\u0435\u043b\u044f");

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
("\u041d\u0435\u0434",
 "\u041f\u043e\u043d",
 "\u0412\u0442\u043e",
 "\u0421\u0440\u044f",
 "\u0427\u0435\u0442",
 "\u041f\u0435\u0442",
 "\u0421\u044a\u0431",
 "\u041d\u0435\u0434");

// full month names
Calendar._MN = new Array
("\u042f\u043d\u0443\u0430\u0440\u0438",
 "\u0424\u0435\u0432\u0440\u0443\u0430\u0440\u0438",
 "\u041c\u0430\u0440\u0442",
 "\u0410\u043f\u0440\u0438\u043b",
 "\u041c\u0430\u0439",
 "\u042e\u043d\u0438",
 "\u042e\u043b\u0438",
 "\u0410\u0432\u0433\u0443\u0441\u0442",
 "\u0421\u0435\u043f\u0442\u0435\u043c\u0432\u0440\u0438",
 "\u041e\u043a\u0442\u043e\u043c\u0432\u0440\u0438",
 "\u041d\u043e\u0435\u043c\u0432\u0440\u0438",
 "\u0414\u0435\u043a\u0435\u043c\u0432\u0440\u0438");

// short month names
Calendar._SMN = new Array
("\u042f\u043d\u0443",
 "\u0424\u0435\u0432",
 "\u041c\u0430\u0440",
 "\u0410\u043f\u0440",
 "\u041c\u0430\u0439",
 "\u042e\u043d\u0438",
 "\u042e\u043b\u0438",
 "\u0410\u0432\u0433",
 "\u0421\u0435\u043f",
 "\u041e\u043a\u0442",
 "\u041d\u043e\u0435",
 "\u0414\u0435\u043a");

// tooltips
Calendar._TT = {};
Calendar._TT["INFO"] = "\u0418\u043d\u0444\u043e\u0440\u043c\u0430\u0446\u0438\u044f \u0437\u0430 \u043a\u0430\u043b\u0435\u043d\u0434\u0430\u0440\u0430";

Calendar._TT["ABOUT"] =
"DHTML Date/Time Selector\n" +
"(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" + // don't translate this this ;-)
"For latest version visit: http://www.dynarch.com/projects/calendar/\n" +
"Distributed under GNU LGPL.  See http://gnu.org/licenses/lgpl.html for details." +
"\n\n" +
"Date selection:\n" +
"- Use the \xab, \xbb buttons to select year\n" +
"- Use the " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " buttons to select month\n" +
"- Hold mouse button on any of the above buttons for faster selection.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Time selection:\n" +
"- Click on any of the time parts to increase it\n" +
"- or Shift-click to decrease it\n" +
"- or click and drag for faster selection.";

Calendar._TT["PREV_YEAR"] = "\u041f\u0440\u0435\u0434\u043d\u0430 \u0433\u043e\u0434\u0438\u043d\u0430 (\u0437\u0430\u0434\u0440\u044a\u0436\u0442\u0435 \u0437\u0430 \u043c\u0435\u043d\u044e)";
Calendar._TT["PREV_MONTH"] = "\u041f\u0440\u0435\u0434\u0435\u043d \u043c\u0435\u0441\u0435\u0446 (\u0437\u0430\u0434\u0440\u044a\u0436\u0442\u0435 \u0437\u0430 \u043c\u0435\u043d\u044e)";
Calendar._TT["GO_TODAY"] = "\u0418\u0437\u0431\u0435\u0440\u0435\u0442\u0435 \u0434\u043d\u0435\u0441";
Calendar._TT["NEXT_MONTH"] = "\u0421\u043b\u0435\u0434\u0432\u0430\u0449 \u043c\u0435\u0441\u0435\u0446 (\u0437\u0430\u0434\u0440\u044a\u0436\u0442\u0435 \u0437\u0430 \u043c\u0435\u043d\u044e)";
Calendar._TT["NEXT_YEAR"] = "\u0421\u043b\u0435\u0434\u0432\u0430\u0449\u0430 \u0433\u043e\u0434\u0438\u043d\u0430 (\u0437\u0430\u0434\u0440\u044a\u0436\u0442\u0435 \u0437\u0430 \u043c\u0435\u043d\u044e)";
Calendar._TT["SEL_DATE"] = "\u0418\u0437\u0431\u0435\u0440\u0435\u0442\u0435 \u0434\u0430\u0442\u0430";
Calendar._TT["DRAG_TO_MOVE"] = "\u041f\u0440\u0435\u043c\u0435\u0441\u0442\u0432\u0430\u043d\u0435";
Calendar._TT["PART_TODAY"] = " (\u0434\u043d\u0435\u0441)";

// the following is to inform that "%s" is to be the first day of week
// %s will be replaced with the day name.
Calendar._TT["DAY_FIRST"] = "%s \u043a\u0430\u0442\u043e \u043f\u044a\u0440\u0432\u0438 \u0434\u0435\u043d";

// This may be locale-dependent.  It specifies the week-end days, as an array
// of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
Calendar._TT["WEEKEND"] = "0,6";

Calendar._TT["CLOSE"] = "\u0417\u0430\u0442\u0432\u043e\u0440\u0435\u0442\u0435";
Calendar._TT["TODAY"] = "\u0414\u043d\u0435\u0441";
Calendar._TT["TIME_PART"] = "(Shift-)Click \u0438\u043b\u0438 drag \u0437\u0430 \u0434\u0430 \u043f\u0440\u043e\u043c\u0435\u043d\u0438\u0442\u0435 \u0441\u0442\u043e\u0439\u043d\u043e\u0441\u0442\u0442\u0430";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d";
Calendar._TT["TT_DATE_FORMAT"] = "%A - %e %B %Y";

Calendar._TT["WK"] = "\u0421\u0435\u0434\u043c";
Calendar._TT["TIME"] = "\u0427\u0430\u0441:";

