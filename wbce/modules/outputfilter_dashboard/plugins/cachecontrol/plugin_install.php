<?php

/*
plugin_install.php

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

opf_register_filter(array(
        'name' => 'Cache Control',
        'type' => OPF_TYPE_PAGE_LAST,
        'file' =>  '{OPF:PLUGIN_PATH}/filter.php',
        'funcname' => 'opff_cachecontrol',
        'desc' => array(
                'EN' => "Prevent Browsers from delivering outdated files (css, js) from it's cache.\nKeep care that this filter is called last!",
                'DE' => "Verhindert, dass Browser veralterte CSS- oder JS-Dateien aus ihrem Cache ausliefern.\nAchten Sie darauf, dass dieser Filter als letzter aufgerufen wird!"
        ),
        'plugin' => 'cachecontrol',
        'active' => 1,
        'allowedit' => 0,
        'allowedittarget' => 1,
        'configurl'=> '',
        'pages_parent' => 'all,backend,search'
));
