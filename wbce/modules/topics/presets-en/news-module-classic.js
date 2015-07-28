//Starting Javascript
document.modify.picture_dir.value = '/media/topics-pictures';
document.modify.header.value = '<table cellpadding="0" cellspacing="0" border="0" width="98%">';
document.modify.topics_loop.value = '<tr class="post_top">\n<td class="post_title">{TITLE}</td>\n<td class="post_date">[MODI_TIME], [MODI_DATE]</td>\n</tr>\n<tr>\n<td class="post_short" colspan="2">\n[TOPIC_SHORT] \n[READ_MORE]\n</td>\n</tr>';
document.modify.footer.value = '</table>\n{PREV_NEXT_PAGES}';
selectDropdownOption (document.modify.sort_topics, 0);

document.modify.topic_header.value = '<table cellpadding="0" cellspacing="0" border="0" width="98%">\n<tr>\n<td height="30"><h1>[TITLE]</h1></td>\n<td rowspan="3">{PICTURE}</td>\n</tr>\n<tr>\n<td valign="top"><b>Posted by [USER_DISPLAY_NAME] ([USER_NAME]) on [PUBL_DATE]</b></td>\n</tr>\n</table>';
document.modify.topic_footer.value = '<p>Last changed: [MODI_DATE] at [MODI_TIME]</p>\n<a href="[BACK]">Back</a>\n[EDITLINK]';
document.modify.topic_block2.value = '<!--see help how to use a second block -->';
document.modify.see_also_link_title.value = '<h4>See also:</h4>';
document.modify.next_link_title.value = '<h4>Newer topics:</h4>';
document.modify.previous_link_title.value = '<h4>Older topics:</h4>';
document.modify.pnsa_string.value = '<p>{TITLE}<span class="pnsa_desc">\n[SHORT_DESCRIPTION]</span></p>\n';
document.modify.sa_string.value = '<p>{TITLE}<span class="pnsa_desc">\n[SHORT_DESCRIPTION]</span></p>\n';
selectRadioButtons (document.modify.sort_comments, 0);
selectRadioButtons (document.modify.use_captcha, 1);
selectDropdownOption (document.modify.commenting, 2);
selectDropdownOption (document.modify.default_link, 3);
document.modify.comments_header.value = '<br /><br />\n<h2>Comments</h2>\n<table cellpadding="2" cellspacing="0" border="0" width="98%">';
document.modify.comments_loop.value = '<tr>\n<td class="comment_info">By {NAME} on [DATE]</td>\n</tr>\n<tr>\n<td colspan="2" class="comment_text">[COMMENT]</td>\n</tr>';
document.modify.comments_footer.value = '</table>';


// To save as a preset, change this line with your description:
document.getElementById('presetsdescription').innerHTML = 'This are the settings (as far as possible) of the classic News-Module<br/>Check changed fields';

alert("Done");