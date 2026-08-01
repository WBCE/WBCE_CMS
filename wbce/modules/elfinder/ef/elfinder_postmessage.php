<?php
/**
 * modules/elfinder/ef/elfinder_postmessage.php
 *
 * Generic elFinder popup bridge for editors that don't need (or shouldn't
 * depend on) CKEditor's or TinyMCE's own callback protocols - talks back to
 * the opener via a plain postMessage only: { wbceMediaPick: true, url,
 * title }. Sibling to elfinder_cke.php and elfinder_tinymce.php, which
 * exist because those editors' OWN file-browser hooks expect their specific
 * callback conventions; this one is for anything that can just listen for
 * a message event, which is what include/PlainMDE and modules/tiptap_editor
 * both do. First built for PlainMDE (see git history), then generalized
 * here once modules/tiptap_editor needed to drop its CKEditor-callback
 * impersonation of elfinder_cke.php too - one shared, maintained bridge
 * instead of the same logic duplicated per editor.
 *
 * Optional ?select=<absolute file URL> jumps straight to the file's real
 * folder and highlights it, so an editor can reopen elFinder scoped to a
 * file already referenced in the document instead of starting from the
 * root every time. This resolves the URL to elFinder's own (intentionally
 * opaque/undocumented) hash by constructing the SAME elFinder + volume
 * driver the live connector uses (via wbce-opts.php in this same
 * directory, so the two configs can't drift apart) and calling its real,
 * protected encode() method through Reflection - i.e. asking elFinder's
 * own code for the hash rather than reimplementing its algorithm here,
 * which would silently break on an elFinder update. Falls back to a plain
 * filename search if resolution doesn't work out for any reason (file
 * outside this volume, moved, etc).
 */
$configPath = realpath(dirname(__FILE__) . '/../../../config.php');
if (!$configPath || !file_exists($configPath)) { die('Access denied'); }
require_once $configPath;

require_once WB_PATH . '/framework/class.admin.php';
$admin = new Admin('Pages', 'pages_modify', false, false);
if (!$admin->is_authenticated()) { die('Access denied'); }

$efBase  = WB_URL . '/modules/elfinder/ef';
$efPath  = __DIR__;
$connUrl = $efBase . '/php/connector.wbce.php';
$lang    = (defined('LANGUAGE') && strtoupper(LANGUAGE) === 'DE') ? 'de' : 'en';
$select  = isset($_GET['select']) ? trim((string) $_GET['select']) : '';

$folderHash = '';
$fileHash   = '';
$searchTerm = '';

if ($select !== '') {
    require_once $efPath . '/php/autoload.php';
    require_once $efPath . '/wbce-opts.php';

    $mediaAdmin = new Admin('Media', 'media_view', false, false);
    $opts = elfinder_wbce_build_opts($mediaAdmin);

    if ($opts !== false) {
        $root     = $opts['roots'][0];
        $rootPath = rtrim($root['path'], '/');
        $rootUrlPath = parse_url(rtrim($root['URL'], '/'), PHP_URL_PATH);
        $selectUrlPath = parse_url($select, PHP_URL_PATH);

        if ($rootUrlPath !== null && $selectUrlPath !== null && strpos($selectUrlPath, $rootUrlPath) === 0) {
            $relative = ltrim(substr($selectUrlPath, strlen($rootUrlPath)), '/');
            $absFile  = $rootPath . '/' . $relative;

            if (is_file($absFile)) {
                try {
                    $elfinder = new elFinder($opts);
                    $volumesProp = new ReflectionProperty($elfinder, 'volumes');
                    $volumesProp->setAccessible(true);
                    $volume = reset($volumesProp->getValue($elfinder));

                    if ($volume) {
                        $encode = new ReflectionMethod($volume, 'encode');
                        $encode->setAccessible(true);
                        // realpath() canonicalizes separators/case the same
                        // way the volume driver's own root path went through
                        // normpathCE() during mount() - encode() strips the
                        // root by a string-prefix match internally, so a
                        // stray forward-vs-backslash mismatch here (Windows)
                        // would make it fail to recognize the file as being
                        // under the root at all and hash the raw absolute
                        // path instead of the intended root-relative one.
                        $realFile = realpath($absFile);
                        $fileHash   = $encode->invoke($volume, $realFile);
                        $folderHash = $encode->invoke($volume, dirname($realFile));
                    }
                } catch (Throwable $e) {
                    $fileHash = $folderHash = '';
                }
            }
        }
    }

    if ($fileHash === '') {
        $searchTerm = basename(parse_url($select, PHP_URL_PATH) ?: $select);
    }
}

