<?php
//     Template Name: book_modify
//     Template Post Type: page
//     Template Path: member/

session_start();
session_regenerate_id(true);
if(isset($_SESSION['cus_login'])==false){
  header('Location:https://ro-crea.com/demo_hotel/login_caution_text/');
  exit();
}

get_header("2");

require_once(dirname(dirname(__FILE__)). '/prvdtb/pdo-class.php');
require_once(dirname(dirname(__FILE__)). '/measure/measure-class.php');

if(isset($_POST['id'])){
  Measure::cus_create();
  $cus_token = $_SESSION['cus_token'];
  $_SESSION['confirm3'] = $_POST['confirm'];
  $confirm = $_SESSION['confirm3'];

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
    }
  }

  foreach(array_map(null,$reserve_day, $_POST['type']) as [$day, $type]){
    $dbh = Database::getPdo();
    // そもそもbookingにデータがないと日付も合計値も呼び出せない。
    $type = Measure::h($type);
    $sql = "SELECT COUNT(*),SUM(number),day FROM (SELECT * FROM booking WHERE day = :day AND type = :type) as booktotal GROUP BY day";
    $ps = $dbh->prepare($sql);
    $ps->bindValue(':type', $type, PDO::PARAM_INT);
    $ps->bindValue(':day', $day, PDO::PARAM_STR);
    $ps->execute();
    $row = $ps->fetch(PDO::FETCH_ASSOC);
    if($row == '') {
	    $row_date = '0';
      $sum_number = '0';
    } else {
      $row_date = $row['day'];
      $sum_number = $row['SUM(number)'];
    }
    $day_out = strtotime((string)$row_date);
    $book_out = (string)$sum_number;
    $books[date('Y-m-d', $day_out)] = $book_out;
  }

  foreach(array_map(null, $books, $reserved_number, $_POST['number']) as [$book, $reserve_number, $number]){
    $number = Measure::h($number);
    $now_books[] = ($book - $reserve_number) + $number;
  }

  foreach(array_map(null, $reserve_day ,$_POST['type']) as [$day, $type]){
    if($type == '0'){
      $roomtype = 'singleroom';
    } 
    elseif($type == '1') {
      $roomtype = 'doubleroom';
    }
    $dbh = Database::getPdo();
    $sql = "SELECT * FROM $roomtype WHERE day = :day";
    $ps = $dbh->prepare($sql);
    $ps->bindValue(':day', $day, PDO::PARAM_STR);
    $ps->execute();
    $rows = $ps->fetchAll(PDO::FETCH_ASSOC);
    foreach($rows as $row){
      $stocks[] = $row['inventory'];
    }
  }

  foreach(array_map(null, $now_books, $stocks) as [$now_book, $stock]){
    if($stock < $now_book){
      $check[] = 0;
    } else {
      $check[] = 1;
    }
  }

  $count = count($_POST['id']);
  for($roop=1; $roop<=$count; $roop++){
    $check2[] = 1;
  }
}

?>
<section>
  <div id="book-modify" class="wrapper">
    <?php if(!empty($_POST['confirm'])):?>
      <?php if($check == $check2):?>
        <ul class="field-flex">
          <li class="date">日程</li>
          <li class="type">タイプ</li>
          <li class="name">名前</li>
          <li class="room">室数</li>
          <li class="price">価格</li>
          <li class="subtotal">小計</li>
        </ul>

        <form method="post" action="https://ro-crea.com/demo_hotel/book_modify_done">
        <?php foreach(array_map(null, $_POST['id'],$_POST['day'],$_POST['name'],$_POST['number'],$_POST['night'],$_POST['price'], $_POST['type']) as [$id, $day, $name, $number, $night, $price, $type]): ?>
          <ul class="info-flex">
            <li class="date"><?= $day ?></li>
	          <?php if($type == 0):?>
	            <li class="type">シングル</li>
	          <?php elseif($type == 1):?>
              <li class="type">ダブル</li>
	          <?php endif; ?>
            <li class="name"><?= $name ?></li>
            <li class="room"><?= $number ?>室</li>
            <li class="price">¥<?= $price ?></li>
            <li class="subtotal">¥<?= $price * $number ?></li>
          </ul>
          <input type="hidden" name="id[]" value="<?= Measure::h($id) ?>">
          <input type="hidden" name="name[]" value="<?= Measure::h($name) ?>">
          <input type="hidden" name="number[]" value="<?= Measure::h($number) ?>">
          <input type="hidden" name="price[]" value="<?= Measure::h($price) ?>">
        <?php endforeach;?>
        <input type="hidden" name="cus_token" value="<?= $cus_token ?>">
        <input type="hidden" name="confirm" value="<?= $confirm ?>"> 
        <p class="note">上記の内容で更新します。</p>
        <input type="submit" class="submit">
        </form>
      <?php else: ?>
        <p>予約不可</p>
      <?php endif; ?>
    <?php else:?>
      <div id="invalid" class="wrapper">
        <p class="caution">不正な画面遷移です。</p>
        <a href="https://ro-crea.com/demo_hotel/menu/">メニューに戻る</a>
      </div>
    <?php endif;?>
  </div>
</section>
<?php get_footer("3"); ?>