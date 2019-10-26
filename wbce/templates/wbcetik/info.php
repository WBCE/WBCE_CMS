<?php

$template_directory = 'wbcetik';
$template_name = 'WBCETik';
$template_version = '1.1';
$template_platform = '1.4';
$template_author = 'Florian Meerwinck';
$template_license = 'MIT License';
$template_description = 'Klassisch aufgebautes Template mit vertikaler Navigation, Content- und optionaler Seitenspalte, optionalen breiten Blöcken darüber und darunter. ';
$template_license = 'Responsee is licenced under MIT, MFG Labs icon set under CC-BY 3.0 and SIL Open Font License, Open Sans is licensed under Apache license. Images from pixabay.com, Pixabay License. ';


$block[1] = 'Inhalt';
$block[2] = 'Seitenspalte';
$block[3] = 'Breiter Inhalt oben';
$block[4] = 'Breiter Inhalt unten';
$block[5] = 'Linke Spalte';
$block[99] = 'nicht zeigen';

$menu[1] = 'Navigation';
$menu[99] = 'nicht in Nav.';

if (LANGUAGE!="DE") {
	$block[1] = 'Content';
	$block[2] = 'Aside';
	$block[3] = 'Big Content Top';
	$block[4] = 'Big Content Bottom';
	$block[5] = 'Left Column';
	$block[99] = 'No Output';	
	$menu[99] = 'Not in Nav.';
}