<?php

session_start();

require_once(__DIR__ . '/../app/config.php');
require_once(__DIR__ . '/../app/measure-class.php');
use MyApp\database;
$dbh = new PDO(DSN,USER,PASS);


if(isset($_POST['mail']) && isset($_POST['roma']) && isset($_POST['name']) && isset($_POST['tel']) && isset($_POST['postal'])  && isset($_POST['address']) && isset($_POST['password']))
{
echo $mail = Measure::h($_POST['mail']);
echo $roma = Measure::h($_POST['roma']);
echo $name = Measure::h($_POST['name']);
echo $tel = Measure::h($_POST['tel']);
echo $gender = Measure::h($_POST['gender']);
echo $year = Measure::h($_POST['year']);
echo $month = Measure::h($_POST['month']);
echo $day = Measure::h($_POST['day']);
echo $postal = Measure::h($_POST['postal']);
echo $prefecture = Measure::h($_POST['prefecture']);
echo $address = Measure::h($_POST['address']);
echo $password = Measure::h($_POST['password']);


$new_pass = password_hash($_POST['password'], PASSWORD_DEFAULT,['cost' => 8]);

$date = $_POST['date'];
$price = $_POST['price'];
$member = $_POST['member'];
$night = $_POST['night'];
$random = $_POST['random'];
$type = $_POST['type'];


foreach(array_map(null, $date, $price, $member) as [$d, $p, $m]){
  $date2[] = $d;
  $price2[] = $p;
  $member2[] = $m;
}


$sql = "INSERT INTO member(mail,roma,name,tel,gender,birth_year,birth_month,birth_day,postal,prefecture,address,password) VALUES (:mail,:roma,:name,:tel,:gender,:birth_year,:birth_month,:birth_day,:postal,:prefecture,:address,:password)";
$ps = $dbh->prepare($sql);
$ps->bindValue(':mail', $mail, PDO::PARAM_STR);
$ps->bindValue(':roma', $roma, PDO::PARAM_STR);
$ps->bindValue(':name', $name, PDO::PARAM_STR);
$ps->bindValue(':tel', $tel, PDO::PARAM_STR);
$ps->bindValue(':gender', $gender, PDO::PARAM_STR);
$ps->bindValue(':birth_year', $year, PDO::PARAM_INT);
$ps->bindValue(':birth_month', $month, PDO::PARAM_INT);
$ps->bindValue(':birth_day', $day, PDO::PARAM_INT);
$ps->bindValue(':postal', $postal, PDO::PARAM_STR);
$ps->bindValue(':prefecture', $prefecture, PDO::PARAM_STR);
$ps->bindValue(':address', $address, PDO::PARAM_STR);
$ps->bindValue(':password', $new_pass, PDO::PARAM_STR);
$ps->execute();

foreach(array_map(null, $date, $member, $price, $night, $random, $type) as [$dates, $members, $prices, $nights, $randoms, $types]){
  $dates = Measure::h($dates);
  $members = Measure::h($members);
  $prices = Measure::h($prices);
  $nights = Measure::h($nights);
  $randoms = Measure::h($randoms);
  $types = Measure::h($types);
  $sql2 = "INSERT INTO booking(mail,roma,name,tel,gender,member,night,day,price,random_id,type) VALUES (:mail,:roma,:name,:tel,:gender,:member,:night,:date,:price,:random,:type)";
  $ps2 = $dbh->prepare($sql2);
  $ps2->bindValue(':mail', $mail, PDO::PARAM_STR);
  $ps2->bindValue(':roma', $roma, PDO::PARAM_STR);
  $ps2->bindValue(':name', $name, PDO::PARAM_STR);
  $ps2->bindValue(':tel', $tel, PDO::PARAM_STR);
  $ps2->bindValue(':gender', $gender, PDO::PARAM_STR);
  $ps2->bindValue(':member', $members, PDO::PARAM_INT);
  $ps2->bindValue(':night', $nights, PDO::PARAM_INT);
  $ps2->bindValue(':date', $dates, PDO::PARAM_STR);
  $ps2->bindValue(':price', $prices, PDO::PARAM_INT);
  $ps2->bindValue(':random', $randoms, PDO::PARAM_INT);
  $ps2->bindValue(':type', $types, PDO::PARAM_INT);
  $ps2->execute();
}

if(isset($_SESSION['date']) && isset($_SESSION['night']) && isset($_SESSION['member']) && isset($_SESSION['total']) && isset($_SESSION['random']) && isset($_SESSION['type'])){
  unset($_SESSION['date']);
  unset($_SESSION['night']);
  unset($_SESSION['member']);
  unset($_SESSION['random']);
  unset($_SESSION['type']);
  unset($_SESSION['total']);
}


$text_dates = $_POST['text_date'];
$text_nights = $_POST['text_night'];
$text_members = $_POST['text_member'];
$total = $_POST['total'];

foreach(array_map(null, $text_dates, $text_nights, $text_members) as [$text_date, $text_night, $text_member]){
  $date_array[] = $text_date;
  $night_array[] = $text_night;
  $member_array[] = $text_member;
}

foreach(array_map(null, $date_array, $night_array,) as $key => [$date, $night])
{
$checkin[] = $date;
$checkout[] = date('Y-m-d', strtotime('+'.$night.' day'.$date));
}

$mail_text = '';
$mail_text .= $name."様<br><br>この度はこのたびは当館をご予約いただきありがとうございます。<br>";
$mail_text .= "<br>";
$mail_text .= "〇〇ご予約完了のお知らせ <br>";
$mail_text .= "こちらの確認メールにてご予約が確定となりました。 <br>";
$mail_text .= "予約詳細は下記の通りです。<br>";
$mail_text .= "<br>";

$mail_text .= "---------------------<br>";
$mail_text .= "到着日&nbsp&nbsp&nbsp出発日 <br>";
foreach(array_map(null, $checkin, $checkout , $night_array ,$member_array) as [$in, $out, $night, $member]) {
  $mail_text .= $in .'&nbsp'.'&nbsp'. $out .'&nbsp'.'&nbsp'. $night.'泊'.'&nbsp'.'&nbsp'. $member.'室';
  $mail_text .= '<br>';
}


$mail_text .= '<br>';
$mail_text .= "---------------------<br>";
$mail_text .= '料金内訳<br>';

foreach(array_map(null, $date2, $member2, $price2) as [$dates2, $members2, $prices2]){
  $mail_text .= $dates2.'&nbsp';
  $mail_text .= '¥'.$prices2.'&nbsp';
  $mail_text .= '×'.'&nbsp';
  $mail_text .= $members2.'室'.'&nbsp'.'&nbsp';
  $mail_text .= '¥'.$prices2 * $members2;
  $mail_text .= '<br>';
}


$mail_text .= '合計：¥'.$total.'<br>';
$mail_text .= "---------------------<br>";
$mail_text .= "<br>";

$mail_text .= "▫︎▫︎▫︎▫︎▫︎▫︎▫︎▫︎▫︎▫︎▫︎▫︎▫︎▫︎▫︎▫︎▫︎<br>";
$mail_text .= "株式会社リゾートホテル<br>";
$mail_text .= "東京都品川区大井 xxx-xx<br>";
$mail_text .= "電話 xxx-xxxx-xxxx<br>";
$mail_text .= "メール info@hotel_group.co.jp<br>";
$mail_text .= "▫︎▫︎▫︎▫︎▫︎▫︎▫︎▫︎▫︎▫︎▫︎▫︎▫︎▫︎▫︎▫︎▫︎<br>";

$title = 'ご予約いただきありがとうございます。';
$header = 'From:info@test.co.jp';
$mail_text = html_entity_decode($mail_text, ENT_QUOTES, 'UTF-8');
mb_language('Japanese');
mb_internal_encoding('UTF-8');
mb_send_mail($mail, $title, $mail_text, $header);

$title = 'お客様からご予約がありました。';
$header = 'From:'.$mail;
// サーバーからのアドレス名に変更
$mail_text = html_entity_decode($mail_text, ENT_QUOTES, 'UTF-8');
mb_language('Japanese');
mb_internal_encoding('UTF-8');
mb_send_mail('info@rokumarunouen.co.jp', $title, $mail_text, $header);

$mail_text;


header('Location: http://localhost:8569/member-done.php');
exit();
}
?>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="reserve.css">
</head>
<body>
  <?php if(!empty($_SESSION['confirm'])): ?>
  <div id="member-done" class="wrapper">
  <p class="done-text">会員登録と予約が完了しました。</p>
  <p class="done-text">この度はご予約いただき誠にありがとうございます。</p>
  <p class="done-text">ご登録いただいたメールアドレスに会員登録、予約確認のメールをお送りしていますのでご確認ください。</p>
  <?php unset($_SESSION['confirm'])?>
  <a href="calender-res.php" class="back">予約状況に戻る</a>
  </div>
  <?php else:?>
  <p>不正な画面遷移です。</p>
  <a href="calender-res.php">予約状況に戻る</a>
  <?php endif; ?>
</body>