<?php
/**
 * tinymce_wbce — uninstall.php
 * Removes module config from {TP}settings.
 * @author  Slugger & Claude KI
 * @license GNU GPL2
 */

Settings::delete('tinymce_cfg');
