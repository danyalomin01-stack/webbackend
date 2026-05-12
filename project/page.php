<?php
$base = htmlspecialchars(base_url(), ENT_QUOTES);
$v = function($name) use ($formData) { return htmlspecialchars($formData[$name] ?? '', ENT_QUOTES); };
$action = $editMode ? $base.'/profile/'.$profileId : $base.'/';
$api = $editMode ? $base.'/api/profile/'.$profileId : $base.'/api/profile';
$methodName = $editMode ? 'PUT' : 'POST';
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
<header class="header" id="top">
  <video class="header__video" autoplay muted loop playsinline>
    <source src="<?= $base ?>/assets/img/video.mp4" type="video/mp4">
  </video>
  <div class="header__overlay"></div>
  <div class="header__content container">
    <nav class="nav">
      <a href="#top" class="logo">Drupal-coder</a>
      <ul class="nav__list desktop">
        <li class="has-sub"><a href="#services">Администрирование</a>
          <ul class="submenu">
            <li><a href="#services">Миграция</a></li>
            <li><a href="#features">Бэкапы</a></li>
            <li><a href="#features">Аудит безопасности</a></li>
            <li><a href="#features">Оптимизация скорости</a></li>
            <li><a href="#pricing">Переезд на HTTPS</a></li>
          </ul>
        </li>
        <li><a href="#pricing">Продвижение</a></li>
        <li><a href="#clients">Реклама</a></li>
        <li><a href="#team">О нас</a></li>
        <li><button type="button" class="nav-btn js-open-modal">Связаться</button></li>
      </ul>
      <button type="button" class="burger" aria-label="Меню"><span></span><span></span><span></span></button>
    </nav>
    <ul class="nav__list mobile">
      <li><button type="button" class="mobile-sub-toggle">Администрирование ▼</button></li>
      <li class="mobile-submenu"><a href="#services">Миграция</a></li>
      <li class="mobile-submenu"><a href="#features">Бэкапы</a></li>
      <li class="mobile-submenu"><a href="#features">Аудит безопасности</a></li>
      <li class="mobile-submenu"><a href="#features">Оптимизация скорости</a></li>
      <li><a href="#pricing">Продвижение</a></li>
      <li><a href="#clients">Реклама</a></li>
      <li><a href="#team">О нас</a></li>
      <li><button type="button" class="mobile-contact js-open-modal">Связаться</button></li>
    </ul>

    <div class="header-grid">
      <div class="header-left">
        <h1>Поддержка сайтов на Drupal</h1>
        <p>Сопровождение и поддержка сайтов на CMS Drupal любых версий и запущенности</p>
        <button type="button" class="btn-primary js-open-modal">Связаться с нами</button>
      </div>
      <div class="header-features">
        <div class="feature-item"><div><div class="feature-title">#1</div><div class="feature-desc">Drupal-разработчик в России по версии Рейтинга Рунета</div></div></div>
        <div class="feature-item"><div><div class="feature-title">3+</div><div class="feature-desc">средний опыт специалистов более 3 лет</div></div></div>
        <div class="feature-item"><div><div class="feature-title">14</div><div class="feature-desc">лет опыта в сфере Drupal</div></div></div>
        <div class="feature-item"><div><div class="feature-title">50+</div><div class="feature-desc">модулей и тем в формате DrupalGive</div></div></div>
        <div class="feature-item"><div><div class="feature-title">90 000+</div><div class="feature-desc">часов поддержки сайтов на Drupal</div></div></div>
        <div class="feature-item"><div><div class="feature-title">300+</div><div class="feature-desc">проектов на поддержке</div></div></div>
      </div>
    </div>
  </div>
</header>

