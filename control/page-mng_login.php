<?php

//     Template Name: mng_login
//     Template Post Type: page
//     Template Path: control/

session_start();
session_regenerate_id();

require_once(dirname(dirname(__FILE__)). '/measure/measure-class.php');
// CSRF対策のtokenを$tokenに代入。
Measure::create();
$token = $_SESSION['token'];
$confirm = '1';
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

<section id="login" class="wrapper">
  <div class="center-box">
    <form action="https://ro-crea.com/demo_hotel/mng_login_check/" method="post">
    <label for="id">ログインID：</label>
    <p class="info"><input type="text" name="login_id" id="id"></p>
    <label for="password">パスワード：</label>
    <p  class="info"><input type="password" name="password" id="password"></p>
    <input type="hidden" name="confirm" value="1">
    <input type="hidden" name="token" value="<?= $token ?>">
    <input type="hidden" name="confirm" value="<?= $confirm ?>">
    <input type="submit" class="submit" value="確定">
    </form>
  </div>
</section>

<footer>
</footer>
<?php wp_footer(); ?>
</body>
</html>