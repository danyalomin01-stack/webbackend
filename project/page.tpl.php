<style>
body {background-color: white; color: black; font-family: Tahoma, Verdana, Arial, sans-serif; font-size: 15px; max-width: 850px; margin: 20px auto; line-height: 1.4;}
h1 {text-align:center;}
label, .block {display:block; margin-top: 14px;}
input[type=text], input[type=email], select, textarea {width: 100%; max-width: 480px; padding: 7px; box-sizing: border-box;}
textarea {resize: vertical;}
input[type=submit] {margin-top: 18px; padding: 8px 18px; cursor:pointer;}
.error {color:#b00020; margin-top:4px;}
.error-box {border:1px solid #b00020; background:#fff4f4; padding:10px; margin:12px 0;}
.ok {border:1px solid #118811; background:#f0fff0; padding:10px; margin:12px 0;}
.note {background:#f7f7f7; padding:10px;}
a, a:visited {color: #339;}
</style>

<?php
foreach ($c['#content'] as $content) {
  echo $content;
}
?>
