//Starting Javascript

document.modify.header.value = '<!-- Header ov_grid3_thumb -->\n<h2>[SECTION_TITLE]</h2>\n<div class="tp-blocks tp-blocks-3 tp_thumbsblock">\n<div class="tp-blocks-inner">\n';
document.modify.topics_loop.value = '<div class="[CLASSES]">\n{THUMB}\n<div class="textblock_thumb">\n<h3 class="commclass[COMMENTSCLASS]">{TITLE}</h3>[TOPIC_SHORT]\n[READ_MORE]\n</div>\n[EDITLINK]</div>\n';
document.modify.footer.value = '<br style="clear:both;">\n{PREV_NEXT_PAGES}\n<br style="clear:both;">\n</div></div>\n';



// To save as a preset, change this line with your description:
document.getElementById('presetsdescription').innerHTML = 'Check changed fields';

document.modify.header.style.backgroundColor = '#e8ff98';
document.modify.topics_loop.style.backgroundColor = '#e8ff98';
document.modify.footer.style.backgroundColor = '#e8ff98';

document.getElementById('linktabarea3').style.backgroundColor = '#e8ff98';
showtabarea(3);


alert("Done");