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

// Create admin object
require('../../config.php');
$admin = new admin('Media', 'media_rename', false);

// Include WBCE functions file (legacy for WBCE 1.1.x)
require_once WB_PATH . '/framework/functions.php';

// Get current dir (relative to media)
$directory = $admin->get_get('dir');
$directory = ($directory == '/' or $directory == '\\') ? '' : $directory;
$dirlink = 'browse.php?dir=' . $directory;

// Ensure directory is inside WBCE media folder
if (!check_media_path($directory)) {
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], 'browse.php?dir=', false);
    die;
}

// include functions.php (backwards compatibility with WBCE 1.x)
require_once WB_PATH . '/framework/functions.php';

// Get the temp id
$file_id = intval($admin->checkIDKEY('id', false, $_SERVER['REQUEST_METHOD']));
if (!$file_id) {
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], $dirlink, false);
}

// Get home folder not to show
$home_folders = get_home_folders();

// Check for potentially malicious files
$forbidden_file_types = preg_replace('/\s*[,;\|#]\s*/', '|', RENAME_FILES_ON_UPLOAD);

// Figure out what folder name the temp id is
if ($handle = opendir(WB_PATH . MEDIA_DIRECTORY . '/' . $directory)) {
    // Loop through the files and dirs an add to list
    while (false !== ($file = readdir($handle))) {
        $info = pathinfo($file);
        $ext = isset($info['extension']) ? $info['extension'] : '';
        if (substr($file, 0, 1) != '.' and $file != '.svn' and $file != 'index.php') {
            if (!preg_match('/' . $forbidden_file_types . '$/i', $ext)) {
                if (is_dir(WB_PATH . MEDIA_DIRECTORY . $directory . '/' . $file)) {
                    if (!isset($home_folders[$directory . '/' . $file])) {
                        $DIR[] = $file;
                    }
                } else {
                    $FILE[] = $file;
                }
            }
        }
    }

    $temp_id = 0;
    if (isset($DIR)) {
        sort($DIR);
        foreach ($DIR as $name) {
            $temp_id++;
            if ($file_id == $temp_id) {
                $rename_file = $name;
                $type = 'folder';
            }
        }
    }

    if (isset($FILE)) {
        sort($FILE);
        foreach ($FILE as $name) {
            $temp_id++;
            if ($file_id == $temp_id) {
                $rename_file = $name;
                $type = 'file';
            }
        }
    }
}

if (!isset($rename_file)) {
    $admin->print_error($MESSAGE['MEDIA_FILE_NOT_FOUND'], $dirlink, false);
}

// Setup template object, parse vars to it, then parse it
// Create new template object
$template = new Template(dirname($admin->correct_theme_source('media_rename.htt')));
$template->set_file('page', 'media_rename.htt');
$template->set_block('page', 'main_block', 'main');

if ($type == 'folder') {
    $template->set_var('DISPlAY_EXTENSION', 'hide');
    $extension = '';
} else {
    $template->set_var('DISPlAY_EXTENSION', '');
    $extension = strstr($rename_file, '.');
}

if ($type == 'folder') {
    $type = $TEXT['FOLDER'];
} else {
    $type = $TEXT['FILE'];
}

$template->set_var(
    array(
        'THEME_URL' => THEME_URL,
        'WB_URL' => WB_URL,
        'FILENAME' => $rename_file,
        'DIR' => $directory,
        'FILE_ID' => $admin->getIDKEY($file_id),
        'TYPE' => $type,
        'EXTENSION' => $extension,
        'FTAN' => $admin->getFTAN()
    )
);


// Insert language text and messages
$template->set_var(
    array(
        'TEXT_TO' => $TEXT['TO'],
        'TEXT_RENAME' => $TEXT['RENAME'],
        'TEXT_CANCEL' => $TEXT['CANCEL'],
        'TEXT_UP' => $TEXT['UP'],
        'TEXT_OVERWRITE_EXISTING' => $TEXT['OVERWRITE_EXISTING']
    )
);

// Parse template object
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');
