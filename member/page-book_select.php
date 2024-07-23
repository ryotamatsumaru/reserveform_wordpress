<?php
//     Template Name: book_select
//     Template Post Type: page
//     Template Path: member/

session_start();
session_regenerate_id(true);
if(isset($_SESSION['cus_login'])==false){
  header('Location:https://ro-crea.com/demo_hotel/login_caution_text/');
  exit();
}

get_header();
require_once(dirname(dirname(__FILE__)). '/prvdtb/pdo-class.php');
require_once(dirname(dirname(__FILE__)). '/measure/measure-class.php');

if(!empty($_GET['num'])){
  $random = Measure::h($_GET['num']);
  $confirm = 1;
  $today = date('Y-m-d');

  $dbh = Database::getPdo();
  $sql = "SELECT * FROM booking WHERE random_id = :random";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':random', $random, PDO::PARAM_STR);
  $ps->execute();
  $rows = $ps->fetchAll(PDO::FETCH_ASSOC);
  foreach($rows as $row){
    $night = $row['night'];
    $reserve = $row['reserve_date'];
    $date[] = $row['day'];
    $id_array[] = $row['id'];
    $checkdates[] = $row['day'];
  }

  if( $rows == true) {
    $timestamp = strtotime($date[0]);
    $out = date('Y-m-d', strtotime('+ '.$night.' day', $timestamp));
    $count = count($id_array);
    for($i=1; $i <= $count; $i++){
      $check2[] = 0;
    }

    foreach($checkdates as $checkdate){
      if($checkdate < $today){
        $check[] = 0;
      } else {
        $check[] = 1;
      }
    }
  }
}
?>

<section>
  <?php if(!empty($_GET['num'])): ?>
    <?php if($rows == true): ?>
      <div id="book-select">
        <h1 class="title">予約詳細</h1>
        <ul class="info-field-flex">
          <li>チェックイン</li>
          <li>チェックアウト</li>
          <li>泊数</li>
          <li>予約日</li>
        </ul>
        <ul class="info-flex">
          <li><?= $date[0]?></li>
          <li><?= $out?></li>
          <li><?= $night?>泊</li>
          <li><?= $reserve?></li>
        </ul>
        <form method="post" action="https://ro-crea.com/demo_hotel/book_divide" id="divide">
        <ul class="list-flex">
        <?php foreach($rows as $row): ?>
          <div class="flex">
            <li><?= $row['day']?></li>
	          <?php if($row['type'] === 0):?>
              <li class="type">シングル</li>
            <?php elseif($row['type'] === 1):?>
              <li class="type">ダブル</li>  
            <?php endif; ?>
            <li class="name"><?= $row['name']?></li>
            <li class="room"><?= $row['number']?>室</li>
            <li class="price">¥<?= $row['price']?></li>
            <li class="subtotal">¥<?= $row['price'] * $row['number']?></li>
            <?php if($today <= $row['day']): ?>
              <li class="check"><input type="checkbox" name="id[]" value="<?= $row['id']?>" ></li>
            <?php else: ?>
              <li class="check"></li>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
        </ul>
        <input type="hidden" name="confirm" value="<?= $confirm?>" >
        </form>
        <?php if($check !== $check2): ?>
          <input type="submit" form="divide" class="submit" value="確定">
        <?php endif; ?>
      </div>
    <?php else:?>
      <div id="invalid" class="wrapper">
        <p class="caution">不正な画面遷移です。</p>
        <a href="https://ro-crea.com/demo_hotel/menu/">メニューに戻る</a>
      </div>
    <?php endif; ?>
  <?php else:?>
    <div id="invalid" class="wrapper">
      <p class="caution">不正な画面遷移です。</p>
      <a href="https://ro-crea.com/demo_hotel/menu/">メニューに戻る</a>
    </div>
  <?php endif; ?>
</section>
<?php get_footer("3");?>