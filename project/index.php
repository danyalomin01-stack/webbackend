<?php
include('./settings.php');

// Выключаем отображение ошибок после отладки.
ini_set('display_errors', DISPLAY_ERRORS);
//error_reporting(E_ALL & E_STRICT);
//ini_set("mysql.trace_mode","On");

// Папки со скриптами и модулями.
ini_set('include_path', INCLUDE_PATH);



include('init.php');

// Определяем путь внутри проекта. Это нужно, потому что на учебном сервере
// проект обычно лежит не в корне сайта, а в подпапке вроде /otch/project/.
if (isset($_GET['q'])) {
  $path = trim($_GET['q'], '/');
}
else {
  $uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $base_path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
  if ($base_path != '' && $base_path != '/' && strpos($uri_path, $base_path) === 0) {
    $uri_path = substr($uri_path, strlen($base_path));
  }
  $path = trim($uri_path, '/');
  if ($path == 'index.php') {
    $path = '';
  }
}
$request = array(
  'url' => $path,
  'method' => isset($_POST['method']) && in_array(strtolower($_POST['method']), array('get', 'post', 'put', 'delete')) ? strtolower($_POST['method']) : strtolower($_SERVER['REQUEST_METHOD']),
  'get' => !empty($_GET) ? $_GET : array(),
  'post' => !empty($_POST) ? $_POST : array(),
  'put' => !empty($_POST) && !empty($_POST['method']) && strtolower($_POST['method']) == 'put' ? $_POST : array(),
  'delete' => !empty($_POST) && !empty($_POST['method']) && strtolower($_POST['method']) == 'delete' ? $_POST : array(),
  'Content-Type' => 'text/html',
);

$response = init($request, $urlconf);

if (!empty($response['headers'])) {
  foreach ($response['headers'] as $key => $value) {
    if (is_string($key)) {
      header(sprintf('%s: %s', $key, $value));
    }
    else {
      header($value);
    }
  }
}

if (!empty($response['entity'])) {
  print($response['entity']);
}
