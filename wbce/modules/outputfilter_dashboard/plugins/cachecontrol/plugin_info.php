<?php

/*
info.php

Copyright (C) 2010 Thomas "thorn" Hornik <thorn@nettest.thekk.de>, http://nettest.thekk.de

This file is part of opf cache control, a plugin-filter for OutputFilter Dashboard.

opf cache control is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

opf cache control is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.        See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with opf cache control. If not, see <http://www.gnu.org/licenses/>.

*/

if(!defined('WB_PATH')) die(header('Location: ../../index.php'));

/*
Header: Version-History

1.0.4 %(mrbaseman; 21 Feb, 2016)%
        - change filter type to page (last)

1.0.3 %(mrbaseman; 21 Feb, 2016)%
        - use sysvar-place holders

1.0.2 %(mrbaseman; 11 Apr, 2015)%
        - made it compatible with RelURL

1.0.1 %(mrbaseman; 11 Apr, 2015)%
        - chaged to page mode

1.0.0 %(thorn; 17 Jan, 2010)%
        - initial version
*/

$plugin_directory   = 'cachecontrol';
$plugin_name        = 'Cache Control';
$plugin_version     = '1.0.4';
$plugin_status      = 'beta';
$plugin_platform    = '2.8';
$plugin_author      = 'thorn, mrbaseman';
$plugin_license     = 'GNU General Public License, Version 3 or later';
$plugin_description = 'Filter to automatically prevent browsers from delivering outdated files (css,js) from cache';

