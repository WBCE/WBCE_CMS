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

if(!defined('WB_PATH')) die(header('Location: index.php'));

opf_register_filter(array (
    "active" => '0',
    "allowedit" => '0',
    "allowedittarget" => '1',
    "name" => 'http to https',
    "func" => '',
    "type" => OPF_TYPE_PAGE_LAST,
    "file" => '{OPF:PLUGIN_PATH}/filter.php',
    "csspath" => '',
    "funcname" => 'opff_http_to_https',
    "configurl" => '',
    "plugin" => 'http_to_https',
    "desc" => array (
        "EN" => 'converts (absolute) links inside of the web page from http to https',
        "DE" => 'konvertiert (absolute) Links innerhalb der Webseite von http nach https',
    )
));
