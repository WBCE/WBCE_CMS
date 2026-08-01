<?php
/**
 * modules/elfinder/ef/wbce-opts.php
 *
 * Builds the elFinder connector $opts array (root/volume config, access
 * control, upload restrictions) for this WBCE install. Factored out of
 * connector.wbce.php so any other code that needs the SAME root
 * configuration - not to serve requests, but e.g. to resolve a real
 * filesystem path into elFinder's own opaque hash for a specific file
 * (see include/PlainMDE/elfinder-resolve.php) - can't silently drift out
 * of sync with what the live connector actually serves. Pure extraction,
 * no behavior change: connector.wbce.php calls this instead of building
 * the array inline.
 */
if (!defined('WB_PATH')) {
    header('Location: ../index.php', true, 301);
    exit;
}

if (!function_exists('wbce_filenames_ok')) {
    /**
     * Prevent uploads forbidden by WBCE's RENAME_FILES_ON_UPLOAD constant.
     * @param string $sFileName
     * @return boolean
     */
    function wbce_filenames_ok($sFileName)
    {
        $sForbidden = str_replace(",", "|", RENAME_FILES_ON_UPLOAD);

        if (preg_match("/\.($sForbidden)$/i", $sFileName)) {
            return false;
        }

        if (preg_match('/^[^\.].*$/', $sFileName)) {
            return true;
        }

        return false;
    }
}

if (!function_exists('access')) {
    /**
     * Disable and hide dot-starting files/folders (except the volume root).
     * @param string $attr
     * @param string $path
     * @param string $data
     * @param object $volume
     * @param bool|null $isDir
     * @param string $relpath
     * @return bool|null
     */
    function access($attr, $path, $data, $volume, $isDir, $relpath)
    {
        $basename = basename($path);
        return $basename[0] === '.'
        && strlen($relpath) !== 1
            ? !($attr == 'read' || $attr == 'write')
            : null;
    }
}

if (!function_exists('elfinder_wbce_build_opts')) {
    /**
     * @param object $admin  Already-permission-checked Admin instance
     * @return array|false   elFinder $opts array, or false if $admin lacks media_view
     */
    function elfinder_wbce_build_opts($admin)
    {
        if ($admin->get_permission('media_view') !== true) {
            return false;
        }

        $sForbiddenRegex = "/(\." . str_replace(",", "|\.", RENAME_FILES_ON_UPLOAD) . ")$/";

        $noup = $norn = $norm = $nomk = $nopa = $noco = $nodu = $noex = $nore = '';
        if ($admin->get_permission('media_upload') === false) {
            $noup = 'upload';
            $nopa = 'paste';
            $noco = 'copy';
            $nodu = 'duplicate';
            $noex = 'extract';
            $nore = 'resize';
        }
        if ($admin->get_permission('media_rename') === false) {
            $norn = 'rename';
        }
        if ($admin->get_permission('media_delete') === false) {
            $norm = 'rm';
        }
        if ($admin->get_permission('media_create') === false) {
            $nomk = 'mkdir';
        }

        $root = array(
            'driver'        => 'LocalFileSystem',
            'quarantine'    => WB_PATH . '/var/modules/elfinder/.quarantine',
            'tmbPath'       => WB_PATH . '/var/modules/elfinder/.tmb',
            'tmbURL'        => WB_URL . '/var/modules/elfinder/.tmb',
            'winHashFix'    => DIRECTORY_SEPARATOR !== '/',
            'accessControl' => 'access',
            'acceptedName'  => 'wbce_filenames_ok',
            'attributes'    => array(
                array(
                    'pattern' => $sForbiddenRegex,
                    'read'    => false,
                    'write'   => false,
                    'locked'  => true,
                    'hidden'  => true,
                ),
            ),
            'disabled' => array(
                'hide', 'empty', 'netmount', 'help', 'preference', 'mkfile', 'edit',
                $noup, $norn, $norm, $nomk, $nopa, $noco, $nodu, $noex, $nore,
            ),
        );

        if (empty($_SESSION['HOME_FOLDER'])) {
            $root['path'] = WB_PATH . MEDIA_DIRECTORY . '/';
            $root['URL']  = WB_URL . MEDIA_DIRECTORY . '/';
        } else {
            $root['alias'] = 'Home (' . $_SESSION['HOME_FOLDER'] . ')';
            $root['path']  = WB_PATH . MEDIA_DIRECTORY . '/' . $_SESSION['HOME_FOLDER'];
            $root['URL']   = WB_URL . MEDIA_DIRECTORY . '/' . $_SESSION['HOME_FOLDER'];
        }

        return array('debug' => false, 'roots' => array($root));
    }
}
