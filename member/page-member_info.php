<?php

//     Template Name: member_info
//     Template Post Type: page
//     Template Path: member/

session_start();
if(isset($_SESSION['cus_login'])==false){
  header('Location:https://ro-crea.com/demo_hotel/login_caution_text/');
  exit();
}

get_header();

require_once(dirname(dirname(__FILE__)). '/prvdtb/pdo-class.php');
require_once(dirname(dirname(__FILE__)). '/measure/measure-class.php');

$id = $_SESSION['cus_id'];

$dbh = Database::getPdo();
$sql = "SELECT * FROM member WHERE id = :id";
$ps = $dbh->prepare($sql);
$ps->bindValue(':id', $id, PDO::PARAM_INT);
$ps->execute();
$rows = $ps->fetch();
?>
<section>
  <div id="member-info" class="wrapper">
    <h1 class="title"> 会員情報</h1>
    <ul class="field">
      <li>氏名： <?= $rows['name'] ?></li>
      <li>メールアドレス： <?= $rows['mail'] ?></li>
      <li>ローマ字： <?= $rows['roma'] ?></li>
      <li>電話番号： <?=$rows['tel'] ?></li>
      <li>性別： <?=$rows['gender'] ?></li>
      <li>生年月日：
      <?=$rows['birth_year'] ?>年
      <?=$rows['birth_month'] ?>月
      <?=$rows['birth_day'] ?>日</li>
      <li>住所：
      <?=$rows['prefecture'] ?>
      <?=$rows['address'] ?></li>
      <li>登録日： <?=$rows['registrate_date'] ?></li>
    </ul>
    <a href="https://ro-crea.com/demo_hotel/menu/" class="back">メニューに戻る</a>
  </div>
</section>
<?php get_footer("3"); ?>