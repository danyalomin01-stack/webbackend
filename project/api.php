<?php
require_once __DIR__ . '/storage.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method === 'POST') {
    [$form, $errors] = validate_form(read_input());

    if ($errors) {
        json_response(['ok' => false, 'errors' => $errors], 400);
    }

    $u = create_user($form);

    json_response([
        'ok' => true,
        'message' => 'Заявка сохранена.',
        'login' => $u['login'],
        'password' => $u['password'],
        'profile' => url_to('profile.php?id=' . $u['id']),
        'login_url' => url_to('login.php')
    ], 201);
}

if ($method === 'PUT') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if (!$id) {
        json_response(['ok' => false, 'errors' => ['profile' => 'Не указан id профиля.']], 400);
    }

    $u = load_user($id);

    if (!$u) {
        json_response(['ok' => false, 'errors' => ['profile' => 'Профиль не найден.']], 404);
    }

    if (empty($_SESSION['uid']) || intval($_SESSION['uid']) !== $id) {
        json_response(['ok' => false, 'errors' => ['auth' => 'Сначала войдите.']], 401);
    }

    [$form, $errors] = validate_form(read_input());

    if ($errors) {
        json_response(['ok' => false, 'errors' => $errors], 400);
    }

    update_user($id, $form);

    json_response([
        'ok' => true,
        'message' => 'Данные обновлены.',
        'profile' => url_to('profile.php?id=' . $id)
    ]);
}

json_response([
    'ok' => false,
    'errors' => ['method' => 'Метод не поддерживается.']
], 405);
