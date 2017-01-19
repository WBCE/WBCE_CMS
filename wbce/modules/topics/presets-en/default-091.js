//Starting Javascript
selectDropdownOption (document.modify.use_timebased_publishing, 1);
selectDropdownOption (document.modify.sort_topics, 1);
document.modify.topics_per_page.value = '0';
document.modify.picture_dir.value = '/media/topics0911_dev-pictures';
document.modify.w_zoom.value = '0';
document.modify.h_zoom.value = '1200';
document.modify.w_view.value = '440';
document.modify.h_view.value = '280';
document.modify.w_thumb.value = '100';
document.modify.h_thumb.value = '100';
document.modify.zoomclass.value = '';
document.modify.zoomrel.value = '';
document.modify.w_zoom2.value = '0';
document.modify.h_zoom2.value = '1200';
document.modify.w_view2.value = '440';
document.modify.h_view2.value = '0';
document.modify.w_thumb2.value = '0';
document.modify.h_thumb2.value = '120';
document.modify.zoomclass2.value = 'colorbox';
document.modify.zoomrel2.value = 'fancybox';
document.modify.header.value = '<!-- Header -->\n<h2>[SECTION_TITLE]</h2>\n';
document.modify.topics_loop.value = '<div class="[CLASSES]">\n{THUMB}\n<h3 class="mt_title">{TITLE}</h3>\n[TOPIC_SHORT]\n[READ_MORE][EDITLINK]<div style="clear:both"></div>\n</div>\n';
document.modify.footer.value = '{PREV_NEXT_PAGES}\n';
selectDropdownOption (document.modify.pnsa_max, 4);
document.modify.topic_header.value = '<div class="[CLASSES]">\n<h1 class="tp_headline">[TITLE]</h1>\n<p class="tp_author">[USER_DISPLAY_NAME] ([USER_NAME]) on [PUBL_DATE]</p>\n[USER_MODIFIEDINFO]\n<div class="tp_teaser hideOnDesktops">{PICTURE}[TOPIC_SHORT]</div>\n<div style="clear:both"></div>\n[ADDITIONAL_PICTURES]\n';
document.modify.topic_footer.value = '<div class="hideOnDesktops showOnTablets">\n{SEE_ALSO}{SEE_PREVNEXT}\n</div>\n<p class="topics-back"><a href="[BACK]">Back</a></p>\n[EDITLINK]\n</div>\n';
document.modify.topic_block2.value = '<!--see help how to use a second block -->\n<div class="sidebar topic_block2 desktop-teaser showOnMobiles">\n{PICTURE}\n[TOPIC_SHORT]\n{SEE_ALSO}{SEE_PREVNEXT}\n</div>\n';
document.modify.see_also_link_title.value = '<h4>Siehe auch:</h4>';
document.modify.next_link_title.value = '<h4>Neuere Themen:</h4>';
document.modify.previous_link_title.value = '<h4>&Auml;ltere Themen:</h4>';
document.modify.pnsa_string.value = '<a class="pnsa_block" href="[LINK]">[THUMB]\n<strong>[TITLE]</strong><br />\n[SHORT_DESCRIPTION]\n<span class="pnsaclear"></span>\n</a>\n';
document.modify.sa_string.value = '<a class="pnsa_block" href="[LINK]">[THUMB]\n<strong>[TITLE]</strong><br />\n[SHORT_DESCRIPTION]\n<span class="pnsaclear"></span>\n</a>\n';
selectDropdownOption (document.modify.commenting, 2);
selectDropdownOption (document.modify.default_link, 3);
selectDropdownOption (document.modify.emailsettings, 2);
selectRadioButtons (document.modify.sort_comments, 0);
selectRadioButtons (document.modify.use_captcha, 1);
document.modify.maxcommentsperpage.value = '0';
selectDropdownOption (document.modify.commentstyle, 1);
document.modify.comments_header.value = '<h2>Comments</h2>\n';
document.modify.comments_loop.value = '<blockquote class="mod_topic_commentbox">\n<p class="comment_date">[DATE]</p>\n<p class="comment_name">{NAME}</p>\n<p class="comment_text">[COMMENT]</p>\n</blockquote>\n';
document.modify.comments_footer.value = '';
document.modify.short_textareaheight.value = '150';
document.modify.long_textareaheight.value = '450';
document.modify.extra_textareaheight.value = '0';


// To save as a preset, change this line with your description:
document.getElementById('presetsdescription').innerHTML = 'Check changed fields';

alert("Done");