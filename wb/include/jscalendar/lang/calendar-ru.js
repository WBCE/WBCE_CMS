// ** I18N

// Calendar RU language
// Translation: Sly Golovanov, http://golovanov.net, <sly@golovanov.net>
// Encoding: any
// Distributed under the same terms as the calendar itself.

// For translators: please use UTF-8 if possible.  We strongly believe that
// Unicode is the answer to a real internationalized world.  Also please
// include your contact information in the header, as can be seen above.

// full day names
Calendar._DN = new Array
("\u0432\u043e\u0441\u043a\u0440\u0435\u0441\u0435\u043d\u044c\u0435",
 "\u043f\u043e\u043d\u0435\u0434\u0435\u043b\u044c\u043d\u0438\u043a",
 "\u0432\u0442\u043e\u0440\u043d\u0438\u043a",
 "\u0441\u0440\u0435\u0434\u0430",
 "\u0447\u0435\u0442\u0432\u0435\u0440\u0433",
 "\u043f\u044f\u0442\u043d\u0438\u0446\u0430",
 "\u0441\u0443\u0431\u0431\u043e\u0442\u0430",
 "\u0432\u043e\u0441\u043a\u0440\u0435\u0441\u0435\u043d\u044c\u0435");

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
("\u0432\u0441\u043a",
 "\u043f\u043e\u043d",
 "\u0432\u0442\u0440",
 "\u0441\u0440\u0434",
 "\u0447\u0435\u0442",
 "\u043f\u044f\u0442",
 "\u0441\u0443\u0431",
 "\u0432\u0441\u043a");

// full month names
Calendar._MN = new Array
("\u044f\u043d\u0432\u0430\u0440\u044c",
 "\u0444\u0435\u0432\u0440\u0430\u043b\u044c",
 "\u043c\u0430\u0440\u0442",
 "\u0430\u043f\u0440\u0435\u043b\u044c",
 "\u043c\u0430\u0439",
 "\u0438\u044e\u043d\u044c",
 "\u0438\u044e\u043b\u044c",
 "\u0430\u0432\u0433\u0443\u0441\u0442",
 "\u0441\u0435\u043d\u0442\u044f\u0431\u0440\u044c",
 "\u043e\u043a\u0442\u044f\u0431\u0440\u044c",
 "\u043d\u043e\u044f\u0431\u0440\u044c",
 "\u0434\u0435\u043a\u0430\u0431\u0440\u044c");

// short month names
Calendar._SMN = new Array
("\u044f\u043d\u0432",
 "\u0444\u0435\u0432",
 "\u043c\u0430\u0440",
 "\u0430\u043f\u0440",
 "\u043c\u0430\u0439",
 "\u0438\u044e\u043d",
 "\u0438\u044e\u043b",
 "\u0430\u0432\u0433",
 "\u0441\u0435\u043d",
 "\u043e\u043a\u0442",
 "\u043d\u043e\u044f",
 "\u0434\u0435\u043a");

// tooltips
Calendar._TT = {};
Calendar._TT["INFO"] = "\u041e \u043a\u0430\u043b\u0435\u043d\u0434\u0430\u0440\u0435...";

Calendar._TT["ABOUT"] =
"DHTML Date/Time Selector\n" +
"(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" + // don't translate this this ;-)
"For latest version visit: http://www.dynarch.com/projects/calendar/\n" +
"Distributed under GNU LGPL.  See http://gnu.org/licenses/lgpl.html for details." +
"\n\n" +
"\u041a\u0430\u043a \u0432\u044b\u0431\u0440\u0430\u0442\u044c \u0434\u0430\u0442\u0443:\n" +
"- \u041f\u0440\u0438 \u043f\u043e\u043c\u043e\u0449\u0438 \u043a\u043d\u043e\u043f\u043e\u043a \xab, \xbb \u043c\u043e\u0436\u043d\u043e \u0432\u044b\u0431\u0440\u0430\u0442\u044c \u0433\u043e\u0434\n" +
"- \u041f\u0440\u0438 \u043f\u043e\u043c\u043e\u0449\u0438 \u043a\u043d\u043e\u043f\u043e\u043a " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " \u043c\u043e\u0436\u043d\u043e \u0432\u044b\u0431\u0440\u0430\u0442\u044c \u043c\u0435\u0441\u044f\u0446\n" +
"- \u041f\u043e\u0434\u0435\u0440\u0436\u0438\u0442\u0435 \u044d\u0442\u0438 \u043a\u043d\u043e\u043f\u043a\u0438 \u043d\u0430\u0436\u0430\u0442\u044b\u043c\u0438, \u0447\u0442\u043e\u0431\u044b \u043f\u043e\u044f\u0432\u0438\u043b\u043e\u0441\u044c \u043c\u0435\u043d\u044e \u0431\u044b\u0441\u0442\u0440\u043e\u0433\u043e \u0432\u044b\u0431\u043e\u0440\u0430.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"\u041a\u0430\u043a \u0432\u044b\u0431\u0440\u0430\u0442\u044c \u0432\u0440\u0435\u043c\u044f:\n" +
"- \u041f\u0440\u0438 \u043a\u043b\u0438\u043a\u0435 \u043d\u0430 \u0447\u0430\u0441\u0430\u0445 \u0438\u043b\u0438 \u043c\u0438\u043d\u0443\u0442\u0430\u0445 \u043e\u043d\u0438 \u0443\u0432\u0435\u043b\u0438\u0447\u0438\u0432\u0430\u044e\u0442\u0441\u044f\n" +
"- \u043f\u0440\u0438 \u043a\u043b\u0438\u043a\u0435 \u0441 \u043d\u0430\u0436\u0430\u0442\u043e\u0439 \u043a\u043b\u0430\u0432\u0438\u0448\u0435\u0439 Shift \u043e\u043d\u0438 \u0443\u043c\u0435\u043d\u044c\u0448\u0430\u044e\u0442\u0441\u044f\n" +
"- \u0435\u0441\u043b\u0438 \u043d\u0430\u0436\u0430\u0442\u044c \u0438 \u0434\u0432\u0438\u0433\u0430\u0442\u044c \u043c\u044b\u0448\u043a\u043e\u0439 \u0432\u043b\u0435\u0432\u043e/\u0432\u043f\u0440\u0430\u0432\u043e, \u043e\u043d\u0438 \u0431\u0443\u0434\u0443\u0442 \u043c\u0435\u043d\u044f\u0442\u044c\u0441\u044f \u0431\u044b\u0441\u0442\u0440\u0435\u0435.";

