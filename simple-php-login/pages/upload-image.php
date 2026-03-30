<?php

declare(strict_types=1);

session_start();

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['is_admin'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (empty($_FILES['file']) || !is_array($_FILES['file'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No file uploaded']);
    exit;
}

$file = $_FILES['file'];
if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['error' => 'Upload failed']);
    exit;
}

$tmpName = (string) ($file['tmp_name'] ?? '');
$maxSize = 5 * 1024 * 1024;
if ((int) ($file['size'] ?? 0) > $maxSize) {
    http_response_code(400);
    echo json_encode(['error' => 'File too large (max 5MB)']);
    exit;
}

$allowedMimeTypes = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/gif' => 'gif',
    'image/webp' => 'webp',
];

$imageInfo = @getimagesize($tmpName);
$mimeType = is_array($imageInfo) && isset($imageInfo['mime'])
    ? strtolower((string) $imageInfo['mime'])
    : '';

if (!isset($allowedMimeTypes[$mimeType])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid image type']);
    exit;
}

$uploadsDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'uploads';
if (!is_dir($uploadsDir) && !mkdir($uploadsDir, 0775, true) && !is_dir($uploadsDir)) {
    http_response_code(500);
    echo json_encode(['error' => 'Cannot create upload directory']);
    exit;
}

$ext = $allowedMimeTypes[$mimeType];
$fileName = 'img_' . date('Ymd_His') . '_' . bin2hex(random_bytes(5)) . '.' . $ext;
$targetPath = $uploadsDir . DIRECTORY_SEPARATOR . $fileName;

if (!move_uploaded_file($tmpName, $targetPath)) {
    http_response_code(500);
    echo json_encode(['error' => 'Cannot move uploaded file']);
    exit;
}

echo json_encode(['location' => '/uploads/' . $fileName]);
