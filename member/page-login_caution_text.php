<?php

//     Template Name: login_caution_text
//     Template Post Type: page
//     Template Path: member/

session_start();

require_once(dirname(dirname(__FILE__)). '/prvdtb/pdo-class.php');
require_once(dirname(dirname(__FILE__)). '/measure/measure-class.php');


if(!empty($_POST['id'])){
  
  Measure::cus_validate();
  $id = Measure::h($_POST['id']);
  $dbh = Database::getPdo();
  $sql = "DELETE FROM member WHERE id = :id ";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':id', $id, PDO::PARAM_INT);
  $ps->execute();

  header('Location:https://ro-crea.com/demo_hotel/leave_done/');
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
        <li><a href="http://demohotel.local"><span class="en">Top</span>トップページ</a></li>
        <li><a href="http://demohotel.local/concept"><span class="en">Concept</span>当館の特徴</a></li>
        <li><a href="http://demohotel.local/fasility"><span class="en">Fasility</span>施設案内</a></li>
        <li><a href="http://demohotel.local/restaurant"><span class="en">Restaurant</span>レストラン</a></li>
        <li><a href="http://demohotel.local/roomlist"><span class="en">Room Type</span>お部屋タイプ</a></li>
        <li><a href="http://demohotel.local/#photo"><span class="en">Gallery</span> フォトギャラリー</a></li>
        <li><a href="http://demohotel.local/#news"><span class="en">News</span>お知らせ</a></li>
        <li><a href="http://demohotel.local/member"><span class="en">Membership</span>メンバーシップ</a></li>
        <li><a href="http://demohotel.local"><span class="en">Contact</span>お問い合せ</a></li>
      </ul>
    </nav>

    <nav id="navi">
      <ul class="navi-area wrapper">
        <li>
          <a href="http://demohotel.local"><span class="en">Top</span>
          <p class="ja">トップページ</p>
          </a>
        </li>
        <li>
          <a href="http://demohotel.local/concept"><span class="en">Concept</span>
          <p class="ja">当館の特徴</p>
          </a>
        </li>
        <li>
          <a href="http://demohotel.local/fasility"><span class="en">Fasility</span>
          <p class="ja">施設案内</p>
          </a>
        </li>
        <li>
          <a href="http://demohotel.local/restaurant"><span class="en">Restaurant</span>
          <p class="ja">レストラン</p>
          </a>
        </li>
        <li>
          <a href="http://demohotel.local/roomlist"><span class="en">Room Type</span>
          <p class="ja">お部屋一覧</p>
          </a>
        </li>
        <li>
          <a href="http://demohotel.local/#news"><span class="en">News</span>
          <p class="ja">お知らせ</p>
          </a>
        </li>
        <li>
          <a href="http://demohotel.local/member"><span class="en">Member</span>
          <p class="ja">会員制度</p>
          </a>
        </li>
      </ul>
    </nav>
  </header>
<section>
  <div id="login-caution" class="wrapper">
    <p class="caution">ログインされてません</p>
    <a href="https://ro-crea.com/demo_hotel/login/">ログイン画面へ</a>
  </div>
</section>
<?php get_footer("3"); ?>

