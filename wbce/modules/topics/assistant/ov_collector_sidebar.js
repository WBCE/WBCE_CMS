//Starting Javascript

document.modify.header.value = '<!-- Header ov_collector_sidebar -->\n<h3>Latest Topics</h3>\n';
document.modify.topics_loop.value = '<a class="pnsa_block" [HREF]>[THUMB]\n<strong>[TITLE]</strong><br />\n[SHORT_DESCRIPTION]\n<span class="pnsaclear"></span>\n</a>';
document.modify.footer.value = '';

// To save as a preset, change this line with your description:
document.getElementById('presetsdescription').innerHTML = 'Check changed fields';

document.modify.header.style.backgroundColor = '#e8ff98';
document.modify.topics_loop.style.backgroundColor = '#e8ff98';
document.modify.footer.style.backgroundColor = '#e8ff98';

document.getElementById('linktabarea3').style.backgroundColor = '#e8ff98';
showtabarea(3);

alert("Done");