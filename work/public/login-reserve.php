<?php
session_start();

require_once(__DIR__ . '/../app/config.php');
require_once(__DIR__ . '/../app/check-class.php');
require_once(__DIR__ . '/../app/measure-class.php');
use MyApp\database;
$dbh = new PDO(DSN,USER,PASS);

$check = new Check();

if(!empty($_POST['date']) && !empty($_POST['night']) && !empty($_POST['member']))
{
$mail = Measure::h($_POST['mail']);
$password = Measure::h($_POST['password']);

$date = $_POST['date'];
$night = $_POST['night'];
$member = $_POST['member'];
$random = $_POST['random'];
$type = $_POST['type'];
$sql = "SELECT * FROM member WHERE mail = :mail";
$ps = $dbh->prepare($sql);
$ps->bindValue(':mail', $mail, PDO::PARAM_STR);
$ps->execute();
$rows = $ps->fetch();
$password2 = $rows['password'];
$_SESSION['confirm'] = '1';
}

?>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="reserve.css">
</head>
<body>
<div id="login-check" class="wrapper">
<?php if(!empty($_POST['date']) && !empty($_POST['night']) && !empty($_POST['member'])): ?>
<?php if(!empty($_POST['mail']) && !empty($_POST['password'])): ?>
  <?php if($rows == true): ?>
  <?php if(password_verify($password,$password2)): ?>
    <form method="post" id="reserve" action="reserve-done.php" >
    <p class="info">氏名： <?= $rows['name']; ?></p>
    <input type="hidden" name="name" value="<?php echo $rows['name']; ?>">
    <p class="info">ローマ字(氏名)： <?= $rows['roma']; ?></p>
    <input type="hidden" name="roma" value="<?php echo $rows['roma']; ?>">
    <p class="info">メールアドレス： <?= $rows['mail']; ?></p>
    <input type="hidden" name="mail" value="<?php echo $rows['mail']; ?>">
    <p class="info">電話番号： <?= $rows['tel']; ?></p>
    <input type="hidden" name="tel" value="<?php echo $rows['tel']; ?>">
    <p class="info">性別： <?= $rows['gender']; ?></p>
    <input type="hidden" name="gender" value="<?php echo $rows['gender']; ?>">

  <p>予約詳細</p>
  <?php $check->reserveList(); ?>
  <?php foreach(array_map(null, $date, $night, $member) as [$text_date, $text_night, $text_member]):?>
    <input type="hidden" name="text_date[]" value="<?= Measure::h($text_date)?>">
    <input type="hidden" name="text_night[]" value="<?= Measure::h($text_night)?>">
    <input type="hidden" name="text_member[]" value="<?= Measure::h($text_member)?>">
  <?php endforeach;?>
  <input type="hidden" name="total" value="<?= $total_price?>">
  </form>

  <p>上記の条件で予約を確定します。</p>
  <input type="submit" class="submit" form="reserve" value="予約を確定">

  <?php else: ?>
    <div class="caution-area">
    <p class="caution">パスワードが違います。</p>
    <a href="list.php" class="back">リストに戻る</a>
    </div>
  <?php endif;?>
  <?php else: ?>
    <div class="caution-area">
    <p class="caution">メールアドレスが違います。</p>
    <a href="list.php" class="back">リストに戻る</a>
    </div>
  <?php endif;?>
  <?php else:?>
    <div class="caution-area">
    <p class="caution">メールアドレスとパスワードを入力してください。</p>
    <a href="list.php" class="back">リストに戻る</a>
    </div>
  <?php endif; ?>
  </div>
  <?php else:?>
  <p>不正な画面遷移です。</p>
  <a href="calender-res.php">予約状況に戻る</a>
  <?php endif; ?>
</body>