<main>
  <section class="section" id="services">
    <div class="container">
      <h2 class="section-title">13 лет совершенствуем компетенции в Друпал поддержке!</h2>
      <p class="section-sub">Разрабатываем и оптимизируем модули, расширяем функциональность сайтов, обновляем дизайн</p>
      <div class="services-grid">
        <?php
        $services = [
          ['competency-1.svg','Добавление информации на сайт, создание новых разделов'],
          ['competency-2.svg','Разработка и оптимизация модулей сайта'],
          ['competency-3.svg','Интеграция с CRM, 1C, платежными системами, любыми веб-сервисами'],
          ['competency-4.svg','Любые доработки функционала и дизайна'],
          ['competency-5.svg','Аудит и мониторинг безопасности Drupal сайтов'],
          ['competency-6.svg','Миграция, импорт контента и апгрейд Drupal'],
          ['competency-7.svg','Оптимизация и ускорение Drupal-сайтов'],
          ['competency-8.svg','Веб-маркетинг, консультации и работы по SEO'],
        ];
        foreach ($services as $s): ?>
          <div class="service-card"><div class="service-ico"><img src="<?= $base ?>/assets/img/<?= $s[0] ?>" alt=""></div><p><?= htmlspecialchars($s[1]) ?></p></div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section light" id="features">
    <div class="container">
      <h2 class="section-title">Поддержка от Drupal-coder</h2>
      <div class="features-grid">
        <?php
        $features = [
          ['01','Постановка задачи по Email','Удобная и привычная модель, при которой задачи фиксируются и никогда не теряются.','support1.svg'],
          ['02','Система Helpdesk – отчетность, прозрачность','Возможность посмотреть все заявки в работе и отработанные часы в личном кабинете через браузер.','support2.svg'],
          ['03','Расширенная техническая поддержка','Возможность организации расширенной техподдержки с 6:00 до 22:00 без выходных.','support3.svg'],
          ['04','Персональный менеджер проекта','Ваш менеджер всегда в курсе состояния проекта и готов ответить на любые вопросы.','support4.svg'],
          ['05','Удобные способы оплаты','Безналичный расчет по договору или электронные деньги: WebMoney, Яндекс.Деньги, Paypal.','support5.svg'],
          ['06','Работаем с SLA и NDA','Работа в рамках соглашений о конфиденциальности и об уровне качества работ.','support6.svg'],
          ['07','Штатные специалисты','Надежные штатные специалисты, никаких фрилансеров.','support7.svg'],
          ['08','Удобные каналы связи','Консультации по телефону, скайпу, в мессенджерах.','support8.svg'],
        ];
        foreach ($features as $f): ?>
          <article class="feature"><div class="feature-num"><?= $f[0] ?></div><h4><?= htmlspecialchars($f[1]) ?></h4><p><?= htmlspecialchars($f[2]) ?></p><img class="feature-icon" src="<?= $base ?>/assets/img/<?= $f[3] ?>" alt=""></article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section" id="pricing">
    <div class="container">
      <h2 class="section-title">Тарифы</h2>
      <p class="section-sub">Выберите подходящий план обслуживания</p>
      <div class="pricing-grid">
        <article class="pricing-card"><div class="plan-header"><h3>Базовый</h3><strong class="price">€99 / мес</strong></div><ul><li>Мониторинг 24/7</li><li>Еженедельный бэкап</li><li>Поддержка 5 заявок</li></ul><button type="button" class="btn-outline js-open-modal">Выбрать</button></article>
        <article class="pricing-card popular"><div class="plan-header"><h3>Профессиональный</h3><strong class="price">€199 / мес</strong></div><ul><li>Приоритетная поддержка</li><li>Ежедневный бэкап</li><li>Оптимизация скорости</li></ul><button type="button" class="btn-primary js-open-modal">Выбрать</button></article>
        <article class="pricing-card"><div class="plan-header"><h3>Enterprise</h3><strong class="price">По запросу</strong></div><ul><li>Индивидуальные SLA</li><li>Выделенный инженер</li><li>Интеграции и миграции</li></ul><button type="button" class="btn-outline js-open-modal">Узнать больше</button></article>
      </div>
    </div>
  </section>

  <section class="section light" id="clients"><div class="container"><h3 class="section-title">Наши клиенты</h3><div class="clients-row"><div>LOGO</div><div>LOGO</div><div>LOGO</div><div>LOGO</div><div>LOGO</div><div>LOGO</div></div></div></section>

  <section class="section testimonials" id="reviews"><div class="container"><h2 class="section-title">Отзывы</h2><div class="review-card"><p class="review-text">“Отличная команда! Быстро реагируют на задачи и всегда на связи.”</p><p class="review-name">— Иван Петров</p></div></div></section>

  <section class="section team" id="team">
    <div class="container"><h2 class="section-title">Наша команда</h2><div class="team-grid">
      <article class="team-card"><img class="team-photo" src="<?= $base ?>/assets/img/IMG_2472_0.jpg" alt=""><h3>Сергей Синица</h3><p>Руководитель отдела веб-разработки</p></article>
      <article class="team-card"><img class="team-photo" src="<?= $base ?>/assets/img/IMG_2474_1.jpg" alt=""><h3>Роман Агабеков</h3><p>Руководитель отдела DevOPS, директор</p></article>
      <article class="team-card"><img class="team-photo" src="<?= $base ?>/assets/img/IMG_2522_0.jpg" alt=""><h3>Алексей Синица</h3><p>Руководитель отдела поддержки сайтов</p></article>
      <article class="team-card"><img class="team-photo" src="<?= $base ?>/assets/img/IMG_2539_0.jpg" alt=""><h3>Дарья Бочкарева</h3><p>Руководитель отдела продвижения</p></article>
    </div></div>
  </section>

  <section class="section light" id="faq"><div class="container"><h3 class="section-title">Вопросы и ответы</h3><div class="faq-list">
    <div class="faq-item"><button type="button" class="faq-q">Как быстро вы отвечаете на запросы? <span>▾</span></button><div class="faq-a">В среднем в течение 1–4 часов в рабочее время по тарифам.</div></div>
    <div class="faq-item"><button type="button" class="faq-q">Можно ли подключить выделенного инженера? <span>▾</span></button><div class="faq-a">Да — в тарифе Enterprise мы предлагаем выделенный ресурс.</div></div>
    <div class="faq-item"><button type="button" class="faq-q">Поддерживаете старые версии Drupal? <span>▾</span></button><div class="faq-a">Да, но есть рекомендации по обновлению для безопасности.</div></div>
  </div></div></section>
