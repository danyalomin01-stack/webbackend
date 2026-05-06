<?php

// Простое хранение данных в JSON-файле. Для учебной работы так проще запускать без настройки MySQL.
define('DATA_FILE', __DIR__ . '/data/users.json');

function storage_init() {
  $dir = dirname(DATA_FILE);
  if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
  }
  if (!file_exists(DATA_FILE)) {
    file_put_contents(DATA_FILE, json_encode(array('last_id' => 0, 'users' => array()), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
  }
}

function storage_read() {
  storage_init();
  $json = file_get_contents(DATA_FILE);
  $data = json_decode($json, true);
  if (!is_array($data)) {
    $data = array('last_id' => 0, 'users' => array());
  }
  if (!isset($data['users']) || !is_array($data['users'])) {
    $data['users'] = array();
  }
  if (!isset($data['last_id'])) {
    $data['last_id'] = count($data['users']);
  }
  return $data;
}

function storage_write($data) {
  storage_init();
  file_put_contents(DATA_FILE, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), LOCK_EX);
}

function generate_login($id) {
  return 'user' . $id;
}

function generate_password($len = 8) {
  $chars = 'abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
  $pass = '';
  for ($i = 0; $i < $len; $i++) {
    $pass .= $chars[random_int(0, strlen($chars) - 1)];
  }
  return $pass;
}

function all_powers() {
  return array(
    'immortality' => 'Бессмертие',
    'noclip' => 'Прохождение сквозь стены',
    'levitation' => 'Левитация'
  );
}

function normalize_form_data($data) {
  $data = is_array($data) ? $data : array();
  if (isset($data['powers']) && !is_array($data['powers'])) {
    $data['powers'] = array($data['powers']);
  }
  if (!isset($data['powers'])) {
    $data['powers'] = array();
  }
  return array(
    'name' => trim($data['name'] ?? ''),
    'email' => trim($data['email'] ?? ''),
    'year' => trim((string)($data['year'] ?? '')),
    'gender' => trim($data['gender'] ?? ''),
    'limbs' => trim((string)($data['limbs'] ?? '')),
    'powers' => array_values(array_unique(array_map('trim', $data['powers']))),
    'bio' => trim($data['bio'] ?? ''),
    'contract' => !empty($data['contract']) ? '1' : ''
  );
}

function validate_form_data($data, $need_contract = true) {
  $data = normalize_form_data($data);
  $errors = array();

  if ($data['name'] === '' || !preg_match('/^[а-яёa-z\s\-]{2,100}$/iu', $data['name'])) {
    $errors['name'] = 'Введите имя: только буквы, пробелы и дефис, от 2 до 100 символов.';
  }
  if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Введите корректный email.';
  }
  $year = intval($data['year']);
  $cur = intval(date('Y'));
  if ($year < 1900 || $year > $cur) {
    $errors['year'] = 'Год рождения должен быть от 1900 до текущего года.';
  }
  if (!in_array($data['gender'], array('male', 'female'), true)) {
    $errors['gender'] = 'Выберите пол.';
  }
  if (!in_array($data['limbs'], array('1', '2', '3', '4'), true)) {
    $errors['limbs'] = 'Выберите количество конечностей.';
  }
  $allowed = array_keys(all_powers());
  foreach ($data['powers'] as $p) {
    if (!in_array($p, $allowed, true)) {
      $errors['powers'] = 'Выбрана неверная сверхспособность.';
      break;
    }
  }
  if ($data['bio'] === '') {
    $errors['bio'] = 'Заполните биографию.';
  }
  if ($need_contract && $data['contract'] !== '1') {
    $errors['contract'] = 'Нужно согласиться с контрактом.';
  }

  return array($data, $errors);
}

function user_create($form) {
  $data = storage_read();
  $id = $data['last_id'] + 1;
  $login = generate_login($id);
  $password = generate_password();
  $data['last_id'] = $id;
  $data['users'][(string)$id] = array(
    'id' => $id,
    'login' => $login,
    'password' => $password,
    'form' => $form,
    'created_at' => date('c'),
    'updated_at' => date('c')
  );
  storage_write($data);
  return $data['users'][(string)$id];
}

function user_update($id, $form) {
  $data = storage_read();
  $key = (string)intval($id);
  if (!isset($data['users'][$key])) {
    return false;
  }
  $data['users'][$key]['form'] = $form;
  $data['users'][$key]['updated_at'] = date('c');
  storage_write($data);
  return $data['users'][$key];
}

function user_load($id) {
  $data = storage_read();
  $key = (string)intval($id);
  return $data['users'][$key] ?? false;
}

function user_load_by_login($login) {
  $data = storage_read();
  foreach ($data['users'] as $u) {
    if ($u['login'] === $login) {
      return $u;
    }
  }
  return false;
}

function public_user($user) {
  return array(
    'id' => $user['id'],
    'login' => $user['login'],
    'profile' => url('profile/' . $user['id'])
  );
}
