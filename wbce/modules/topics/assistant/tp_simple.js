//Starting Javascript
document.modify.topic_header.value = '<!-- Header tp_simple -->\n<div class="[CLASSES]">\n<h1 class="tp_headline">[TITLE]</h1>\n<div class="tp_teaser tp_teaser_right" style="font-weight:bold;">{PICTURE}[TOPIC_SHORT]</div>\n';
document.modify.topic_footer.value = '<div class="tp_footerpnsa">\n{SEE_ALSO}\n{SEE_PREVNEXT}\n</div>\n<p class="topics-back"><a href="[BACK]">Back</a></p>\n[EDITLINK]\n</div>\n';
document.modify.topic_block2.value = '';

// To save as a preset, change this line with your description:
document.getElementById('presetsdescription').innerHTML = 'Check changed fields';

document.modify.topic_header.style.backgroundColor = '#e8ff98';
document.modify.topic_footer.style.backgroundColor = '#e8ff98';
document.modify.topic_block2.style.backgroundColor = '#e8ff98';

document.getElementById('linktabarea4').style.backgroundColor = '#e8ff98';
showtabarea(4);


alert("Done");