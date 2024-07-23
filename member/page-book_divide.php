<?php
//     Template Name: book_divide
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

if(!empty($_POST['id'])){

  $confirm = $_POST['confirm'];

  foreach($_POST['id'] as $id){
    $dbh = Database::getPdo();
    $sql = "SELECT * FROM booking WHERE id = :id";
    $ps = $dbh->prepare($sql);
    $ps->bindValue(':id', $id, PDO::PARAM_INT);
    $ps->execute();
    $rows = $ps->fetchAll(PDO::FETCH_ASSOC);
    foreach($rows as $row){
      $ids[] = $row['id'];
      $dates[] = $row['day'];
      $names[] = $row['name'];
      $numbers[] = $row['number'];
      $nights[] = $row['night'];
      $prices[] = $row['price'];
      $types[] = $row['type'];
   }
  }
}
?>

<section>
  <?php if(!empty($_POST['id'])):?>
    <?php if($rows == true): ?>
      <div id="book-divide">
        <h1 class="title">予約更新</h1>
        <p class="note">予約を更新する場合は変更可能な項目を編集して更新を押してください。予約のキャンセルを希望の場合は削除を押してください。</p>
        <form method="post" action="https://ro-crea.com/demo_hotel/book_modify" id="modify">
        <?php foreach(array_map(null, $ids, $dates, $names, $numbers, $nights, $prices, $types) as [$id, $date, $name, $number, $night, $price, $type]):?>
          <ul class="info-flex">
            <li><?= $date?></li>
	          <?php if( $type === 0):?>
	            <li>シングル</li>
	          <?php elseif($type === 1):?>
              <li>ダブル</li>
	          <?php endif; ?>
            <li><input type="text" name="name[]" value="<?= $name ?>"></li>
            <li><input style="width:20px;" type="text" name="number[]" value="<?= $number ?>">室</li>
            <li>¥<?= $price?></li>
            <li>¥<?= $price * $number ?></li>
          </ul>
          <input type="hidden" name="id[]" value="<?= $id?>" >
          <input type="hidden" name="day[]" value="<?= $date?>" >
          <input type="hidden" name="night[]" value="<?= $night?>" >
          <input type="hidden" name="price[]" value="<?= $price?>" >
          <input type="hidden" name="type[]" value="<?= $type?>" >
        <?php endforeach;?>
        <input type="hidden" name="token" value="<?= $token?>" >
        <input type="hidden" name="confirm" value="<?= $confirm?>" >
        </form>

        <form method="post" action="https://ro-crea.com/demo_hotel/book_delete" id="delete">
        <?php foreach(array_map(null, $ids, $nights, $types) as [$id, $night, $type]):?>
          <input type="hidden" name="id[]" value="<?= $id ?>" >
          <input type="hidden" name="night[]" value="<?= $night ?>" >
          <input type="hidden" name="type[]" value="<?= $type?>" >
        <?php endforeach; ?>
        <input type="hidden" name="confirm" value="<?= $confirm?>" >
        </form>
        <div class="button-area">
          <span><input type="submit" class="submit" form="modify" value="更新"></span>
          <span><input type="submit" class="delete" form="delete" value="削除"></span>
        </div>
      </div>
    <?php else: ?>
      <p>不正な動作です。</p>
    <?php endif; ?>
  <?php else: ?>
    <div id="not-set-value" class="wrapper">
      <p>予約が選択されてません。</p>
    </div>
  <?php endif; ?>
</section>
<?php get_footer("3"); ?>