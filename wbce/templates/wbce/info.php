<?php

$template_directory = 'wbce';
$template_name = 'WBCE';
$template_version = '0.1';
$template_platform = '2.8.3';
$template_author = 'Florian Meerwinck';
$template_license = 'Creative Commons Attribution Licence 3.0 / http://creativecommons.org/licenses/by/3.0/deed.de';
$template_description = 'Initial release of the WBCE multipupose theme. There are 6 possible Content blocks. You can combine Main with Left and/or Right add a block running over the full width above and/or beyond the content block(s) and display a hero image. <br /> The hero image area shows only up if it has some content in it. <br /> By default, the hero image is underlayed with a photo of a pretzel in the oven. To use another image, simply create a folder "heroimages" in the media directory and place there the pictures which should be used. They have to get a special file name: it has to start with "hero_" and then followed by the PAGE_ID where the hero image should occur. It is inherited to the childpages, if they have a hero image content block and no own hero image.';

$block[1] = 'Main';
$block[2] = 'Full top';
$block[3] = 'Left';
$block[4] = 'Right';
$block[5] = 'Full bottom';
$block[6] = 'Hero area';

$menu[1] = 'Mainmenu';
$menu[99] = 'none';
