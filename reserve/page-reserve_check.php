<?php
//     Template Name: reserve_check
//     Template Post Type: page
//     Template Path: reserve/

get_header("2");

require_once(dirname(dirname(__FILE__)). '/prvdtb/pdo-class.php');
require_once(dirname(dirname(__FILE__)). '/measure/measure-class.php');
require_once('list-class.php');

$list = new listKeep();

if(!empty($_POST['date']) && !empty($_POST['night']) && !empty($_POST['number']) && !empty($_POST['confirm']))
{
  // CSRF対策用のtoken発行
  Measure::cus_create();
  $cus_token= $_SESSION['cus_token'];

  $date = $_POST['date'];
  $night = $_POST['night'];
  $number = $_POST['number'];
  $random = $_POST['random'];
  $type = $_POST['type'];
  $_SESSION['confirm'] = 1;
}
?>

<section>
  <?php if(!empty($_POST['date']) && !empty($_POST['night']) && !empty($_POST['number']) && !empty($_POST['random']) && !empty($_POST['type']) && !empty($_POST['confirm'])): ?>
    <div id="reserve-check" class="wrapper">
      <form method="post" id="reserve-form" action="https://ro-crea.com/demo_hotel/reserve_done/">

      <?php if(!empty($_POST['mail']) || !empty($_POST['mail2'])): ?>
        <?php if(preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-])*([.])+([a-zA-Z0-9\._-])+([a-zA-Z0-9\._-])+$/u', $_POST['mail']) == 0 ): ?>
          <p class="caution">入力してるメールアドレスが不適切です。</p>
          <?php $mail = ''?>
        <?php elseif($_POST['mail'] !== $_POST['mail2']): ?>
          <?php $mail = '' ?>
          <p class="caution">入力しているメールアドレスが一致しません。</p>
          <?php $mail = '' ?>
        <?php else: ?>
          <p class="info">メールアドレス： <?= $mail = Measure::h($_POST['mail']); ?></p>
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

      <?php if(!empty($_POST['namae'])): ?>
        <?php if(preg_match("/^.{4,30}$/u", $_POST['namae']) == 0 ): ?>
          <p class="caution">氏名を正しく入力してください。</p>
          <?php $name = '' ?>
        <?php else:?>
          <p class="info">氏名： <?php echo $name = Measure::h($_POST['namae']); ?></p>
          <input type="hidden" name="namae" value="<?php echo $name; ?>">
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
        <p class="info">性別：<?php echo Measure::h($_POST['gender']) ?></p>
        <input type="hidden" name="gender" value="<?php echo Measure::h($_POST['gender']); ?>">
      <?php endif;?>

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

      <?php if( $mail != '' && $roma != ''  && $name != '' && $tel != ''):?>
        <input type="submit" class="submit" form="reserve-form" value="確定する">
      <?php else:?>
        <a href="https://ro-crea.com/demo_hotel/list" class="back">リストに戻る</a>
      <?php endif; ?>
	  </div>

  <?php else: ?>
    <div id="invalid" class="wrapper">
      <p class="caution">不正な画面遷移です。</p>
      <a href="https://ro-crea.com/demo_hotel/calendar">予約状況に戻る</a>
    </div>
  <?php endif;?>
</section>
<?php get_footer("3"); ?>