<?php
//     Template Name: list
//     Template Post Type: page
//     Template Path: reserve/

session_start();
session_regenerate_id(true);

require_once('prefecture.php');
require_once('list-class.php');

require_once(dirname(dirname(__FILE__)). '/measure/measure-class.php');
require_once(dirname(dirname(__FILE__)). '/prvdtb/pdo-class.php');

$list = new listKeep();

if(isset($_SESSION['date']) == true && isset($_SESSION['night']) == true && isset($_SESSION['number']) == true && isset($_SESSION['random']) == true && isset($_SESSION['type']) == true){
  $date = $_SESSION['date'];
  $night = $_SESSION['night'];
  $number = $_SESSION['number'];
  $random = $_SESSION['random'];
  $type = $_SESSION['type'];
  $roomtype = $_SESSION['roomtype'];

if(isset($_SESSION['confirm'])){
  unset($_SESSION['confirm']);
  unset($_SESSION['token']);
  echo '削除済';
}
}

// 予約をリストに追加するため、必要な変数をセッション変数に格納する。
if(isset($_POST['date']) && isset($_POST['night']) && isset($_POST['number']) && isset($_POST['random']) && isset($_POST['type']) && isset($_POST['roomtype'])){
  $date[] = Measure::h($_POST['date']);
  $_SESSION['date'] = $date;
  $night[] = Measure::h($_POST['night']);
  $_SESSION['night'] = $night;
  $number[] = Measure::h($_POST['number']);
  $_SESSION['number'] = $number;
  $random[] = Measure::h($_POST['random']);
  $_SESSION['random'] = $random;
  $type[] = Measure::h($_POST['type']);
  $_SESSION['type'] = $type;
  $roomtype[] = Measure::h($_POST['roomtype']);
  $_SESSION['roomtype'] = $roomtype;
  header('Location:https://ro-crea.com/demo_hotel/list/');
  exit();
}

?>
<!-- wordpress環境だとheader関数を置く位置によってjQueryがうまく機能せず構造的にやむを得ずheadタグやheaderタグを直接記述している。 -->
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
        <li><a href="http://demohotel.local"><span class="en">Top</span>
            <p class="ja">トップページ</p>
          </a></li>
        <li><a href="http://demohotel.local/concept"><span class="en">Concept</span>
            <p class="ja">当館の特徴</p>
          </a></li>
        <li><a href="http://demohotel.local/fasility"><span class="en">Fasility</span>
            <p class="ja">施設案内</p>
          </a></li>
        <li><a href="http://demohotel.local/restaurant"><span class="en">Restaurant</span>
            <p class="ja">レストラン</p>
          </a></li>
        <li><a href="http://demohotel.local/roomlist"><span class="en">Room Type</span>
            <p class="ja">お部屋一覧</p>
          </a></li>
        <li><a href="http://demohotel.local/#news"><span class="en">News</span>
            <p class="ja">お知らせ</p>
          </a></li>
        <li><a href="http://demohotel.local/member"><span class="en">Member</span>
            <p class="ja">会員制度</p>
          </a></li>
      </ul>
    </nav>
  </header>