Calendar._TT["PREV_YEAR"] = "\u041d\u0430 \u0433\u043e\u0434 \u043d\u0430\u0437\u0430\u0434 (\u0443\u0434\u0435\u0440\u0436\u0438\u0432\u0430\u0442\u044c \u0434\u043b\u044f \u043c\u0435\u043d\u044e)";
Calendar._TT["PREV_MONTH"] = "\u041d\u0430 \u043c\u0435\u0441\u044f\u0446 \u043d\u0430\u0437\u0430\u0434 (\u0443\u0434\u0435\u0440\u0436\u0438\u0432\u0430\u0442\u044c \u0434\u043b\u044f \u043c\u0435\u043d\u044e)";
Calendar._TT["GO_TODAY"] = "\u0421\u0435\u0433\u043e\u0434\u043d\u044f";
Calendar._TT["NEXT_MONTH"] = "\u041d\u0430 \u043c\u0435\u0441\u044f\u0446 \u0432\u043f\u0435\u0440\u0435\u0434 (\u0443\u0434\u0435\u0440\u0436\u0438\u0432\u0430\u0442\u044c \u0434\u043b\u044f \u043c\u0435\u043d\u044e)";
Calendar._TT["NEXT_YEAR"] = "\u041d\u0430 \u0433\u043e\u0434 \u0432\u043f\u0435\u0440\u0435\u0434 (\u0443\u0434\u0435\u0440\u0436\u0438\u0432\u0430\u0442\u044c \u0434\u043b\u044f \u043c\u0435\u043d\u044e)";
Calendar._TT["SEL_DATE"] = "\u0412\u044b\u0431\u0435\u0440\u0438\u0442\u0435 \u0434\u0430\u0442\u0443";
Calendar._TT["DRAG_TO_MOVE"] = "\u041f\u0435\u0440\u0435\u0442\u0430\u0441\u043a\u0438\u0432\u0430\u0439\u0442\u0435 \u043c\u044b\u0448\u043a\u043e\u0439";
Calendar._TT["PART_TODAY"] = " (\u0441\u0435\u0433\u043e\u0434\u043d\u044f)";

// the following is to inform that "%s" is to be the first day of week
// %s will be replaced with the day name.
Calendar._TT["DAY_FIRST"] = "\u041f\u0435\u0440\u0432\u044b\u0439 \u0434\u0435\u043d\u044c \u043d\u0435\u0434\u0435\u043b\u0438 \u0431\u0443\u0434\u0435\u0442 %s";

// This may be locale-dependent.  It specifies the week-end days, as an array
// of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
Calendar._TT["WEEKEND"] = "0,6";

Calendar._TT["CLOSE"] = "\u0417\u0430\u043a\u0440\u044b\u0442\u044c";
Calendar._TT["TODAY"] = "\u0421\u0435\u0433\u043e\u0434\u043d\u044f";
Calendar._TT["TIME_PART"] = "(Shift-)\u043a\u043b\u0438\u043a \u0438\u043b\u0438 \u043d\u0430\u0436\u0430\u0442\u044c \u0438 \u0434\u0432\u0438\u0433\u0430\u0442\u044c";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d";
Calendar._TT["TT_DATE_FORMAT"] = "%e %b, %a";

Calendar._TT["WK"] = "\u043d\u0435\u0434";
Calendar._TT["TIME"] = "\u0412\u0440\u0435\u043c\u044f:";
