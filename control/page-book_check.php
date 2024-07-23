<?php
//     Template Name: book_check
//     Template Post Type: page
//     Template Path: control/
//     
session_start();
session_regenerate_id(true);

// sessionが一致しない場合、ログインの警告ページに遷移する
if(isset($_SESSION['mng_login'])==false){
  header('Location:https://ro-crea.com/demo_hotel/mng_login_caution_text');
  exit();
}

require_once(dirname(dirname(__FILE__)). '/measure/measure-class.php');
require_once(dirname(dirname(__FILE__)). '/prvdtb/pdo-class.php');
require_once(dirname(dirname(__FILE__)). '/reserve/list-class.php');

// list-class.phpのクラスを呼び出す。
$list = new listKeep();

if(!empty($_POST['namae']) && !empty($_POST['roma']) && !empty($_POST['mail'])  && !empty($_POST['mail2'])  && !empty($_POST['tel'])){
Measure::validate();

// エスケープ処理
$_SESSION['confirm2'] = 1;
$token = Measure::h($_SESSION['token']);
$date = Measure::h($_POST['date']);
$night = Measure::h($_POST['night']);
$number = Measure::h($_POST['number']);
$random = Measure::h($_POST['random']);
$type = Measure::h($_POST['type']);
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
  <div id="book-info-check" class="wrapper">
    <div class="text-box">
      <form method="post" id="reserve-form" action="https://ro-crea.com/demo_hotel/book_done">

      <?php if($_POST['namae'] == ''): ?>
        <p class="caution">氏名が入力されてません。</p>
        <?php $name = ''; ?>
      <?php elseif(preg_match("/^.{4,30}$/u", $_POST['namae']) == 0 ): ?>
        <p class="caution">氏名を正しく入力してください。</p>
        <?php $name = ''; ?>
      <?php else:?>
        <p class="info">氏名： <?php echo $name = Measure::h($_POST['namae']); ?></p>
        <input type="hidden" name="namae" value="<?php echo $name; ?>">
      <?php endif; ?>

      <?php if($_POST['roma'] == ""): ?>
        <p class="caution">ローマ字で氏名が入力されてません。</p>
      <?php elseif(preg_match('/^([  a-z])+$/u', $_POST['roma']) == 0 ): ?>
        <p class="caution">半角ローマ字で入力してください。</p>
        <?php $roma = ''; ?>
      <?php else:?>
        <p class="info">ローマ字(氏名)： <?php echo $roma = Measure::h($_POST['roma']); ?></p>
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
        <p class="info">メールアドレス： <?php echo $mail = Measure::h($_POST['mail']); ?></p>
        <input type="hidden" name="mail" value="<?php echo $mail; ?>">
      <?php endif; ?>

      <?php if($_POST['tel'] == ''): ?>
        <p class="caution">電話番号が入力されてません。</p>
        <?php $tel = ''; ?>
      <?php elseif(preg_match("/^.[0-9]{9,16}$/u", $_POST['tel']) == 0 ): ?>
        <p class="caution">電話番号を正しく入力してください。</p>
        <?php $tel = ''; ?>
      <?php else:?>
        <p class="info">電話番号： <?php echo $tel = Measure::h($_POST['tel']); ?></p>
        <input type="hidden" name="tel" value="<?php echo $tel; ?>">
      <?php endif; ?>

      <p class="info">性別： <?php echo Measure::h($_POST['gender']) ?></p>
      <input type="hidden" name="gender" value="<?php echo Measure::h($_POST['gender']); ?>">

      <p>予約詳細</p>
      <!-- reserve/list-class.phpから予約情報を表示させるメソッド。 -->
      <?php $list->reserveStrList(); ?>

      <input type="hidden" name="random" value="<?= $random ?>">
      <input type="hidden" name="token" value="<?= $token ?>">
      <input type="hidden" name="type" value="<?= $type?>">
      </form>

      <?php if(!$mail == '' && !$roma == '' && !$name == '' && !$tel == ''):?>
        <input type="submit" class="submit" form="reserve-form" value="確定する">
      <?php endif; ?>
      <a href="https://ro-crea.com/demo_hotel/book_make" class="back">予約作成に戻る</a>
    </div>
  </div>
<?php else: ?>
  <div id="invalid" class="wrapper">
    <p class="caution">不正な画面遷移です。</p>
    <a href="https://ro-crea.com/demo_hotel/book_make">予約作成に戻る</a>
  </div>
<?php endif;?>

<footer>
</footer>
<?php wp_footer(); ?>
</body>
</html>