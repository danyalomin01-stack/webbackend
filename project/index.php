<?php
require_once __DIR__ . '/storage.php';

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base = base_url();
$path = $uri;
if ($base && strpos($path, $base) === 0) $path = substr($path, strlen($base));
$path = '/' . trim($path, '/');
if ($path === '/index.php') $path = '/';
$path = preg_replace('#^/index\.php#', '', $path);
if ($path === '') $path = '/';

if ($path === '/api/profile' && $method === 'POST') {
  [$form, $errors] = validate_form(read_input());
  if ($errors) json_response(['ok'=>false, 'errors'=>$errors], 400);
  $u = create_user($form);
  json_response(['ok'=>true, 'message'=>'Заявка сохранена.', 'login'=>$u['login'], 'password'=>$u['password'], 'profile'=>url_to('profile/'.$u['id'])], 201);
}
if (preg_match('#^/api/profile/(\d+)$#', $path, $m) && $method === 'PUT') {
  $id = intval($m[1]);
  $u = load_user($id);
  if (!$u) json_response(['ok'=>false, 'errors'=>['profile'=>'Профиль не найден.']], 404);
  $auth = auth_user();
  if (!$auth || intval($auth['id']) !== $id) {
    header('WWW-Authenticate: Basic realm="profile"');
    json_response(['ok'=>false, 'errors'=>['auth'=>'Нужно войти под логином и паролем этого профиля.']], 401);
  }
  [$form, $errors] = validate_form(read_input());
  if ($errors) json_response(['ok'=>false, 'errors'=>$errors], 400);
  update_user($id, $form);
  json_response(['ok'=>true, 'message'=>'Данные обновлены.', 'profile'=>url_to('profile/'.$id)]);
}
if (preg_match('#^/profile/(\d+)$#', $path, $m)) {
  $id = intval($m[1]);
  $profile = load_user($id);
  if (!$profile) { http_response_code(404); echo 'Профиль не найден'; exit; }
  $auth = auth_user();
  if (!$auth || intval($auth['id']) !== $id) {
    header('WWW-Authenticate: Basic realm="profile"'); http_response_code(401); echo 'Нужна авторизация'; exit;
  }
  $editMode = true; $profileId = $id; $formData = $profile['form'];
  include __DIR__ . '/page.php'; exit;
}

$message = ''; $errors = [];
if ($path === '/' && $method === 'POST') {
  [$form, $errors] = validate_form($_POST);
  if (!$errors) {
    $u = create_user($form);
    $message = 'Заявка сохранена. Логин: '.$u['login'].' Пароль: '.$u['password'].' Профиль: '.url_to('profile/'.$u['id']);
  }
}
if ($path === '/') { $editMode = false; $profileId = 0; $formData = []; include __DIR__ . '/page.php'; exit; }
http_response_code(404); echo '404 ресурс не найден';
