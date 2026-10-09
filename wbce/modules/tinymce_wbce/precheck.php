<?php
/**
 * tinymce_wbce — precheck.php
 * Requirements: WBCE >= 1.7.0, PHP >= 8.1, PDO extension
 * @author  SC-Peet
 * @license GNU GPL2
 */

if (!defined('WB_PATH')) {
    if (!headers_sent()) { header('Location: ../index.php', true, 301); }
    die('<head><title>Access denied</title></head>');
}

$PRECHECK = [];

// WBCE >= 1.7.0 required (PDO database layer)
$PRECHECK['WBCE_VERSION'] = [
    'VERSION'  => '1.7.0',
    'OPERATOR' => '>='
];

// PHP >= 8.1
$PRECHECK['PHP_VERSION'] = [
    'VERSION'  => '8.1.0',
    'OPERATOR' => '>='
];

// PDO extension must be available
$pdoOk = extension_loaded('pdo') && extension_loaded('pdo_mysql');
$PRECHECK['CUSTOM_CHECKS'] = [
    'PDO' => [
        'REQUIRED' => 'TEXT:INSTALLED',
        'ACTUAL'   => $pdoOk ? 'TEXT:INSTALLED' : 'TEXT:NOT_INSTALLED',
        'STATUS'   => $pdoOk,
    ]
];
