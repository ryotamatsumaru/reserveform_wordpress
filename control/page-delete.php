<?php
//     Template Name: delete
//     Template Post Type: page
//     Template Path: control/

session_start();
session_regenerate_id(true);
// sessionが一致しない場合、ログインの警告ページに遷移する
if(isset($_SESSION['mng_login'])==false){
  header('Location:https://ro-crea.com/demo_hotel/mng_login_caution_text');
  exit();
}

require_once(dirname(dirname(__FILE__)). '/measure/measure-class.php');
require_once(dirname(dirname(__FILE__)). '/prvdtb/pdo-class.php');

if(isset($_POST['id'])){

  $token = $_SESSION['token'];
  $_SESSION['confirm2'] = $_POST['confirm'];
  $confirm = $_SESSION['confirm2'];

  // idからDBのbookingテーブルより予約情報を呼び出してそれぞれの配列に代入。
  foreach($_POST['id'] as $rec_id){
    Measure::h($rec_id);
    $dbh = Database::getPdo();
    $sql = "SELECT * FROM booking WHERE id = :rec_id";
    $ps = $dbh->prepare($sql);
    $ps->bindValue(':rec_id', $rec_id, PDO::PARAM_INT);
    $ps->execute();
    $rows = $ps->fetchAll(PDO::FETCH_ASSOC);

    foreach($rows as $row){
      $id[] = $row['id'];
      $type[] = $row['type'];
      $mail[] = $row['mail'];
      $roma[] = $row['roma'];
      $namae[] = $row['name'];
      $tel[] = $row['tel'];
      $gender[] = $row['gender'];
      $number[] = $row['number'];
      $night[] = $row['night'];
      $date[] = $row['day'];
      $price[] = $row['price'];
      $reserve[] = $row['reserve_date'];
      $random[] = $row['random_id'];
    }
  } 
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
<header>
</header>
<?php if(!empty($_POST['confirm'])): ?>
  <div id="delete" class="wrapper">
    <h2>予約削除</h2>
    <a href="https://ro-crea.com/demo_hotel/search" class="back">検索画面に戻る</a>
    <ul class="field-flex">
      <li class="random">予約ID</li>
      <li class="date">日付</li>
      <li class="name">名前</li>
      <li class="room">室数</li>
      <li class="night">泊数</li>
      <li class="price">価格</li>
      <li class="tel">電話番号</li>
      <li class="mail">メール</li>
    </ul>
    <form method="post" action="https://ro-crea.com/demo_hotel/delete_done" id="delete-form">
    <!-- 受け取った変数から予約情報を呼び出す。 -->
    <?php foreach(array_map(null, $id, $type, $date, $namae, $number, $night, $price, $tel,$mail,$reserve, $random) as [$ids, $types, $dates, $names, $numbers, $nights, $prices,$tels,$mails, $reserves, $randoms]): ?>
      <ul class="info-flex">
        <li class="random"><?= $randoms ?></li>
        <li class="date"><?= $dates ?></li>
        <li class="name"><?= $names ?>様</li>
        <li class="room"><?= $numbers ?>室</li>
        <li class="night"><?= $nights ?>泊</li>
        <li class="price">¥<?= $prices ?></li>
        <li class="tel"><?= $tels ?></li>
        <li class="mail"><?= $mails ?></li>
      </ul>
      <input type="hidden" name="id[]" value="<?= $ids ?>">
      <input type="hidden" name="reserve[]" value="<?= $reserves ?>">
      <input type="hidden" name="random[]" value="<?= $randoms ?>">
    <?php endforeach; ?>

    <?php foreach($_POST['night'] as $night2): ?>
      <input type="hidden" name="row_night[]" value="<?= Measure::h($night2) ?>">
    <?php endforeach; ?>

    <input type="hidden" name="token" value="<?= $token ?>">
    <input type="hidden" name="confirm" value="<?= $confirm ?>">
    </form>
    <p class="note">上記の予約をキャンセルしてよろしいですか</p>
    <input type="submit" form="delete-form" class="submit">
  </div>
<?php else: ?>
  <div id="invalid" class="wrapper">
    <p class="caution">不正な画面遷移です。</p>
    <a href="https://ro-crea.com/demo_hotel/search">予約確認に戻る</a>
  </div>
<?php endif;?>
<footer>
</footer>
<?php wp_footer(); ?>
</body>
</html>