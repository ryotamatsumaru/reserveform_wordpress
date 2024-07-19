<?php

session_start();

require_once('prefecture.php');
require_once(__DIR__ . '/../app/list-class.php');
require_once(__DIR__ . '/../app/measure-class.php');
require_once(__DIR__ . '/../app/config.php');
use MyApp\database;
$dbh = new PDO(DSN,USER,PASS);
$list = new listKeep();

// echo $test = $measure->getRoomtype();

if(isset($_SESSION['date']) == true && isset($_SESSION['night']) == true && isset($_SESSION['member']) == true && isset($_SESSION['random']) == true && isset($_SESSION['type']) == true){
$date = $_SESSION['date'];
$night = $_SESSION['night'];
$member = $_SESSION['member'];
$random = $_SESSION['random'];
$type = $_SESSION['type'];
$roomtype = $_SESSION['roomtype'];

if(isset($_SESSION['confirm'])){
  unset($_SESSION['confirm']);
  echo '削除済';
}

}

if(isset($_POST['member']) && isset($_POST['night']) && isset($_POST['member'])){
  $date[] = Measure::h($_POST['date']);
  $_SESSION['date'] = $date;
  $night[] = Measure::h($_POST['night']);
  $_SESSION['night'] = $night;
  $member[] = Measure::h($_POST['member']);
  $_SESSION['member'] = $member;
  $random[] = Measure::h($_POST['random']);
  $_SESSION['random'] = $random;
  $type[] = Measure::h($_POST['type']);
  $_SESSION['type'] = $type;
  $roomtype[] = $_POST['roomtype'];
  $_SESSION['roomtype'] = $roomtype;
  header('Location: http://localhost:8569/list.php');
}

?>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="tab.css">
  <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <link rel="stylesheet" type="text/css" href="reserve-list.css">
  <!-- <script src="tab.js"></script> -->
