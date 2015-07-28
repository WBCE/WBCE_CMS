// ** I18N

// Calendar EN language
// Author: Idan Sofer, <idan@idanso.dyndns.org>
// Encoding: UTF-8
// Distributed under the same terms as the calendar itself.

// For translators: please use UTF-8 if possible.  We strongly believe that
// Unicode is the answer to a real internationalized world.  Also please
// include your contact information in the header, as can be seen above.

// full day names
Calendar._DN = new Array
("\u05e8\u05d0\u05e9\u05d5\u05df",
 "\u05e9\u05e0\u05d9",
 "\u05e9\u05dc\u05d9\u05e9\u05d9",
 "\u05e8\u05d1\u05d9\u05e2\u05d9",
 "\u05d7\u05de\u05d9\u05e9\u05d9",
 "\u05e9\u05d9\u05e9\u05d9",
 "\u05e9\u05d1\u05ea",
 "\u05e8\u05d0\u05e9\u05d5\u05df");

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
("\u05d0",
 "\u05d1",
 "\u05d2",
 "\u05d3",
 "\u05d4",
 "\u05d5",
 "\u05e9",
 "\u05d0");

// full month names
Calendar._MN = new Array
("\u05d9\u05e0\u05d5\u05d0\u05e8",
 "\u05e4\u05d1\u05e8\u05d5\u05d0\u05e8",
 "\u05de\u05e8\u05e5",
 "\u05d0\u05e4\u05e8\u05d9\u05dc",
 "\u05de\u05d0\u05d9",
 "\u05d9\u05d5\u05e0\u05d9",
 "\u05d9\u05d5\u05dc\u05d9",
 "\u05d0\u05d5\u05d2\u05d5\u05e1\u05d8",
 "\u05e1\u05e4\u05d8\u05de\u05d1\u05e8",
 "\u05d0\u05d5\u05e7\u05d8\u05d5\u05d1\u05e8",
 "\u05e0\u05d5\u05d1\u05de\u05d1\u05e8",
 "\u05d3\u05e6\u05de\u05d1\u05e8");

// short month names
Calendar._SMN = new Array
("\u05d9\u05e0\u05d0",
 "\u05e4\u05d1\u05e8",
 "\u05de\u05e8\u05e5",
 "\u05d0\u05e4\u05e8",
 "\u05de\u05d0\u05d9",
 "\u05d9\u05d5\u05e0",
 "\u05d9\u05d5\u05dc",
 "\u05d0\u05d5\u05d2",
 "\u05e1\u05e4\u05d8",
 "\u05d0\u05d5\u05e7",
 "\u05e0\u05d5\u05d1",
 "\u05d3\u05e6\u05de");

// tooltips
Calendar._TT = {};
Calendar._TT["INFO"] = "\u05d0\u05d5\u05d3\u05d5\u05ea \u05d4\u05e9\u05e0\u05ea\u05d5\u05df";

Calendar._TT["ABOUT"] =
"\u05d1\u05d7\u05e8\u05df \u05ea\u05d0\u05e8\u05d9\u05da/\u05e9\u05e2\u05d4 DHTML\n" +
"(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" + // don't translate this this ;-)
"\u05d4\u05d2\u05d9\u05e8\u05e1\u05d0 \u05d4\u05d0\u05d7\u05e8\u05d5\u05e0\u05d4 \u05d6\u05de\u05d9\u05e0\u05d4 \u05d1: http://www.dynarch.com/projects/calendar/\n" +
"\u05de\u05d5\u05e4\u05e5 \u05ea\u05d7\u05ea \u05d6\u05d9\u05db\u05d9\u05d5\u05df \u05d4 GNU LGPL.  \u05e2\u05d9\u05d9\u05df \u05d1 http://gnu.org/licenses/lgpl.html \u05dc\u05e4\u05e8\u05d8\u05d9\u05dd \u05e0\u05d5\u05e1\u05e4\u05d9\u05dd." +
"\n\n" +
\u05d1\u05d7\u05d9\u05e8\u05ea \u05ea\u05d0\u05e8\u05d9\u05da:\n" +
"- \u05d4\u05e9\u05ea\u05de\u05e9 \u05d1\u05db\u05e4\u05ea\u05d5\u05e8\u05d9\u05dd \xab, \xbb \u05dc\u05d1\u05d7\u05d9\u05e8\u05ea \u05e9\u05e0\u05d4\n" +
"- \u05d4\u05e9\u05ea\u05de\u05e9 \u05d1\u05db\u05e4\u05ea\u05d5\u05e8\u05d9\u05dd " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " \u05dc\u05d1\u05d7\u05d9\u05e8\u05ea \u05d7\u05d5\u05d3\u05e9\n" +
"- \u05d4\u05d7\u05d6\u05e7 \u05d4\u05e2\u05db\u05d1\u05e8 \u05dc\u05d7\u05d5\u05e5 \u05de\u05e2\u05dc \u05d4\u05db\u05e4\u05ea\u05d5\u05e8\u05d9\u05dd \u05d4\u05de\u05d5\u05d6\u05db\u05e8\u05d9\u05dd \u05dc\u05e2\u05d9\u05dc \u05dc\u05d1\u05d7\u05d9\u05e8\u05d4 \u05de\u05d4\u05d9\u05e8\u05d4 \u05d9\u05d5\u05ea\u05e8.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"\u05d1\u05d7\u05d9\u05e8\u05ea \u05d6\u05de\u05df:\n" +
"- \u05dc\u05d7\u05e5 \u05e2\u05dc \u05db\u05dc \u05d0\u05d7\u05d3 \u05de\u05d7\u05dc\u05e7\u05d9 \u05d4\u05d6\u05de\u05df \u05db\u05d3\u05d9 \u05dc\u05d4\u05d5\u05e1\u05d9\u05e3\n" +
"- \u05d0\u05d5 shift \u05d1\u05e9\u05d9\u05dc\u05d5\u05d1 \u05e2\u05dd \u05dc\u05d7\u05d9\u05e6\u05d4 \u05db\u05d3\u05d9 \u05dc\u05d4\u05d7\u05e1\u05d9\u05e8\n" +
"- \u05d0\u05d5 \u05dc\u05d7\u05e5 \u05d5\u05d2\u05e8\u05d5\u05e8 \u05dc\u05e4\u05e2\u05d5\u05dc\u05d4 \u05de\u05d4\u05d9\u05e8\u05d4 \u05d9\u05d5\u05ea\u05e8.";

