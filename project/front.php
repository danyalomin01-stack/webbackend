<?php
require_once('storage.php');
require_once('api.php');

function years_options() {
  $years = array();
  for ($y = intval(date('Y')); $y >= 1900; $y--) {
    $years[] = $y;
  }
  return $years;
}

function render_form($form = array(), $errors = array(), $result = array(), $user = false) {
  $form = normalize_form_data($form);
  return theme('front', array(
    'form' => $form,
    'errors' => $errors,
    'result' => $result,
    'user' => $user,
    'powers' => all_powers(),
    'years' => years_options(),
    'action' => $user ? url('profile/' . $user['id']) : url(''),
    'api' => $user ? url('api/profile/' . $user['id']) : url('api/profile'),
    'method' => $user ? 'put' : 'post'
  ));
}

function front_get($request) {
  return render_form();
}

// Обычная отправка формы без JavaScript.
function front_post($request) {
  list($form, $errors) = validate_form_data($request['post'], true);
  if ($errors) {
    return render_form($form, $errors);
  }

  $user = user_create($form);
  return render_form($form, array(), array(
    'message' => 'Анкета сохранена. Запишите логин и пароль.',
    'login' => $user['login'],
    'password' => $user['password'],
    'profile' => url('profile/' . $user['id'])
  ));
}

function profile_get($request, $id) {
  $user = user_load($id);
  if (!$user) {
    return not_found();
  }
  if (empty($request['user']) || intval($request['user']['id']) !== intval($id)) {
    return access_denied();
  }
  return render_form($user['form'], array(), array('message' => 'Вы авторизованы. Можно изменить данные.'), $user);
}

// Fallback для браузера без JS: POST + hidden method=put.
function profile_put($request, $id) {
  $user = user_load($id);
  if (!$user) {
    return not_found();
  }
  if (empty($request['user']) || intval($request['user']['id']) !== intval($id)) {
    return access_denied();
  }

  list($form, $errors) = validate_form_data($request['put'], true);
  if ($errors) {
    return render_form($form, $errors, array(), $user);
  }

  $user = user_update($id, $form);
  return render_form($user['form'], array(), array('message' => 'Данные обновлены.'), $user);
}
