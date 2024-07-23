<?php

//     Template Name: mng_logout
//     Template Post Type: page
//     Template Path: control/

session_start();
// sessionが一致しない場合、ログインの警告ページに遷移する
if(isset($_SESSION['mng_login'])==false){
  header('Location:https://ro-crea.com/demo_hotel/mng_login_caution_text');
  exit();
}

$_SESSION['mng_login']=array();
$_SESSION['mng_id']=array();
$_SESSION['token']=array();

unset($_SESSION['mng_login']);
unset($_SESSION['mng_id']);
unset($_SESSION['token']);

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>管理画面(デモサイト)</title>
  <?php wp_head(); ?>
</head>
<body>
<header>
</header>
<section>
  <div id="mng-logout" class="wrapper">
    <p class="done-text">ログアウトしました。</p>
    <a href="https://ro-crea.com/demo_hotel/mng_login/" class="back">ログイン画面へ</a>
  </div>
</section>
<footer>
</footer>
<?php wp_footer(); ?>
</body>
</html>