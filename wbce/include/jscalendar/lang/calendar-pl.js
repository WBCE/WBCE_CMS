// ** I18N

// Calendar PL language
// Author: Dariusz Pietrzak, <eyck@ghost.anime.pl>
// Author: Janusz Piwowarski, <jpiw@go2.pl>
// Encoding: utf-8
// Distributed under the same terms as the calendar itself.

Calendar._DN = new Array
("Niedziela",
 "Poniedzia\u0142ek",
 "Wtorek",
 "\u015aroda",
 "Czwartek",
 "Pi\u0105tek",
 "Sobota",
 "Niedziela");
Calendar._SDN = new Array
("Nie",
 "Pn",
 "Wt",
 "\u015ar",
 "Cz",
 "Pt",
 "So",
 "Nie");
Calendar._MN = new Array
("Stycze\u0144",
 "Luty",
 "Marzec",
 "Kwiecie\u0144",
 "Maj",
 "Czerwiec",
 "Lipiec",
 "Sierpie\u0144",
 "Wrzesie\u0144",
 "Pa\u017adziernik",
 "Listopad",
 "Grudzie\u0144");
Calendar._SMN = new Array
("Sty",
 "Lut",
 "Mar",
 "Kwi",
 "Maj",
 "Cze",
 "Lip",
 "Sie",
 "Wrz",
 "Pa\u017a",
 "Lis",
 "Gru");

// tooltips
Calendar._TT = {};
Calendar._TT["INFO"] = "O kalendarzu";

Calendar._TT["ABOUT"] =
"DHTML Date/Time Selector\n" +
"(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" + // don't translate this this ;-)
"Aby pobra\u0107 najnowsz\u0105 wersj\u0119, odwied\u017a: http://www.dynarch.com/projects/calendar/\n" +
"Dost\u0119pny na licencji GNU LGPL. Zobacz szczeg\u00f3\u0142y na http://gnu.org/licenses/lgpl.html." +
"\n\n" +
"Wyb\u00f3r daty:\n" +
"- U\u017cyj przycisk\u00f3w \xab, \xbb by wybra\u0107 rok\n" +
"- U\u017cyj przycisk\u00f3w " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " by wybra\u0107 miesi\u0105c\n" +
"- Przytrzymaj klawisz myszy nad jednym z powy\u017cszych przycisk\u00f3w dla szybszego wyboru.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Wyb\u00f3r czasu:\n" +
"- Kliknij na jednym z p\u00f3l czasu by zwi\u0119kszy\u0107 jego warto\u015b\u0107\n" +
"- lub kliknij trzymaj\u0105c Shift by zmiejszy\u0107 jego warto\u015b\u0107\n" +
"- lub kliknij i przeci\u0105gnij dla szybszego wyboru.";

//Calendar._TT["TOGGLE"] = "Zmie\u0144 pierwszy dzie\u0144 tygodnia";
Calendar._TT["PREV_YEAR"] = "Poprzedni rok (przytrzymaj dla menu)";
Calendar._TT["PREV_MONTH"] = "Poprzedni miesi\u0105c (przytrzymaj dla menu)";
Calendar._TT["GO_TODAY"] = "Id\u017a do dzisiaj";
Calendar._TT["NEXT_MONTH"] = "Nast\u0119pny miesi\u0105c (przytrzymaj dla menu)";
Calendar._TT["NEXT_YEAR"] = "Nast\u0119pny rok (przytrzymaj dla menu)";
Calendar._TT["SEL_DATE"] = "Wybierz dat\u0119";
Calendar._TT["DRAG_TO_MOVE"] = "Przeci\u0105gnij by przesun\u0105\u0107";
Calendar._TT["PART_TODAY"] = " (dzisiaj)";
Calendar._TT["MON_FIRST"] = "Wy\u015bwietl poniedzia\u0142ek jako pierwszy";
Calendar._TT["SUN_FIRST"] = "Wy\u015bwietl niedziel\u0119 jako pierwsz\u0105";
Calendar._TT["CLOSE"] = "Zamknij";
Calendar._TT["TODAY"] = "Dzisiaj";
Calendar._TT["TIME_PART"] = "(Shift-)Kliknij lub przeci\u0105gnij by zmieni\u0107 warto\u015b\u0107";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d";
Calendar._TT["TT_DATE_FORMAT"] = "%e %B, %A";

Calendar._TT["WK"] = "ty";

Calendar._TT["DAY_FIRST"] = "Display %s first";
Calendar._TT["WEEKEND"] = "0,6";
Calendar._TT["TIME"] = "Time:";

