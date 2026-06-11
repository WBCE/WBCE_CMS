<?php
/**
 * WBCE CMS — htmx inline validation endpoint
 *
 * Validates a single form field and returns a small HTML fragment 
 * with success/error styling. Used by hx-post on the username 
 * and email inputs in the user form.
 *
 * GET params: ?field=username|email
 * POST data:  username or email value, plus user_id for exclude-self check
 *
 * @since 1.7.0
 */

require '../../config.php';

// Only respond to htmx requests
if (empty($_SERVER['HTTP_HX_REQUEST'])) {
    http_response_code(400);
    exit;
}

$admin  = new Admin('Access', 'users', false);
$users  = new UserManager();
$field  = $_GET['field'] ?? '';
$userId = intval($admin->get_post('user_id') ?? 0);

switch ($field) {

    case 'username':
        $value  = strtolower(trim($admin->get_post('username') ?? ''));
        if (strlen($value) < 2) {
            // Don't validate while still typing
            echo '';
            exit;
        }
        $errors = $users->validateUsername($value, $userId);
        break;

    case 'email':
        $value  = trim($admin->get_post('email') ?? '');
        if (strlen($value) < 4) {
            echo '';
            exit;
        }
        $errors = $users->validateEmail($admin, $value, $userId);
        break;

    default:
        http_response_code(400);
        echo '';
        exit;
}

// Output feedback
if (empty($errors)) {
    echo '<small class="text-success" style="color:#48BF40;"><i class="fa fa-check"></i> OK</small>';
} else {
    $msg = htmlspecialchars(implode(', ', $errors));
    echo '<small class="text-error" style="color:#c0392b;"><i class="fa fa-warning"></i> ' . $msg . '</small>';
}
