<?php
/**
 * WBCE CMS — admin/pages/add.php
 * Inserts a new page and its first section, then redirects to modify.php.
 */

require '../../config.php';

$admin = new Admin('Pages', 'pages_add', false);

if (!$admin->checkFTAN()) {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
}

// ── Sanitize POST input ───────────────────────────────────────────────────────

$title          = htmlspecialchars($admin->get_post_escaped('title'));
$module         = preg_replace('/[^a-z0-9_-]/i', '', $admin->get_post('type'));
$parent         = (int) $admin->get_post('parent');
$admin_groups   = $admin->get_post('admin_groups')   ?: [1];
$viewing_groups = $admin->get_post('viewing_groups') ?: [1];
$visibility     = $admin->get_post('visibility');

if (!in_array($visibility, ['public', 'private', 'registered', 'hidden', 'none'], true)) {
    $visibility = 'public';
}

$admin->print_header();

// ── Error collection ──────────────────────────────────────────────────────────
// Nothing is written to DB or filesystem until $errors is empty.

$errors = [];

// Permission checks
if ($parent !== 0) {
    if (!$admin->get_page_permission($parent, 'admin')) {
        $errors[] = $MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS'];
    }
} elseif (!$admin->get_permission('pages_add_l0', 'system')) {
    $errors[] = $MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS'];
}

if (!$admin->get_permission($module, 'module')) {
    $errors[] = $MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS'];
}

if (!in_array(1, $admin->get_groups_id(), true)) {
    $userGroups = $admin->get_groups_id();
    if (empty(array_intersect((array) $admin_groups,   $userGroups))) $errors[] = $MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS'];
    if (empty(array_intersect((array) $viewing_groups, $userGroups))) $errors[] = $MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS'];
}

if ($title === '' || $title[0] === '.') {
    $errors[] = $MESSAGE['PAGES_BLANK_PAGE_TITLE'];
}

// Bail early — no point building links if permissions/title already wrong
if ($errors) {
    $admin->print_error(implode('<br>', array_unique($errors)));
}

// ── Build link / filename ─────────────────────────────────────────────────────

$link = $filename = '';

if ($parent === 0) {
    $link = '/' . page_filename($title);
    if ($link === '/index' || $link === '/intro') $link .= '_0';
    $filename = WB_PATH . PAGES_DIRECTORY . $link . PAGE_EXTENSION;
} else {
    $parentLink = $database->fetchValue(
        'SELECT `link` FROM `{TP}pages` WHERE `page_id` = ?', [$parent]
    );
    if ($parentLink === null) {
        $errors[] = $MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS'];
    } else {
        make_dir(WB_PATH . PAGES_DIRECTORY . $parentLink);
        $link     = $parentLink . '/' . page_filename($title);
        $filename = WB_PATH . PAGES_DIRECTORY . $link . PAGE_EXTENSION;
    }
}

// Check for duplicates
if (empty($errors)) {
    $linkExists = $database->fetchValue(
        'SELECT `page_id` FROM `{TP}pages` WHERE `link` = ?', [$link]
    );
    if ($linkExists || file_exists(WB_PATH . PAGES_DIRECTORY . $link . PAGE_EXTENSION)
                    || file_exists(WB_PATH . PAGES_DIRECTORY . $link . '/')) {
        $errors[] = $MESSAGE['PAGES_PAGE_EXISTS'];
    }
}

if ($errors) {
    $admin->print_error(implode('<br>', array_unique($errors)));
}

// ── All validation passed — write to DB ───────────────────────────────────────

$order = new Order(TABLE_PREFIX . 'pages', 'position', 'page_id', 'parent');
$order->clean($parent);
$position = $order->get_new($parent);

$parentRow = $parent > 0
    ? $database->fetchRow('SELECT `template`, `language` FROM `{TP}pages` WHERE `page_id` = ?', [$parent])
    : null;

$template = $parentRow['template'] ?? '';
$language = $parentRow['language'] ?? DEFAULT_LANGUAGE;

// 1. Insert page (link + derived fields resolved in second pass)
$database->insertRow('{TP}pages', [
    'parent'            => $parent,
    'slug'              => null,
    'description'       => '',
    'keywords'          => '',
    'admin_users'       => '',
    'viewing_users'     => '',
    'target'            => '_top',
    'page_title'        => $title,
    'menu_title'        => $title,
    'template'          => $template,
    'visibility'        => $visibility,
    'visibility_backup' => '',
    'position'          => $position,
    'menu'              => 1,
    'language'          => $language,
    'searching'         => 1,
    'modified_when'     => time(),
    'modified_by'       => $admin->get_user_id(),
    'admin_groups'      => implode(',', (array) $admin_groups),
    'viewing_groups'    => implode(',', (array) $viewing_groups),
]);

if ($database->hasError()) $errors[] = $database->getError();

if ($errors) {
    $admin->print_error(implode('<br>', $errors));
}

$page_id = $database->getLastInsertId();

// 2. Resolve derived fields now that page_id is known
$database->upsertRow('{TP}pages', 'page_id', [
    'page_id'     => $page_id,
    'link'        => $link,
    'root_parent' => root_parent($page_id),
    'level'       => level_count($page_id),
    'page_trail'  => get_page_trail($page_id),
]);

if ($database->hasError()) $errors[] = $database->getError();

// 3. Insert first section
$database->insertRow('{TP}sections', [
    'page_id'  => $page_id,
    'position' => 1,
    'block'    => 1,
    'module'   => $module,
]);

if ($database->hasError()) $errors[] = $database->getError();

$section_id = $database->getLastInsertId();
if (!$section_id)  $errors[] = $database->getError();

// ── Filesystem only if everything succeeded ───────────────────────────────────

if ($errors) {
    // Rollback: remove the orphaned page row
    if ($page_id) $database->deleteRow('{TP}pages', 'page_id', $page_id);
    $admin->print_error(implode('<br>', $errors));
}

if ($visibility !== 'none') {
    create_access_file($filename, $page_id, level_count($page_id));
}

// Run module add.php if present
if (file_exists($moduleAddFile = WB_PATH . '/modules/' . $module . '/add.php')) {
    require $moduleAddFile;
}

$admin->print_success($MESSAGE['PAGES_ADDED'], ADMIN_URL . '/pages/modify.php?page_id=' . $page_id);
$admin->print_footer();