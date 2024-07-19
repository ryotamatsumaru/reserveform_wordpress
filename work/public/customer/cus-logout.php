<?php
session_start();
// $_SESSION['login']=array();
// $_SESSION['login_id']=array();

  unset($_SESSION['cus_login']);
  unset($_SESSION['cus_id']);

// if(isset($_COOKIE[session_name()]) == true){
//   setcookie(session_name(), '',time()-42000, '/');
// }
// session_destroy();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel= "stylesheet" href="customer-login.css" >
  <title>ログイン画面</title>
</head>
<body>
<div id="logout" class="wrapper">
<p class="done-text">ログアウトしました。</p>
<a href="maneger-login.html" class="back">ログイン画面へ</a>
</div>
</body>
</html>