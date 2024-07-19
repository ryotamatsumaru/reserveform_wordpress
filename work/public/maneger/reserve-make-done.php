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

try
{
$dbh = new PDO(DSN,USER,PASS);

if(isset($_POST['date'])){

Measure::validate();

$mail = $_POST['mail'];
$roma = $_POST['roma'];
$name = $_POST['name'];
$tel = $_POST['tel'];
$gender = $_POST['gender'];

$date = $_POST['date'];
$member = $_POST['member'];
$price = $_POST['price'];
$night = $_POST['night'];
$random = $_POST['random'];
$type = $_POST['type'];

foreach($date as $d){
  $date2[] = $d;
}

foreach($price as $p){
  $price2[] = $p;
}

foreach($member as $m){
  $member2[] = $m;
}

foreach(array_map(null, $date, $member, $price, $night) as [$dates, $members, $prices, $nights]){
  $sql = "INSERT INTO booking(mail,roma,name,tel,gender,member,night,day,price,random_id,type) VALUES (:mail,:roma,:name,:tel,:gender,:member,:night,:date,:price,:random,:type)";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':mail', $mail, PDO::PARAM_STR);
  $ps->bindValue(':roma', $roma, PDO::PARAM_STR);
  $ps->bindValue(':name', $name, PDO::PARAM_STR);
  $ps->bindValue(':tel', $tel, PDO::PARAM_STR);
  $ps->bindValue(':gender', $gender, PDO::PARAM_STR);
  $ps->bindValue(':member', $members, PDO::PARAM_INT);
  $ps->bindValue(':night', $nights, PDO::PARAM_INT);
  $ps->bindValue(':date', $dates, PDO::PARAM_STR);
  $ps->bindValue(':price', $prices, PDO::PARAM_INT);
  $ps->bindValue(':random', $random, PDO::PARAM_INT);
  $ps->bindValue(':type', $type, PDO::PARAM_INT);
  $ps->execute();
}


$text_dates = $_POST['text_date'];
$text_nights = $_POST['text_night'];
$text_members = $_POST['text_member'];
$total = $_POST['total'];

foreach($text_dates as $text_date){
  $date_array[] = $text_date;
}

foreach($text_nights as $text_night){
  $night_array[] = $text_night;
}

foreach($text_members as $text_member){
  $member_array[] = $text_member;
}

foreach(array_map(null, $date_array, $night_array,) as $key => [$date, $night])
{
$checkin[] = $date;
$checkout[] = date('Y-m-d', strtotime('+'.$night.' day'.$date));
}

header('Location:http://localhost:8569/maneger/reserve-make-done.php');
exit();
}

}
catch (Exception $e)
{
  print 'ただいま障害により大変ご迷惑をおかけします';
  exit();
}


?>
<body>
  <?php if(!empty($_SESSION['confirm2'])):?>
  <div id="reserve-make-done" class="wrapper">
  <p class="done-text">予約が完了しました。</p>
  <a class="back" href="reservation2.php">予約確認に戻る</a>
  </div>
  <?php unset($_SESSION['confirm2']) ?>
  <?php unset($_SESSION['token']) ?>
  <?php else: ?>
  <div id="invalid" class="wrapper">
  <p>不正な画面遷移です。</p>
  <a href="reserve-make.php">予約作成に戻る</a>
  </div>
  <?php endif;?>
  
</body>