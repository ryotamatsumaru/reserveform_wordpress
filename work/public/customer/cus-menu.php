<?php
session_start();
session_regenerate_id(true);
if(isset($_SESSION['cus_login'])==false){
  // print '<p>ログインされてません</p>';
  // print '<a href="maneger-login.html">ログイン画面へ</a>';
  header('Location:not-login-text.php');
  exit();
}
?>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="customer-login.css">
</head>
<body>
<div id="menu" class="wrapper">
<h1 class="title">メンバーメニュー</h1>
<div class="center">
<p><a href="member-confirm.php">会員情報の確認</a></p>
<p><a href="cus-reserve.php">予約の確認</a></p>
<p><a href="member-cancel.php">退会手続き</a></p>
<p><a href="cus-logout.php">ログアウト</a></p>
</div>
</div>
</body>