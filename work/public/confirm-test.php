<?php

require_once(__DIR__ . '/../app/config.php');
require_once(__DIR__ . '/../app/measure-class.php');
use MyApp\database;

$dbh = new PDO(DSN,USER,PASS);
$measure = new Measure();
session_start();

if(isset($_SESSION['confirm'])){
  unset($_SESSION['confirm']);
  echo '削除済';
}

if(!empty($_POST['dates']) && !empty($_POST['night']) && !empty($_POST['member']))
{
$date = Measure::h($_POST['dates']);
$night = Measure::h($_POST['night']);
$member = Measure::h($_POST['member']);
$random = mt_rand();


$roomtype = $measure->getRoomtype();
$type = $measure->getType();


$roop_night =  $night - 1;
for($roop=0; $roop<=$roop_night; $roop++){
  $reserve[] = (int)$member;
  $check2[] = 1;
}

$date1 = date('Y-m-d', strtotime($date));
$date2 = date('Y-m-d', strtotime('+'.$night.' day'.$date1));

$start = new DateTime($date1);
$interval = new DateInterval('P1D');
$end = new DateTime($date2);
$period =new DatePeriod($start,$interval, $end);

foreach($period as $ymd){
  $date3 = $ymd->format('Y-m-d');
  // そもそもbookingにデータがないと日付も合計値も呼び出せない。
  $sql = "SELECT COUNT(*),SUM(member),day FROM (SELECT * FROM booking WHERE day = :date AND type = :type) as t";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':date', $date3, PDO::PARAM_STR);
  $ps->bindValue(':type', $type, PDO::PARAM_STR);
  $ps->execute();
  $row = $ps->fetch(PDO::FETCH_ASSOC);
  // 予約がない日には0を代入してる。
  if($row['SUM(member)'] == ''){
    $row['SUM(member)'] = (int)'0';
  }
  $day_out = strtotime((string)$row['day']);
  $book_out = (string)$row['SUM(member)'];
  $books_display[date('Y-m-d', $day_out)] = $book_out;
}

// var_dump($books_display);

foreach($period as $ymd2){
  $date4 = $ymd2->format('Y-m-d');
  // echo '<br>';
  $sql2 = "SELECT * FROM $roomtype WHERE day = :date";
  $ps2 = $dbh->prepare($sql2);
  $ps2->bindValue(':date', $date4, PDO::PARAM_STR);
  $ps2->execute();
  $row2 = $ps2->fetch(PDO::FETCH_ASSOC);
  $date_dis[] = $row2['inventory'];
  $day[] = $row2['day'];
  $price[] = $row2['price']; 
  $price_array[] = $row2['price'] * $member;
}
$total = array_sum($price_array);

foreach(array_map(null, $books_display, $date_dis) as [$books, $dates]){
  $stock[] = $dates - $books;
}

foreach(array_map(null, $stock, $reserve) as [$stocks, $reserves]){
  if($stocks < $reserves){
    // echo '不可';
    $check[] = 0;
  } else {
    // echo '可能';
    $check[] = 1;
  }
}

}
?>


<head>
  <meta charset="utf-8">
  <link rel= "stylesheet" href="calender.css" >
</head>
<body>
<?php if(!empty($_POST['dates']) && !empty($_POST['night']) && !empty($_POST['member'])):?>
<?php if($check == $check2):?>
  <?php echo '予約可能'; ?>
  <div id="reserve-confirm" class="wrapper">
  <div class="box">
  <div class="field-flex">  
  <p class="field">日付</p>
  <p class="field">室数</p>
  <p class="field">価格</p>
  <p class="field">小計</p>
  </div>  
  <?php foreach(array_map(null, $day, $price) as [$days, $prices]): ?>
  <ul class="data-flex">
  <li class="data-field"><?= $days; ?></li>
  <li class="data-field"><?= $member; ?>室</li>
  <li class="data-field">¥<?= $prices; ?></li>
  <li class="data-field">¥<?= $prices * $member; ?></li>
  </ul>
  <?php endforeach;?>
  <?php if($type == '0'): ?>
  <p class="info">部屋タイプ: シングル</p>
  <?php elseif($type == '1'): ?>
  <p class="info">部屋タイプ: ダブル</p>
  <?php endif; ?>
  <p class="info">泊数: <?= $night; ?>泊</p>
  <p class="info">合計: ¥<?= $total; ?></p>
  <form method="post" action="list.php">
  <input type="hidden" name="date" value="<?= $date; ?>">
  <input type="hidden" name="night" value="<?= $night; ?>">
  <input type="hidden" name="member" value="<?= $member; ?>">
  <input type="hidden" name="random" value="<?= $random; ?>">
  <input type="hidden" name="type" value="<?= $type; ?>">
  <input type="hidden" name="roomtype" value="<?= $roomtype; ?>">
  <input type="submit" value="日付を確定" class="submit">
  </form>
  <p class="note">※日付を確定を押しても予約はまだ完了しません。</p>
  </div>
  </div>

<?php else: ?>
  <div id="reserve-confirm-worn" class="wrapper">
  <p class="caution">該当の条件では予約できません。</p>
  <p class="caution">泊数が販売停止日を含んでいるか、室数が予約できる残室数を超えています。</p>
  <p class="caution">泊数、室数を調整するか別日程で予約してください。</p>
  </div>
<?php endif;?>
<?php else: ?>
  <p class >不正な画面遷移です。</p>
  <a href="calender-tem.php">予約状況に戻る</a>
<?php endif;?>

</body>