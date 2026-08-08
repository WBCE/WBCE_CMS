<?php
/**
 * WBCE CMS AdminTool: wbSeoTool
 *
 * ajax/save.php
 * HTMX endpoint: saves a single inline-edited SEO field for one page.
 *
 * Security model: the page_id is never trusted from the request body.
 * It comes only from redeeming the one-time `idkey` that PageTree::load()
 * issued for this exact node — meaning it was only handed to the browser
 * for pages the current admin already had canModifyPage = true on when the
 * tree was rendered. checkIDKEY(..., $ajax = true) does not invalidate
 * sibling keys, so every other editable cell on the page stays usable.
 *
 * @package     wbSeoTool
 * @author      Christian M. Stefan (https://www.wbEasy.de/)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 */

declare(strict_types=1);

require '../../../config.php';

/** @var Database $database */

$admin = new Admin('Pages', 'pages_modify', false);

$pageId = (int) $admin->checkIDKEY('idkey', 0, 'POST', true);
if ($pageId <= 0) {
    http_response_code(403);
    exit('invalid or expired token');
}

$field = (string) ($_POST['field'] ?? '');

$allowedFields = ['page_title', 'description', 'keywords'];

$aSettings = json_decode((string) Settings::get('seo_cfg', '{}'), true) ?: [];

if (!empty($aSettings['menuTitleConfig']['use'])) {
    $allowedFields[] = 'menu_title';
}

if (!empty($aSettings['rewriteUrl']['use']) && !empty($aSettings['rewriteUrl']['dbString'])) {
    $candidate = (string) $aSettings['rewriteUrl']['dbString'];
    if (preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $candidate) && $database->fieldExists('{TP}pages', $candidate)) {
        $allowedFields[] = $candidate;
    }
}

if (!in_array($field, $allowedFields, true)) {
    http_response_code(400);
    exit('unknown field');
}

$value = trim(str_replace("\r", '', (string) ($_POST['value'] ?? '')));

$database->query(
    "UPDATE `{TP}pages` SET `{$field}` = ? WHERE `page_id` = ?",
    [$value, $pageId]
);

if ($database->hasError()) {
    http_response_code(500);
    exit('save failed');
}

echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
