// ** I18N

// Calendar HU language
// Author: ???
// Modifier: KARASZI Istvan, <jscalendar@spam.raszi.hu>
// Encoding: any
// Distributed under the same terms as the calendar itself.

// For translators: please use UTF-8 if possible.  We strongly believe that
// Unicode is the answer to a real internationalized world.  Also please
// include your contact information in the header, as can be seen above.

// full day names
Calendar._DN = new Array
("Vas\u00e1rnap",
 "H\u00e9tf\u0151",
 "Kedd",
 "Szerda",
 "Cs\u00fct\u00f6rt\u00f6k",
 "P\u00e9ntek",
 "Szombat",
 "Vas\u00e1rnap");

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
("v",
 "h",
 "k",
 "sze",
 "cs",
 "p",
 "szo",
 "v");

// full month names
Calendar._MN = new Array
("janu\u00e1r",
 "febru\u00e1r",
 "m\u00e1rcius",
 "\u00e1prilis",
 "m\u00e1jus",
 "j\u00fanius",
 "j\u00falius",
 "augusztus",
 "szeptember",
 "okt\u00f3ber",
 "november",
 "december");

// short month names
Calendar._SMN = new Array
("jan",
 "feb",
 "m\u00e1r",
 "\u00e1pr",
 "m\u00e1j",
 "j\u00fan",
 "j\u00fal",
 "aug",
 "sze",
 "okt",
 "nov",
 "dec");

// tooltips
Calendar._TT = {};
Calendar._TT["INFO"] = "A kalend\u00e1riumr\u00f3l";

Calendar._TT["ABOUT"] =
"DHTML d\u00e1tum/id\u0151 kiv\u00e1laszt\u00f3\n" +
"(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" + // don't translate this this ;-)
"a legfrissebb verzi\u00f3 megtal\u00e1lhat\u00f3: http://www.dynarch.com/projects/calendar/\n" +
"GNU LGPL alatt terjesztve.  L\u00e1sd a http://gnu.org/licenses/lgpl.html oldalt a r\u00e9szletekhez." +
"\n\n" +
"D\u00e1tum v\u00e1laszt\u00e1s:\n" +
"- haszn\u00e1lja a \xab, \xbb gombokat az \u00e9v kiv\u00e1laszt\u00e1s\u00e1hoz\n" +
"- haszn\u00e1lja a " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " gombokat a h\u00f3nap kiv\u00e1laszt\u00e1s\u00e1hoz\n" +
"- tartsa lenyomva az eg\u00e9rgombot a gyors v\u00e1laszt\u00e1shoz.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Id\u0151 v\u00e1laszt\u00e1s:\n" +
"- kattintva n\u00f6velheti az id\u0151t\n" +
"- shift-tel kattintva cs\u00f6kkentheti\n" +
"- lenyomva tartva \u00e9s h\u00fazva gyorsabban kiv\u00e1laszthatja.";

Calendar._TT["PREV_YEAR"] = "El\u0151z\u0151 \u00e9v (tartsa nyomva a men\u00fch\u00f6z)";
Calendar._TT["PREV_MONTH"] = "El\u0151z\u0151 h\u00f3nap (tartsa nyomva a men\u00fch\u00f6z)";
Calendar._TT["GO_TODAY"] = "Mai napra ugr\u00e1s";
Calendar._TT["NEXT_MONTH"] = "K\u00f6v. h\u00f3nap (tartsa nyomva a men\u00fch\u00f6z)";
Calendar._TT["NEXT_YEAR"] = "K\u00f6v. \u00e9v (tartsa nyomva a men\u00fch\u00f6z)";
Calendar._TT["SEL_DATE"] = "V\u00e1lasszon d\u00e1tumot";
Calendar._TT["DRAG_TO_MOVE"] = "H\u00fazza a mozgat\u00e1shoz";
Calendar._TT["PART_TODAY"] = " (ma)";

// the following is to inform that "%s" is to be the first day of week
// %s will be replaced with the day name.
Calendar._TT["DAY_FIRST"] = "%s legyen a h\u00e9t els\u0151 napja";

// This may be locale-dependent.  It specifies the week-end days, as an array
// of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
Calendar._TT["WEEKEND"] = "0,6";

Calendar._TT["CLOSE"] = "Bez\u00e1r";
Calendar._TT["TODAY"] = "Ma";
Calendar._TT["TIME_PART"] = "(Shift-)Klikk vagy h\u00faz\u00e1s az \u00e9rt\u00e9k v\u00e1ltoztat\u00e1s\u00e1hoz";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d";
Calendar._TT["TT_DATE_FORMAT"] = "%b %e, %a";

Calendar._TT["WK"] = "h\u00e9t";
Calendar._TT["TIME"] = "id\u0151:";
