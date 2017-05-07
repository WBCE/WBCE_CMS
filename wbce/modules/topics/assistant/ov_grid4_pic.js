//Starting Javascript

document.modify.header.value = '<!-- Header ov_grid4_pic -->\n<style type="text/css">\n.tp-blocks-4 .mod_topic_loop {font-size: 0.9em}\n</style>\n\n<!-- Header ov_grid4_pic -->\n<h2>[SECTION_TITLE]</h2>\n<div class="tp-blocks tp-blocks-4">\n<div class="tp-blocks-inner">\n';
document.modify.topics_loop.value = '<!-- use style="height:.."  -->\n<div class="[CLASSES]">\n<a [HREF] class="loop-pic" style="background-image:url([PICTURE_DIR]/[PICTURE])"><img src="[PICTURE_DIR]/[PICTURE]" alt="" /><span class="miniclear"></span></a>\n\n<div class="textblock">\n<h3 class="commclass[COMMENTSCLASS]">{TITLE}</h3>[TOPIC_SHORT]\n</div>\n[EDITLINK]</div>\n';
document.modify.footer.value = '<br style="clear:both;">\n{PREV_NEXT_PAGES}\n<br style="clear:both;">\n</div></div>\n';



// To save as a preset, change this line with your description:
document.getElementById('presetsdescription').innerHTML = 'Check changed fields';

document.modify.header.style.backgroundColor = '#e8ff98';
document.modify.topics_loop.style.backgroundColor = '#e8ff98';
document.modify.footer.style.backgroundColor = '#e8ff98';

document.getElementById('linktabarea3').style.backgroundColor = '#e8ff98';
showtabarea(3);

alert("Done");