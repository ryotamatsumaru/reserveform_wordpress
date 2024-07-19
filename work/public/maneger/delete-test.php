<?php
session_start();
session_regenerate_id(true);
if(isset($_SESSION['login'])==false){
  print 'ログインされてません <br>';
  print '<a href="maneger-login.html">ログイン画面へ</a>';
  exit();
}

require('header2.php');
require_once('/../work/app/measure-class.php');
require_once('/../work/app/config.php');
use MyApp\database; 

if(isset($_POST['id'])){

Measure::validate();
$token = $_SESSION['token'];

$_SESSION['confirm2'] = $_POST['confirm'];
$confirm = $_SESSION['confirm2'];
foreach($_POST['id'] as $rec_id){
$dbh = new PDO(DSN,USER,PASS);
$sql = "SELECT * FROM booking WHERE ban = :rec_id";
$ps = $dbh->prepare($sql);
$ps->bindValue(':rec_id', $rec_id, PDO::PARAM_INT);
$ps->execute();
$rows = $ps->fetchAll(PDO::FETCH_ASSOC);

foreach($rows as $row){
  $id[] = $row['ban'];
  $mail[] = $row['mail'];
  $roma[] = $row['roma'];
  $name[] = $row['name'];
  $tel[] = $row['tel'];
  $gender[] = $row['gender'];
  $member[] = $row['member'];
  $night[] = $row['night'];
  $day[] = $row['day'];
  $price[] = $row['price'];
  $reserve[] = $row['reserve_date'];
  $random[] = $row['random_id'];
}

}
}
?>
<body>
<?php if(!empty($_POST['confirm'])): ?>
<div id="delete" class="wrapper">
<h2>予約削除</h2>
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
<form method="post" action="reserve-delete-done.php">
<?php foreach(array_map(null, $id, $day, $name, $member, $night, $price, $reserve, $random, $tel, $mail) as [$ids, $days, $names, $members, $nights, $prices, $reserves, $randoms, $tels, $mails]): ?>
  <ul class="info-flex">
  <li class="random"><?= $randoms ?></li>
  <li class="date"><?= $days ?></li>
  <li class="name"><?= $names ?>様</li>
  <li class="room"><?= $members ?>室</li>
  <li class="night"><?= $nights ?>泊</li>
  <li class="price">¥<?= $prices ?></li>
  <li class="tel"><?= $tels ?></li>
  <li class="mail"><?= $mails ?></li>
  </ul>
  <input type="hidden" name="id[]" value="<?= $ids ?>">
  <input type="hidden" name="day[]" value="<?= $days ?>">
  <input type="hidden" name="name[]" value="<?= $names ?>">
  <input type="hidden" name="member[]" value="<?= $members ?>">
  <input type="hidden" name="night[]" value="<?= $nights ?>">
  <input type="hidden" name="price[]" value="<?= $prices ?>">
  <input type="hidden" name="reserve[]" value="<?= $reserves ?>">
  <input type="hidden" name="random[]" value="<?= $randoms ?>">
<?php endforeach; ?>

<?php foreach($_POST['id'] as $ban): ?>
  <input type="hidden" name="row_id[]" value="<?= $ban ?>">
<?php endforeach; ?>

<?php foreach($_POST['night'] as $night): ?>
  <input type="hidden" name="row_night[]" value="<?= $night ?>">
<?php endforeach; ?>
<input type="hidden" name="token" value="<?= $token ?>">
<input type="hidden" name="confirm" value="<?= $confirm ?>">
<p class="note">上記の予約をキャンセルしてよろしいですか</p>
<input type="submit" class="submit">
</form>
</div>
<?php else: ?>
  <div id="invalid" class="wrapper">
  <p>不正な画面遷移です。</p>
  <a href="reservation2.php">予約状況に戻る</a>
  </div>
<?php endif;?>




</body>