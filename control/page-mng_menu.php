<?php

//     Template Name: mng_menu
//     Template Post Type: page
//     Template Path: control/

session_start();
session_regenerate_id(true);

// sessionが一致しない場合、ログインの警告ページに遷移する
if(isset($_SESSION['mng_login'])==false){
  header('Location:https://ro-crea.com/demo_hotel/mng_login_caution_text');
  exit();
}
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
<header id="con-header">
  <h1 class="title">管理画面</h1>
</header>
<section id="mng-menu">
  <div class="menu-board">
    <h2 class="menu-title">予約・在庫管理</h2>
    <ul class="menu-list">
      <li><a href="https://ro-crea.com/demo_hotel/insert/">料金・室数の設定</a></li>
      <li><a  href="https://ro-crea.com/demo_hotel/update/">料金・室数の変更</a></li>
      <li><a href="https://ro-crea.com/demo_hotel/search/">予約の確認</a></li>
      <li><a href="https://ro-crea.com/demo_hotel/book_make/">予約を作成する</a></li>
      <li><a href="https://ro-crea.com/demo_hotel/mng_logout">ログアウト</a></li>
    </ul>
  </div>
</section>
<footer>
</footer>
<?php wp_footer(); ?>
</body>
</html>