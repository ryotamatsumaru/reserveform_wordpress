<?php

//     Template Name: leave
//     Template Post Type: page
//     Template Path: member/

session_start();
if(isset($_SESSION['cus_login'])==false){
  header('Location:https://ro-crea.com/demo_hotel/login_caution_text/');
  exit();
}

get_header();

require_once(dirname(dirname(__FILE__)). '/measure/measure-class.php');

echo $id = $_SESSION['cus_id'];
Measure::cus_create();
$cus_token = $_SESSION['cus_token'];
$_SESSION['cus_confirm'] = 1;
?>
<section>
  <div id="leave" class="wrapper">
    <h1 class="title">退会手続き</h1>
    <div class="text-area">
      <p class="text">退会手続きに進みます。</p>
      <p  class="text">一度退会した場合、登録したお客様情報は削除されます。</p>
      <p  class="text">もう一度、会員制度を利用される場合は、再度登録が必要になります。</p>
      <p>問題なければ下記の退会するを押してください。</p>
    </div>
    <form method="post" action="https://ro-crea.com/demo_hotel/leave_done/">
    <input type="hidden" name="id" value="<?= $id?>">
    <input type="hidden" name="cus_token" value="<?= $cus_token?>">
    <input type="submit" class="submit" value="退会する">
    </form>
    <a href="https://ro-crea.com/demo_hotel/menu/" class="back" >メニューに戻る</a>
  </div>
</section>
<?php get_footer("3"); ?>