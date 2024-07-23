<?php

//     Template Name: menu
//     Template Post Type: page
//     Template Path: member/

session_start();
session_regenerate_id(true);

if(isset($_SESSION['cus_login'])==false){
  header('Location:https://ro-crea.com/demo_hotel/login_caution_text/');
  exit();
}

get_header();
?>

<section>
  <div id="menu" class="wrapper">
    <div class="menu-form">
      <h1 class="title">メンバーメニュー</h1>
      <div class="center">
        <p><a href="https://ro-crea.com/demo_hotel/member_info/">会員情報の確認</a></p>
        <p><a href="https://ro-crea.com/demo_hotel/book_detail/">予約の確認</a></p>
        <p><a href="https://ro-crea.com/demo_hotel/leave/">退会手続き</a></p>
        <p><a href="https://ro-crea.com/demo_hotel/logout">ログアウト</a></p>
      </div>
    </div>
  </div>
</section>

<?php get_footer("3"); ?>