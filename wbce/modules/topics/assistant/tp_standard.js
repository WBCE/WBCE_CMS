//Starting Javascript
document.modify.topic_header.value = '<!-- Header tp_standard -->\n<div class="[CLASSES]">\n<h1 class="tp_headline">[TITLE]</h1>\n<p class="tp_author">[USER_DISPLAY_NAME] ([USER_NAME]) on [PUBL_DATE]</p>\n[USER_MODIFIEDINFO]\n<div class="tp_teaser hideOnDesktops">{PICTURE}[TOPIC_SHORT]</div>\n<div style="clear:both"></div>\n[ADDITIONAL_PICTURES]\n';
document.modify.topic_footer.value = '<div class="hideOnDesktops showOnTablets">\n{SEE_ALSO}{SEE_PREVNEXT}\n</div>\n<p class="topics-back"><a href="[BACK]">Back</a></p>\n[EDITLINK]\n</div>\n';
document.modify.topic_block2.value = '<!--see help how to use a second block -->\n<div class="topic_block2 desktop-teaser showOnMobiles">\n{PICTURE}\n[TOPIC_SHORT]\n{SEE_ALSO}{SEE_PREVNEXT}\n</div>\n';



// To save as a preset, change this line with your description:
document.getElementById('presetsdescription').innerHTML = 'Check changed fields';

document.modify.topic_header.style.backgroundColor = '#e8ff98';
document.modify.topic_footer.style.backgroundColor = '#e8ff98';
document.modify.topic_block2.style.backgroundColor = '#e8ff98';

document.getElementById('linktabarea4').style.backgroundColor = '#e8ff98';
showtabarea(4);


alert("Done");