</main>

<footer class="footer" id="contacts">
  <div class="footer-content">
    <h2>Связаться с нами</h2>
    <form class="footer-form js-contact-form" method="post" action="<?= $action ?>" data-api="<?= $api ?>" data-method="<?= $methodName ?>">
      <label><input name="name" placeholder="Ваше имя" value="<?= $v('name') ?>" required></label>
      <label><input name="email" type="email" placeholder="Email" value="<?= $v('email') ?>" required></label>
      <label><input name="phone" placeholder="Телефон" value="<?= $v('phone') ?>" required></label>
      <label><textarea name="comment" placeholder="Сообщение" required><?= $v('comment') ?></textarea></label>
      <label class="checkbox light"><input name="agree" type="checkbox" value="1" <?= !empty($formData['agree']) ? 'checked' : '' ?> required><span>Я согласен на обработку персональных данных</span></label>
      <button class="btn-primary" type="submit"><?= $editMode ? 'Сохранить' : 'Связаться' ?></button>
      <div class="form-result"><?php if (!empty($message)) echo htmlspecialchars($message); if (!empty($errors)) echo htmlspecialchars(implode(' ', $errors)); ?></div>
    </form>
  </div>
</footer>

<div class="modal-overlay" id="contactModal" hidden>
  <div class="modal-window">
    <button type="button" class="modal-close" aria-label="Закрыть">×</button>
    <h2>Связаться с нами</h2>
    <p>Оставьте данные, и мы ответим вам в ближайшее время.</p>
    <form class="modal-form js-contact-form" method="post" action="<?= $action ?>" data-api="<?= $api ?>" data-method="<?= $methodName ?>">
      <label>Имя<input name="name" value="<?= $v('name') ?>" required></label>
      <label>Email<input name="email" type="email" value="<?= $v('email') ?>" required></label>
      <label>Телефон<input name="phone" placeholder="+7 (___) ___-__-__" value="<?= $v('phone') ?>" required></label>
      <label>Сообщение<textarea name="comment" required><?= $v('comment') ?></textarea></label>
      <label class="checkbox"><input name="agree" type="checkbox" value="1" <?= !empty($formData['agree']) ? 'checked' : '' ?> required><span>Я согласен на обработку персональных данных</span></label>
      <button class="btn-primary" type="submit"><?= $editMode ? 'Сохранить' : 'Отправить сообщение' ?></button>
      <div class="form-result"></div>
    </form>
  </div>
</div>

<script src="<?= $base ?>/script.js"></script>
</body>
</html>
