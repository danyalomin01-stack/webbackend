<?php
$form = $c['form'];
$errors = $c['errors'];
$result = $c['result'];
$user = $c['user'];
function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function err($errors, $name) { return !empty($errors[$name]) ? '<div class="error">' . h($errors[$name]) . '</div>' : ''; }
?>

<h1>Анкета</h1>

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

<?php if ($user) { ?>
  <p class="note">Логин и пароль изменить нельзя: <b><?php print h($user['login']); ?></b></p>
<?php } ?>

<form id="profile-form" action="<?php print h($c['action']); ?>" method="POST" data-api="<?php print h($c['api']); ?>" data-method="<?php print h($c['method']); ?>">
  <?php if ($c['method'] == 'put') { ?>
    <input type="hidden" name="method" value="put">
  <?php } ?>

  <label>Имя:<br>
    <input type="text" name="name" value="<?php print h($form['name']); ?>" required pattern="[A-Za-zА-Яа-яЁё\s\-]{2,100}">
  </label>
  <?php print err($errors, 'name'); ?>

  <label>Email:<br>
    <input type="email" name="email" value="<?php print h($form['email']); ?>" required>
  </label>
  <?php print err($errors, 'email'); ?>

  <label>Год рождения:<br>
    <select name="year" required>
      <option value="">Выберите год</option>
      <?php foreach ($c['years'] as $year) { ?>
        <option value="<?php print $year; ?>" <?php if ($form['year'] == $year) print 'selected'; ?>><?php print $year; ?></option>
      <?php } ?>
    </select>
  </label>
  <?php print err($errors, 'year'); ?>

  <div class="block">Пол:<br>
    <label><input type="radio" name="gender" value="male" <?php if ($form['gender'] == 'male') print 'checked'; ?> required> мужской</label>
    <label><input type="radio" name="gender" value="female" <?php if ($form['gender'] == 'female') print 'checked'; ?>> женский</label>
  </div>
  <?php print err($errors, 'gender'); ?>

  <div class="block">Количество конечностей:<br>
    <?php for ($i = 1; $i <= 4; $i++) { ?>
      <label><input type="radio" name="limbs" value="<?php print $i; ?>" <?php if ($form['limbs'] == (string)$i) print 'checked'; ?> required> <?php print $i; ?></label>
    <?php } ?>
  </div>
  <?php print err($errors, 'limbs'); ?>

  <label>Сверхспособности:<br>
    <select name="powers[]" multiple size="3">
      <?php foreach ($c['powers'] as $key => $title) { ?>
        <option value="<?php print h($key); ?>" <?php if (in_array($key, $form['powers'])) print 'selected'; ?>><?php print h($title); ?></option>
      <?php } ?>
    </select>
  </label>
  <?php print err($errors, 'powers'); ?>

  <label>Биография:<br>
    <textarea name="bio" rows="5" required><?php print h($form['bio']); ?></textarea>
  </label>
  <?php print err($errors, 'bio'); ?>

  <label class="block">
    <input type="checkbox" name="contract" value="1" <?php if ($form['contract'] == '1') print 'checked'; ?> required>
    С контрактом ознакомлен(а)
  </label>
  <?php print err($errors, 'contract'); ?>

  <input type="submit" value="<?php print $user ? 'Сохранить изменения' : 'Отправить'; ?>">
</form>

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
    var powers = fd.getAll('powers[]');
    var data = {
      name: fd.get('name'),
      email: fd.get('email'),
      year: fd.get('year'),
      gender: fd.get('gender'),
      limbs: fd.get('limbs'),
      powers: powers,
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