Calendar._TT["PREV_YEAR"] = "\u05e9\u05e0\u05d4 \u05e7\u05d5\u05d3\u05de\u05ea - \u05d4\u05d7\u05d6\u05e7 \u05dc\u05e7\u05d1\u05dc\u05ea \u05ea\u05e4\u05e8\u05d9\u05d8";
Calendar._TT["PREV_MONTH"] = "\u05d7\u05d5\u05d3\u05e9 \u05e7\u05d5\u05d3\u05dd - \u05d4\u05d7\u05d6\u05e7 \u05dc\u05e7\u05d1\u05dc\u05ea \u05ea\u05e4\u05e8\u05d9\u05d8";
Calendar._TT["GO_TODAY"] = "\u05e2\u05d1\u05d5\u05e8 \u05dc\u05d4\u05d9\u05d5\u05dd";
Calendar._TT["NEXT_MONTH"] = "\u05d7\u05d5\u05d3\u05e9 \u05d4\u05d1\u05d0 - \u05d4\u05d7\u05d6\u05e7 \u05dc\u05ea\u05e4\u05e8\u05d9\u05d8";
Calendar._TT["NEXT_YEAR"] = "\u05e9\u05e0\u05d4 \u05d4\u05d1\u05d0\u05d4 - \u05d4\u05d7\u05d6\u05e7 \u05dc\u05ea\u05e4\u05e8\u05d9\u05d8";
Calendar._TT["SEL_DATE"] = "\u05d1\u05d7\u05e8 \u05ea\u05d0\u05e8\u05d9\u05da";
Calendar._TT["DRAG_TO_MOVE"] = "\u05d2\u05e8\u05d5\u05e8 \u05dc\u05d4\u05d6\u05d6\u05d4";
Calendar._TT["PART_TODAY"] = " )\u05d4\u05d9\u05d5\u05dd(";

// the following is to inform that "%s" is to be the first day of week
// %s will be replaced with the day name.
Calendar._TT["DAY_FIRST"] = "\u05d4\u05e6\u05d2 %s \u05e7\u05d5\u05d3\u05dd";

// This may be locale-dependent.  It specifies the week-end days, as an array
// of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
Calendar._TT["WEEKEND"] = "6";

Calendar._TT["CLOSE"] = "\u05e1\u05d2\u05d5\u05e8";
Calendar._TT["TODAY"] = "\u05d4\u05d9\u05d5\u05dd";
Calendar._TT["TIME_PART"] = "(\u05e9\u05d9\u05e4\u05d8-)\u05dc\u05d7\u05e5 \u05d5\u05d2\u05e8\u05d5\u05e8 \u05db\u05d3\u05d9 \u05dc\u05e9\u05e0\u05d5\u05ea \u05e2\u05e8\u05da";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d";
Calendar._TT["TT_DATE_FORMAT"] = "%a, %b %e";

Calendar._TT["WK"] = "wk";
Calendar._TT["TIME"] = "\u05e9\u05e2\u05d4::";
