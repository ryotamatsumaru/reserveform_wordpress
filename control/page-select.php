<?php
//     Template Name: select
//     Template Post Type: page
//     Template Path: control/

session_start();
// sessionが一致しない場合、ログインの警告ページに遷移する
if(isset($_SESSION['mng_login'])==false){
  header('Location:https://ro-crea.com/demo_hotel/mng_login_caution_text');
  exit();
}

require_once(dirname(dirname(__FILE__)). '/prvdtb/pdo-class.php');
require_once(dirname(dirname(__FILE__)). '/measure/measure-class.php');

if(isset($_POST['show'])){

  $confirm = 1;
  $reserve_date = Measure::h($_POST['reserve_date']);
  $random = Measure::h($_POST['random']);
  $dbh = Database::getPdo();
  // bookingテーブルの予約日とランダムIDから予約を表示する
  $sql = "SELECT * FROM booking WHERE reserve_date = :reserve_date AND random_id = :random";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':reserve_date', $reserve_date, PDO::PARAM_STR);
  $ps->bindValue(':random', $random, PDO::PARAM_STR);
  $ps->execute();
  $rows = $ps->fetchAll(PDO::FETCH_ASSOC);

  $today = date('Y-m-d');

  foreach($rows as $row){
    $id_array[] = $row['id'];
    $checkdates[] = $row['day'];
  }

  // bookingテーブルから呼び出されたidの値の数だけ配列$check2に0を代入する
  $count = count($id_array);
  for($i=1; $i <= $count; $i++){
    $check2[] = 0;
  }
  
  // bookingテーブルから呼び出された予約日が当日以前なら配列$check2に0を代入し、そうでなければ１を代入する
  foreach($checkdates as $checkdate){
    if($checkdate < $today){
      $check[] = 0;
    } else {
      $check[] = 1;
    }
  }

  // html内で$checkと$check2の要素が全て合致した場合、送信ボタンを表示しないという記述をしてる。
  // 表示してる予約全てが当日以前の場合は、送信ボタンを表示させたくないため。(予約変更をさせない)
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
<?php if(isset($_POST['show'])):?>
  <div id="select" class="wrapper">
    <h2>予約状況</h2>
    <ul class="field-flex">
      <li class="checkbox">選択</li>
      <li class="random">予約ID</li>
      <li class="type">タイプ</li>
      <li class="date">日付</li>
      <li class="name">名前</li>
      <li class="room">室数</li>
      <li class="night">泊数</li>
      <li class="price">価格</li>
    </ul>
    <!-- 予約情報が入ってる$rowsからループを回して予約情報を表示する。 -->
    <form method="post" action="https://ro-crea.com/demo_hotel/divide" id="divide-form">
    <?php foreach($rows as $row): ?>
      <ul class="info-flex">
      <?php if( $today <= $row['day']):?>
        <li class="checkbox"><input type="checkbox" name="id[]" value="<?= $row['id']?>"></li>
      <?php else: ?>
        <li class="checkbox"></li>
      <?php endif; ?>
        <li class="random"><?= $row['random_id'] ?></li>
      <?php if($row['type'] === 0):?>
        <li class="type">シングル</li>
      <?php elseif($row['type'] === 1):?>
        <li class="type">ダブル</li>  
      <?php endif; ?>
        <li class="date"><?= $row['day'] ?></li>
        <li class="name"><?= $row['name'] ?></li>
        <li class="room"><?= $row['number'] ?>室</li>
        <li class="night"><?= $row['night'] ?>泊</li>
        <li class="price">¥<?= $row['price'] ?></li>
      </ul>
    <?php endforeach; ?>

    <!-- $checkと$check2の要素が全て合致した場合、表示してる予約全てが当日以前なのでボタンを表示しない -->
    <?php if($check !== $check2): ?>
      <input type="hidden" name="confirm" value="<?= $confirm?>">
      <input type="submit" class="submit" value="送信" form="divide-form">
    <?php endif; ?>
    </form>
  </div>
<?php else:?>
  <div id="invalid" class="wrapper">
    <p class="caution">不正な画面遷移です。</p>
    <a href="https://ro-crea.com/demo_hotel/search/">予約状況に戻る</a>
  </div>
<?php endif;?>
<footer>
</footer>
<?php wp_footer(); ?>
</body>
</html>