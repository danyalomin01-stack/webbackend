<?php
$form = $c['form'];
$errors = $c['errors'];
$result = $c['result'];
$user = $c['user'];
function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function err($errors, $name) { return !empty($errors[$name]) ? '<div class="error">' . h($errors[$name]) . '</div>' : ''; }
?>

<header class="header">
  <div class="container nav">
    <a href="<?php print h(url('')); ?>" class="logo">Drupal<span>-coder</span></a>
    <nav class="menu">
      <a href="#services">Администрирование</a>
      <a href="#features">Продвижение</a>
      <a href="#price">Тарифы</a>
      <a href="#form">Связаться</a>
    </nav>
  </div>
</header>

<section class="hero">
  <div class="container hero-grid">
    <div>
      <h1>Поддержка сайтов на Drupal</h1>
      <p>Сопровождение и поддержка сайтов на CMS Drupal любых версий и запущенности. Это страница из прошлого семестра, дополненная backend-формой и REST веб-сервисом.</p>
      <a class="btn-primary" href="#form">Связаться с нами</a>
    </div>
    <div class="stats">
      <div class="stat"><b>#1</b>Drupal-разработчик в России по версии рейтинга</div>
      <div class="stat"><b>3+</b>средний опыт специалистов более 3 лет</div>
      <div class="stat"><b>14</b>лет опыта в сфере Drupal</div>
      <div class="stat"><b>300+</b>проектов на поддержке</div>
    </div>
  </div>
</section>

<section class="section" id="services">
  <div class="container">
    <h2>Что мы делаем</h2>
    <p class="section-lead">Небольшая статическая часть фронтенда, чтобы работа выглядела как продолжение сайта с прошлого семестра.</p>
    <div class="cards">
      <div class="card"><div class="num">1</div><h3>Администрирование</h3><p>Миграция, бэкапы, аудит безопасности, оптимизация скорости и переезд на HTTPS.</p></div>
      <div class="card"><div class="num">2</div><h3>Поддержка</h3><p>Исправление ошибок, обновление модулей, помощь с контентом и настройками сайта.</p></div>
      <div class="card"><div class="num">3</div><h3>Развитие</h3><p>Доработка интерфейса, новые блоки, формы, интеграции и простая аналитика.</p></div>
    </div>
  </div>
</section>

<section class="section" id="features" style="background:#fff;">
  <div class="container">
    <h2>Почему удобно</h2>
    <div class="cards">
      <div class="card"><h3>Работа без фреймворка</h3><p>Backend сделан на обычном PHP и учебном роутере, как требуется в задании.</p></div>
      <div class="card"><h3>AJAX без перезагрузки</h3><p>Если JavaScript включен, форма отправляется через Fetch и показывает ответ сразу.</p></div>
      <div class="card"><h3>Fallback</h3><p>Если JavaScript отключен, обычная отправка формы всё равно сохраняет данные.</p></div>
    </div>
  </div>
</section>

<section class="section" id="price">
  <div class="container">
    <h2>Тарифы</h2>
    <p class="section-lead">Для учебного проекта оставлены простые карточки, а реальная логика находится в форме ниже.</p>
    <div class="cards">
      <div class="card"><h3>Старт</h3><p>Базовая поддержка сайта и консультации.</p></div>
      <div class="card"><h3>Бизнес</h3><p>Регулярные доработки, обновления и контроль ошибок.</p></div>
      <div class="card"><h3>Про</h3><p>Поддержка, аудит, оптимизация скорости и развитие проекта.</p></div>
    </div>
  </div>
</section>

