<?php

session_start();

require_once(__DIR__ . '/../app/config.php');
require_once(__DIR__ . '/../app/check-class.php');
require_once(__DIR__ . '/../app/measure-class.php');
use MyApp\database;
$dbh = new PDO(DSN,USER,PASS);

$check = new Check();
// $list = $check->reserveList();

if(!empty($_POST['date']) && !empty($_POST['night']) && !empty($_POST['member']) && !empty($_POST['confirm']))
{
$date = $_POST['date'];
$night = $_POST['night'];
$member = $_POST['member'];
$random = $_POST['random'];
$type = $_POST['type'];
$_SESSION['confirm'] = 1;

}
?>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="reserve.css">
</head>
<body>
<?php if(!empty($_POST['date']) && !empty($_POST['night']) && !empty($_POST['member']) && !empty($_POST['confirm'])): ?>

<div id="reserve-check" class="wrapper">
<form method="post" id="reserve" action="reserve-done.php">
<?php if(!empty($_POST['mail']) || !empty($_POST['mail2'])): ?>
<?php if(preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-])*([.])+([a-zA-Z0-9\._-])+([a-zA-Z0-9\._-])+$/u', $_POST['mail']) == 0 ): ?>
<p  class="caution">入力してるメールアドレスが不適切です。</p>
<?php $mail = ''?>
<?php elseif($_POST['mail'] !== $_POST['mail2']): ?>
<?php $mail = '' ?>
<p  class="caution">入力しているメールアドレスが一致しません。</p>
<?php $mail = '' ?>
<?php else: ?>
<p class="info">メールアドレス： <?php echo $mail = Measure::h($_POST['mail']); ?></p>
<input type="hidden" name="mail" value="<?php echo $mail; ?>">
<?php endif; ?>
<?php else: ?>
<p class="caution">メールアドレスを入力してください。</p>
<?php $mail = '' ?>
<?php endif; ?>

<?php if(!empty($_POST['roma'])): ?>
<?php if(preg_match('/^([  a-z]{4,30})+$/u', $_POST['roma']) == 0 ): ?>
<p class="caution">半角ローマ字で正しく入力してください。</p>
<?php $roma = '' ?>
<?php else:?>
<p class="info">ローマ字(氏名)： <?php echo $roma = Measure::h($_POST['roma']); ?></p>
<input type="hidden" name="roma" value="<?php echo $roma; ?>">
<?php endif; ?>
<?php else:?>
<p class="caution">ローマ字で氏名を入力してください。</p>
<?php $roma = '' ?>
<?php endif; ?>

<?php if(!empty($_POST['name'])): ?>
<?php if(preg_match("/^.{4,30}$/u", $_POST['name']) == 0 ): ?>
<p class="caution">氏名を正しく入力してください。</p>
<p><?php $name = '' ?></p>
<?php else:?>
<p class="info">氏名： <?php echo $name = Measure::h($_POST['name']); ?></p>
<input type="hidden" name="name" value="<?php echo $name; ?>">
<?php endif; ?>
<?php else: ?>
<p class="caution">氏名を入力してください。</p>
<?php $name = '' ?>
<?php endif; ?>


<?php if(!empty($_POST['tel'])): ?>
<?php if(preg_match("/^.[0-9]{9,16}$/u", $_POST['tel']) == 0 ): ?>
<p  class="caution">電話番号を正しく入力してください。</p>
<?php $tel = '' ?>
<?php else:?>
<p class="info">電話番号： <?php echo $tel = Measure::h($_POST['tel']); ?></p>
<input type="hidden" name="tel" value="<?php echo $tel; ?>">
<?php endif; ?>
<?php else: ?>
<p class="caution">電話番号を入力してください。</p>
<?php $tel = '' ?>
<?php endif; ?>

<?php if(!empty($_POST['gender'])): ?>
<p class="info">性別： <?php echo $_POST['gender'] ?></p>
<input type="hidden" name="gender" value="<?php echo Measure::h($_POST['gender']); ?>">
<?php endif;?>

<?php if(!empty($_POST['date']) && !empty($_POST['night']) && !empty($_POST['member'])):?>

<p>予約詳細</p>
<?php $check->reserveList(); ?>
  <?php foreach(array_map(null, $date, $night, $member) as [$text_date, $text_night, $text_member]):?>
    <input type="hidden" name="text_date[]" value="<?= Measure::h($text_date)?>">
    <input type="hidden" name="text_night[]" value="<?= Measure::h($text_night)?>">
    <input type="hidden" name="text_member[]" value="<?= Measure::h($text_member)?>">
  <?php endforeach;?>
  <input type="hidden" name="total" value="<?= $total_price?>">
  <?php endif; ?>

  </form>
  <?php if( $mail != '' && $roma != ''  && $name != '' && $tel != ''):?>
  <input type="submit" class="submit" form="reserve" value="予約を確定">
  <?php else:?>
  <a href="list.php" class="back">リストに戻る</a>
  <?php endif; ?>
</div>
<?php else: ?>
  <p>不正な画面遷移です。</p>
  <a href="calender-res.php">予約状況に戻る</a>
<?php endif;?>
</body>