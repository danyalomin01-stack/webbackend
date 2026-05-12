<?php
require_once __DIR__ . '/storage.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$id) {
    http_response_code(404);
    echo 'Профиль не найден';
    exit;
}

$profile = load_user($id);
if (!$profile) {
    http_response_code(404);
    echo 'Профиль не найден';
    exit;
}

$auth = null;
if (!empty($_SESSION['uid'])) {
    $auth = load_user((int)$_SESSION['uid']);
}

if (!$auth || intval($auth['id']) !== $id) {
    header('Location: ' . url_to('login.php') . '?return=' . rawurlencode(url_to('profile.php?id=' . $id)));
    exit;
}

$message = '';
$errors = [];

if ($method === 'POST') {
    [$form, $errors] = validate_form($_POST);
    if (!$errors) {
        update_user($id, $form);
        $message = 'Данные обновлены.';
        $profile = load_user($id);
    }
}

$editMode = true;
$profileId = $id;
$formData = $profile['form'];
$sessionUser = $auth;
include __DIR__ . '/page.php';
