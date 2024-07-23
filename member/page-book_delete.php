<?php
//     Template Name: book_delete
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
  foreach($_POST['id'] as $rec_id){
    $rec_id = Measure::h($rec_id);
    $dbh = Database::getPdo();
    $sql = "SELECT * FROM booking WHERE id = :rec_id";
    $ps = $dbh->prepare($sql);
    $ps->bindValue(':rec_id', $rec_id, PDO::PARAM_INT);
    $ps->execute();
    $rows = $ps->fetchAll(PDO::FETCH_ASSOC);

    foreach($rows as $row){
      echo $row['id'];
      $id[] = $row['id'];
      $mail[] = $row['mail'];
      $roma[] = $row['roma'];
      $name = array($row['name']);
      $tel[] = $row['tel'];
      $gender[] = $row['gender'];
      $number[] = $row['number'];
      $night[] = $row['night'];
      $day = array($row['day']);
      $price[] = $row['price'];
      $type[] = $row['type'];
      $reserve[] = $row['reserve_date'];
      $random[] = $row['random_id'];
    }
  }
}

?>
<section>
  <?php if(!empty($_POST['confirm'])): ?>
    <div id="book-delete" class="wrapper">
      <ul class="field-flex">
        <li class="date">日程</li>
        <li class="type">タイプ</li>
        <li class="name">名前</li>
        <li class="room">室数</li>
        <li class="price">価格</li>
        <li class="subtotal">小計</li>
      </ul>
      <form method="post" action="https://ro-crea.com/demo_hotel/book_delete_done" id="delete">
      <?php foreach(array_map(null, $id, $day, $name, $number, $night, $price, $reserve, $random, $type) as [$ids, $days, $names, $numbers, $nights, $prices, $reserves, $randoms, $types]): ?>
        <ul class="info-flex">
          <li class="date"><?= $days ?></li>
          <?php if($types === 0):?>
            <li class="type">シングル</li>
          <?php elseif($types === 1):?>
            <li class="type">ダブル</li>
          <?php endif; ?>
          <li class="name"><?= $names ?>様</li>
          <li class="room"><?= $numbers ?>室</li>
          <li class="price">¥<?= $prices ?></li>
          <li class="subtotal">¥<?= $prices * $numbers ?></li>
        </ul>
        <input type="hidden" name="id[]" value="<?= $ids ?>">
        <input type="hidden" name="price[]" value="<?= $prices ?>">
        <input type="hidden" name="reserve[]" value="<?= $reserves ?>">
        <input type="hidden" name="random[]" value="<?= $randoms ?>">
      <?php endforeach; ?>

      <?php foreach($_POST['id'] as $id): ?>
        <input type="hidden" name="row_id[]" value="<?= Measure::h($id) ?>">
      <?php endforeach; ?>

      <?php foreach($_POST['night'] as $night): ?>
        <input type="hidden" name="row_night[]" value="<?= Measure::h($night) ?>">
      <?php endforeach; ?>
      <br>

      <input type="hidden" name="cus_token" value="<?= $cus_token ?>">
      <input type="hidden" name="confirm" value="<?= $confirm ?>">
      </form>
      <p class="note">上記の予約をキャンセルしてよろしいですか</p>
      <input type="submit" class="submit" form="delete" valeu="確定">
    </div>
  <?php else: ?>
    <div id="invalid" class="wrapper">
      <p class="caution">不正な画面遷移です。</p>
      <a href="https://ro-crea.com/demo_hotel/menu/">メニューに戻る</a>
    </div>
  <?php endif;?>
</section>
<?php get_footer("3"); ?>