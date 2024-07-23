<?php
//     Template Name: member_done
//     Template Post Type: page
//     Template Path: reserve/

session_start();

require_once(dirname(dirname(__FILE__)). '/prvdtb/pdo-class.php');
require_once(dirname(dirname(__FILE__)). '/measure/measure-class.php');
$database = new Database();

if(isset($_POST['mail']) && isset($_POST['roma']) && isset($_POST['namae']) && isset($_POST['tel']) && isset($_POST['postal'])  && isset($_POST['address']) && isset($_POST['password']))
{
  // mesure-class.phpで発行したtokenと一致するか確認。
  Measure::cus_validate();  

  // エスケープ処理
  $mail = Measure::h($_POST['mail']);
  $roma = Measure::h($_POST['roma']);
  $name = Measure::h($_POST['namae']);
  $tel = Measure::h($_POST['tel']);
  $gender = Measure::h($_POST['gender']);
  $year = Measure::h($_POST['b_year']);
  $month = Measure::h($_POST['b_month']);
  $day = Measure::h($_POST['b_day']);
  $postal = Measure::h($_POST['postal']);
  $prefecture = Measure::h($_POST['prefecture']);
  $address = Measure::h($_POST['address']);
  $password = Measure::h($_POST['password']);

  $new_pass = password_hash($_POST['password'], PASSWORD_DEFAULT,['cost' => 8]);

  $date = $_POST['date'];
  $price = $_POST['price'];
  $number = $_POST['number']; 
  $night = $_POST['night'];
  $random = $_POST['random'];
  $type = $_POST['type'];


  foreach(array_map(null, $date, $price, $number) as [$d, $p, $n]){
    $date2[] = $d;
    $price2[] = $p;
    $number2[] = $n;
  }

  // DBのmemberテーブルに予約情報を登録
  $dbh = Database::getPdo();
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

  // DBのbookingテーブルに予約情報を登録
  foreach(array_map(null, $date, $number, $price, $night, $random, $type) as [$dates, $numbers, $prices, $nights, $randoms, $types]){
    $dates = Measure::h($dates);
    $numbers = Measure::h($numbers);
    $prices = Measure::h($prices);
    $nights = Measure::h($nights);
    $randoms = Measure::h($randoms);
    $types = Measure::h($types);
    $sql2 = "INSERT INTO booking(mail,roma,name,tel,gender,number,night,day,price,random_id,type) VALUES (:mail,:roma,:name,:tel,:gender,:number,:night,:date,:price,:random,:type)";
    $ps2 = $dbh->prepare($sql2);
    $ps2->bindValue(':mail', $mail, PDO::PARAM_STR);
    $ps2->bindValue(':roma', $roma, PDO::PARAM_STR);
    $ps2->bindValue(':name', $name, PDO::PARAM_STR);
    $ps2->bindValue(':tel', $tel, PDO::PARAM_STR);
    $ps2->bindValue(':gender', $gender, PDO::PARAM_STR);
    $ps2->bindValue(':number', $numbers, PDO::PARAM_INT);
    $ps2->bindValue(':night', $nights, PDO::PARAM_INT);
    $ps2->bindValue(':date', $dates, PDO::PARAM_STR);
    $ps2->bindValue(':price', $prices, PDO::PARAM_INT);
    $ps2->bindValue(':random', $randoms, PDO::PARAM_INT);
    $ps2->bindValue(':type', $types, PDO::PARAM_INT);
    $ps2->execute();
  }

  // DBに登録後、SESSIONを消去する。
  if(isset($_SESSION['date']) && isset($_SESSION['night']) && isset($_SESSION['number']) && isset($_SESSION['total']) && isset($_SESSION['random']) && isset($_SESSION['type'])){
    unset($_SESSION['date']);
    unset($_SESSION['night']);
    unset($_SESSION['number']);
    unset($_SESSION['random']);
    unset($_SESSION['type']);
    unset($_SESSION['total']);
  }

  $text_dates = $_POST['text_date'];
  $text_nights = $_POST['text_night'];
  $text_numbers = $_POST['text_number'];
  $total = $_POST['total'];

  // メール送信文で使う日付、泊数、室数の変数。
  foreach(array_map(null, $text_dates, $text_nights, $text_numbers) as [$text_date, $text_night, $text_number]){
    $date_array[] = $text_date;
    $night_array[] = $text_night;
    $number_array[] = $text_number;
  }

  // メール送信文で使う宿泊期間の変数。
  foreach(array_map(null, $date_array, $night_array,) as $key => [$date, $night])
  {
    $checkin[] = $date;
    $checkout[] = date('Y-m-d', strtotime('+'.$night.' day'.$date));
  }

  // 予約完了メール本文。
  $mail_text = '';
  $mail_text .= $name." 様"."\r\n";
  $mail_text .= "\r\n";
  $mail_text .= 'この度はこのたびは株式会社リゾートホテルをご予約いただき誠にありがとうございます。'."\r\n";
  $mail_text .= 'こちらの確認メールにて会員登録の完了とご予約が確定となります。'."\r\n";
  $mail_text .= "\r\n";
  $mail_text .= '次回より登録されたメールアドレスとパスワードの入力のみでご予約いただけます。'."\r\n";
  $mail_text .= "\r\n";
  $mail_text .= '予約詳細は下記の通りです。'."\r\n";

  $mail_text .= '-----------------------------'."\r\n";
  $mail_text .= "\r\n";
  foreach(array_map(null, $checkin, $checkout , $night_array ,$number_array) as [$in, $out, $night, $number]) {
    $mail_text.='チェックイン日: '.$in."\r\n";
    $mail_text.='チェックアウト日: '.$out."\r\n";
    $mail_text.='泊数: '.$night.'泊'."\r\n";
	  $mail_text .= "\r\n";
  }

  $mail_text .= '-----------------------------'."\r\n";
  $mail_text .= "\r\n";
  $mail_text .= '料金内訳'."\r\n";

  foreach(array_map(null, $date2, $number2, $price2) as [$dates2, $numbers2, $prices2]){
    $mail_text .= $dates2;
    $mail_text .= ' ¥'.$prices2;
    $mail_text .= ' x ';
    $mail_text .= $numbers2.'室'."\r\n";
    $mail_text.='合計¥'.$prices2 * $numbers2."\r\n";
    $mail_text .= "\r\n";
  }

  $mail_text .= '-----------------------------'."\r\n";
  $mail_text .= "\r\n";

  $mail_text .= '□□□□□□□□□□□□□□□□'."\r\n";
  $mail_text .= '株式会社リゾートホテル'."\r\n";
  $mail_text .= '東京都品川区大井 xxx-xx'."\r\n";
  $mail_text .= '電話 xxx-xxxx-xxxx'."\r\n";
  $mail_text .= 'メール xxxxx@xxxxxxx.xx.xx'."\r\n";
  $mail_text .= '□□□□□□□□□□□□□□□□'."\r\n";

  $title = 'ホテル（デモサイト）ご予約いただきありがとうございます。';
  $header = 'From:ryota.myapp@gmail.com';
  $mail_text = html_entity_decode($mail_text, ENT_QUOTES, 'UTF-8');
  mb_language('Japanese');
  mb_internal_encoding('UTF-8');
  mb_send_mail($mail, $title, $mail_text, $header);

  $title = 'お客様からご予約がありました。';
  $header = 'From:ryota.myapp@gmail.com';
  $mail_text = html_entity_decode($mail_text, ENT_QUOTES, 'UTF-8');
  mb_language('Japanese');
  mb_internal_encoding('UTF-8');
  mb_send_mail( 'ryota.myapp@gmail.com',$title, $mail_text, $header);

  $mail_text;

  header('Location:https://ro-crea.com/demo_hotel/member_done');
  exit();
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>デモサイト(ホテル)</title>
  <?php wp_head(); ?>
</head>

<body>
  <header id="header">
    <div class="flex wrapper">
      <h1 class="logo-title">
        <a href="<?php echo esc_url(home_url('#')); ?>">
          <img src="<?php echo esc_url(get_theme_file_uri('img/logo3.png')); ?>" alt="logo" class="logo">
          <span class="title">Hotel&city group</span>
        </a>
      </h1>
      <div class="right">
        <a href="#" class="login-btn">ログイン</a>
        <a href="#" class="book-btn"><span>宿泊予約</span></a>
        <div class="hamburger">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
    </div>

    <nav id="sp-navi">
      <ul class="back-list">
        <li><a href="https://ro-crea.com/demo_hotel"><span class="en">Top</span>トップページ</a></li>
        <li><a href="https://ro-crea.com/demo_hotel/concept"><span class="en">Concept</span>当館の特徴</a></li>
        <li><a href="https://ro-crea.com/demo_hotel/fasility"><span class="en">Fasility</span>施設案内</a></li>
        <li><a href="https://ro-crea.com/demo_hotel/restaurant"><span class="en">Restaurant</span>レストラン</a></li>
        <li><a href="https://ro-crea.com/demo_hotel/roomlist"><span class="en">Room Type</span>お部屋タイプ</a></li>
        <li><a href="https://ro-crea.com/demo_hotel/#photo"><span class="en">Gallery</span> フォトギャラリー</a></li>
        <li><a href="https://ro-crea.com/demo_hotel/#news"><span class="en">News</span>お知らせ</a></li>
        <li><a href="https://ro-crea.com/demo_hotel/member"><span class="en">Membership</span>メンバーシップ</a></li>
        <li><a href="https://ro-crea.com/demo_hotel"><span class="en">Contact</span>お問い合せ</a></li>
      </ul>
    </nav>

    <nav id="navi">
      <ul class="navi-area wrapper">
        <li><a href="https://ro-crea.com/demo_hotel"><span class="en">Top</span>
            <p class="ja">トップページ</p>
          </a></li>
        <li><a href="https://ro-crea.com/demo_hotel/concept"><span class="en">Concept</span>
            <p class="ja">当館の特徴</p>
          </a></li>
        <li><a href="https://ro-crea.com/demo_hotel/fasility"><span class="en">Fasility</span>
            <p class="ja">施設案内</p>
          </a></li>
        <li><a href="https://ro-crea.com/demo_hotel/restaurant"><span class="en">Restaurant</span>
            <p class="ja">レストラン</p>
          </a></li>
        <li><a href="https://ro-crea.com/demo_hotel/roomlist"><span class="en">Room Type</span>
            <p class="ja">お部屋一覧</p>
          </a></li>
        <li><a href="https://ro-crea.com/demo_hotel/#news"><span class="en">News</span>
            <p class="ja">お知らせ</p>
          </a></li>
        <li><a href="https://ro-crea.com/demo_hotel/member"><span class="en">Member</span>
            <p class="ja">会員制度</p>
          </a></li>
      </ul>
    </nav>
  </header>
<section>
  <?php if(!empty($_SESSION['confirm'])): ?>
    <div id="member-done" class="wrapper">
      <p class="done-text">会員登録と予約が完了しました。</p>
      <p class="done-text">この度はご予約いただき誠にありがとうございます。</p>
      <p class="done-text">ご登録いただいたメールアドレスに会員登録、予約確認のメールをお送りしていますのでご確認ください。</p>
      <a href="https://ro-crea.com/demo_hotel/calendar"  class="back">予約状況に戻る</a>
      <!-- 外部からの不正アクセスを防ぐSESSIONを消去 -->
      <?php unset($_SESSION['confirm'])?>
      <?php unset($_SESSION['cus_token'])?>
    </div>
  <?php else:?>
    <div id="invalid" class="wrapper">
      <p class="caution">不正な画面遷移です。</p>
      <a href="https://ro-crea.com/demo_hotel/calendar">予約状況に戻る</a>
    </div>
  <?php endif; ?>
</section>
<?php get_footer("3")?>