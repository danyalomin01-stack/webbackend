<?php
require_once('storage.php');

function auth(&$request, $r) {
  if (empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW'])) {
    return auth_required();
  }

  $user = user_load_by_login($_SERVER['PHP_AUTH_USER']);
  if (!$user || $_SERVER['PHP_AUTH_PW'] !== $user['password']) {
    return auth_required();
  }

  $request['user'] = $user;
  return null;
}

function auth_required() {
  return array(
    'headers' => array(
      sprintf('WWW-Authenticate: Basic realm="%s"', conf('sitename')),
      'HTTP/1.1 401 Unauthorized'
    ),
    'entity' => theme('401', array()),
  );
}
