<?php
//     Template Name: date_confirm
//     Template Post Type: page
//     Template Path: reserve/
get_header();

require_once(dirname(dirname(__FILE__)). '/prvdtb/pdo-class.php');
require_once(dirname(dirname(__FILE__)). '/measure/measure-class.php');

$measure = new Measure();

// 日付、泊数、室数が入力されてるか判定。
if(!empty($_POST['dates']) && !empty($_POST['night']) && !empty($_POST['member']))
{
  $date = Measure::h($_POST['dates']);
  $night = Measure::h($_POST['night']);
  $number = Measure::h($_POST['number']);

  // ランダムな予約IDを作成する変数。
  $random = mt_rand();

  // Measure-class.phpの中でPOSTで受け取った部屋タイプの変数と部屋タイプ名を判定して$roomtypeと$typeに代入する。
  $roomtype = $measure->getRoomtype();
  $type = $measure->getType();

  // $nightに代入された値の数だけ室数($number)を配列reserveに代入。判定用の値1を配列$check2に代入。
  for($roop=1; $roop<=$night; $roop++){
    $reserve[] = $number;
    $check2[] = 1;
  }

  $date1 = date('Y-m-d', strtotime($date));
  $date2 = date('Y-m-d', strtotime('+'.$night.' day'.$date1));

  $start = new DateTime($date1);
  $interval = new DateInterval('P1D');
  $end = new DateTime($date2);
  $period =new DatePeriod($start,$interval, $end);

  // $periodに代入された宿泊期間の1日あたりの合計予約数をforeachを使って呼び出して変数$book_outに代入する。
  foreach($period as $ymd){
    $date3 = $ymd->format('Y-m-d');
    $dbh = Database::getPdo();
    $sql = "SELECT COUNT(*),SUM(number),day FROM (SELECT * FROM booking WHERE day = :date AND type = :type) as booktotal GROUP BY day";
    $ps = $dbh->prepare($sql);
    $ps->bindValue(':date', $date3, PDO::PARAM_STR);
    $ps->bindValue(':type', $type, PDO::PARAM_STR);
    $ps->execute();
    $row = $ps->fetch(PDO::FETCH_ASSOC);
    // 予約がない日には0を代入してる。
    if($row['SUM(member)'] == ''){
      $row['SUM(member)'] = '0';
    }
    $day_out = strtotime($row['day']);
    $book_out = $row['SUM(number)'];
    // 日付をキーにした配列に$book_out(合計予約数)を代入して、1日あたりの予約数が格納される。
    $books_total[date('Y-m-d', $day_out)] = $book_out;
  }

  // $periodに代入された宿泊期間とDBの部屋タイプテーブルから日付、在庫数、料金を呼び出してそれぞれの配列に代入する。
  foreach($period as $ymd2){
    $date3 = $ymd2->format('Y-m-d');
    $sql2 = "SELECT * FROM $roomtype WHERE day = :date";
    $ps2 = $dbh->prepare($sql2);
    $ps2->bindValue(':date', $date3, PDO::PARAM_STR);
    $ps2->execute();
    $row2 = $ps2->fetch(PDO::FETCH_ASSOC);

    $date_inv[] = $row2['inventory'];
    $day[] = $row2['day'];
    $price[] = $row2['price']; 
    // 配列$price_arrayに価格と室数をかけたもの（小計）を代入する。
    $price_array[] = $row2['price'] * $number;
  }
  // 合計金額をtotalに代入。
  $total = array_sum($price_array);

  // $date_inv(在庫数)から$books_display(日毎の合計予約数)を引いた値を配列$stockに代入。
  foreach(array_map(null, $books_total, $date_inv) as [$books, $dates]){
    $stock[] = $dates - $books;
  }

  // $reserve(これから予約する室数)が$stockを超えていれば$checkに0を代入。超えていなければ1を代入する。
  foreach(array_map(null, $stock, $reserve) as [$stocks, $reserves]){
    if($stocks < $reserves){
      // 予約不可;
      $check[] = 0;
    } else {
      // 可能;
      $check[] = 1;
    }
  }

  // $check2には泊数に応じて必ず1を代入しているが、$checkには$stockを超えていない場合のみ1を代入させ、html内の$checkと$check2の値1が全て合致しないと予約確認画面を表示させない。
  }
?>

<section>
  <?php if(!empty($_POST['dates']) && !empty($_POST['night']) && !empty($_POST['number'])):?>
    <!-- $checkと$check2の1が全て合致しないと予約確認画面を表示させない。 -->
    <?php if($check == $check2):?>
      <div id="reserve-confirm" class="wrapper">
        <div class="box">
          <div class="field-flex">  
            <p class="field">日付</p>
            <p class="field">室数</p>
            <p class="field">価格</p>
            <p class="field">小計</p>
          </div>
          <?php foreach(array_map(null, $day2, $price) as [$days, $prices]): ?>
            <ul class="data-flex">
              <li class="data-field"><?= $days; ?></li>
              <li class="data-field"><?= $number; ?>室</li>
              <li class="data-field">¥<?= $prices; ?></li>
              <li class="data-field">¥<?= $prices * $number; ?></li>
            </ul>
          <?php endforeach;?>
          <?php if($type == '0'): ?>
            <p class="info">部屋タイプ: シングル</p>
          <?php elseif($type == '1'): ?>
            <p class="info">部屋タイプ: ダブル</p>
          <?php endif; ?>
          <p class="info">泊数: <?= $night; ?>泊</p>
          <p class="info">合計: ¥<?= $total; ?></p>
          <form method="post" action="https://ro-crea.com/demo_hotel/list/">
          <input type="hidden" name="date" value="<?= $date; ?>">
          <input type="hidden" name="night" value="<?= $night; ?>">
          <input type="hidden" name="number" value="<?= $number; ?>">
          <input type="hidden" name="random" value="<?= $random; ?>">
          <input type="hidden" name="type" value="<?= $type; ?>">
          <input type="hidden" name="roomtype" value="<?= $roomtype; ?>">
          <input type="submit" value="日付を確定" class="submit">
          </form>
          <p class="note">※日付を確定を押しても予約はまだ完了しません。</p>
        </div>
      </div>
      <!-- $checkと$check2の1が全て合致しない場合、予約ができない画面に遷移する。 -->
    <?php else: ?>
      <div id="reserve-confirm-worn" class="wrapper">
        <p class="caution">該当の条件では予約できません。</p>
        <p class="caution">泊数が販売停止日を含んでいるか、室数が予約できる残室数を超えています。</p>
        <p class="caution">泊数、室数を調整するか別日程で予約してください。</p>
      </div>
    <?php endif;?>
  <?php else: ?>
    <div id="invalid" class="wrapper">
      <p class="caution">不正な画面遷移です。</p>
      <a href="https://ro-crea.com/demo_hotel/calendar/">予約状況に戻る</a>
    </div>
  <?php endif;?>
</section>
<?php get_footer("3"); ?>
