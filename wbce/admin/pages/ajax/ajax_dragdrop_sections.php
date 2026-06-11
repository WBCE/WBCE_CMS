<?php
/**
 * WBCE CMS — Sections: AJAX Drag & Drop order update
 *
 * Receives the new section order from jQuery UI sortable
 * and writes positions back to the database via PDO.
 */

require '../../../config.php';
if (!defined('WB_PATH')) exit();

$response = ['success' => false, 'message' => ''];

$admin = new Admin('Pages', 'pages_modify', false, false);

if (!$admin->is_authenticated() || !$admin->get_permission('pages_modify')) {
    $response['message'] = 'Insufficient privileges';
    header('Content-Type: application/json');
    exit(json_encode($response));
}

if (isset($_POST['action']) && $_POST['action'] === 'updateArray') {
    $page_id    = (int)($_POST['page_id'] ?? 0);
    $sectionIDs = isset($_POST['sectionID']) ? (array)$_POST['sectionID'] : [];

    if ($page_id < 1 || empty($sectionIDs)) {
        $response['message'] = 'Invalid parameters';
        header('Content-Type: application/json');
        exit(json_encode($response));
    }

    $position = 1;
    foreach ($sectionIDs as $rawID) {
        $sectionID = (int)$rawID;
        if ($sectionID < 1) { $position++; continue; }

        $database->query(
            'UPDATE `{TP}sections` SET `position` = ? WHERE `section_id` = ? AND `page_id` = ?',
            [$position, $sectionID, $page_id]
        );

        if ($database->hasError()) {
            $response['message'] = 'DB error: ' . $database->getError();
            header('Content-Type: application/json');
            exit(json_encode($response));
        }
        $position++;
    }

    $response['success'] = true;
    $response['message'] = 'Saved';
}

header('Content-Type: application/json');
exit(json_encode($response));
