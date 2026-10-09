<?php

/*
plugin_install.php

Copyright (C) 2010 Thomas "thorn" Hornik <thorn@nettest.thekk.de>, http://nettest.thekk.de

This file is part of opf assets cache busting, a plugin-filter for OutputFilter Dashboard.

opf assets cache busting is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

opf assets cache busting is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.        See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with opf assets cache busting. If not, see <http://www.gnu.org/licenses/>.

*/

if (!defined('WB_PATH')) {
    die(header('Location: ../../index.php'));
}

opf_register_filter(array(
        'name' => 'Assets Cache Busting',
        'type' => OPF_TYPE_PAGE_FINAL,
        'file' =>  '{OPF:PLUGIN_PATH}/filter.php',
        'funcname' => 'opff_assets_cache_busting',
        'desc' => array(
                'EN' => "Prevents browsers from delivering outdated CSS/JS files from cache. Turning this on also switches on AssetQueue's own cache busting (OPF_ASSETS_CACHE_BUSTING); this filter additionally busts CSS/JS references that don't go through AssetQueue at all.",
                'DE' => "Verhindert, dass Browser veraltete CSS-/JS-Dateien aus ihrem Cache ausliefern. Aktivieren schaltet auch AssetQueues eigenes Cache-Busting ein (OPF_ASSETS_CACHE_BUSTING); dieser Filter behandelt zusätzlich CSS-/JS-Referenzen, die AssetQueue gar nicht durchlaufen."
        ),
        'plugin' => 'opf_assets_cache_busting',
        'active' => 1,
        'allowedit' => 0,
        'allowedittarget' => 1,
        'configurl'=> '',
        'pages_parent' => 'all,backend,search'
));
