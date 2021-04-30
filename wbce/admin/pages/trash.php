<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

// Include config file
if (!defined('WB_URL') && file_exists(realpath('../../config.php'))) {
    require('../../config.php');
}

// Check if the config file has been set-up
if (!defined('WB_PATH')) {
    header("Location: ../../index.php");
} else {
    header('Location: ' . ADMIN_URL . '/pages/index.php');
}
