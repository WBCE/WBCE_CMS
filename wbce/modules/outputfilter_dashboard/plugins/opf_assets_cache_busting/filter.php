<?php

/*
filter.php

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

function opff_assets_cache_busting(&$content, $page_id, $section_id, $module, $wb)
{
    global $opf_HEADER, $opf_BODY; // PRIVATE - do not do this

    $head = $body = '';
    foreach ($opf_HEADER as $str) {
        $head .= $str;
    }
    foreach ($opf_BODY as $str) {
        $body .= $str;
    }

    // Matches the full href/src value, including any existing query string, so
    // URLs that already carry their own cache-busting mtime (e.g. from
    // AssetQueue's OPF_ASSETS_CACHE_BUSTING, or an earlier run of this filter) are
    // recognized and left alone, instead of being silently skipped just for
    // having a "?" -- and files with unrelated query strings (e.g. ?media=print)
    // still get busted, with the existing query string preserved.
    $regex = '~(href|src)="([^"]+\.(?:css|js))(\?[^"]*)?"~i';
    $bust = function ($m) {
        $full  = $m[0];
        $attr  = $m[1];
        $path  = $m[2];
        $query = $m[3] ?? '';
        if ($query !== '' && preg_match('~^\?\d+$~', $query)) {
            return $full; // already cache-busted -- leave it alone
        }
        $file = (strpos($path, WB_PATH) === 0) ? $path : str_replace(WB_URL, WB_PATH, $path);
        if (strpos($file, WB_PATH) === 0 && file_exists($file) && is_numeric($t = @filemtime($file))) {
            $sep = ($query !== '') ? '&' : '?';
            return $attr.'="'.$path.$query.$sep.$t.'"';
        }
        return $full;
    };
    $content = preg_replace_callback($regex, $bust, $content);
    $head    = preg_replace_callback($regex, $bust, $head);
    $body    = preg_replace_callback($regex, $bust, $body);

    $opf_HEADER = array($head);
    $opf_BODY = array($body);

    return(true);
}
