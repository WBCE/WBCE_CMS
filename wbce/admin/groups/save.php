<?php
/**
 * WBCE CMS — Groups save (OBSOLETE)
 *
 * Group saving is now handled by form.php (htmx endpoint).
 * This file exists only for backward compatibility with bookmarks/links.
 *
 * @deprecated 1.7.0 Use form.php instead
 */

require '../../config.php';
header('Location: ' . ADMIN_URL . '/groups/');
exit;
