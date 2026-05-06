<?php
require_once('storage.php');

function api_input($request) {
  $raw = file_get_contents('php://input');
  $ctype = $_SERVER['CONTENT_TYPE'] ?? '';

  if (stripos($ctype, 'application/json') !== false && $raw !== '') {
    $data = json_decode($raw, true);
    return is_array($data) ? $data : array();
  }

  if ((stripos($ctype, 'application/xml') !== false || stripos($ctype, 'text/xml') !== false) && $raw !== '') {
    libxml_use_internal_errors(true);
    $xml = simplexml_load_string($raw);
    if ($xml) {
      return json_decode(json_encode($xml), true);
    }
  }

  if (!empty($request['put'])) {
    return $request['put'];
  }
  if (!empty($request['post'])) {
    return $request['post'];
  }
  return array();
}

function api_json($data, $code = 200) {
  $status = array(
    200 => 'HTTP/1.1 200 OK',
    201 => 'HTTP/1.1 201 Created',
    400 => 'HTTP/1.1 400 Bad Request',
    401 => 'HTTP/1.1 401 Unauthorized',
    403 => 'HTTP/1.1 403 Forbidden',
    404 => 'HTTP/1.1 404 Not Found'
  );
  return array(
    'headers' => array($status[$code] ?? $status[200], 'Content-Type' => 'application/json; charset=' . conf('charset')),
    'entity' => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
  );
}

// POST /api/profile - создание новой анкеты. Возвращает логин, пароль и ссылку профиля.
function api_post($request) {
  list($form, $errors) = validate_form_data(api_input($request), true);
  if ($errors) {
    return api_json(array('ok' => false, 'errors' => $errors), 400);
  }

  $user = user_create($form);
  return api_json(array(
    'ok' => true,
    'message' => 'Анкета сохранена.',
    'login' => $user['login'],
    'password' => $user['password'],
    'profile' => url('profile/' . $user['id']),
    'user' => public_user($user)
  ), 201);
}

// PUT /api/profile/{id} - изменение анкеты авторизованным пользователем.
function api_put($request, $id) {
  $user = user_load($id);
  if (!$user) {
    return api_json(array('ok' => false, 'errors' => array('profile' => 'Профиль не найден.')), 404);
  }
  if (empty($request['user']) || intval($request['user']['id']) !== intval($id)) {
    return api_json(array('ok' => false, 'errors' => array('auth' => 'Можно редактировать только свой профиль.')), 403);
  }

  list($form, $errors) = validate_form_data(api_input($request), true);
  if ($errors) {
    return api_json(array('ok' => false, 'errors' => $errors), 400);
  }

  $user = user_update($id, $form);
  return api_json(array(
    'ok' => true,
    'message' => 'Данные обновлены.',
    'profile' => url('profile/' . $user['id']),
    'user' => public_user($user)
  ));
}