<section>
<div id="reserve-list" class="wrapper">
  <!-- listkeepクラスからkeepListメソッドで予約リストを表示させる -->
  <?php $keeplist = $list->keepList();?>

  <?php if(empty($_SESSION['date'])): ?>
    <div class="nolist">
      <p>リストに入ってません。</p>
      <a href="https://ro-crea.com/demo_hotel/calendar" class="back">予約状況に戻る</a>
    </div>
  <?php else: ?>
    <a href="https://ro-crea.com/demo_hotel/calendar" class="back">予約を追加する</a>
    <ul class="tab">
      <li><a href="#reserve1">このまま予約</a></li>
      <li><a href="#reserve2">会員登録して予約</a></li>
      <li><a href="#reserve3">会員として予約</a></li>
    </ul>

    <!-- 予約フォーム  -->
    <div id="reserve1" class="area">
      <form method="post" action="https://ro-crea.com/demo_hotel/reserve_check/">
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
      <input type="text" name="namae" id="name">
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
      <?php foreach(array_map(null, $date, $night, $number, $random, $type,$roomtype) as [$dates, $nights, $numbers, $randoms, $types, $roomtypes]):?>
        <input type="hidden" name="date[]" value="<?php echo $dates; ?>">
        <input type="hidden" name="night[]" value="<?php echo $nights; ?>">
        <input type="hidden" name="number[]" value="<?php echo $numbers; ?>">
        <input type="hidden" name="random[]" value="<?php echo $randoms; ?>">
        <input type="hidden" name="type[]" value="<?php echo $types; ?>">
        <input type="hidden" name="roomtype[]" value="<?php echo $roomtypes; ?>">
      <?php endforeach;?>
      <input type="hidden" name="confirm" value="1">
      <input type="submit" class="submit" value="予約する">
      </form>
    </div>
	
    <!-- 会員登録兼予約フォーム  -->
    <div id="reserve2" class="area">
      <p class="note" >※会員登録をして予約を希望の方は下記の必要事項を全て入力した上で予約するを押してください。</p>
      <form method="post" action="https://ro-crea.com/demo_hotel/member_check/">
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
      <input type="text" name="namae" id="name">
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
      <select id="year" name="b_year">
      <?php for($first_year; $first_year<=$end_year; $first_year++):?>
        <option><?= $first_year; ?></option>
      <?php endfor; ?>
      </select>
      <select id="month" name="b_month">
      <?php for($first_month; $first_month<=$end_month; $first_month++):?>
        <option><?= $first_month; ?></option>
      <?php endfor; ?>
      </select>
      <select id="day" name="b_day">
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
      <label for="prefecture">都道府県:</label>
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
      <input type="text" name="password" id="password">
      </p>
      <p class="reserve-info">
      <label for="password2">パスワードを再度入力してください。</label>
      </p>
      <p class="reserve-info">
      <input type="text" name="password2" id="password2">
      </p>

      <?php foreach(array_map(null, $date, $night, $number, $random, $type ,$roomtype) as [$dates, $nights, $numbers, $randoms, $types, $roomtypes]):?>
        <input type="hidden" name="date[]" value="<?php echo $dates; ?>">
        <input type="hidden" name="night[]" value="<?php echo $nights; ?>">
        <input type="hidden" name="number[]" value="<?php echo $numbers; ?>">
        <input type="hidden" name="random[]" value="<?php echo $randoms; ?>">
        <input type="hidden" name="type[]" value="<?php echo $types; ?>">
        <input type="hidden" name="roomtype[]" value="<?php echo $roomtypes; ?>">
      <?php endforeach;?>
      <input type="hidden" name="confirm[]" value="1">
      <input type="submit" class="submit" value="予約する">
      </form>
    </div>

    <!-- 会員用予約フォーム  -->
    <div id="reserve3" class="area">
      <p class="note" >※会員の方はメールアドレスとパスワードのみ入力して予約するを押してください。</p>
      <form method="post" action="https://ro-crea.com/demo_hotel/login_reserve">
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

      <?php foreach(array_map(null, $date, $night, $number, $random, $type ,$roomtype) as [$dates, $nights, $numbers, $randoms, $types, $roomtypes]):?>
        <input type="hidden" name="date[]" value="<?php echo $dates; ?>">
        <input type="hidden" name="night[]" value="<?php echo $nights; ?>">
        <input type="hidden" name="number[]" value="<?php echo $numbers; ?>">
        <input type="hidden" name="random[]" value="<?php echo $randoms; ?>">
        <input type="hidden" name="type[]" value="<?php echo $types; ?>">
        <input type="hidden" name="roomtype[]" value="<?php echo $roomtypes; ?>">
      <?php endforeach;?>
      <input type="hidden" name="confirm[]" value="1">
      <input type="submit" class="submit" value="予約する">
      </form>
    </div>
  <?php endif; ?>
</div>
</section>
<?php get_footer("3"); ?>