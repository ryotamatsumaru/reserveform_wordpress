<?php

//     Template Name: login
//     Template Post Type: page
//     Template Path: member/
session_start();

require_once(dirname(dirname(__FILE__)). '/measure/measure-class.php');
Measure::cus_create();
$cus_token = $_SESSION['cus_token'];
$confirm = 1;

get_header();
?>
<section>
  <div id="login" class="wrapper">
    <div class="login-form">
      <h1 class="title">メンバー用ログイン</h1>
      <form action="https://ro-crea.com/demo_hotel/login_check/" method="post">
      <div class="center">
        <p class="bar-text"><label for="mail">メールアドレス:</label></p>
        <input type="text" name="mail" id="mail" class="bar">
        <br>
        <br>
        <p class="bar-text"><label for="password">パスワード:</label></p>
        <input type="password" name="password" id="password" class="bar">
        <input type="hidden" name="cus_token" value="<?= $cus_token ?>">
        <input type="hidden" name="confirm" value="<?= $confirm ?>">
        <input type="submit" class="submit">
      </div>
      </form>
    </div>
    <div class="note-area">
      <p class="note">※メンバーのお客様はパスワードとメールアドレスを入力するとメンバー専用ページに移動します。</p>
    </div>
  </div>
</section>
<?php get_footer("3");?>