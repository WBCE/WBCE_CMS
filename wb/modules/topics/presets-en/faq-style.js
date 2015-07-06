//Starting Javascript
selectDropdownOption (document.modify.sort_topics, 0);

document.modify.picture_dir.value = '/media/topics-pictures';
document.modify.header.value = '<!-- Header -->\n<h2>Die Welt fragt, Chio antwortet</h2>\n{JUMP_LINKS_LIST_PLUS}';
document.modify.topics_loop.value = '<a name="jumptid[TOPIC_ID]"></a>\n<a href="#" style="float:right; padding-right:20px;">up</a>\n<h3 class="mt_title">{TITLE}</h3>\n<table><tr>\n<td style="width:160px; padding-right:10px">[TOPIC_SHORT] [EDITLINK]</td>\n<td>[CONTENT_LONG]</td>\n</tr></table><hr/>\n';
document.modify.footer.value = '<div style="clear:left">{PREV_NEXT_PAGES}</div>';
document.modify.topic_header.value = '';
document.modify.topic_footer.value = '';
document.modify.topic_block2.value = '';
document.modify.see_also_link_title.value = '';
document.modify.next_link_title.value = '';
document.modify.previous_link_title.value = '';
document.modify.pnsa_string.value = '';
document.modify.sa_string.value = '';
selectRadioButtons (document.modify.sort_comments, 0);
selectRadioButtons (document.modify.use_captcha, 0);
selectDropdownOption (document.modify.commenting, 0);
selectDropdownOption (document.modify.default_link, 0);
document.modify.comments_header.value = '';
document.modify.comments_loop.value = '';
document.modify.comments_footer.value = '';


// To save as a preset, change this line with your description:
document.getElementById('presetsdescription').innerHTML = 'Check changed fields';

alert("Done");