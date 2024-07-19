<?php 
session_start();
session_regenerate_id(true);
if(isset($_SESSION['cus_login'])==false){
  print 'ログインされてません <br>';
  print '<a href="maneger-login.html">ログイン画面へ</a>';
  exit();
}

require_once('/../work/app/measure-class.php');
require_once('/../work/app/config.php');
use MyApp\database;

// echo $_GET['num'];

if(!empty($_POST['id'])){
  // var_dump($_POST['id']);

Measure::create();
$token = $_SESSION['token'];
$confirm = $_POST['confirm'];

foreach($_POST['id'] as $id){
$dbh = new PDO(DSN,USER,PASS);
$sql = "SELECT * FROM booking WHERE ban = :id";
$ps = $dbh->prepare($sql);
$ps->bindValue(':id', $id, PDO::PARAM_INT);
$ps->execute();
$rows = $ps->fetchAll(PDO::FETCH_ASSOC);
foreach($rows as $row){
  $ids[] = $row['ban'];
  $dates[] = $row['day'];
  $names[] = $row['name'];
  $members[] = $row['member'];
  $nights[] = $row['night'];
  $prices[] = $row['price'];
}
}
}

// var_dump($id_array);

?>

<head>
  <meta charset="utf-8">
  <link rel= "stylesheet" href="customer-reserve.css" >
</head>
<body>
  <?php if(!empty($_POST['id'])):?>
  <?php if($rows == true): ?>
  <div id="book-divide">
  <h1 class="title">予約更新</h1>
  <p class="note">予約を更新する場合は変更可能な項目を編集して更新を押してください。予約のキャンセルを希望の場合は削除を押してください。</p>
  <form method="post" action="cus-reserve-modify.php" id="modify">
  <?php foreach(array_map(null, $ids, $dates, $names, $members, $nights, $prices) as [$id, $date, $name, $member, $night, $price]):?>
    <ul class="info-flex">
    <li><?= $date?></li>
    <li><input type="text" name="name[]" value="<?= $name ?>"></li>
    <li><input style="width:20px;" type="text" name="member[]" value="<?= $member ?>">室</li>
    <li>¥<?= $price?></li>
    <li>小計 ¥<?= $price * $member ?></li>
    </ul>
    <input type="hidden" name="id[]" value="<?= $id?>" >
    <input type="hidden" name="day[]" value="<?= $date?>" >
    <input type="hidden" name="night[]" value="<?= $night?>" >
    <input type="hidden" name="price[]" value="<?= $price?>" >
  <?php endforeach;?>
  <input type="hidden" name="token" value="<?= $token?>" >
  <input type="hidden" name="confirm" value="<?= $confirm?>" >
  </form>


  <form method="post" action="cus-reserve-delete.php" id="delete">
  <?php foreach(array_map(null, $ids, $nights) as [$id, $night]):?>
  <input type="hidden" name="id[]" value="<?= $id ?>" >
  <input type="hidden" name="night[]" value="<?= $night ?>" >
  <?php endforeach; ?>
  <input type="hidden" name="token" value="<?= $token?>" >
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
  <p>予約を選択してください。</p>
  </div>
  <?php endif; ?>
</body>