</head>
<body>

  <div id="reserve-list" class="wrapper">
  <?php $keeplist = $list->keepList();?>

  <?php if(empty($_SESSION['date'])): ?>
  <div class="nolist">
  <p class="coution">予約がリストに入ってません。</p>
  <a href="calender-tem.php" class="back">予約カレンダーに戻る</a>
  </div>
  <?php else: ?>
  <a href="calender-tem.php" class="back">予約を追加する</a>
  
  <ul class="tab">
  <li><a href="#reserve1">このまま予約</a></li>
  <li><a href="#reserve2">会員登録して予約</a></li>
  <li><a href="#reserve3">会員として予約</a></li>
  </ul>
  <div id="reserve1" class="area">
  <form method="post" action="reserve-enter.php">
    <p class="note" >※このまま予約画面に進む方は下記の必要事項を全て入力した上で予約するを押してください。</p>
    <p class="reserve-info">
      <label for="mail">メールアドレス:</label>
    </p>
    <p class="reserve-info"><input type="text" name="mail" id="mail"></p>
    <p class="reserve-info">
      <label for="mail2">確認のためメールアドレスを再度入力してください。</label>
    </p>
    <p class="reserve-info"><input type="text" name="mail2" id="mail2"></p>
    
    <p class="reserve-info">
      <label for="roma">氏名(ローマ字):</label>
    </p>
    <p class="reserve-info">
      <input type="text" name="roma" id="roma">
    </p>
    <p class="reserve-info">
    <label for="name">氏名:</label>
    </p>
    <p class="reserve-info">
    <input type="text" name="name" id="name">
    </p>
    <p class="reserve-info">
    <label for="tel">電話番号:</label>
    </p>
    <p class="reserve-info">
    <input type="text" name="tel" id="tel">
    </p>
    <p class="reserve-info">
    <input type="radio" name="gender" value="男" checked>男
    <input type="radio" name="gender" value="女">女
    </p>
    <?php foreach(array_map(null, $date, $night, $member, $random, $type, $roomtype) as [$dates, $nights, $members, $randoms, $types, $roomtypes]):?>
    <input type="hidden" name="date[]" value="<?php echo $dates; ?>">
    <input type="hidden" name="night[]" value="<?php echo $nights; ?>">
    <input type="hidden" name="member[]" value="<?php echo $members; ?>">
    <input type="hidden" name="random[]" value="<?php echo $randoms; ?>">
    <input type="hidden" name="type[]" value="<?php echo $types; ?>">
    <input type="hidden" name="roomtype[]" value="<?php echo $roomtypes; ?>">
    <?php endforeach;?>
    <input type="hidden" name="confirm" value="1">
    <input type="submit" class="submit" value="予約する">
  </form>
  </div>

  <div id="reserve2" class="area">
  <p class="note" >※会員登録をして予約を希望の方は下記の必要事項を全て入力した上で予約するを押してください。</p>
  <form method="post" action="member-check.php">
    <p class="reserve-info">
      <label for="mail">メールアドレス:</label>
    </p>
    <p class="reserve-info">
      <input type="text" name="mail" id="mail">
    </p>
    <p class="reserve-info">
      <label for="mail2">確認のため再度入力してください。</label>
    </p>
    <p class="reserve-info">
      <input type="text" name="mail2" id="mail2">
    </p>
    <p class="reserve-info">
      <label for="roma">氏名(ローマ字):</label>
    </p>
    <p class="reserve-info">
      <input type="text" name="roma" id="roma">
    </p>
    <p class="reserve-info">
    <label for="name">氏名:</label>
    </p>
    <p class="reserve-info">
    <input type="text" name="name" id="name">
    </p>
    <p class="reserve-info">
    <label for="tel">電話番号:</label>
    </p>
    <p class="reserve-info">
    <input type="text" name="tel" id="tel">
    </p>
    <p>
    <p class="reserve-info">性別:
    <input type="radio" name="gender" value="男" checked>男
    <input type="radio" name="gender" value="女">女
    </p>
    
    <p class="reserve-info">
    <label for="year">生年月日:</label>
    <select id="year" name="year">
      <?php for($first_year; $first_year<=$end_year; $first_year++):?>
      <option><?= $first_year; ?></option>
      <?php endfor; ?>
    </select>

    <select id="month" name="month">
      <?php for($first_month; $first_month<=$end_month; $first_month++):?>
      <option><?= $first_month; ?></option>
      <?php endfor; ?>
    </select>

    <select id="day" name="day">
      <?php for($first_day; $first_day<=$end_day; $first_day++):?>
      <option><?= $first_day; ?></option>
      <?php endfor; ?>
    </select>
    </p>

    <p class="reserve-info">
    <label for="postal">郵便番号:</label>
    </p>
    <p class="reserve-info">
    <input type="text" name="postal" id="postal">
    </p>

    <p class="reserve-info">
    <label for="prefeture">都道府県:</label>
    <select id="prefecture" name="prefecture">
      <?php foreach($prefectures as $prefecture):?>
      <option><?= $prefecture; ?></option>
      <?php endforeach; ?>
    </select>
    </p>

    <p class="reserve-info">
    <label for="address">住所(市区町村):</label>
    </p>
    <p class="reserve-info">
    <input type="text" name="address" id="address">
    </p>

    <p class="reserve-info">
      <span>※下記項目のパスワード欄は半角全角アルファベッド、数字、8文字以上で設定してください。</span>
    </p>
    <p class="reserve-info">
      <label for="password">パスワードを設定してください。</label>
    </p>
    <p class="reserve-info">
      <input type="password" name="password" id="password">
    </p>
    <p class="reserve-info">
      <label for="password2">パスワードを再度入力してください。</label>
    </p>
    <p class="reserve-info">
      <input type="password" name="password2" id="password2">
    </p>

    <?php foreach(array_map(null, $date, $night, $member, $random, $type, $roomtype) as [$dates, $nights, $members, $randoms, $types, $roomtypes]):?>
    <input type="hidden" name="date[]" value="<?php echo $dates; ?>">
    <input type="hidden" name="night[]" value="<?php echo $nights; ?>">
    <input type="hidden" name="member[]" value="<?php echo $members; ?>">
    <input type="hidden" name="random[]" value="<?php echo $randoms; ?>">
    <input type="hidden" name="type[]" value="<?php echo $types; ?>">
    <input type="hidden" name="roomtype[]" value="<?php echo $roomtypes; ?>">
    <?php endforeach;?>
    <input type="hidden" name="confirm" value="1">
    <input type="submit" class="submit" value="予約する">
  </form>
  </div>

  <div id="reserve3" class="area">
  <p class="note" >※会員の方はメールアドレスとパスワードのみ入力して予約するを押してください。</p>
  <form method="post" action="login-reserve.php">
    <p class="reserve-info">
      <label for="mail">メールアドレス:</label>
    </p>
    <p class="reserve-info">
      <input type="text" name="mail" id="mail">
    </p>
    <p class="reserve-info">
      <label for="password">パスワード:</label>
    </p>
    <p class="reserve-info">
      <input type="password" name="password" id="password">
    </p>

    <?php foreach(array_map(null, $date, $night, $member, $random, $type, $roomtype) as [$dates, $nights, $members, $randoms, $types, $roomtypes]):?>
    <input type="hidden" name="date[]" value="<?php echo $dates; ?>">
    <input type="hidden" name="night[]" value="<?php echo $nights; ?>">
    <input type="hidden" name="member[]" value="<?php echo $members; ?>">
    <input type="hidden" name="random[]" value="<?php echo $randoms; ?>">
    <input type="hidden" name="type[]" value="<?php echo $types; ?>">
    <input type="hidden" name="roomtype[]" value="<?php echo $roomtypes; ?>">
    <?php endforeach;?>
    <input type="hidden" name="confirm" value="1">
    <input type="submit" class="submit" value="予約する">
  </form>
  </div>
  </div>
  <?php endif; ?>
  <script src="tab.js"></script>
</body>