<?php
/**
 *
 * @category        admintool / initialize 
 * @package         CodeMirror_Config
 * @author          Christian M. Stefan
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WBCE 1.5.4
 *
 */

// Must include code to stop this file from being accessed directly
defined('WB_PATH') or die("This file can't be accessed directly!");

// default settings
$aCfg = array(
    'theme'     => "wbce-night",
    'font'      => 'Proggy',
    'font_size' => '14'
);

// write default settings into {TP}settings table
Settings::Set("cmc_cfg", serialize( $aCfg ));