<section class="dark-form" id="form">
  <div class="container form-wrap">
    <div>
      <h2>Связаться с нами</h2>
      <p>Заполните форму. Для нового пользователя сервис вернет логин, пароль и ссылку на профиль. После входа можно изменить ранее отправленные данные, кроме логина и пароля.</p>
      <?php if ($user) { ?>
        <p class="note">Вы вошли как <b><?php print h($user['login']); ?></b>. Логин и пароль изменить нельзя.</p>
      <?php } ?>
    </div>

    <div class="form-panel">
      <h2><?php print $user ? 'Редактирование анкеты' : 'Анкета клиента'; ?></h2>
      <div id="server-result">
      <?php if (!empty($result['message'])) { ?>
        <div class="ok">
          <p><?php print h($result['message']); ?></p>
          <?php if (!empty($result['login'])) { ?>
            <p><b>Логин:</b> <?php print h($result['login']); ?></p>
            <p><b>Пароль:</b> <?php print h($result['password']); ?></p>
            <p><b>Профиль:</b> <a href="<?php print h($result['profile']); ?>"><?php print h($result['profile']); ?></a></p>
          <?php } ?>
        </div>
      <?php } ?>
      </div>
      <div id="js-result"></div>

      <form id="profile-form" action="<?php print h($c['action']); ?>" method="POST" data-api="<?php print h($c['api']); ?>" data-method="<?php print h($c['method']); ?>">
        <?php if ($c['method'] == 'put') { ?><input type="hidden" name="method" value="put"><?php } ?>

        <label>Имя
          <input type="text" name="name" value="<?php print h($form['name']); ?>" required pattern="[A-Za-zА-Яа-яЁё\s\-]{2,100}" placeholder="Иван Иванов">
        </label>
        <?php print err($errors, 'name'); ?>

        <label>Email
          <input type="email" name="email" value="<?php print h($form['email']); ?>" required placeholder="mail@example.com">
        </label>
        <?php print err($errors, 'email'); ?>

        <label>Год рождения
          <select name="year" required>
            <option value="">Выберите год</option>
            <?php foreach ($c['years'] as $year) { ?>
              <option value="<?php print $year; ?>" <?php if ($form['year'] == $year) print 'selected'; ?>><?php print $year; ?></option>
            <?php } ?>
          </select>
        </label>
        <?php print err($errors, 'year'); ?>

        <div class="block">Пол
          <div class="inline-options">
            <label><input type="radio" name="gender" value="male" <?php if ($form['gender'] == 'male') print 'checked'; ?> required> мужской</label>
            <label><input type="radio" name="gender" value="female" <?php if ($form['gender'] == 'female') print 'checked'; ?>> женский</label>
          </div>
        </div>
        <?php print err($errors, 'gender'); ?>

        <div class="block">Количество конечностей
          <div class="inline-options">
            <?php for ($i = 1; $i <= 4; $i++) { ?>
              <label><input type="radio" name="limbs" value="<?php print $i; ?>" <?php if ($form['limbs'] == (string)$i) print 'checked'; ?> required> <?php print $i; ?></label>
            <?php } ?>
          </div>
        </div>
        <?php print err($errors, 'limbs'); ?>

        <label>Сверхспособности <span class="hint">можно выбрать несколько</span>
          <select name="powers[]" multiple size="3">
            <?php foreach ($c['powers'] as $key => $title) { ?>
              <option value="<?php print h($key); ?>" <?php if (in_array($key, $form['powers'])) print 'selected'; ?>><?php print h($title); ?></option>
            <?php } ?>
          </select>
        </label>
        <?php print err($errors, 'powers'); ?>

        <label>Сообщение / биография
          <textarea name="bio" rows="5" required placeholder="Коротко опишите задачу"><?php print h($form['bio']); ?></textarea>
        </label>
        <?php print err($errors, 'bio'); ?>

        <label class="checkbox">
          <input type="checkbox" name="contract" value="1" <?php if ($form['contract'] == '1') print 'checked'; ?> required>
          <span>Я согласен на обработку персональных данных</span>
        </label>
        <?php print err($errors, 'contract'); ?>

        <input type="submit" value="<?php print $user ? 'Сохранить изменения' : 'Связаться'; ?>">
      </form>
    </div>
  </div>
</section>

<footer class="small-footer">Drupal-coder, учебный проект по веб-программированию</footer>

<script>
(function () {
  var form = document.getElementById('profile-form');
  if (!form || !window.fetch) return;

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    var result = document.getElementById('js-result');
    result.innerHTML = '';

    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }

    var fd = new FormData(form);
    fd.delete('method');
    var data = {
      name: fd.get('name'),
      email: fd.get('email'),
      year: fd.get('year'),
      gender: fd.get('gender'),
      limbs: fd.get('limbs'),
      powers: fd.getAll('powers[]'),
      bio: fd.get('bio'),
      contract: fd.get('contract') ? '1' : ''
    };

    fetch(form.dataset.api, {
      method: form.dataset.method.toUpperCase(),
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify(data),
      credentials: 'same-origin'
    })
    .then(function (resp) {
      return resp.json().then(function (json) { return {status: resp.status, json: json}; });
    })
    .then(function (r) {
      if (!r.json.ok) {
        var html = '<div class="error-box"><b>Ошибки:</b><ul>';
        for (var k in r.json.errors) html += '<li>' + r.json.errors[k] + '</li>';
        html += '</ul></div>';
        result.innerHTML = html;
        return;
      }
      var html = '<div class="ok"><p>' + r.json.message + '</p>';
      if (r.json.login) {
        html += '<p><b>Логин:</b> ' + r.json.login + '</p>';
        html += '<p><b>Пароль:</b> ' + r.json.password + '</p>';
        html += '<p><b>Профиль:</b> <a href="' + r.json.profile + '">' + r.json.profile + '</a></p>';
      }
      html += '</div>';
      result.innerHTML = html;
      document.getElementById('server-result').innerHTML = '';
    })
    .catch(function () {
      result.innerHTML = '<div class="error-box">Ошибка отправки запроса.</div>';
    });
  });
})();
</script>
