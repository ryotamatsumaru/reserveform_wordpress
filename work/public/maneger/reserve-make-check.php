<?php

session_start();
session_regenerate_id(true);

if(isset($_SESSION['login'])==false){
  print 'ログインされてません <br>';
  print '<a href="maneger-login.html">ログイン画面へ</a>';
  exit();
}

require_once('header2.php');
require_once('/../work/app/measure-class.php');
require_once('/../work/app/config.php');
use MyApp\database; 
$dbh = new PDO(DSN,USER,PASS);

if(!empty($_POST['day']) && !empty($_POST['night']) && !empty($_POST['member'])){
Measure::create();
$token = $_SESSION['token'];
$confirm = $_POST['confirm'];
$date = $_POST['day'];
$night = $_POST['night'];
$member = $_POST['member'];
// echo $roomtype = $_POST['type'];
echo $type = $_POST['type'];
$random = mt_rand();

$roop_night =  $night - 1;
// ①泊数を-１しないと$periodでループを回した日数合致しない。
for($roop=0; $roop<=$roop_night; $roop++){
  $reserve[] = (int)$member;
  $check2[] = 1;
}
// ②ループを回した泊数分を配列$reserveに室数を代入する。配列$check2には判定用の値１を代入。


$date1 = date('Y-m-d', strtotime($date));
// echo '<br>';
$date2 = date('Y-m-d', strtotime('+'.$night.' day'.$date));
// echo '<br>';
// 受け取った泊数を＄nightに代入。 代入した泊数分ループを回す。

$start = new DateTime($date1);
$interval = new DateInterval('P1D');
$end = new DateTime($date2);
$period =new DatePeriod($start,$interval, $end);

foreach($period as $ymd){
  $date3 = $ymd->format('Y-m-d');
  // $roomtype = $_POST['type'];
  // そもそもbookingにデータがないと日付も合計値も呼び出せない。
  $sql = "SELECT COUNT(*),SUM(member),day FROM (SELECT * FROM booking WHERE day = '$date3' AND type = '$type') as t";
  $ps = $dbh->prepare($sql);
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

  if($_POST['type'] === '0'){
    $roomtype = 'stock';
  } elseif($_POST['type'] === '1') {
    $roomtype = 'daubleroom';
  }
  // echo '<br>';
  $sql2 = "SELECT * FROM $roomtype WHERE day = '$date4'";
  $ps2 = $dbh->prepare($sql2);
  $ps2->execute();
  $row2 = $ps2->fetch(PDO::FETCH_ASSOC);
  $date_dis[] = $row2['inventory'];
  $day[] = $row2['day'];
  $price[] = $row2['price']; 
  $price_array[] = $row2['price'] * $member;
}
$total = array_sum($price_array);
// 価格 × 室数 = 合計額(配列全ての)

foreach(array_map(null, $books_display, $date_dis) as [$books, $dates]){
  $stock[] = $dates - $books;
}
// ③ループを回して配列$date_dis(該当日の予約限度数)から配列$books_display(予約の合計数)
// を差し引いた値を配列$stock(残室数)にそれぞれ代入する。

foreach(array_map(null, $stock, $reserve) as [$stocks, $reserves]){
  if($stocks < $reserves){
    // echo '不可';
    $check[] = 0;
  } else {
    // echo '可能';
    $check[] = 1;
  }
}
// ④配列$stock(残室数)と配列$reserve(予約する室数)をループで回して
// if文で$stock(残室数)を$reserve(予約する室数)が超えた場合,配列$checkに0(予約不可),
// それ以外(残室数が予約数より大きい場合)は配列$checkに1(予約可)を代入する。

// var_dump($check);
// var_dump($check2);

// if($check == $check2) {
//   echo '成功';
// } else {
//   echo '不可';
// }
// ⑤if文で②で配列$check2に代入した判定用の1と④で配列$checkに代入した値を比較。
// 配列$check2泊数分の1と配列$checkの値が全て1(予約可)で合致する場合、予約ページを
// 合致しない場合はエラーページを表示する。
}
?>
<body>
<?php if(!empty($_POST['confirm'])):?>
<?php if(!empty($_POST['day']) && !empty($_POST['night']) && !empty($_POST['member'])): ?>
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
  <?php foreach(array_map(null, $day, $price) as [$days, $prices]): ?>
  <ul class="info-flex">
  <li><?= $days; ?></li>
  <?php if($type == '0'): ?>
  <li class="info">シングル</li>
  <?php elseif($type == '1'): ?>
  <li class="info">ダブル</li>
  <?php endif; ?>
  <li><?= $night; ?>泊</li>
  <li>¥<?= $prices; ?></li>
  <li><?= $member; ?>室</li>
  <li>¥<?= $prices * $member; ?></li>
  </ul>
  <?php endforeach;?>
  <div class="total-area">
  <p class="total">合計:¥<?= $total; ?></p>
  </div>


  <br>

  <form method="post" action="reserve-info-enter.php">
    <p class="reserve-info"><label for="name">氏名:</label></p>
    <p class="reserve-info"><input type="text" name="name" id="name"></p>

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
  <input type="hidden" name="date[]" value="<?= $date ?>">
  <input type="hidden" name="night[]" value="<?= $night ?>">
  <input type="hidden" name="member[]" value="<?= $member ?>">
  <input type="hidden" name="random[]" value="<?= $random ?>">
  <input type="hidden" name="type[]" value="<?= $type ?>">
  <input type="hidden" name="roomtype[]" value="<?= $roomtype ?>">
  <input type="hidden" name="token" value="<?= $token ?>">
  <input type="hidden" name="confirm" value="<?= $confirm ?>">
  <input type="submit" class="submit">
  </form>
  </div>
<?php else: ?>
  <?php echo '該当の条件では予約できません。'; ?>
<?php endif;?>

<?php else: ?>
  <div id="not-set-value" class="wrapper">
  <p class="note">日付を入力してください。</p>
  <a href="reserve-make.php" class="back">予約作成画面に戻る</a>
  </div>
<?php endif;?>

<?php else: ?>
  <div id="invalid" class="wrapper">
  <p>不正な画面遷移です。</p>
  <a href="reserve-make.php">予約作成に戻る</a>
  </div>
<?php endif;?>


</body>