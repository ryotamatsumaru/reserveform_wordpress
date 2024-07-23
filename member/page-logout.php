<?php

//     Template Name: logout
//     Template Post Type: page
//     Template Path: member/

session_start();
if(isset($_SESSION['cus_login'])==false){
  header('Location:https://ro-crea.com/demo_hotel/login_caution_text/');
  exit();
}

  $_SESSION['cus_login']=array();
  $_SESSION['cus_id']=array();
  $_SESSION['cus_token']=array();

  unset($_SESSION['cus_login']);
  unset($_SESSION['cus_id']);
  unset($_SESSION['cus_token']);

  get_header();
?>
<section>
  <div id="logout" class="wrapper">
    <p class="done-text">ログアウトしました。</p>
    <a href="https://ro-crea.com/demo_hotel/login/" class="back">ログイン画面へ</a>
  </div>
</section>
<?php get_footer("3") ?>