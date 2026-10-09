<?php
/**
 * tinymce_wbce — elfinder_tinymce.php
 * elFinder popup bridge for TinyMCE file_picker_callback.
 * Simplified per Florian's recommendation: elFinder 2.1.68+ needs no theme.
 * @author  SC-Peet
 * @license GNU GPL2
 */

$configPath = realpath(dirname(__FILE__) . '/../../config.php');
if (!$configPath || !file_exists($configPath)) { die('Access denied'); }
require_once $configPath;

require_once WB_PATH . '/framework/class.admin.php';
$admin = new Admin('Pages', 'pages_modify', false, false);
if (!$admin->is_authenticated()) { die('Access denied'); }

$efBase  = WB_URL . '/modules/elfinder/ef';
$connUrl = $efBase . '/php/connector.wbce.php';
$lang    = (defined('LANGUAGE') && strtoupper(LANGUAGE) === 'DE') ? 'de' : 'en';

?><!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mediathek</title>

    <script src="<?= WB_URL ?>/include/jquery/jquery-min.js"></script>
    <script src="<?= WB_URL ?>/include/jquery/jquery-ui-min.js"></script>
    <link rel="stylesheet" href="<?= WB_URL ?>/include/jquery/jquery-ui.min.css">

    <style>body { margin: 0; } #elfinder { height: 100vh; }</style>

    <script src="<?= $efBase ?>/js/elfinder.min.js"></script>
</head>
<body>
<div id="elfinder"></div>
<script>
(function () {
    var cbName = (function () {
        var m = window.location.search.match(/[?&]callback=([^&]+)/);
        return m ? decodeURIComponent(m[1]) : null;
    })();

    $(function () {
        $('#elfinder').elfinder({
            url:       '<?= $connUrl ?>',
            lang:      '<?= $lang ?>',
            height:    '100%',
            resizable: false,
            getFileCallback: function (file, fm) {
                var url = fm.convAbsUrl(file.url);
                if (cbName && window.opener && typeof window.opener[cbName] === 'function') {
                    window.opener[cbName](url, { title: file.name });
                }
                if (window.opener) {
                    window.opener.postMessage(
                        { mceAction: 'fileSelected', url: url, title: file.name },
                        window.location.origin
                    );
                }
                fm.destroy();
                window.close();
            }
        });
    });
}());
</script>
</body>
</html>
