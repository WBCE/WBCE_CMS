<?php
$template_directory = 'wbce_vertal';
$template_name = 'Vertal (WBCE)';
$template_version = '0.8';
$template_platform = '1.x';
$template_author = 'Chio Maisriml, beesign.com';
$template_license = 'GNU General Public License. Photos: pexels.com';
$template_description = 'Responsive template with vertical fly-out menu';

$block[1] = 'Main Content';
$block[2] = 'Sidebar';
$block[3] = 'Wide Top';
$block[4] = 'Wide Bottom';

$block[10] = 'Alt. Header';
$block[99] = 'None';

$menu[1] = 'Main';
$menu[2] = 'None';

if (LANGUAGE == 'DE') {
	$block[1] ='Hauptinhalt';
	$block[2] ='Linke Seite';
	$block[3] ='Grosser Inhalt Oben';
	$block[4] ='Grosser Inhalt Unten';

	$block[10] ='alternativer Kopfbereich';
	$block[99] ='keiner';

	$menu[1] ='Normal';
	$menu[2] ='Unsichtbar';
}
