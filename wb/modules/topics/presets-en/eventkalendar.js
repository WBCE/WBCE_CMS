//Starting Javascript
selectDropdownOption (document.modify.sort_topics, 3);
document.modify.topics_per_page.value = '5';
document.modify.picture_dir.value = '/media/topics-pictures';
document.modify.header.value = '<!-- Header -->\n<h1>[SECTION_TITLE]</h1>\n<table style="width:99%;"  >';
document.modify.topics_loop.value = '<tr class="mod_topic_loop"><td style="width:70px;" class="topics_calendar_day">\n<div style="font-size:10px;text-align:center;">[EVENT_START_DAYNAME]</div>\n<div style="font-size:24px;text-align:center;">[EVENT_START_DAY]</div>\n<div style="font-size:10px;text-align:center;">[EVENT_START_MONTHNAME]<br/>[EVENT_START_YEAR]</div>\n</td><td>\n<h3 class="mt_title">{TITLE}</h3>\n[TOPIC_SHORT] \n<p>Live dabei: [EVENT_START_TIME] - [EVENT_STOP_TIME]</p>\n[CONTENT_LONG_FIRST]\n<p>[READ_MORE] </p>\n</td></tr>';
document.modify.footer.value = '</table>\n{PREV_NEXT_PAGES}\n\n';
document.modify.topic_header.value = '<h1>[TITLE]</h1>\n<div style="width:80px; float:left;" class="topics_calendar_day">\n<div style="font-size:10px;text-align:center;">[EVENT_START_DAYNAME]</div>\n<div style="font-size:24px;text-align:center;">[EVENT_START_DAY]</div>\n<div style="font-size:10px;text-align:center;">[EVENT_START_MONTHNAME]<br/>[EVENT_START_YEAR]</div>\n</div><strong>[TOPIC_SHORT] </strong>\n';
document.modify.topic_footer.value = '<div style="clear:left;height:1px"></div>\n<p>See live at: [EVENT_START_TIME] - [EVENT_STOP_TIME] </p>\n{SEE_ALSO}\n{SEE_PREVNEXT}\n<p class="topics-back"><a href="[BACK]">Back</a></p>\n[EDITLINK]\n<hr/>';
document.modify.topic_block2.value = '<!--see help how to use a second block -->';
document.modify.see_also_link_title.value = '<h4>See also:</h4>';
document.modify.next_link_title.value = '<h4>Later:</h4>';
document.modify.previous_link_title.value = '<h4>Earlier:</h4>';
document.modify.pnsa_string.value = '<p><a href="[LINK]">[TITLE]</a><span class="pnsa_link">\n[SHORT_DESCRIPTION]</span></p>';
document.modify.sa_string.value = '<p><a href="[LINK]">[TITLE]</a><span class="pnsa_link">\n[SHORT_DESCRIPTION]</span></p>';
selectRadioButtons (document.modify.sort_comments, 1);
selectRadioButtons (document.modify.use_captcha, 1);
selectDropdownOption (document.modify.commenting, 2);
selectDropdownOption (document.modify.default_link, 3);
document.modify.comments_header.value = '<h2>Comments</h2>';
document.modify.comments_loop.value = '<blockquote class="topics-commentbox">\n<p class="comment_date">[DATE]</p>\n<p class="comment_name">{NAME}</p>\n<p class="comment_text">[COMMENT]</p>\n</blockquote>';
document.modify.comments_footer.value = '';


// To save as a preset, change this line with your description:
document.getElementById('presetsdescription').innerHTML = 'Check changed fields';

alert("Done");