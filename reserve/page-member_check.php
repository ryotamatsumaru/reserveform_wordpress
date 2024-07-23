<?php
//     Template Name: member_check
//     Template Post Type: page
//     Template Path: reserve/

get_header("2");

require_once(dirname(dirname(__FILE__)). '/prvdtb/pdo-class.php');
require_once(dirname(dirname(__FILE__)). '/measure/measure-class.php');
require_once('list-class.php');


$list = new listKeep();

if(!empty($_POST['date']) && !empty($_POST['night']) && !empty($_POST['number']) && !empty($_POST['random']) && !empty($_POST['type'])&& !empty($_POST['confirm']))
{

  // CSRF対策のためのtoken
  Measure::cus_create();
  $cus_token = $_SESSION['cus_token'];
	
  $date = $_POST['date'];
  $night = $_POST['night'];
  $number = $_POST['number'];
  $random = $_POST['random'];
  $type = $_POST['type'];
  $_SESSION['confirm'] = 1;

  // DBのmemberテーブルから重複するメールアドレスが確認する。
  if(isset($_POST['mail'])){
    $mail = Measure::h($_POST['mail']);
    $dbh = Database::getPdo();
    $sql = "SELECT * FROM member WHERE mail = :mail";
    $ps = $dbh->prepare($sql);
    $ps->bindValue(':mail', $mail, PDO::PARAM_STR);
    $ps->execute();
    $rows = $ps->fetchAll(PDO::FETCH_ASSOC);
  }
}

