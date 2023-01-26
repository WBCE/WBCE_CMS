<?php
/**
 *
 * @category        admintool / initialize 
 * @package         CodeMirror_Config
 * @author          Christian M. Stefan
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WBCE 1.5.5
 *
 */

// Must include code to stop this file from being accessed directly
defined('WB_PATH') or die("This file can't be accessed directly!");

$module_directory   = 'CodeMirror_Config';
$module_name        = 'CodeMirror Configurator';
$module_function    = 'tool, initialize';
$module_version     = '0.1.0';
$module_platform    = '1.5.5'; // will not work with older versions!
$module_author      = 'Christian M. Stefan (Stefek)';
                      // Some of the CodeMirror implementation mechanism have  
                      // been inspired by Martin Hecht's (mrbaseman) work
                      // in the Code2 module. Thank you.
$module_license	    = 'GNU General Public License';
                      // Please see codemirror/LICENSE to view the
                      // license of the CodeMirror script
                      // More information can be found
                      // here: https://github.com/codemirror/codemirror5
$module_description = 'Change Settings of the CodeMirror implementation';
$module_icon        = 'fa fa-code';