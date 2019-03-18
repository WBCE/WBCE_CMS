<?php

/*
plugin_install.php

Copyright (C) 2019 Martin Hecht (mrbaseman)

This file is part of opf http to https, a plugin-filter for OutputFilter Dashboard.

opf automatic links is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

opf automatic links is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with opf automatic links. If not, see <http://www.gnu.org/licenses/>.

*/

if(!defined('WB_PATH')) die(header('Location: ../../index.php'));

/*
Header: Version-History

1.0.1 %(Martin Hecht (mrbaseman); Mar 18, 2019)%
        - disable by default to avoid self-made problems by just installing it

1.0.0 %(Martin Hecht (mrbaseman); Mar 18, 2019)%
	- initial version

*/

$plugin_directory   = 'http_to_https';
$plugin_name        = 'http to https';
$plugin_version     = '1.0.1';
$plugin_status      = 'stable';
$plugin_platform    = 'any';
$plugin_author      = 'Martin Hecht (mrbaseman)';
$plugin_license     = 'GNU General Public License, Version 3 or later';
$plugin_description = 'converts occurrences of http to https';
