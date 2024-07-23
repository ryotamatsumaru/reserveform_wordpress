<?php
//     Template Name: modify
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

if(isset($_POST['id'])){


  $token = $_SESSION['token'];
  $_SESSION['confirm2'] = $_POST['confirm'];
  $confirm = $_SESSION['confirm2'];

  // idからDBのbookingテーブルより室数、日付、部屋タイプを呼び出してそれぞれの配列に代入。
  foreach($_POST['id'] as $id){
    $id = Measure::h($id);
    $dbh = Database::getPdo();
    $sql = "SELECT * FROM booking WHERE id = :id";
    $ps = $dbh->prepare($sql);
    $ps->bindValue(':id', $id, PDO::PARAM_STR);
    $ps->execute();
    $rows = $ps->fetchAll(PDO::FETCH_ASSOC);
    foreach($rows as $row){
    $reserved_number[] = $row['number'];
    $reserve_day[] = $row['day'];
    $reserve_type[] = $row['type'];
    }
  }

  // 日付を代入した配列$reserve_dayと部屋タイプを代入した配列$reserve_typeをループに回してbookingテーブルから該当日の合計予約数を求める。
  foreach(array_map(null,$reserve_day,$reserve_type) as [$day,$type]){
    $dbh = Database::getPdo();
    $sql = "SELECT COUNT(*),SUM(number),day FROM (SELECT * FROM booking WHERE day = :day AND type = :type) as booktotal GROUP BY day";
    $ps = $dbh->prepare($sql);
    $ps->bindValue(':day', $day, PDO::PARAM_STR);
    $ps->bindValue(':type', $type, PDO::PARAM_INT);
    $ps->execute();
    $row = $ps->fetch(PDO::FETCH_ASSOC);
    // 予約がない日には0を代入してる。
    if($row == '') {
	    $row_date = '0';
      $sum_number = '0';
    } else {
      $row_date = $row['day'];
      $sum_number = $row['SUM(number)'];
    }
    $day_out = strtotime($row_date);
    $book_out = $sum_number;
    $books[date('Y-m-d', $day_out)] = $book_out;
  }

  // ①$bookには該当日の合計予約数が、$reserve_numberには現在予約の室数が、$_POST['nunmber']にはこれから変更したい予約の室数がそれぞれ代入されてる。
  foreach(array_map(null, $books, $reserved_number, $_POST['number']) as [$book, $reserve_number, $number]){
    $number = Measure::h($number);
    $now_books[] = ($book - $reserve_number) + $number;
    // (該当日予約の合計 - 現予約の室数) + 今変更したい室数
    // ②データベースのbookingテーブルより合計室数には現予約の室数も含まれているから一度、操作している予約の室数を引いて、新しい室数を足す。
  }

  foreach(array_map(null, $reserve_day, $reserve_type) as [$day, $type]){
    if($type == '0'){
      $roomname = 'singleroom';
    } 
    elseif($type == '1') {
      $roomname = 'doubleroom';
    }
    $dbh = Database::getPdo();
    $sql = "SELECT * FROM $roomname WHERE day = :day";
    $ps = $dbh->prepare($sql);
    $ps->bindValue(':day', $day, PDO::PARAM_STR);
    $ps->execute();
    $rows = $ps->fetchAll(PDO::FETCH_ASSOC);
    foreach($rows as $row){
      $stocks[] = $row['inventory'];
    }
  }

  
  // これから変更する該当日の合計予約数が室タイプテーブルで設定されてる在庫数を超えた場合、判定用配列に0を代入し、そうでない場合、0を代入する。
  foreach(array_map(null, $now_books, $stocks) as [$now_book, $stock]){
    if($stock < $now_book){
      $check[] = 0;
    } else {
      $check[] = 1;
    }
  }

  // これから変更したい予約数を判定用配列に値1として代入する。
  $count = count($_POST['id']);
  for($roop=1; $roop<=$count; $roop++){
    $check2[] = 1;
  }

  // ③html内で配列$check(予約数)と配列$check2(在庫数を超えてるか超えていないかで代入された値)、をifで判定し配列が全て合致しなければ処理を弾く。(配列$check2の要素が全て1の値のみ変更処理が可能)
  // オーバーブッキング予防。
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
<?php if(!empty($_POST['confirm'])):?>
  <?php if($check == $check2):?>
    <div id="modify" class="wrapper">
      <h2>予約更新</h2>
      <a href="https://ro-crea.com/demo_hotel/search" class="back">検索画面に戻る</a>
      <ul class="field-flex">
        <li class="random">予約ID</li>
        <li class="date">日付</li>
        <li class="name">名前</li>
        <li class="room">室数</li>
        <li class="night">泊数</li>
        <li class="price">価格</li>
        <li class="tel">電話番号</li>
        <li class="mail">メール</li>
      </ul>
      <!-- 受け取った変数から予約情報を呼び出す。 -->
      <form method="post" action="https://ro-crea.com/demo_hotel/modify_done">
      <?php foreach(array_map(null, $_POST['id'],$_POST['type'],$_POST['day'],$_POST['namae'],$_POST['number'],$_POST['night'],$_POST['price'],$_POST['tel'],$_POST['mail'],$_POST['random']) as [$id, $type, $day, $name, $number, $night, $price, $tel, $mail, $random]): ?>
        <ul class="info-flex">
          <li class="random"><?= Measure::h($random) ?></li>
          <li class="date"><?= Measure::h($day) ?></li>
          <li class="name"><?= Measure::h($name) ?></li>
          <li class="room"><?= Measure::h($number) ?>室</li>
          <li class="night"><?= Measure::h($night) ?>泊</li>
          <li class="price">¥<?= Measure::h($price) ?></li>
          <li class="tel"><?= Measure::h($tel) ?></li>
          <li class="mail"><?= Measure::h($mail) ?></li>
        </ul>
        <input type="hidden" name="id[]" value="<?= Measure::h($id) ?>">
        <input type="hidden" name="namae[]" value="<?= Measure::h($name) ?>">
        <input type="hidden" name="number[]" value="<?= Measure::h($number) ?>">
        <input type="hidden" name="price[]" value="<?= Measure::h($price) ?>">
        <input type="hidden" name="tel[]" value="<?= Measure::h($tel) ?>">
        <input type="hidden" name="mail[]" value="<?= Measure::h($mail) ?>"> 
      <?php endforeach;?>
      <input type="hidden" name="token" value="<?= $token ?>">
      <input type="hidden" name="confirm" value="<?= $confirm ?>"> 
      <p class="note">上記の内容で更新します。</p>
      <input type="submit" class="submit">
      </form>
    </div>
  <?php else: ?>
    <div id="modify-check" class="wrapper">
      <p class="caution">変更した条件での更新はできません。</p>
    </div>
  <?php endif; ?>
<?php else:?>
  <div id="invalid" class="wrapper">
    <p class="caution">不正な画面遷移です。</p>
    <a href="https://ro-crea.com/demo_hotel/search">予約確認に戻る</a>
  </div>
<?php endif;?>
<footer>
</footer>
<?php wp_footer(); ?>
</body>
</html>