?>
<body>
<section> 
  <?php if(!empty($_POST['date']) && !empty($_POST['night']) && !empty($_POST['number']) && !empty($_POST['confirm'])):?>

  <div id="member-check" class="wrapper">
    <form method="post" id="member-form" action="https://ro-crea.com/demo_hotel/member_done/">
    <?php if(!empty($_POST['mail']) || !empty($_POST['mail2'])): ?>
      <?php if(preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-])*([.])+([a-zA-Z0-9\._-])+([a-zA-Z0-9\._-])+$/u', $_POST['mail']) == 0 ): ?>
        <p class="caution">入力してるメールアドレスが不適切です。</p>
        <?php $mail = '' ?>
      <?php elseif($_POST['mail'] !== $_POST['mail2']): ?>
        <?php $mail = '' ?>
        <p class="caution">入力しているメールアドレスが一致しません。</p>
        <?php $mail = '' ?>
      <!-- 重複しているアドレスがある場合は弾く -->
      <?php elseif($rows == true):?> 
        <p class="caution">入力しているメールアドレスはすでに登録されています。</p>
        <?php $mail = ''; ?>
      <?php else: ?>
        <p class="info">メールアドレス： <?= $mail = $_POST['mail']; ?></p>
        <input type="hidden" name="mail" value="<?= Measure::h($mail); ?>">
      <?php endif; ?>
    <?php else: ?>
      <p class="caution">メールアドレスが入力されてません。</p>
      <?php $mail = '' ?>
    <?php endif; ?>

    <?php if(!empty($_POST['roma'])): ?>
      <?php if(preg_match('/^([  a-z]{4,30})+$/u', $_POST['roma']) == 0 ): ?>
        <p class="caution">半角ローマ字で正しく入力してください。</p>
        <?php $roma = '' ?>
      <?php else:?>
        <p class="info">ローマ字(氏名)： <?= $roma = Measure::h($_POST['roma']); ?></p>
        <input type="hidden" name="roma" value="<?= $roma; ?>">
      <?php endif; ?>
    <?php else:?>
      <p class="caution">ローマ字で氏名が入力されてません。</p>
      <?php $roma = '' ?>
    <?php endif; ?>

    <?php if(!empty($_POST['namae'])): ?>
      <?php if(preg_match("/^.{4,30}$/u", $_POST['namae']) == 0 ): ?>
        <p class="caution">氏名を正しく入力してください。</p>
        <?php $name = '' ?>
      <?php else:?>
        <p class="info">氏名： <?= $name = Measure::h($_POST['namae']); ?></p>
        <input type="hidden" name="namae" value="<?= $name; ?>">
      <?php endif; ?>
    <?php else: ?>
      <p  class="caution">氏名が入力されてません。</p>
      <?php $name = '' ?>
    <?php endif; ?>

    <?php if(!empty($_POST['tel'])): ?>
      <?php if(preg_match("/^[0-9]{10,16}$/u", $_POST['tel']) == 0 ): ?>
        <p class="caution">電話番号を正しく入力してください。</p>
        <?php $tel = '' ?>
      <?php else:?>
        <p class="info">電話番号： <?php echo $tel = Measure::h($_POST['tel']); ?></p>
        <input type="hidden" name="tel" value="<?= $tel; ?>">
      <?php endif; ?>
    <?php else: ?>
      <p class="caution">電話番号が入力されてません。</p>
      <?php $tel = '' ?>
    <?php endif; ?>

    <?php if(!empty($_POST['gender'])): ?>
      <p class="info">性別： <?php echo Measure::h($_POST['gender']) ?></p>
      <input type="hidden" name="gender" value="<?php echo Measure::h($_POST['gender']); ?>">
    <?php endif;?>

    <?php if(!empty($_POST['b_year']) && !empty($_POST['b_month']) && !empty($_POST['b_day'])): ?>
      <?php $b_month = Measure::h($_POST['b_month']); ?>
      <?php $b_year = Measure::h($_POST['b_year']); ?>
      <?php $b_day = Measure::h($_POST['b_day']); ?>
      <p class="info">生年月日： <?= $b_year;?>年 <?= $b_month; ?>月 <?= $b_day;?>日</p>
      <input type="hidden" name="b_year" value="<?= $b_year; ?>">
      <input type="hidden" name="b_month" value="<?= $b_month; ?>">
      <input type="hidden" name="b_day" value="<?= $b_day; ?>">
    <?php endif; ?>

    <?php if(!empty($_POST['postal'])): ?>
      <?php if(preg_match('/^([0-9]){7}$/u', $_POST['postal']) == 0 ): ?>
        <p  class="caution">郵便番号が正しく入力されてません。</p>
        <?php $postal = ''; ?>
      <?php else:?>
        <p class="info">郵便番号： <?php echo $postal = Measure::h($_POST['postal']); ?></p>
        <input type="hidden" name="postal" value="<?= $postal; ?>">
      <?php endif; ?>
    <?php else: ?>
      <p  class="caution">郵便番号が入力されてません。</p>
      <?php $postal = '' ?>
    <?php endif; ?>

    <?php if(!empty($_POST['prefecture'])): ?>
      <p class="info">都道府県： <?= $prefecture = Measure::h($_POST['prefecture'])?></p>
      <input type="hidden" name="prefecture" value="<?= $prefecture; ?>">
    <?php endif;?>

    <?php if(!empty($_POST['postal'])): ?>
      <?php if(preg_match("/^[ぁ-んァ-ヶ亜-熙].{6,20}$/u", $_POST['address']) == 0 ): ?>
        <p class="caution">住所を正しく入力してください。</p>
        <?php $address = ''; ?>
      <?php else:?>
        <p class="info">住所： <?php echo $address = Measure::h($_POST['address']) ?></p>
        <input type="hidden" name="address" value="<?= $address; ?>">
      <?php endif; ?>
    <?php else: ?>
      <p class="caution">住所が入力されてません。</p>
      <?php $address = '' ?>
    <?php endif; ?>

    <?php if(!empty($_POST['password']) || !empty($_POST['password2'])): ?>
      <?php if(preg_match('/^.[0-9a-zA-Z_]{6,15}$/u', $_POST['password']) == 0 ): ?>
        <p class="caution">入力してるパスワードが不適切です。</p>
        <?php $password = ''; ?>
      <?php elseif($_POST['password'] !== $_POST['password2']): ?>
        <p class="caution">入力しているメールアドレスが一致しません。</p>
        <?php $password = '' ?>
      <?php else: ?>
        <p class="info">パスワード： <?= $password = Measure::h($_POST['password'])?></p>
        <input type="hidden" name="password" value="<?= $password ?>">
      <?php endif; ?>
    <?php else: ?>
      <p  class="caution">パスワードが入力されてません。</p>
      <?php $password = ''; ?>
    <?php endif; ?>

    <?php if(!empty($_POST['date']) && !empty($_POST['night']) && !empty($_POST['number'])):?>
      <p>予約詳細</p>
      <!-- list-class.phpより予約リストを表示させるためのメソッド -->
      <?php $list->reserveList(); ?>
      <?php foreach(array_map(null, $date, $night, $number) as [$text_date, $text_night, $text_number]):?>
        <input type="hidden" name="text_date[]" value="<?= Measure::h($text_date)?>">
        <input type="hidden" name="text_night[]" value="<?= Measure::h($text_night)?>">
        <input type="hidden" name="text_number[]" value="<?= Measure::h($text_number)?>">
      <?php endforeach;?>
      <input type="hidden" name="total" value="<?= $total_price?>">
      <!-- CSRF対策のためのtoken -->
      <input type="hidden" name="cus_token" value="<?= $cus_token?>">
    <?php endif; ?>
    </form>

    <?php if(!$mail == '' && !$roma == '' && !$name == '' && !$tel == '' && !$postal == '' && !$address == '' && !$password == ''):?>
      <input type="submit" class="submit" form="member-form" value="確定する">
    <?php else:?>
      <a href="https://ro-crea.com/demo_hotel/list" class="back">リストに戻る</a>
    <?php endif; ?>
  </div>
  <?php else:?>
    <div id="invalid" class="wrapper">
      <p class="caution">不正な画面遷移です。</p>
      <a href="https://ro-crea.com/demo_hotel/calendar">予約状況に戻る</a>
    </div>
  <?php endif; ?>
</section>
<?php get_footer("3"); ?>