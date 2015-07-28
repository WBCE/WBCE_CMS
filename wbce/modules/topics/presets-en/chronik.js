//Starting Javascript
document.modify.w_zoom.value = '0';
document.modify.h_zoom.value = '400';
document.modify.w_view.value = '200';
document.modify.h_view.value = '0';
document.modify.w_thumb.value = '100';
document.modify.h_thumb.value = '0';
document.modify.zoomclass.value = 'fbx';
selectDropdownOption (document.modify.sort_topics, 1);

document.modify.picture_dir.value = '/media/topics-pictures';
document.modify.header.value = '<!-- Chronik Menu -->\n<style type="text/css">\n.container-timeline {\n	margin: 20px auto; \n	padding-top: 25px; \n	width: 500px; \n	overflow: hidden; \n	background: url(timeline_bg.gif) repeat-y center top; \n}\n/* timeslot-settings */\n.timeslot0, .timeslot1 {\n	width: 260px;\n	margin: 0 0 30px;\n}\n.timeslot0 {\n	float: left;\n	padding: 10px 130px 0 0;\n	border-top: 3px solid #ddd;\n	position: relative;\n}\n.timeslot1{\n	float: right;\n	padding: 10px 0 0 130px;\n	border-top: 3px solid #ca0000;\n	position: relative;\n}\n/* title H3 Settings */\n.timeslot0 h3, .timeslot1 h3 {\n	position: absolute;\n	right: 0; top: -25px;\n	font-size: 1.6em;\n	font-weight: normal;\n	line-height: 1em;\n	color: #999;\n}\n.timeslot1 h3 { left: 0; color: #ca0000; }\n/* span settings */\n.timeslot0 span, .timeslot1 span {\n	position: absolute;\n	font-size: 1.6em;\n	font-weight: normal;\n	line-height: 1em;\n}\n.timeslot0 span {\n	right: 0; top: 20px;\n	color: #999;\n}\n.timeslot1 span {\n	left: 0; top: 20px;\n	color: #ca0000;\n}\n</style>\n<div class="container-timeline">';
document.modify.topics_loop.value = '<div class="timeslot[COUNTER2]">\n<h3>[TITLE]</h3><span>[EVENT_START_MONTH]/[EVENT_START_YEAR]</span><a name="jumptid[TOPIC_ID]"></a>\n<p>[SHORT_DESCRIPTION][READ_MORE]</p>\n</div>\n\n';
document.modify.footer.value = '{PREV_NEXT_PAGES}\n</div>\n';
document.modify.topic_header.value = '<div class="mod_topic_page">\n<h1 class="tp_headline">[TITLE]</h1>\n\n';
document.modify.topic_footer.value = '{SEE_PREVNEXT}\n<p class="topics-back"><a href="[BACK]">Zurück zur Übersicht</a></p>\n[EDITLINK]\n</div>\n<hr/>\n';
document.modify.topic_block2.value = '<!--see help how to use a second block -->\n<div class="topic_block2">\n{PICTURE}\n</div>\n';
document.modify.see_also_link_title.value = '<h4>Siehe auch:</h4>';
document.modify.next_link_title.value = '<h4>N&auml;chster Eintrag:</h4>';
document.modify.previous_link_title.value = '<h4>Vorheriger Eintrag:</h4>';
document.modify.pnsa_string.value = '<p><a href="[LINK]">[TITLE]</a><span class="pnsa_link">\n[SHORT_DESCRIPTION]</span></p>';
document.modify.sa_string.value = '<p><a href="[LINK]">[TITLE]</a><span class="pnsa_link">\n[SHORT_DESCRIPTION]</span></p>';
selectRadioButtons (document.modify.sort_comments, 0);
selectRadioButtons (document.modify.use_captcha, 1);
selectDropdownOption (document.modify.commenting, -1);
selectDropdownOption (document.modify.default_link, 3);
document.modify.comments_header.value = '<h2>Kommentare</h2>\n';
document.modify.comments_loop.value = '<blockquote class="mod_topic_commentbox">\n<p class="comment_date">[DATE]</p>\n<p class="comment_name">{NAME}</p>\n<p class="comment_text">[COMMENT]</p>\n</blockquote>\n';
document.modify.comments_footer.value = '';


// To save as a preset, change this line with your description:
document.getElementById('presetsdescription').innerHTML = 'Check changed fields';

alert("Done");