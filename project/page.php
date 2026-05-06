<?php
$base = htmlspecialchars(base_url(), ENT_QUOTES);
$v = function($name) use ($formData) { return htmlspecialchars($formData[$name] ?? '', ENT_QUOTES); };
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Drupal-coder</title>
  <link rel="stylesheet" href="<?= $base ?>/style.css">
</head>
<body>
  <main class="site-wrap">
    <picture>
      <source media="(max-width: 700px)" srcset="<?= $base ?>/assets/mobile.png">
      <img class="mock" src="<?= $base ?>/assets/page.png" alt="Drupal-coder">
    </picture>

    <form id="contactForm" class="contact-form" method="post" action="<?= $editMode ? $base.'/profile/'.$profileId : $base.'/' ?>" data-api="<?= $editMode ? $base.'/api/profile/'.$profileId : $base.'/api/profile' ?>" data-method="<?= $editMode ? 'PUT' : 'POST' ?>">
      <input name="name" placeholder="Ваше имя" value="<?= $v('name') ?>" required>
      <input name="phone" placeholder="Телефон" value="<?= $v('phone') ?>" required>
      <input name="email" type="email" placeholder="E-mail" value="<?= $v('email') ?>" required>
      <textarea name="comment" placeholder="Ваш комментарий" required><?= $v('comment') ?></textarea>
      <label class="agree"><input name="agree" type="checkbox" value="1" <?= !empty($formData['agree']) ? 'checked' : '' ?> required><span>Отправляя заявку, я даю согласие на обработку своих персональных данных.</span></label>
      <label class="captcha"><input type="checkbox" required><span>Я не робот</span></label>
      <button type="submit"><?= $editMode ? 'СОХРАНИТЬ' : 'СВЯЖИТЕСЬ С НАМИ' ?></button>
      <div id="result" class="result"><?php if (!empty($message)) echo htmlspecialchars($message); if (!empty($errors)) echo htmlspecialchars(implode(' ', $errors)); ?></div>
    </form>
  </main>
  <script src="<?= $base ?>/script.js"></script>
</body>
</html>
