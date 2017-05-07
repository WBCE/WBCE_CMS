//Starting Javascript

document.modify.header.value = '<!-- Header ov_standard -->\n<h2>[SECTION_TITLE]</h2>\n';
document.modify.topics_loop.value = '<div class="[CLASSES]">\n{THUMB}\n<h3 class="mt_title">{TITLE}</h3>\n[TOPIC_SHORT]\n[READ_MORE][EDITLINK]<div style="clear:both"></div>\n</div>\n';
document.modify.footer.value = '{PREV_NEXT_PAGES}\n';

// To save as a preset, change this line with your description:
document.getElementById('presetsdescription').innerHTML = 'Check changed fields';

document.modify.header.style.backgroundColor = '#e8ff98';
document.modify.topics_loop.style.backgroundColor = '#e8ff98';
document.modify.footer.style.backgroundColor = '#e8ff98';

document.getElementById('linktabarea3').style.backgroundColor = '#e8ff98';
showtabarea(3);

alert("Done");