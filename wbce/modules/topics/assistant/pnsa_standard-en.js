//Starting Javascript
selectDropdownOption (document.modify.pnsa_max, 4);
document.modify.see_also_link_title.value = '<h4>See also:</h4>';
document.modify.next_link_title.value = '<h4>Newer Topics:</h4>';
document.modify.previous_link_title.value = '<h4>Older Topics:</h4>';
document.modify.pnsa_string.value = '<a class="pnsa_block" [HREF]>[THUMB]\n<strong>[TITLE]</strong><br />\n[SHORT_DESCRIPTION]\n<span class="pnsaclear"></span>\n</a>\n';
document.modify.sa_string.value = '<a class="pnsa_block" [HREF]>[THUMB]\n<strong>[TITLE]</strong><br />\n[SHORT_DESCRIPTION]\n<span class="pnsaclear"></span>\n</a>\n';


// To save as a preset, change this line with your description:
document.getElementById('presetsdescription').innerHTML = 'Check changed fields';

document.modify.pnsa_max.style.backgroundColor = '#e8ff98';
document.modify.see_also_link_title.style.backgroundColor = '#e8ff98';
document.modify.next_link_title.style.backgroundColor = '#e8ff98';
document.modify.previous_link_title.style.backgroundColor = '#e8ff98';
document.modify.pnsa_string.style.backgroundColor = '#e8ff98';
document.modify.sa_string.style.backgroundColor = '#e8ff98';

document.getElementById('linktabarea5').style.backgroundColor = '#e8ff98';

showtabarea(5);

alert("Done");