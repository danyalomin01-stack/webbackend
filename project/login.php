<?php
require_once __DIR__ . '/storage.php';
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

$error = '';
$return = $_GET['return'] ?? $_POST['return'] ?? url_to('/');
if (!is_string($return) || $return === '' || preg_match('#^https?://#i', $return)) {
    $return = url_to('/');
}

if (isset($_GET['logout'])) {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
    header('Location: '.url_to('/'));
    exit;
}

if (!empty($_SESSION['uid'])) {
    $u = load_user((int)$_SESSION['uid']);
    if ($u) {
        header('Location: '.url_to('profile/'.$u['id']));
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $pass = trim($_POST['password'] ?? ($_POST['pass'] ?? ''));

    if ($login === '' || $pass === '') {
        $error = 'Введите логин и пароль.';
    } else {
        $u = load_user_by_login($login);
        if ($u && ($u['password'] ?? '') === $pass) {
            session_regenerate_id(true);
            $_SESSION['uid'] = (int)$u['id'];
            $_SESSION['login'] = $u['login'];
            header('Location: '.url_to('profile/'.$u['id']));
            exit;
        }
        $error = 'Неверный логин или пароль.';
    }
}

$base = htmlspecialchars(base_url(), ENT_QUOTES);
function h_login($v) { return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Вход — Drupal-coder</title>
  <link rel="stylesheet" href="<?= $base ?>/style.css">
  <style>
    body.login-page{min-height:100vh;background:#111827;display:flex;align-items:center;justify-content:center;padding:24px;color:#222;}
    .login-card{width:100%;max-width:430px;background:#fff;border-radius:18px;padding:34px;box-shadow:0 20px 60px rgba(0,0,0,.25)}
    .login-card h1{font-size:30px;margin:0 0 10px;color:#111;}
    .login-card p{margin:0 0 24px;color:#666;line-height:1.45;}
    .login-field{display:block;margin-bottom:16px;font-weight:700;color:#111;}
    .login-field input{display:block;width:100%;margin-top:7px;padding:13px 14px;border:1px solid #cfcfcf;border-radius:8px;font-size:16px;}
    .login-error{background:#fff1f0;border:1px solid #ffb4a9;color:#b42318;border-radius:8px;padding:12px;margin-bottom:16px;}
    .login-actions{display:flex;gap:12px;align-items:center;justify-content:space-between;margin-top:20px;}
    .login-back{color:#666;text-decoration:none;font-size:14px;}
    .login-back:hover{color:#f04d36;}
    @media(max-width:520px){.login-card{padding:26px}.login-actions{display:block}.login-actions .btn-primary{width:100%;margin-bottom:14px}}
  </style>
</head>
<body class="login-page">
  <main class="login-card">
    <h1>Войти</h1>
    <p>Введите логин и пароль, которые сайт выдал после отправки формы.</p>
    <?php if ($error): ?><div class="login-error"><?= h_login($error) ?></div><?php endif; ?>
    <form method="post" action="<?= $base ?>/login.php">
      <input type="hidden" name="return" value="<?= h_login($return) ?>">
      <label class="login-field">Логин
        <input name="login" autocomplete="username" value="<?= h_login($_POST['login'] ?? '') ?>" placeholder="user1">
      </label>
      <label class="login-field">Пароль
        <input name="password" type="password" autocomplete="current-password" placeholder="пароль из сообщения">
      </label>
      <div class="login-actions">
        <a class="login-back" href="<?= $base ?>/">← На главную</a>
        <button class="btn-primary" type="submit">Войти</button>
      </div>
    </form>
  </main>
</body>
</html>
