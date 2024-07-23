<?php

//     Template Name: mng_login_caution_text
//     Template Post Type: page
//     Template Path: member/

session_start();

require_once(dirname(dirname(__FILE__)). '/prvdtb/pdo-class.php');
require_once(dirname(dirname(__FILE__)). '/measure/measure-class.php');


if(!empty($_POST['id'])){
  
  Measure::cus_validate();
  $id = Measure::h($_POST['id']);
  $dbh = Database::getPdo();
  $sql = "DELETE FROM member WHERE id = :id ";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':id', $id, PDO::PARAM_INT);
  $ps->execute();

  header('Location:https://ro-crea.com/demo_hotel/leave_done/');
  exit();
}
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>デモサイト(ホテル)</title>
  <?php wp_head(); ?>
</head>

<body>
<header id="header">
</header>
<section>
  <div id="mng-login-caution" class="wrapper">
    <p class="caution">ログインされてません</p>
    <a href="https://ro-crea.com/demo_hotel/mng_login/">ログイン画面へ</a>
  </div>
</section>
<footer>
</footer>
<?php wp_footer(); ?>
</body>
</html>


