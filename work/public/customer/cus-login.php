<?php 
$confirm = 1;


require_once(__DIR__ . '/..work/app/measure-class.php');
echo $test = Measure::create();
?>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="customer-login.css">
</head>
<body>
<div id="login" class="wrapper">
<h1 class="title">お客様ログイン</h1>
<form action="cus-login-check.php" method="post">
  <div class="center">
  <p class="bar-text"><label for="mail">メールアドレス:</label></p>
  <input type="text" name="mail" id="mail" class="bar">
  <br>
  <br>
  <p class="bar-text"><label for="password">パスワード:</label></p>
  <input type="password" name="password" id="password" class="bar">
  <input type="hidden" name="confirm" value="<?= $confirm ?>">
  <input type="submit" class="submit">
  </div>
</form>
</div>
<div class="note-area wrapper">
<p class="note">※メンバーのお客様はパスワードとメールアドレスを入力するとメンバー専用ページに移動します。</p>
</div>
</body>