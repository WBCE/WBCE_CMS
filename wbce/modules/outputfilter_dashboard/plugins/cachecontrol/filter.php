<?php

/*
filter.php

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

if (!defined('WB_PATH')) {
    die(header('Location: ../../index.php'));
}

function opff_cachecontrol(&$content, $page_id, $section_id, $module, $wb)
{
    global $opf_HEADER, $opf_BODY; // PRIVATE - do not do this

    $head = $body = '';
    foreach ($opf_HEADER as $str) {
        $head .= $str;
    }
    foreach ($opf_BODY as $str) {
        $body .= $str;
    }

    $regex = '(href|src)="([^"]+\.(css|js))"';
    $extracts = opf_cut_extract($content, $regex, 2, 'i', '~');
    $extracts = opf_cut_extract($head, $regex, 2, 'i', '~', $extracts);
    $extracts = opf_cut_extract($body, $regex, 2, 'i', '~', $extracts);
    foreach ($extracts as $k=>$str) {
        if (strpos($str, WB_PATH)===0) {
            $file = $str;
        } else {
            $file = str_replace(WB_URL, WB_PATH, $str);
        }
        if (strpos($file, WB_PATH)===0) {
            if (file_exists($file) && is_numeric($t = @filemtime($file))) {
                $extracts[$k] = $str.'?'.$t;
            }
        }
    }
    opf_glue_extract($body, $extracts);
    opf_glue_extract($head, $extracts);
    opf_glue_extract($content, $extracts);

    $opf_HEADER = array($head);
    $opf_BODY = array($body);

    return(true);
}
