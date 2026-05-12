<?php
require_once __DIR__ . '/storage.php';

if (session_status() !== PHP_SESSION_ACTIVE) session_start();

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$base = base_url();
$path = $uri;
if ($base && strpos($path, $base) === 0) $path = substr($path, strlen($base));
$path = '/' . trim($path, '/');
$path = preg_replace('#^/index\.php#', '', $path);
if ($path === '') $path = '/';

if ($path === '/login.php') {
    require __DIR__ . '/login.php';
    exit;
}

function current_session_user() {
    if (empty($_SESSION['uid'])) return null;
    return load_user((int)$_SESSION['uid']);
}

function current_auth_user() {
    $sessionUser = current_session_user();
    if ($sessionUser) return $sessionUser;
    return auth_user_basic(); // на всякий случай оставлено для API-тестов через curl
}

// REST API: создание заявки
if ($path === '/api/profile' && $method === 'POST') {
    [$form, $errors] = validate_form(read_input());
    if ($errors) json_response(['ok'=>false, 'errors'=>$errors], 400);
    $u = create_user($form);
    json_response([
        'ok'=>true,
        'message'=>'Заявка сохранена.',
        'login'=>$u['login'],
        'password'=>$u['password'],
        'profile'=>url_to('profile/'.$u['id']),
        'login_url'=>url_to('login.php')
    ], 201);
}

// REST API: изменение заявки авторизованным пользователем
if (preg_match('#^/api/profile/(\d+)$#', $path, $m) && $method === 'PUT') {
    $id = intval($m[1]);
    $u = load_user($id);
    if (!$u) json_response(['ok'=>false, 'errors'=>['profile'=>'Профиль не найден.']], 404);
    $auth = current_auth_user();
    if (!$auth || intval($auth['id']) !== $id) {
        json_response(['ok'=>false, 'errors'=>['auth'=>'Сначала войдите через страницу входа.']], 401);
    }
    [$form, $errors] = validate_form(read_input());
    if ($errors) json_response(['ok'=>false, 'errors'=>$errors], 400);
    update_user($id, $form);
    json_response(['ok'=>true, 'message'=>'Данные обновлены.', 'profile'=>url_to('profile/'.$id)]);
}

// Страница профиля: теперь обычная форма входа, без браузерного Basic Auth окна
if (preg_match('#^/profile/(\d+)$#', $path, $m)) {
    $id = intval($m[1]);
    $profile = load_user($id);
    if (!$profile) { http_response_code(404); echo 'Профиль не найден'; exit; }

    $auth = current_session_user();
    if (!$auth || intval($auth['id']) !== $id) {
        header('Location: '.url_to('login.php').'?return='.rawurlencode(url_to('profile/'.$id)));
        exit;
    }

    $message = ''; $errors = [];
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
    exit;
}

// Обычная отправка без JS
$message = ''; $errors = [];
if ($path === '/' && $method === 'POST') {
    [$form, $errors] = validate_form($_POST);
    if (!$errors) {
        $u = create_user($form);
        $message = 'Заявка сохранена. Логин: '.$u['login'].' Пароль: '.$u['password'].' Профиль: '.url_to('profile/'.$u['id']);
    }
}

if ($path === '/') {
    $editMode = false;
    $profileId = 0;
    $formData = [];
    $sessionUser = current_session_user();
    include __DIR__ . '/page.php';
    exit;
}

http_response_code(404);
echo '404 ресурс не найден';
