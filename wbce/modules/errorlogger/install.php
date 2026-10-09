<?php
/**
 *
 * @category        admintool / preinit / initialize
 * @package         errorlogger
 * @author          Ruud Eisinga · www.dev4me.com (https://dev4me.com/)
 * @author          Christian M. Stefan  (https://www.wbEasy.de)
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WBCE 1.7.x
 *
 */

// Must include code to stop this file being access directly
if (defined('WB_PATH') == false) {
    die("Cannot access this file directly");
}

make_dir(WB_PATH.'/var/logs', OCTAL_DIR_MODE, true);
