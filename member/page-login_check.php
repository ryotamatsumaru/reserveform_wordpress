<?php

//     Template Name: login_check
//     Template Post Type: page
//     Template Path: member/

session_start();
session_regenerate_id(true);
require_once(dirname(dirname(__FILE__)). '/prvdtb/pdo-class.php');
require_once(dirname(dirname(__FILE__)). '/measure/measure-class.php');
Measure::cus_validate();

if(isset($_POST['mail']) && isset($_POST['password'])){
  $mail = Measure::h($_POST['mail']);
  $password = Measure::h($_POST['password']);
	
  $dbh = Database::getPdo();
  $sql = "SELECT * FROM member WHERE mail = :mail";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':mail', $mail, PDO::PARAM_STR);
  $ps->execute();
  $rows = $ps->fetch();
  if($rows == true){
    $password2 = $rows['password'];
    $id = $rows['id'];
  }
}

if(!empty($password) && !empty($password2)){
  if(password_verify($password,$password2)) {
    session_start();
    session_regenerate_id(true);
    $_SESSION['cus_login']=1;
    $_SESSION['cus_id'] = $id;
    header('Location:https://ro-crea.com/demo_hotel/menu');
    exit();
  }
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
	  <a href="https://ro-crea.com/demo_hotel"><span class="en">Top</span>
          <p class="ja">トップページ</p>
          </a>
	</li>
        <li>
	  <a href="https://ro-crea.com/demo_hotel/concept"><span class="en">Concept</span>
          <p class="ja">当館の特徴</p>
          </a>
	</li>
        <li>
	  <a href="https://ro-crea.com/demo_hotel/fasility"><span class="en">Fasility</span>
          <p class="ja">施設案内</p>
          </a>
	</li>
        <li>
	  <a href="https://ro-crea.com/demo_hotel/restaurant"><span class="en">Restaurant</span>
          <p class="ja">レストラン</p>
          </a>
	</li>
        <li>
	  <a href="https://ro-crea.com/demo_hotel/roomlist"><span class="en">Room Type</span>
          <p class="ja">お部屋一覧</p>
          </a>
	</li>
        <li>
	  <a href="https://ro-crea.com/demo_hotel/#news"><span class="en">News</span>
          <p class="ja">お知らせ</p>
          </a>
	</li>
        <li>
	  <a href="https://ro-crea.com/demo_hotel/member"><span class="en">Member</span>
          <p class="ja">会員制度</p>
          </a>
	</li>
      </ul>
    </nav>
  </header>
<body>
<section>
  <?php if(!empty($_POST['confirm'])): ?>
    <?php if(!empty($_POST['mail']) && !empty($_POST['password'])): ?>
      <?php if($rows == false): ?>
        <div id="login-check" class="wrapper">
          <p class="caution">メールアドレスが間違っています。</p>
          <a href="https://ro-crea.com/demo_hotel/login">戻る</a>
        </div>
      <?php else: ?>
        <?php if(password_verify($password,$password2) == false): ?>
          <div id="login-check" class="wrapper">
            <p class="caution">入力しているパスワードが一致しません。</p>
            <a href="https://ro-crea.com/demo_hotel/login" class="back">ログイン画面に戻る</a>
          </div>
        <?php endif; ?>
      <?php endif; ?>
    <?php else: ?>
      <div id="login-check" class="wrapper">
        <p class="caution">メールアドレスとパスワードを入力してください。</p>
        <a href="https://ro-crea.com/demo_hotel/login" class="back">ログイン画面に戻る</a>
      </div>
    <?php endif; ?>
  <?php else: ?>
    <div id="invalid" class="wrapper">
      <p class="caution">不正な画面遷移です。</p>
      <a href="https://ro-crea.com/demo_hotel/login/">ログインに戻る</a>
    </div>
  <?php endif; ?>
</section>
<?php get_footer("3");?>
