<?php
//     Template Name: book_info
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

if(!empty($_POST['date']) && !empty($_POST['night']) && !empty($_POST['number'])){
  if(date('Y-m-d') <= $_POST['date'] ){
    Measure::create();
    $token = $_SESSION['token'];
    $confirm = Measure::h($_POST['confirm']);
    $date = Measure::h($_POST['date']);
    $night = Measure::h($_POST['night']);
    $number = Measure::h($_POST['number']);
    $type = Measure::h($_POST['type']);
    $random = mt_rand();
    $roop_night =  $night - 1;
    // 宿泊日数分、室数を配列$reserveに代入する。また宿泊日数分、判定用の値として配列$check2に1を代入する。
    for($roop=0; $roop<=$roop_night; $roop++){
      $reserve[] = $number;
      $check2[] = 1;
    }

    $date1 = date('Y-m-d', strtotime($date));
    $date2 = date('Y-m-d', strtotime('+'.$night.' day'.$date));
    // 受け取った泊数を＄nightに代入。 代入した泊数分ループを回す。

    $start = new DateTime($date1);
    $interval = new DateInterval('P1D');
    $end = new DateTime($date2);
    $period =new DatePeriod($start,$interval, $end);

    // 宿泊期間をループに回してbookingテーブルから該当期間の1日あたりの合計予約数を求める。
    foreach($period as $ymd){
      $date3 = $ymd->format('Y-m-d');
      $dbh = Database::getPdo();
      $sql = "SELECT COUNT(*),SUM(number),day FROM (SELECT * FROM booking WHERE day = :date AND type = :type) as booktotal GROUP BY day";
      $ps = $dbh->prepare($sql);
      $ps->bindValue(':date', $date3, PDO::PARAM_STR);
      $ps->bindValue(':type', $type, PDO::PARAM_INT);
      $ps->execute();
      $row = $ps->fetch(PDO::FETCH_ASSOC);
      // 予約がない日には0を代入してる。    
      if($row == '') {
	      $row_date = '0';
        $sum_number = '0';
      } else {
        $row_date = $row['day'];
        $sum_number = $row['SUM(number)'];
      }
      $day_out = strtotime($row_date);
      $book_out = $sum_number;
      $books_display[date('Y-m-d', $day_out)] = $book_out;
    }

    // 宿泊期間と部屋タイプをループで回してそれぞれの配列に代入する。
    foreach($period as $ymd2){
      $date4 = $ymd2->format('Y-m-d');
      if($_POST['type'] === '0'){
        $roomtype = 'singleroom';
      } elseif($_POST['type'] === '1') {
        $roomtype = 'doubleroom';
      }
      $dbh = Database::getPdo();
      $sql2 = "SELECT * FROM $roomtype WHERE day = :date";
      $ps2 = $dbh->prepare($sql2);
      $ps2->bindValue(':date', $date4, PDO::PARAM_STR);
      $ps2->execute();
      $row2 = $ps2->fetch(PDO::FETCH_ASSOC);
      // DBの部屋タイプテーブルにデータがあるか判定する
      if($row2 == true) {
        $date_dis[] = $row2['inventory'];
        $day2[] = $row2['day'];
        $price[] = $row2['price']; 
        $price_array[] = $row2['price'] * $number;
      } else {
        // wordpressのローカル環境でエラーが出たので値を入れる処理をした
        $date_dis[] = '0';
        $day2[] = '0';
        $price[] = '0'; 
        $price_array[] = '0';
      }
    }
    $total = array_sum($price_array);

    // 該当日の在庫から合計予約数を引いた値を、配列$stock(残室)に代入
    foreach(array_map(null, $books_display, $date_dis) as [$books, $dates]){
      $stock[] = $dates - $books;
    }

    // 配列$stock(残室)より予約数が上回った場合、0を代入する。そうでなければ1を代入する
    foreach(array_map(null, $stock, $reserve) as [$stocks, $reserves]){
      if($stocks < $reserves){
        $check[] = 0;
      } else {
        $check[] = 1;
      }
    }
    // html内で配列$check2(宿泊数)と配列$check(在庫数を超えてるか超えていないかで代入された値)、をifで判定し配列が全て合致しなければ処理を弾く。(配列$checkの要素が全て1の値の予約作成が可能)
    // オーバーブッキング対策
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
<?php if(!empty($_POST['confirm'])):?>
  <?php if(!empty($_POST['date']) && !empty($_POST['night']) && !empty($_POST['number'])): ?>
    <?php if( date('Y-m-d') <= $_POST['date'] ):?>
      <?php if($check == $check2):?>
        <div id="book-make-check" class="wrapper">
          <ul class="field-flex">
            <li>日付</li>
            <li>タイプ</li>
            <li>泊数</li>
            <li>価格</li>
            <li>室数</li>
            <li>小計</li>
          </ul>

          <?php foreach(array_map(null, $day2, $price) as [$days, $prices]): ?>
            <ul class="info-flex">
              <li><?= $days; ?></li>
              <?php if($type == '0'): ?>
                <li class="info">シングル</li>
              <?php elseif($type == '1'): ?>
                <li class="info">ダブル</li>
              <?php endif; ?>
              <li><?= $night; ?>泊</li>
              <li>¥<?= $prices; ?></li>
              <li><?= $number; ?>室</li>
              <li>¥<?= $prices * $number; ?></li>
            </ul>
          <?php endforeach;?>
          <div class="total-area">
            <p class="total">合計:¥<?= $total; ?></p>
          </div>

          <form method="post" action="https://ro-crea.com/demo_hotel/book_check">
          <p class="reserve-info"><label for="name">氏名:</label></p>
          <p class="reserve-info"><input type="text" name="namae" id="name"></p>
          <p class="reserve-info"><label for="roma">氏名(ローマ字):</label></p>
          <p class="reserve-info"><input type="text" name="roma" id="roma"></p>
          <p class="reserve-info"><label for="mail">メールアドレス:</label></p>
          <p class="reserve-info"><input type="text" name="mail" id="mail"></p>
          <p class="reserve-info"><label for="mail2">確認のため再度入力してください。</label></p>
          <p class="reserve-info"><input type="text" name="mail2" id="mail2"></p>
          <p class="reserve-info"><label for="tel">電話番号:</label></p>
          <p class="reserve-info"><input type="text" name="tel" id="tel"></p>
          <p class="reserve-info">
          <input type="radio" name="gender" value="男" checked>男
          <input type="radio" name="gender" value="女">女
          </p>
          <input type="hidden" name="date" value="<?= $date ?>">
          <input type="hidden" name="night" value="<?= $night ?>">
          <input type="hidden" name="number" value="<?= $number ?>">
          <input type="hidden" name="random" value="<?= $random ?>">
          <input type="hidden" name="type" value="<?= $type ?>">
          <input type="hidden" name="roomtype" value="<?= $roomtype ?>">
          <input type="hidden" name="token" value="<?= $token ?>">
          <input type="hidden" name="confirm" value="<?= $confirm ?>">
          <input type="submit" class="submit">
          </form>
        </div>
      <?php else: ?>
        <div id="not-set-value" class="wrapper">
          <p class="note">該当の条件では予約できません。</p>
          <a href="https://ro-crea.com/demo_hotel/book_make/" class="back">予約作成画面に戻る</a>
        </div>
      <?php endif;?>

    <?php else: ?>
      <div id="not-set-value" class="wrapper">
        <p class="note">過去日は予約できません。</p>
        <a href="https://ro-crea.com/demo_hotel/book_make/" class="back">予約作成画面に戻る</a>
      </div>
    <?php endif;?>

  <?php else: ?>
    <div id="not-set-value" class="wrapper">
      <p class="note">日付を入力してください。</p>
      <a href="https://ro-crea.com/demo_hotel/book_make/" class="back">予約作成に戻る</a>
    </div>
  <?php endif;?>

<?php else: ?>
  <div id="invalid" class="wrapper">
    <p class="caution">不正な画面遷移です。</p>
    <a href="https://ro-crea.com/demo_hotel/book_make/">予約作成に戻る</a>
  </div>
<?php endif;?>
<footer>
</footer>
<?php wp_footer(); ?>
</body>
</html>