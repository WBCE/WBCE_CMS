//Starting Javascript
selectDropdownOption (document.modify.sort_topics, 1);

document.modify.picture_dir.value = '/media/topics-pictures';
document.modify.header.value = '[SECTION_DESCRIPTION]';
document.modify.topics_loop.value = '<div class="mod_topic_loop commclass[COMMENTSCLASS]">\n<h3 class="mt_title">{TITLE}</h3>\n{THUMB}\n<div>[TOPIC_SHORT] \n[READ_MORE] </div></div>\n\n';
document.modify.footer.value = '<div style="clear:left"></div>';
document.modify.topic_header.value = '<h1>[TITLE]</h1>';
document.modify.topic_footer.value = '{SEE_ALSO}\n{SEE_PREVNEXT}\n<p class="topics-back"><a href="[BACK]">Back</a></p>\n[EDITLINK]\n<hr/>';
document.modify.topic_block2.value = '<!--see help how to use a second block -->\n{PICTURE}<strong>[TOPIC_SHORT]</strong>';
document.modify.see_also_link_title.value = '<h4>See also:</h4>';
document.modify.next_link_title.value = '<h4>Recent topics:</h4>';
document.modify.previous_link_title.value = '<h4>Older Topics:</h4>';
document.modify.pnsa_string.value = '<p><a href="[LINK]">[TITLE]</a><span class="pnsa_link">\n[SHORT_DESCRIPTION]</span></p>';
document.modify.sa_string.value = '<p><a href="[LINK]">[TITLE]</a><span class="pnsa_link">\n[SHORT_DESCRIPTION]</span></p>';
selectRadioButtons (document.modify.sort_comments, 0);
selectRadioButtons (document.modify.use_captcha, 1);
selectDropdownOption (document.modify.commenting, 2);
selectDropdownOption (document.modify.default_link, 3);
document.modify.comments_header.value = '<h2>Comments</h2>';
document.modify.comments_loop.value = '<blockquote class="topics-commentbox">\n<p class="comment_date">[DATE]</p>\n<p class="comment_name">{NAME}</p>\n<p class="comment_text">[COMMENT]</p>\n</blockquote>';
document.modify.comments_footer.value = '';
 
 
// To save as a preset, change this line with your description:
document.getElementById('presetsdescription').innerHTML = 'Check changed fields';
 
alert("Done");