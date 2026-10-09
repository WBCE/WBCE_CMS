<?php

/*
info.php

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

/*
Header: Version-History
1.1.0 %(Christian M. Stefan; 17 Aug, 2026)%
        - Renamed from "Cache Control" to "Assets Cache Busting" (directory: cachecontrol ->
          opf_assets_cache_busting). Existing installations are migrated automatically on
          upgrade -- the filter's active/inactive state and settings are preserved, the old
          DB row is renamed in place rather than replaced.
        - Moved from OPF_TYPE_PAGE_LAST to OPF_TYPE_PAGE_FINAL, positioned after "Remove
          System PH", so it always runs against the final, fully assembled page content
          instead of racing "Class Insert Helper" (AssetQueue's own flush) within the same
          stage.
        - Turning the filter on/off now also flips the OPF_ASSETS_CACHE_BUSTING constant
          (via WBCE's existing Settings-to-constant bootstrap), which AssetQueue (I::) reads
          to decide whether to append its own ?<mtime> cache-busting to the CSS/JS files it
          manages. This filter's own regex-based busting pass still runs separately, for
          CSS/JS references that never go through AssetQueue.

1.0.9 %(Christian M. Stefan; 17 Aug, 2026)%
        - Only skip URLs that already carry their own cache-busting mtime (?<digits> at
          the end); previously any "?" at all (e.g. ?media=print) caused the file to be
          skipped entirely, so it never got busted. Switched from the cut/extract
          str_replace mechanism to preg_replace_callback(), since matching multiple
          variants of the same URL (busted and not) made the old placeholder swap
          unsafe -- one matched value could be a substring of another.

1.0.8 %(florian, 25 Feb, 2023)%
        - Update description

1.0.7 %(mrbaseman; 1 Jul, 2021)%
        - check for file existence, first

1.0.6 %(mrbaseman; 15 Jul, 2019)%
        - use by default in search results, too
        - bugfix: correct the previous change

1.0.5 %(mrbaseman; 11 Jul, 2019)%
        - activate by default in backend

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

$plugin_directory   = 'opf_assets_cache_busting';
$plugin_name        = 'Assets Cache Busting';
$plugin_version     = '1.1.0';
$plugin_status      = 'beta';
$plugin_platform    = '1.7.x';
$plugin_author      = 'thorn, mrbaseman, Christian M. Stefan';
$plugin_license     = 'GNU General Public License, Version 3 or later';
$plugin_description = 'Prevents browsers from delivering outdated CSS/JS from cache -- both by switching on AssetQueue\'s own cache busting and by busting CSS/JS references that bypass AssetQueue entirely';
