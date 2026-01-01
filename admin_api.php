<?php
declare(strict_types=1);
session_start();
require_once 'config.php';

header('Content-Type: application/json; charset=utf-8');

/* ================= AUTH GUARD ================= */
// Yeh dashboard ko secure rakhta hai
if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    http_response_code(401);
    echo json_encode(['status' => 'unauthorized']);
    exit;
}

/* ================= HELPERS ================= */
function cleanPhone(string $phone): ?string {
    return preg_match('/^\d{10}$/', $phone) ? $phone : null;
}

function safeReadJson(string $file): array {
    $raw = @file_get_contents($file);
    if ($raw === false) return ['messages' => []];
    $data = json_decode($raw, true);
    return is_array($data) ? $data : ['messages' => []];
}

function safeWriteJson(string $file, array $data): bool {
    return file_put_contents($file, json_encode($data, JSON_UNESCAPED_UNICODE), LOCK_EX) !== false;
}

/* ================= ROUTER ================= */
$action = $_GET['action'] ?? '';

// FETCH CHAT
if ($action === 'fetch') {
    $phone = cleanPhone($_GET['phone'] ?? '');
    if (!$phone) { echo json_encode(['messages' => []]); exit; }
    $file = DATA_DIR . $phone . '.json';
    if (!is_file($file)) { echo json_encode(['messages' => []]); exit; }
    echo json_encode(safeReadJson($file));
    exit;
}

// REPLY TEXT
if ($action === 'reply' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = cleanPhone($_POST['phone'] ?? '');
    $msg = trim($_POST['msg'] ?? '');
    if (!$phone || $msg === '') { echo json_encode(['status' => 'error']); exit; }
    $file = DATA_DIR . $phone . '.json';
    $data = safeReadJson($file);
    $data['messages'][] = ['sender' => 'agent', 'type' => 'text', 'text' => htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'), 'time' => date('H:i')];
    safeWriteJson($file, $data);
    echo json_encode(['status' => 'ok']);
    exit;
}

// UPLOAD IMAGE
if ($action === 'upload' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = cleanPhone($_POST['phone'] ?? '');
    if (!$phone || !isset($_FILES['image'])) { echo json_encode(['status' => 'error']); exit; }
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    $fileName = time() . '_' . $_FILES['image']['name'];
    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
        $file = DATA_DIR . $phone . '.json';
        $data = safeReadJson($file);
        $data['messages'][] = ['sender' => 'agent', 'type' => 'image', 'url' => $uploadDir . $fileName, 'time' => date('H:i')];
        safeWriteJson($file, $data);
        echo json_encode(['status' => 'ok']);
    }
    exit;
}

// DELETE USER PERMANENT
if ($action === 'delete_user' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = cleanPhone($_POST['phone'] ?? '');
    $file = DATA_DIR . $phone . '.json';
    if ($phone && is_file($file)) {
        if (unlink($file)) { echo json_encode(['status' => 'ok']); }
        else { echo json_encode(['status' => 'error', 'message' => 'Permission error']); }
    } else { echo json_encode(['status' => 'error', 'message' => 'File not found']); }
    exit;
}

// CLEAR USER SCREEN
if ($action === 'clear' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = cleanPhone($_POST['phone'] ?? '');
    $file = DATA_DIR . $phone . '.json';
    if ($phone && is_file($file)) {
        $data = safeReadJson($file);
        $data['last_clear'] = count($data['messages']);
        safeWriteJson($file, $data);
        echo json_encode(['status' => 'ok']);
    }
    exit;
}

echo json_encode(['status' => 'invalid_action']);