?><!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang, ENT_QUOTES) ?>">
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
    var folderHash = <?= json_encode($folderHash) ?>;
    var fileHash   = <?= json_encode($fileHash) ?>;
    var searchTerm = <?= json_encode($searchTerm) ?>;

    // elFinder's startDir() - which decides what its very first "open"
    // request targets - checks window.location.hash BEFORE anything else,
    // including the startPathHash option below (elfinder.full.js's
    // startDir(): `if (locHash && locHash.match(/^#elf_/)) return
    // locHash.replace(...)`). This popup's window is reused by name
    // (window.open(url, "PlainMDEMedia"/"WbceTiptapMedia")) across repeat
    // clicks, and elFinder writes its own "#elf_<hash>" bookmark fragment
    // into the address bar on every navigation - so a STALE fragment left
    // over from a previous folder visited in this same popup window
    // silently overrides whatever folder we actually want to open this
    // time, no matter what we pass elFinder as an option. Clear it before
    // elFinder's own init call ever reads it.
    if (/^#elf_/.test(window.location.hash)) {
        history.replaceState(null, '', window.location.pathname + window.location.search);
    }

    $(function () {
        $('#elfinder').elfinder({
            url:       '<?= $connUrl ?>',
            lang:      '<?= $lang ?>',
            height:    '100%',
            resizable: false,
            // Officially supported way to target a specific folder on the
            // very first "open" request (see startDir() above) - avoids the
            // separate exec('open', folderHash) round trip this used to do
            // after the fact, which raced elFinder's own rememberLastDir
            // restore depending on network timing.
            startPathHash: folderHash || undefined,
            getFileCallback: function (file, fm) {
                var url = fm.convAbsUrl(file.url);
                if (window.opener) {
                    window.opener.postMessage(
                        { wbceMediaPick: true, url: url, title: file.name },
                        window.location.origin
                    );
                }
                fm.destroy();
                window.close();
            }
        });

        var fm = $('#elfinder').elfinder('instance');

        if (folderHash && fileHash) {
            // The initial open already lands on the right folder (via
            // startPathHash above) - just wait for it, then select/scroll
            // to the file once its node exists. elFinder's own selectfiles
            // handler looks the node up via this same cwdHash2Elm() and
            // just silently no-ops in a try/catch if it isn't there yet
            // (see elfinder.full.js's `.bind('... selectfiles ...')`
            // handler), so poll briefly rather than assume it's ready the
            // instant "open" resolves.
            var onOpen = function () {
                fm.unbind('open', onOpen);
                var tries = 0;
                (function trySelect() {
                    var node = fm.cwdHash2Elm(fileHash);
                    if (node && node.length) {
                        fm.trigger('selectfiles', { files: [fileHash] });
                        node.trigger('scrolltoview', { blink: true });
                    } else if (tries++ < 40) {
                        setTimeout(trySelect, 50);
                    }
                })();
            };
            fm.bind('open', onOpen);
        } else if (searchTerm) {
            var onOpen = function () {
                fm.unbind('open', onOpen);
                fm.exec('search', searchTerm);
            };
            fm.bind('open', onOpen);
        }
    });
}());
</script>
</body>
</html>
