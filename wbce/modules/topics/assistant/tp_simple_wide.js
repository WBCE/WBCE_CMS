//Starting Javascript
document.modify.topic_header.value = '<!-- Header tp_simple_wide -->\n<div class="[CLASSES] tp_content_wide">\n<h1 class="tp_headline">[TITLE]</h1>\n<div class="tp_teaser" style="font-weight:bold;">{THUMB}[TOPIC_SHORT]</div>\n<div style="clear:both"></div>';
document.modify.topic_footer.value = '[ADDITIONAL_PICTURES]\n<div class="tp_footerpnsa tp-blocks-3">\n{SEE_ALSO}\n{SEE_PREVNEXT}\n</div>\n<p class="topics-back"><a href="[BACK]">Back</a></p>\n[EDITLINK]\n</div>\n';
document.modify.topic_block2.value = '';

// To save as a preset, change this line with your description:
document.getElementById('presetsdescription').innerHTML = 'Check changed fields';

document.modify.topic_header.style.backgroundColor = '#e8ff98';
document.modify.topic_footer.style.backgroundColor = '#e8ff98';
document.modify.topic_block2.style.backgroundColor = '#e8ff98';

document.getElementById('linktabarea4').style.backgroundColor = '#e8ff98';
showtabarea(4);


alert("Done");