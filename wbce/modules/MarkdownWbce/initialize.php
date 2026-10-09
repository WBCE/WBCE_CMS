<?php
/**
 * MarkdownWbce — initialize.php
 *
 * Runs on every WBCE request (function = 'tool, initialize' in info.php),
 * both frontend and backend. WBCE core needs a Markdown parser regardless
 * of whether the doc-viewer/editor UI is ever opened, so ParsedownWbce is
 * registered here — not gated behind initialize_be.php — the same way
 * DynamicFields/EventCalendar/catalogue_hub register their always-needed
 * classes.
 */

defined('WB_PATH') or die('No direct access allowed');

defined('MDR_PATH') or define('MDR_PATH', __DIR__);
defined('MDR_URL')  or define('MDR_URL', WB_URL . '/modules/MarkdownWbce');

WbAuto::AddFile('Parsedown',     __DIR__ . '/Parsedown/Parsedown.php');
WbAuto::AddFile('ParsedownWbce', __DIR__ . '/Parsedown/ParsedownWbce.php');

// MdReaderLink also always available (FE+BE) — reader.php is Admin-session-gated
// regardless of what page embeds the link, so a frontend template/module can
// safely render a MdReaderLink::file(...)->linkHtml(...) trigger for a logged-in
// editor browsing the live site. MdReaderHelper (internal to reader.php/
// ajax_save_doc.php) stays backend-only in initialize_be.php — nothing
// frontend-facing calls it directly.
WbAuto::AddFile('MdReaderLink',   __DIR__ . '/MdReaderLink.php');
WbAuto::AddFile('MdReaderHelper', __DIR__ . '/MdReaderHelper.php');
