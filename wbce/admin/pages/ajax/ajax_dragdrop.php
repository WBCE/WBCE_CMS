<?php
/**
 * WBCE CMS — Pages: AJAX Drag & Drop order update
 *
 * Receives the new page order from jQuery UI sortable
 * and writes positions back to the database via PDO.
 */

require '../../../config.php';
if (!defined('WB_PATH')) exit();

if (!isset($_POST['action'])) {
    header('Location: ../../../index.php');
    exit();
}

$response = ['success' => false, 'message' => '', 'icon' => ''];

$admin = new Admin('Pages', 'pages', false, false);

if (!$admin->is_authenticated()
    || !$admin->get_permission('admintools')
    || !$admin->get_permission('pages')) {
    $response['message'] = 'Insufficient privileges';
    $response['icon']    = 'failure.gif';
    header('Content-Type: application/json');
    exit(json_encode($response));
}

if ($_POST['action'] === 'updateArray') {
    $pageIDs = isset($_POST['pageID']) ? (array)$_POST['pageID'] : [];

    $position = 1;
    foreach ($pageIDs as $rawID) {
        $pageID = (int)$rawID;
        if ($pageID < 1) {
            $position++;
            continue;
        }

        $database->query(
            'UPDATE `{TP}pages` SET `position` = ? WHERE `page_id` = ?',
            [$position, $pageID]
        );

        if ($database->hasError()) {
            $response['message'] = 'DB error: ' . $database->getError();
            $response['icon']    = 'failure.gif';
            header('Content-Type: application/json');
            exit(json_encode($response));
        }

        $position++;
    }

    $response['success'] = true;
    $response['message'] = $MESSAGE['RECORD_MODIFIED_SAVED'] ?? 'Saved';
    $response['icon']    = 'success.gif';
}

header('Content-Type: application/json');
exit(json_encode($response));
