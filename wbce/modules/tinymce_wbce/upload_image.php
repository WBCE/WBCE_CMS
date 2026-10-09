<?php
/**
 * tinymce_wbce — upload_image.php
 *
 * Receives a pasted clipboard image from TinyMCE's images_upload_handler and
 * stores it under MEDIA_DIRECTORY. Target folder is resolved per toolbar preset
 * (paste_images / paste_folder) and, when the logged-in user has a home folder
 * ({TP}users.home_folder), placed inside it:
 *   MEDIA_DIRECTORY[/{home_folder}]/{paste_folder}/<file>
 *
 * Returns { location: <absolute URL> } on success, { error: <msg> } otherwise.
 *
 * Security: backend auth required; only real images (getimagesize + MIME
 * allowlist) are accepted; the extension is derived from the detected type, not
 * the client filename; the folder setting is sanitized to safe path segments.
 *
 * @license GNU GPL2
 */

if (!defined('WB_PATH')) {
    $configPath = realpath(dirname(__FILE__) . '/../../config.php');
    if (!$configPath || !file_exists($configPath)) {
        http_response_code(403); exit('Access denied');
    }
    require_once $configPath;
}

require_once WB_PATH . '/framework/class.admin.php';
$admin = new Admin('Pages', 'pages_modify', false, false);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

if (!$admin->is_authenticated()) {
    http_response_code(403);
    exit(json_encode(['error' => 'auth']));
}

require_once __DIR__ . '/presets.php';

$presetId = ($_POST['preset'] ?? '') !== '' ? (string) $_POST['preset'] : null;
$settings = tinymce_wbce_paste_settings($presetId);
if (!$settings['enabled']) {
    http_response_code(403);
    exit(json_encode(['error' => 'disabled']));
}

$file = $_FILES['file'] ?? null;
if (!is_array($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
    http_response_code(400);
    exit(json_encode(['error' => 'upload']));
}

// Size limit (10 MB)
if (($file['size'] ?? 0) > 10 * 1024 * 1024) {
    http_response_code(400);
    exit(json_encode(['error' => 'too_large']));
}

// Must be a real image; extension comes from the detected type.
$allowed = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/gif'  => 'gif',
    'image/webp' => 'webp',
];
$info = @getimagesize($file['tmp_name']);
$mime = $info['mime'] ?? '';
if ($info === false || !isset($allowed[$mime])) {
    http_response_code(400);
    exit(json_encode(['error' => 'not_image']));
}
$ext = $allowed[$mime];

// Target folder: MEDIA[/home_folder]/paste_folder
$home   = trim((string) $admin->get_home_folder(), '/');   // '' when no home folder
$folder = $settings['folder'];                             // already sanitized
$relDir = '/' . ($home !== '' ? $home . '/' : '') . $folder;
$absDir = WB_PATH . MEDIA_DIRECTORY . $relDir;

if (!is_dir($absDir) && !@mkdir($absDir, 0755, true) && !is_dir($absDir)) {
    http_response_code(500);
    exit(json_encode(['error' => 'mkdir']));
}

// Filename: sanitized original stem (fallback 'image'), collision-safe.
$stem = media_filename(pathinfo((string) ($file['name'] ?? ''), PATHINFO_FILENAME));
$stem = trim($stem) !== '' ? $stem : 'image';

// First free filename for a given extension in the target dir.
$freeName = function (string $stem, string $ext) use ($absDir): string {
    $name = $stem . '.' . $ext; $i = 1;
    while (file_exists($absDir . '/' . $name)) { $name = $stem . '-' . $i . '.' . $ext; $i++; }
    return $name;
};

// #7 Optional WebP conversion (per preset) via claviska/SimpleImage. Falls back
// to storing the original untouched if the library/GD-WebP is unavailable or the
// conversion throws — a paste must never fail just because WebP didn't work.
$stored   = null;
$wantWebp = !empty($settings['webp']) && $mime !== 'image/webp';
if ($wantWebp) {
    $lib = WB_PATH . '/include/claviska/SimpleImage.php';
    if (is_file($lib)) {
        require_once $lib;
        try {
            $name = $freeName($stem, 'webp');
            $img  = new \claviska\SimpleImage();
            $img->fromFile($file['tmp_name']);
            $img->toFile($absDir . '/' . $name, 'image/webp', (int) ($settings['quality'] ?? 82));
            $stored = $name;
        } catch (\Throwable $e) {
            $stored = null; // fall through to a plain move
        }
    }
}

if ($stored === null) {
    $name = $freeName($stem, $ext);
    if (!move_uploaded_file($file['tmp_name'], $absDir . '/' . $name)) {
        http_response_code(500);
        exit(json_encode(['error' => 'move']));
    }
    $stored = $name;
}

if (function_exists('change_mode')) { change_mode($absDir . '/' . $stored); }

echo json_encode(['location' => WB_URL . MEDIA_DIRECTORY . $relDir . '/' . $stored]);
