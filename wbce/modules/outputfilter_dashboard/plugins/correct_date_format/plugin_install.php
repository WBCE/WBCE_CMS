<?php

/*
plugin_install.php

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
if(!defined('WB_PATH')) die(header('Location: index.php'));

opf_register_filter(array(
    'name' => 'Correct Date Format',
    'type' => OPF_TYPE_SECTION,
    'funcname' => 'opff_correct_date_format',
    'desc' => array(
        'EN' => "Dates like \"Monday, 1st January, 2008\" will be always in English, even if the language is set to e.g. German. This filter will correct those dates to display as expected \"Montag, 1. Januar, 2008\".\nOn a windows host this filter may fail.\nRequires PHP 4.3.3 or higher.\nConfig:\nLocale: Enter a 'locale'-string if the filter doesn't work automatically.\nDate format: Allows to format the Date-strings.",
        'DE' => "Datumsangaben, wie \"Monday, 1st January, 2008\" erscheinen immer in englisch, auch wenn als Sprache Deutsch eingestellt ist. Dieser Filter behebt das Problem, und zeigt sie in der Form \"Montag, 1. Januar, 2008\" an.\nM&ouml;glicherweise funktioniert der Filter auf einem Windows-System nicht richtig.\nDer Filter Ben&ouml;tigt PHP 4.3.3 oder h&ouml;her.\nKonfiguration:\nSpracheinstellung: Wenn der Filter nicht automatisch funktioniert, kann hier eine andere 'locale'-Einstellung eingetragen werden.\nDatumsformat: Das gew&uuml;nschte Datumsformat kann beliebig Formatiert werden."
    ),
    'modules' => 'download_gallery,news',
    'file' => '{OPF:PLUGIN_PATH}/filter.php',
    'plugin' => 'correct_date_format',
    'active' => 1,
    'allowedit' => 0,
    'allowedittarget' => 1,
    'configurl'=> '',
    'additional_fields' => 
        array(
            array(
                'label' => array('EN'=>'Locale','DE'=>'Spracheinstellung'),
                'variable' => 'locale',
                'type' => 'text',
                'name' => 'af_locale',
                'value' => '',
                'style' => 'width: 98%;'
            ),
            array(
                'label' => array('EN'=>'Date format','DE'=>'Datumsformat'),
                'variable' => 'date_formats',
                'type' => 'array',
                'name' => 'af_date_formats',
                'value' => array(
                    'D M d, Y'  => '%a %b %d %Y',
                    'M d Y'     => '%b %d. %Y',
                    'd M Y'     => '%d. %b %Y',
                    'jS F, Y'   => '%e. %B %Y',
                    'l, jS F, Y'=> '%A, %e. %B %Y'
                )
            )
        )
));
