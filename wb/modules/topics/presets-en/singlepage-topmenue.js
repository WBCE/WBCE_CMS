//Starting Javascript
document.modify.header.value = '<!-- Header -->\n';
document.modify.topics_loop.value = '';
document.modify.footer.value = '';
selectDropdownOption (document.modify.sort_topics, 0);
document.modify.topics_per_page.value = '1';
document.modify.topic_header.value = '<h3>Alle Artikel in "Einzelansicht":</h3>{FULL_TOPICS_LIST} <hr style="clear:both" />\n<h1>[TITLE]</h1>\n{PICTURE}<div style="font-weight:bold">[TOPIC_SHORT]</div>\n';
document.modify.topic_footer.value = '{SEE_ALSO}\n<h4>Vorige/n&auml;chste Themen:</h4>\n{SEE_PREVNEXT}\n<hr/>';
document.modify.topic_block2.value = '<!--see help how to use a second block -->';
document.modify.see_also_link_title.value = '<h4>Siehe auch:</h4>';
document.modify.next_link_title.value = '';
document.modify.previous_link_title.value = '';
document.modify.pnsa_string.value = '<p style="width:40%;float:left;"><a href="[LINK]">[TITLE]</a><span class="pnsa_link" style="width:100%;">\n[SHORT_DESCRIPTION]</span></p>';
document.modify.sa_string.value = '<p style="width:49%;"><a href="[LINK]">[TITLE]</a><span class="pnsa_link" style="width:100%;">\n[SHORT_DESCRIPTION]</span></p>';
selectRadioButtons (document.modify.sort_comments, 1);
selectRadioButtons (document.modify.use_captcha, 1);
selectDropdownOption (document.modify.commenting, 2);
selectDropdownOption (document.modify.default_link, 3);
document.modify.comments_header.value = '<h2>Kommentare</h2>';
document.modify.comments_loop.value = '<blockquote class="topics-commentbox">\n<p class="comment_date">[DATE]</p>\n<p class="comment_name">{NAME}</p>\n<p class="comment_text">[COMMENT]</p>\n</blockquote>';
document.modify.comments_footer.value = '';


// To save as a preset, change this line with your description:
document.getElementById('presetsdescription').innerHTML = 'Check changed fields';

alert("Done");