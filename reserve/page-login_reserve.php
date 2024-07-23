<?php
//     Template Name: login_reserve
//     Template Post Type: page
//     Template Path: reserve/

get_header("2");

require_once(dirname(dirname(__FILE__)). '/prvdtb/pdo-class.php');
require_once(dirname(dirname(__FILE__)). '/measure/measure-class.php');
require_once('list-class.php');

$list = new listKeep();
if(!empty($_POST['date']) && !empty($_POST['night']) && !empty($_POST['number']))
{
  // CSRF対策用のtoken発行
  Measure::cus_create();
  $cus_token= $_SESSION['cus_token'];	

  // エスケープ処理
  $mail = Measure::h($_POST['mail']);
  $password = Measure::h($_POST['password']);

  $date = $_POST['date'];
  $night = $_POST['night'];
  $number = $_POST['number'];
  $random = $_POST['random'];
  $type = $_POST['type'];
	
  // 入力されたメールアドレスからDBのmemberテーブルの中で該当する会員情報を呼び出す。
  $database = new Database();
  $dbh = Database::getPdo();
  $sql = "SELECT * FROM member WHERE mail = :mail";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':mail', $mail, PDO::PARAM_STR);
  $ps->execute();
  $rows = $ps->fetch();
  // 該当するメールアドレスがある場合、memberテーブルからパスワードを変数に代入。不正アクセス防止のためにSESSIONに判定用値1を代入する。
  if($rows == true){
    $password2 = $rows['password'];
    $_SESSION['confirm'] = '1';
  }
}

?>
<section>
  <div id="login-check" class="wrapper"> 
    <?php if(!empty($_POST['date']) && !empty($_POST['night']) && !empty($_POST['number'])): ?>
      <?php if(!empty($_POST['mail']) && !empty($_POST['password'])): ?>
        <!-- 該当するメールアドレスがあるか判定 -->
        <?php if($rows == true): ?>
                  <!-- DBに登録されてる暗号化されたパスワードと入力されたパスワードを判定 -->
          <?php if(password_verify($password,$password2)): ?>
            <form method="post" id="reserve-form" action="https://ro-crea.com/demo_hotel/reserve_done" >
            <p class="info">氏名： <?= $rows['name']; ?></p>
            <input type="hidden" name="namae" value="<?php echo $rows['name']; ?>">
            <p class="info">ローマ字(氏名)： <?= $rows['roma']; ?></p>
            <input type="hidden" name="roma" value="<?php echo $rows['roma']; ?>">
            <p class="info">メールアドレス： <?= $rows['mail']; ?></p>
            <input type="hidden" name="mail" value="<?php echo $rows['mail']; ?>">
            <p class="info">電話番号： <?= $rows['tel']; ?></p>
            <input type="hidden" name="tel" value="<?php echo $rows['tel']; ?>">
            <p class="info">性別： <?= $rows['gender']; ?></p>
            <input type="hidden" name="gender" value="<?php echo $rows['gender']; ?>">

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
            </form>

            <p>上記の条件で予約を確定します。</p>
            <input type="submit" form="reserve-form" class="submit" value="予約を確定">

          <?php else: ?>
            <div class="caution-area">
              <p class="caution">パスワードが違います。</p>
              <a href="https://ro-crea.com/demo_hotel/list" class="back">リストに戻る</a>
            </div>
          <?php endif;?>
        <?php else: ?>
          <div class="caution-area">
            <p class="caution">メールアドレスが違います。</p>
            <a href="https://ro-crea.com/demo_hotel/list" class="back">リストに戻る</a>
          </div>
        <?php endif;?>
      <?php else:?>
        <div class="caution-area">
          <p class="caution">メールアドレスとパスワードを入力してください。</p>
          <a href="https://ro-crea.com/demo_hotel/list" class="back">リストに戻る</a>
        </div>
      <?php endif; ?>
    <?php else:?>
      <div id="invalid" class="wrapper">
        <p class="caution">不正な画面遷移です。</p>
        <a href="https://ro-crea.com/demo_hotel/calendar/">予約状況に戻る</a>
      </div>
    <?php endif; ?>
  </div>
</section>
<? get_footer("3") ?>
