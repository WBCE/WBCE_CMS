//Starting Javascript

document.modify.header.value = '<!-- Header -->\n<h2>[SECTION_TITLE]</h2>\n<div class="tp-blocks-3"><!-- 3 or 4  -->\n<div class="tp-blocks-inner">\n';
document.modify.topics_loop.value = '<!-- use style="height:.."  -->\n<div class="[CLASSES]" style="height:350px;">\n<strong class="short_description">[SHORT_DESCRIPTION]  </strong>\n<a href="[LINK]" class="loop-pic" style="background-image:url([PICTURE_DIR]/[PICTURE])"><img src="[PICTURE_DIR]/[PICTURE]" alt="" /><span class="miniclear"></span></a>\n\n<div class="textblock" style="height:130px;" >\n<h3 class="commclass[COMMENTSCLASS]">{TITLE}</h3>[TOPIC_SHORT]\n</div>\n[READ_MORE][EDITLINK]</div>\n';
document.modify.footer.value = '<br style="clear:both;">\n{PREV_NEXT_PAGES}\n<br style="clear:both;">\n</div></div>\n';

// To save as a preset, change this line with your description:
document.getElementById('presetsdescription').innerHTML = 'Check loop fields';
showtabarea(3);

document.modify.header.style.backgroundColor = '#e8ff98';
document.modify.topics_loop.style.backgroundColor = '#e8ff98';
document.modify.footer.style.backgroundColor = '#e8ff98';

alert("Done");