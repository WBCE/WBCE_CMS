//Starting Javascript
document.modify.picture_dir.value = '/media/topics-pictures';
document.modify.header.value = '<!-- Header -->\n';
document.modify.topics_loop.value = '<div class="mod_topic_loop">\n<h3 class="mt_title">{TITLE}</h3>\n[TOPIC_SHORT] \n[READ_MORE]</div>\n';
document.modify.footer.value = '{PREV_NEXT_PAGES}\n';
selectDropdownOption (document.modify.sort_topics, 0);

document.modify.topic_header.value = '<div class="mod_topic_page">\n<h1 class="tp_headline">[TITLE]</h1>\n<p class="tp_author">von [USER_DISPLAY_NAME] ([USER_NAME]) am [PUBL_DATE]</p>\n';
document.modify.topic_footer.value = '{SEE_ALSO}\n{SEE_PREVNEXT}\n<p class="topics-back"><a href="[BACK]">Back</a></p>\n[EDITLINK]\n</div>\n<hr/>\n';
document.modify.topic_block2.value = '<!--see help how to use a second block -->\n<div class="topic_block2">\n{PICTURE}\n[TOPIC_SHORT]\n</div>\n';
document.modify.see_also_link_title.value = '<h4>See also:</h4>';
document.modify.next_link_title.value = '<h4>Newer topics:</h4>';
document.modify.previous_link_title.value = '<h4>Older topics:</h4>';
document.modify.pnsa_string.value = '<p>{TITLE}<span class="pnsa_desc">\n[SHORT_DESCRIPTION]</span></p>\n';
document.modify.sa_string.value = '<p>{TITLE}<span class="pnsa_desc">\n[SHORT_DESCRIPTION]</span></p>\n';
selectRadioButtons (document.modify.sort_comments, 0);
selectRadioButtons (document.modify.use_captcha, 1);
selectDropdownOption (document.modify.commenting, 2);
selectDropdownOption (document.modify.default_link, 3);
document.modify.comments_header.value = '<h2>Kommentare</h2>\n';
document.modify.comments_loop.value = '<blockquote class="mod_topic_commentbox">\n<p class="comment_date">[DATE]</p>\n<p class="comment_name">{NAME}</p>\n<p class="comment_text">[COMMENT]</p>\n</blockquote>\n';
document.modify.comments_footer.value = '';


// To save as a preset, change this line with your description:
document.getElementById('presetsdescription').innerHTML = 'The default presets file from Topics 0.3';

alert("YO!");