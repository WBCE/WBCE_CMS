<?php
/*
 * Remark:  HTTP/1.1 requires a qualified URI incl. the scheme, name
 * of the host and absolute path as the argument of location. Some, but
 * not all clients will accept relative URIs also.
 */
    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    $file = 'install/index.php';
    $target_url = 'http://' . $host . $uri . '/' . $file;
    $sResponse = $_SERVER['SERVER_PROTOCOL'] . ' 307 Temporary Redirect';
    header($sResponse);
    header('Location: ' . $target_url); exit;  
