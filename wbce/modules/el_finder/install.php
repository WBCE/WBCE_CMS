<?php
/**
 * @category        modules
 * @package         maintainance_mode
 * @author          WBCE Project
 * @copyright       Norbert Heimsath
 * @license			WTFPL
 */

//no direct file access
if(count(get_included_files())==1) header("Location: ../index.php",TRUE,301);

make_dir(WB_PATH. '/var/modules/el_finder/', OCTAL_DIR_MODE, true);


$msg = '';

