<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @author    Christian M. Stefan (https://www.wbEasy.de)
 * @license   GNU/GPL v2 https://www.gnu.org/licenses/gpl-2.0.html
 * 
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * 
 * wbePwGen — WBCE backend setup helper
 *
 * Enqueues the widget assets via WBCE's I:: asset manager and exposes
 * $wpg_labels (localised strings ready for json_encode).
 *
 * Usage in any WBCE module backend file:
 *
 *   require_once INCLUDE_PATH . '/wbePwGen/wbce_setup.php';
 *
 *   // Then in your template / HTML output:
 *   // <input type="password" id="my_pw" name="my_pw" minlength="12">
 *   // <div id="my-pw-wrap" class="wpg-wrap"></div>
 *   // <script>
 *   //   WbePwGen.attach('my_pw', 'my-pw-wrap', <?php echo json_encode($wpg_labels); ?>);
 *   // </script>
 */

if (!defined('WB_PATH')) { http_response_code(403); exit; }

/* Load localised labels */
require_once __DIR__ . '/i18n.php';   // defines $wpg_labels

/* Enqueue assets once */
$_wpg_base = WB_URL . '/include/wbePwGen/';

I::insertCssFile($_wpg_base . 'wbePwGen.css');
I::insertJsFile($_wpg_base  . 'wbePwGen.js', 'BODY BTM-');

unset($_wpg_base);
