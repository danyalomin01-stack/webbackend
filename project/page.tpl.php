<!doctype html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Drupal-coder — поддержка сайтов</title>
  <style>
    * { box-sizing: border-box; }
    body { margin: 0; font-family: Arial, Helvetica, sans-serif; background: #f5f6fa; color: #1e1e1e; line-height: 1.45; }
    a { color: inherit; text-decoration: none; }
    .container { width: min(1140px, calc(100% - 32px)); margin: 0 auto; }
    .header { position: sticky; top: 0; z-index: 10; background: rgba(17, 17, 20, .94); color: white; border-bottom: 1px solid rgba(255,255,255,.08); }
    .nav { min-height: 68px; display: flex; align-items: center; justify-content: space-between; gap: 20px; }
    .logo { font-size: 24px; font-weight: 800; letter-spacing: .5px; }
    .logo span { color: #f15a24; }
    .menu { display: flex; gap: 22px; align-items: center; font-size: 15px; }
    .menu a { opacity: .9; }
    .menu a:hover { color: #f15a24; }
    .hero { background: radial-gradient(circle at 75% 20%, rgba(241,90,36,.28), transparent 30%), linear-gradient(135deg, #18191f 0%, #242736 55%, #101116 100%); color: white; padding: 80px 0 70px; }
    .hero-grid { display: grid; grid-template-columns: 1.1fr .9fr; gap: 44px; align-items: center; }
    .hero h1 { font-size: clamp(34px, 6vw, 62px); line-height: 1.05; margin: 0 0 20px; }
    .hero p { font-size: 19px; color: #d8d8d8; max-width: 620px; margin-bottom: 28px; }
    .btn-primary, input[type=submit] { display: inline-block; border: 0; background: #f15a24; color: white; padding: 14px 28px; border-radius: 4px; font-size: 16px; font-weight: 700; cursor: pointer; transition: .2s; }
    .btn-primary:hover, input[type=submit]:hover { background: #d94c1c; transform: translateY(-1px); }
    .stats { display: grid; grid-template-columns: repeat(2, 1fr); gap: 18px; }
    .stat { padding: 22px; background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12); border-radius: 10px; }
    .stat b { display: block; font-size: 34px; color: #f15a24; margin-bottom: 6px; }
    .section { padding: 64px 0; }
    .section h2 { text-align: center; font-size: 34px; margin: 0 0 14px; }
    .section-lead { text-align: center; max-width: 700px; margin: 0 auto 36px; color: #666; }
    .cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; }
    .card { background: white; padding: 28px; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,.07); border: 1px solid #eee; }
    .card h3 { margin-top: 0; color: #222; }
    .card .num { width: 42px; height: 42px; background: #fff0ea; color: #f15a24; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; margin-bottom: 14px; }
    .dark-form { background: #15161d; color: white; padding: 64px 0; }
    .form-wrap { display: grid; grid-template-columns: .75fr 1.25fr; gap: 40px; align-items: start; }
    .form-panel { background: white; color: #1e1e1e; padding: 30px; border-radius: 14px; box-shadow: 0 15px 40px rgba(0,0,0,.25); }
    .form-panel h2 { margin: 0 0 8px; text-align: left; }
    label, .block { display: block; margin-top: 15px; font-weight: 700; }
    .hint { color: #777; font-size: 14px; font-weight: 400; }
    input[type=text], input[type=email], select, textarea { width: 100%; padding: 12px 14px; margin-top: 7px; border: 1px solid #d7d7d7; border-radius: 6px; font-size: 15px; font-family: inherit; background: white; }
    select[multiple] { min-height: 102px; }
    textarea { resize: vertical; }
    .inline-options { display: flex; gap: 16px; flex-wrap: wrap; margin-top: 8px; font-weight: 400; }
    .inline-options label { margin: 0; font-weight: 400; }
    .checkbox { display: flex; gap: 8px; align-items: flex-start; font-weight: 400; }
    .error { color: #b00020; margin-top: 5px; font-size: 14px; }
    .error-box { border: 1px solid #e18b8b; background: #fff4f4; color: #8a1111; padding: 13px; margin: 14px 0; border-radius: 8px; }
    .ok { border: 1px solid #5eba78; background: #f0fff4; color: #174b25; padding: 14px; margin: 14px 0; border-radius: 8px; }
    .ok a { text-decoration: underline; color: #174b25; }
    .note { background: #fff7e8; border-left: 4px solid #f15a24; padding: 12px; border-radius: 6px; }
    .small-footer { background: #101116; color: #aaa; text-align: center; padding: 22px; font-size: 14px; }
    @media (max-width: 850px) { .hero-grid, .form-wrap { grid-template-columns: 1fr; } .cards { grid-template-columns: 1fr; } .stats { grid-template-columns: 1fr; } .menu { display: none; } .hero { padding-top: 54px; } }
  </style>
</head>
<body>
<?php foreach ($c['#content'] as $content) { echo $content; } ?>
</body>
</html>
