//Starting Javascript
document.modify.w_zoom.value = '0';
document.modify.h_zoom.value = '0';
document.modify.w_view.value = '300';
document.modify.h_view.value = '0';
document.modify.w_thumb.value = '70';
document.modify.h_thumb.value = '70';
document.modify.zoomclass.value = 'colorbox';
selectDropdownOption (document.modify.sort_topics, 1);
document.modify.topics_per_page.value = '0';
document.modify.picture_dir.value = '/media/topics-pictures';
document.modify.header.value = '<!-- Header -->\n';
document.modify.topics_loop.value = '<div class="mod_topic_loop mod_topic_active[ACTIVE] mod_topic_comments[COMMENTSCLASS]">\n{THUMB}\n<h3 class="mt_title">{TITLE}</h3>\n[TOPIC_SHORT] \n[READ_MORE][EDITLINK]<div style="clear:both"></div></div>\n';
document.modify.footer.value = '{PREV_NEXT_PAGES}\n';
document.modify.topic_header.value = '<div class="mod_topic_page">\n<h1 class="tp_headline">[TITLE]</h1>\n<p class="tp_author">[USER_DISPLAY_NAME] on [PUBL_DATE]</p>\n[USER_MODIFIEDINFO]\n<div class="tp_picture">{PICTURE}</div><div class="tp_topicshort">[TOPIC_SHORT]</div> \n<div style="clear:both"></div>\n';
document.modify.topic_footer.value = '{SEE_ALSO}\n{SEE_PREVNEXT}\n<p class="topics-back"><a href="[BACK]">Zurück</a></p>\n[EDITLINK]\n</div>\n<hr/>\n';
document.modify.topic_block2.value = '<!--see help how to use a second block -->\n<div class="topic_block2">\n{PICTURE}\n[TOPIC_SHORT]\n{SEE_ALSO}\n{SEE_PREVNEXT}\n</div>\n';
document.modify.see_also_link_title.value = '<h4>Siehe auch:</h4>';
document.modify.next_link_title.value = '<h4>Neuere Themen:</h4>';
document.modify.previous_link_title.value = '<h4>&Auml;ltere Themen:</h4>';
document.modify.pnsa_string.value = '<p style="margin-top:5px; clear:left;">\n<a href="[LINK]"><img src="[PICTURE_DIR]/thumbs/[PICTURE]" height="30" width="30" border="0" alt="[TITLE]" style="float:left; margin:0 6px 10px 0;" /></a>\n<a href="[LINK]"><strong>[TITLE]</strong></a><br/>\n<span class="pnsa_link">[SHORT_DESCRIPTION]</span></p>';
document.modify.sa_string.value = '<p style="margin-top:5px; clear:left;">\n<a href="[LINK]"><img src="[PICTURE_DIR]/thumbs/[PICTURE]" height="30" width="30" border="0" alt="[TITLE]" style="float:left; margin:0 6px 10px 0;" /></a>\n<a href="[LINK]"><strong>[TITLE]</strong></a><br/>\n<span class="pnsa_link">[SHORT_DESCRIPTION]</span></p>';
selectRadioButtons (document.modify.sort_comments, 0);
selectRadioButtons (document.modify.use_captcha, 1);
selectDropdownOption (document.modify.commenting, 2);
selectDropdownOption (document.modify.default_link, 3);
document.modify.comments_header.value = '<h2>Kommentare</h2>\n';
document.modify.comments_loop.value = '<blockquote class="mod_topic_commentbox">\n<p class="comment_date">[DATE]</p>\n<p class="comment_name">{NAME}</p>\n<p class="comment_text">[COMMENT]</p>\n</blockquote>\n';
document.modify.comments_footer.value = '';


// To save as a preset, change this line with your description:
document.getElementById('presetsdescription').innerHTML = 'Check changed fields';

alert("Done");