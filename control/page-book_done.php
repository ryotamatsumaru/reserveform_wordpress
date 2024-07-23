<?php

//     Template Name: book_done
//     Template Post Type: page
//     Template Path: control/

session_start();
session_regenerate_id(true);

// sessionが一致しない場合、ログインの警告ページに遷移する
if(isset($_SESSION['mng_login'])==false){
  header('Location:https://ro-crea.com/demo_hotel/mng_login_caution_text');
  exit();
}

require_once(dirname(dirname(__FILE__)). '/measure/measure-class.php');
require_once(dirname(dirname(__FILE__)). '/prvdtb/pdo-class.php');

if(isset($_POST['date'])){
  // CSRF用対策のtokenが一致するかは判定するメソッド 
  Measure::validate();

  // エスケープ処理
  $mail = Measure::h($_POST['mail']);
  $roma = Measure::h($_POST['roma']);
  $name = Measure::h($_POST['namae']);
  $tel = Measure::h($_POST['tel']);
  $gender = Measure::h($_POST['gender']);

  $date = $_POST['date'];
  $number = $_POST['number'];
  $price = $_POST['price'];
  $night = $_POST['night'];
  $random = Measure::h($_POST['random']);
  $type = Measure::h($_POST['type']);

  
  foreach(array_map(null, $date, $number, $price, $night) as [$dates, $numbers, $prices, $nights]){
    // エスケープ処理
    $dates = Measure::h($dates);
    $numbers = Measure::h($numbers);
    $prices = Measure::h($prices);
    $nights = Measure::h($nights);
    $dbh = Database::getPdo();
    $sql = "INSERT INTO booking(mail,roma,name,tel,gender,number,night,day,price,random_id,type) VALUES (:mail,:roma,:name,:tel,:gender,:number,:night,:date,:price,:random,:type)";
    $ps = $dbh->prepare($sql);
    $ps->bindValue(':mail', $mail, PDO::PARAM_STR);
    $ps->bindValue(':roma', $roma, PDO::PARAM_STR);
    $ps->bindValue(':name', $name, PDO::PARAM_STR);
    $ps->bindValue(':tel', $tel, PDO::PARAM_STR);
    $ps->bindValue(':gender', $gender, PDO::PARAM_STR);
    $ps->bindValue(':number', $numbers, PDO::PARAM_INT);
    $ps->bindValue(':night', $nights, PDO::PARAM_INT);
    $ps->bindValue(':date', $dates, PDO::PARAM_STR);
    $ps->bindValue(':price', $prices, PDO::PARAM_INT);
    $ps->bindValue(':random', $random, PDO::PARAM_INT);
    $ps->bindValue(':type', $type, PDO::PARAM_INT);
    $ps->execute();
  }
  header('Location:https://ro-crea.com/demo_hotel/book_done/');
  exit();
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>管理画面(デモサイト)</title>
  <?php wp_head(); ?>
</head>
<body>
<header>
</header>
<?php if(!empty($_SESSION['confirm2'])):?>
  <div id="reserve-make-done" class="wrapper">
    <p class="done-text">予約が完了しました。</p>
    <a href="https://ro-crea.com/demo_hotel/book_make/" class="back">予約作成に戻る</a>
  </div>
  <!-- 不正アクセス防止用のSESSIONを消去する。 -->
  <?php unset($_SESSION['confirm2']) ?>
  <?php unset($_SESSION['token']) ?>
<?php else: ?>
  <div id="invalid" class="wrapper">
    <p  class="caution">不正な画面遷移です。</p>
    <a href="https://ro-crea.com/demo_hotel/book_make/">予約作成に戻る</a>
  </div>
<?php endif;?>
<footer>
</footer>
<?php wp_footer(); ?>
</body>
</html>