<?php

require_once '../../../../config.php';

/**
 * @file /modules/elfinder/connector.wbce_cke.php
 * @brief The WBCE connector for connecting whith elFinder
 * @important The including script needs to take care of a granular access controll!
 */

// no direct file access
if (count(get_included_files()) == 1) {
    header("Location: ../index.php", true, 301);
}

// Make sure we include from wbce
if (!defined('WB_PATH')) {
    header("Location: ../index.php", true, 301);
}

// In wbce we have our own setting for Error reporting
// error_reporting(E_ALL); // Set E_ALL for debuging

// load composer autoload before load elFinder autoload If you need composer
// No composer in wbce
// require './vendor/autoload.php';

// elFinder autoload
require 'autoload.php';

// Root/volume config builder (access() and wbce_filenames_ok() callbacks
// included) - shared with include/PlainMDE/elfinder-resolve.php, which
// needs the exact same configuration to resolve a real filesystem path to
// elFinder's own hash for a specific file. See that file's docblock.
require_once __DIR__ . '/../wbce-opts.php';

$admin = new admin('Media', 'media_view', false, false);
$opts = elfinder_wbce_build_opts($admin);
if ($opts === false) {
    die;
}

// run elFinder
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();
