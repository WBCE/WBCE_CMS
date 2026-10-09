<?php
/**
 * Asset Optimizer — install.php
 *
 * @package asset_optimizer
 * @author  Christian M. Stefan · www.wbEasy.de
 * @license http://www.gnu.org/licenses/gpl.html
 */

if (!defined('WB_PATH')) {
    die('Cannot access this file directly');
}

// Nothing to set up: the tool only reads/writes existing config constants and
// the AssetQueue cache directories, which the queue creates on its own.
