<?php

/*
filter.php

Copyright (C) 2009 Thomas "thorn" Hornik <thorn@nettest.thekk.de>

This file is part of opf Correct Date Format, a module for Website Baker CMS.

opf Correct Date Format is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

opf Correct Date Format is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with opf Correct Date Format. If not, see <http://www.gnu.org/licenses/>.

*/

// prevent this file from being accessed directly
if(!defined('WB_PATH')) die(header('Location: ../index.php'));

function opff_correct_date_format(&$content, $page_id, $section_id, $module, $wb) {
    $values = opf_filter_get_additional_values();
    $locale = $values['locale'];
    $date_formats = $values['date_formats'];

    if(strpos(strtoupper(PHP_OS), 'WIN')===0)
        define('OPF_CDF_SYSTEM_BAD', TRUE);
    else define('OPF_CDF_SYSTEM_BAD', FALSE);
    $ext = '';
    $format = opff_cdf_get_date_format($date_formats);
    if(!$format) return(TRUE);
    if($locale) $locale = str_replace(array('"','\'',"\x00","\xff","\x0a",'`',';','#'), '', $locale);
    else list($locale,$ext) = opff_cdf_fetch_locale();
    if(!$locale) return(TRUE);
    $locales = opff_cdf_explode_locale($locale, $ext);
    $locale_old = setlocale(LC_TIME, 0);
    $res = setlocale(LC_TIME, $locales);
    if($res===FALSE) {
        // locale not present!
        return(TRUE);
    }
    $day_l = 'Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday';
    $day_s = 'Mon|Tue|Wed|Thu|Fri|Sat|Sun';
    $day_pf = '(?:<sub>)?(?:st|nd|rd|th)(?:</sub>)?';
    $month_s = 'Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec';
    $month_l = 'January|February|March|April|May|June|July|August|September|October|November|December';
    $extracts = array();
    $regex = "(?:(?:($day_s) )?(?:($month_s) )?[0-3]\d(?(1),|)((?(2)| (?2)))|(($day_l), )?[1-3]?\d($day_pf) ($month_l),) (19|20)\d{2,2}";
    $extracts = opf_cut_extract($content, $regex, 0, 'i');
    foreach($extracts as $k => $d) {
        $d = trim(str_replace(array_merge(explode('|',$day_l),explode('|',$day_s),array('st','nd','rd','th',',','<sub>','</sub>')), '', $d));
        $str = strftime($format, strtotime($d));
        $str = opff_cdf_fix_utf8($str);
        $extracts[$k] = $str;
    }
    opf_glue_extract($content, $extracts);
    setlocale(LC_TIME, $locale_old);
    return(TRUE);
}

function opff_cdf_fix_utf8($str) { // try to fix utf-8
    static $res = FALSE;
    if($res==1) return($str);
    if(DEFAULT_CHARSET=='utf-8'
       && OPF_CDF_SYSTEM_BAD) { // windows
        $charset = 'cp1252';
        if(LANGUAGE=='RU')
            $charset = 'cp1251';
        $str = htmlentities($str, ENT_QUOTES, $charset);
    } else {
        $res == 1;
    }
    return($str);
}

function opff_cdf_get_date_format($date_formats) {
    global $wb;
    if($wb->is_authenticated()) $format = DATE_FORMAT;
    else $format = DEFAULT_DATE_FORMAT;
    if(array_key_exists($format, $date_formats)) {
        if(OPF_CDF_SYSTEM_BAD) { // windows
            $date_formats[$format] = str_replace('%e','%#d',$date_formats[$format]);
        }
        return($date_formats[$format]);
    }
    else return(FALSE);
}

function opff_cdf_fetch_locale() {
    global $wb;
    $code = $ext = $lang = '';
    if(OPF_CDF_SYSTEM_BAD) { // windows
        $table = array(
            'CS' => 'czech csy',
            'DA' => 'danish dan',
            'DE' => 'german deu',
            'EN' => 'english',
            'ES' => 'spanish esp',
            'FI' => 'finnish fin',
            'FR' => 'french fra',
            'HU' => 'hungarian hun',
            'IT' => 'italian ita',
            'NL' => 'dutch nld',
            'NO' => 'norwegian',
            'PL' => 'polish plk',
            'PT' => 'portuguese ptg',
            'RU' => 'russian rus',
            'SE' => 'swedish sve',
            'TR' => 'turkish trk'
        );
    } else { // *nix
        $table = array(
            'BG' => 'bg_BG',
            'CA' => 'ca_ES',
            'CS' => 'cs_CZ',
            'DA' => 'da_DK',
            'DE' => 'de_DE',
            'EN' => 'en_US',
            'ES' => 'es_ES',
            'ET' => 'et_EE',
            'FI' => 'fi_FI',
            'FR' => 'fr_FR',
            'HR' => 'hr_HR',
            'HU' => 'hu_HU',
            'IT' => 'it_IT',
            'LV' => 'lv_LV',
            'NL' => 'nl_NL',
            'NO' => 'no_NO',
            'PL' => 'pl_PL',
            'PT' => 'pt_PT',
            'RU' => 'ru_RU',
            'SE' => 'se_NO',
            'TR' => 'tr_TR'
        );
    }
    $lang = LANGUAGE;
    if($lang=='EN') return(array(FALSE, FALSE));
    if(isset($table[$lang])) $code = $table[$lang];
    else $code = 'en_US';
    if(DEFAULT_CHARSET=='utf-8') $ext = '.utf-8';
    return(array($code,$ext));
}

function opff_cdf_explode_locale($str, $ext='') {
    $locales_new = array();
    $str = trim($str);
    $str = preg_replace('~\s+~',' ',$str);
    $locales = explode(' ', $str);
    foreach($locales as $l) {
        $locales_new[] = $l.$ext;
        if($ext=='.utf-8')
            $locales_new[] = $l.'.utf8';
    }
    return($locales_new);
}
