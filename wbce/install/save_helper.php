<?php

/**
    @file 
    @brief  this file contains the functions used by installer save script. 
    
    I moved the functions here to have a more clean installer file 
*/

// Function to set error
// Stores errors to Session 
// Returns to Installer form if  there are invalid Values
function set_error($message, $field_name = '', $now=false)
{
//    global $_POST;
    if (isset($message) and $message != '') {
        // Copy values entered into session so user doesn't have to re-enter everything
        if (isset($_POST['website_title'])) {
            $_SESSION['wb_url'] = $_POST['wb_url'];
            $_SESSION['default_timezone'] = $_POST['default_timezone'];
            $_SESSION['default_language'] = $_POST['default_language'];
            if (!isset($_POST['operating_system'])) {
                $_SESSION['operating_system'] = 'linux';
            } else {
                $_SESSION['operating_system'] = $_POST['operating_system'];
            }
            if (!isset($_POST['world_writeable'])) {
                $_SESSION['world_writeable'] = false;
            } else {
                $_SESSION['world_writeable'] = true;
            }
            $_SESSION['database_host'] = $_POST['database_host'];
            $_SESSION['database_username'] = $_POST['database_username'];
            $_SESSION['database_password'] = $_POST['database_password'];
            $_SESSION['database_name'] = $_POST['database_name'];
            $_SESSION['table_prefix'] = $_POST['table_prefix'];
            if (!isset($_POST['install_tables'])) {
                $_SESSION['install_tables'] = false;
            } else {
                $_SESSION['install_tables'] = true;
            }
            $_SESSION['website_title'] = $_POST['website_title'];
            $_SESSION['admin_username'] = $_POST['admin_username'];
            $_SESSION['admin_email'] = $_POST['admin_email'];
            $_SESSION['admin_password'] = $_POST['admin_password'];
            $_SESSION['admin_repassword'] = $_POST['admin_repassword'];
        }
        // Set the message
        
        $_SESSION['message'][] = $message;
        // Set the element(s) to highlight
        if ($field_name != '') {
            $_SESSION['ERROR_FIELD'][] = $field_name;
        }
        // Specify that session support is enabled
        $_SESSION['session_support'] = '<span class="good">Enabled</span>';
        
        // There was a request for immediate redirect 
        if ($now===true) {
            header('Location: index.php?sessions_checked=true'); 
            exit;
        }
    }
}

// Function to workout what the default permissions are for files created by the webserver
function default_file_mode($temp_dir)
{
    if (version_compare(PHP_VERSION, '5.3.6', '>=') && is_writable($temp_dir)) {
        $filename = $temp_dir . '/test_permissions.txt';
        $handle = fopen($filename, 'w');
        fwrite($handle, 'This file is to get the default file permissions');
        fclose($handle);
        $default_file_mode = '0' . substr(sprintf('%o', fileperms($filename)), -3);
        unlink($filename);
    } else {
        $default_file_mode = '0777';
    }
    return $default_file_mode;
}

// Function to workout what the default permissions are for directories created by the webserver
function default_dir_mode($temp_dir)
{
    if (version_compare(PHP_VERSION, '5.3.6', '>=') && is_writable($temp_dir)) {
        $dirname = $temp_dir . '/test_permissions/';
        mkdir($dirname);
        $default_dir_mode = '0' . substr(sprintf('%o', fileperms($dirname)), -3);
        rmdir($dirname);
    } else {
        $default_dir_mode = '0777';
    }
    return $default_dir_mode;
}

function add_slashes($input)
{
    if (get_magic_quotes_gpc() || (!is_string($input))) {
        return $input;
    }
    $output = addslashes($input);
    return $output;
}


