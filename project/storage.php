<?php
const DATA_FILE = __DIR__ . '/data/users.json';

function storage_init() {
    $dir = dirname(DATA_FILE);
    if (!is_dir($dir)) mkdir($dir, 0777, true);
    if (!file_exists(DATA_FILE)) {
        file_put_contents(DATA_FILE, json_encode(['last_id'=>0, 'users'=>[]], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
}
function storage_read() {
    storage_init();
    $data = json_decode(file_get_contents(DATA_FILE), true);
    return is_array($data) ? $data : ['last_id'=>0, 'users'=>[]];
}
function storage_write($data) {
    storage_init();
    file_put_contents(DATA_FILE, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), LOCK_EX);
}
function base_url() {
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    $dir = rtrim(str_replace('\\', '/', dirname($script)), '/');
    return ($dir === '' || $dir === '.') ? '' : $dir;
}
function url_to($path='') { return base_url() . '/' . ltrim($path, '/'); }
function read_input() {
    $raw = file_get_contents('php://input');
    $ctype = $_SERVER['CONTENT_TYPE'] ?? '';
    if ($raw && stripos($ctype, 'json') !== false) {
        $d = json_decode($raw, true);
        return is_array($d) ? $d : [];
    }
    if ($raw && stripos($ctype, 'xml') !== false) {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($raw);
        if ($xml) return json_decode(json_encode($xml), true);
    }
    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'PUT') {
        parse_str($raw, $d);
        return is_array($d) ? $d : [];
    }
    return $_POST;
}
function normalize_form($d) {
    return [
        'name' => trim($d['name'] ?? ''),
        'email' => trim($d['email'] ?? ''),
        'phone' => trim($d['phone'] ?? ''),
        'comment' => trim($d['comment'] ?? ($d['message'] ?? '')),
        'agree' => !empty($d['agree']) ? '1' : '',
    ];
}
function validate_form($d) {
    $d = normalize_form($d);
    $e = [];
    if ($d['name'] === '' || !preg_match('/^[а-яёa-z\s\-]{2,80}$/iu', $d['name'])) $e['name'] = 'Введите имя буквами.';
    if (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) $e['email'] = 'Введите корректный Email.';
    if ($d['phone'] === '' || !preg_match('/^\+?[0-9\s\-\(\)]{7,25}$/u', $d['phone'])) $e['phone'] = 'Введите корректный телефон.';
    if ($d['comment'] === '') $e['comment'] = 'Введите сообщение.';
    if ($d['agree'] !== '1') $e['agree'] = 'Нужно согласиться на обработку персональных данных.';
    return [$d, $e];
}
function new_password($len=8) {
    $chars='abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789'; $p='';
    for($i=0;$i<$len;$i++) $p .= $chars[random_int(0, strlen($chars)-1)];
    return $p;
}
function create_user($form) {
    $data = storage_read();
    $id = intval($data['last_id'] ?? 0) + 1;
    $user = [
        'id'=>$id,
        'login'=>'user'.$id,
        'password'=>new_password(),
        'form'=>$form,
        'created_at'=>date('c'),
        'updated_at'=>date('c')
    ];
    $data['last_id'] = $id;
    $data['users'][(string)$id] = $user;
    storage_write($data);
    return $user;
}
function load_user($id) { $d=storage_read(); return $d['users'][(string)intval($id)] ?? null; }
function load_user_by_login($login) { foreach ((storage_read()['users'] ?? []) as $u) if (($u['login'] ?? '') === $login) return $u; return null; }
function update_user($id, $form) {
    $d=storage_read(); $k=(string)intval($id);
    if (empty($d['users'][$k])) return null;
    $d['users'][$k]['form']=$form;
    $d['users'][$k]['updated_at']=date('c');
    storage_write($d);
    return $d['users'][$k];
}
function auth_user() {
    $login = $_SERVER['PHP_AUTH_USER'] ?? '';
    $pass = $_SERVER['PHP_AUTH_PW'] ?? '';
    $u = $login ? load_user_by_login($login) : null;
    return ($u && ($u['password'] ?? '') === $pass) ? $u : null;
}
function json_response($data, $code=200) {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}
