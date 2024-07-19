<?php
session_start();
session_regenerate_id(true);
if(isset($_SESSION['login'])==false){
  print 'ログインされてません <br>';
  print '<a href="maneger-login.html">ログイン画面へ</a>';
  exit();
}

require_once('header2.php');

require_once('/../work/app/check-class.php');
require_once('/../work/app/measure-class.php');
require_once('/../work/app/config.php');
use MyApp\database; 

$dbh = new PDO(DSN,USER,PASS);
$check = new Check();

if(!empty($_POST['name']) && !empty($_POST['roma']) && !empty($_POST['mail'])  && !empty($_POST['mail2'])  && !empty($_POST['tel'])){
Measure::validate();

$_SESSION['confirm2'] = 1;
$token = $_SESSION['token'];
$date = $_POST['date'];
$night = $_POST['night'];
$member = $_POST['member'];
$random = $_POST['random'];
$type = $_POST['type'];
$roomtype = $_POST['roomtype'];
}
?>
<body>
<?php if(!empty($_POST['confirm'])): ?>
<div id="book-info-check" class="wrapper">
<div class="text-box">
<form method="post" id="reserve" action="reserve-make-done.php">

<?php if($_POST['name'] == ''): ?>
<p class="caution">氏名が入力されてません。</p>
<?php $name = ''; ?>
<?php elseif(preg_match("/^.{4,30}$/u", $_POST['name']) == 0 ): ?>
<p class="caution">氏名を正しく入力してください。</p>
<?php $name = ''; ?>
<?php else:?>
<p class="info">氏名： <?php echo $name = $_POST['name']; ?></p>
<input type="hidden" name="name" value="<?php echo $name; ?>">
<?php endif; ?>


<?php if($_POST['roma'] == ""): ?>
<p class="caution">ローマ字で氏名が入力されてません。</p>
<?php elseif(preg_match('/^([  a-z])+$/u', $_POST['roma']) == 0 ): ?>
<p class="caution">半角ローマ字で入力してください。</p>
<?php $roma = ''; ?>
<?php else:?>
<p class="info">ローマ字(氏名)： <?php echo $roma = $_POST['roma']; ?></p>
<input type="hidden" name="roma" value="<?php echo $roma; ?>">
<?php endif; ?>

<?php if($_POST['mail'] == "" || $_POST['mail2'] == ""): ?>
<p class="caution">メールアドレスが入力されてません。</p>
<?php $mail = ''; ?>
<?php elseif(preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-])*([.])+([a-zA-Z0-9\._-])+([a-zA-Z0-9\._-])+$/u', $_POST['mail']) == 0 ): ?>
<p class="caution">入力してるメールアドレスが不適切です。</p>
<?php $mail = ''; ?>
<?php elseif($_POST['mail'] !== $_POST['mail2']): ?>
<p class="caution">入力しているメールアドレスが一致しません。</p>
<?php $mail = ''; ?>
<?php else:?>
<p class="info">メールアドレス： <?php echo $mail = $_POST['mail']; ?></p>
<input type="hidden" name="mail" value="<?php echo $mail; ?>">
<?php endif; ?>

<?php if($_POST['tel'] == ''): ?>
<p class="caution">電話番号が入力されてません。</p>
<?php $tel = ''; ?>
<?php elseif(preg_match("/^.[0-9]{9,16}$/u", $_POST['tel']) == 0 ): ?>
<p class="caution">電話番号を正しく入力してください。</p>
<?php $tel = ''; ?>
<?php else:?>
<p class="info">電話番号： <?php echo $tel = $_POST['tel']; ?></p>
<input type="hidden" name="tel" value="<?php echo $tel; ?>">
<?php endif; ?>

<p class="info">性別： <?php echo $_POST['gender'] ?></p>
<input type="hidden" name="gender" value="<?php echo $_POST['gender']; ?>">

<p>予約詳細</p>
<?php $check->reserveList(); ?>

  <input type="hidden" name="random" value="<?= $random ?>">
  <input type="hidden" name="token" value="<?= $token ?>">
  <input type="hidden" name="type" value="<?= $type?>">
  <input type="hidden" name="total" value="<?= $total_price?>">
  </form>
  <?php if(!$mail == '' && !$roma == '' && !$name == '' && !$tel == ''):?>
  <input type="submit" class="submit" form="reserve" value="確定する">
  <?php endif; ?>
  <a href="reserve-make.php" class="back">予約作成に戻る</a>
  </div>
  </div>
  <?php else: ?>
  <div id="invalid" class="wrapper">
  <p>不正な画面遷移です。</p>
  <a href="reserve-make.php">予約作成に戻る</a>
  </div>
  <?php endif;?>

</body>