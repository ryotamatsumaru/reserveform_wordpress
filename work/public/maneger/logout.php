<?php
session_start();
// $_SESSION['login']=array();
// $_SESSION['login_id']=array();

  unset($_SESSION['login']);
  unset($_SESSION['login_id']);

  require_once('header2.php');

// if(isset($_COOKIE[session_name()]) == true){
//   setcookie(session_name(), '',time()-42000, '/');
// }
// session_destroy();
?>
<body>
<div id="logout" class="wrapper">
<p class="done-text">ログアウトしました。</p>
<a href="maneger-login.php" class="back">ログイン画面へ</a>
</div>
</body>
</html>