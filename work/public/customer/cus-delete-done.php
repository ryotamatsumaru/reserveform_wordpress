<?php
session_start();
session_regenerate_id(true);
if(isset($_SESSION['login'])==false){
  print 'ログインされてません <br>';
  print '<a href="maneger-login.html">ログイン画面へ</a>';
  exit();
}

require_once('/../work/app/measure-class.php');
require_once('/../work/app/config.php');
use MyApp\database;
$dbh = new PDO(DSN,USER,PASS);

if(isset($_POST['id'])){

  Measure::validate();

foreach($_POST['id'] as $id){
  // echo $id;
  $sql = "INSERT INTO cancel(id, mail, roma, name, tel, gender, member, night, day, price, reserve_date, random_id) SELECT ban, mail, roma, name, tel, gender, member, night, day, price, reserve_date, random_id FROM booking WHERE ban = :id ";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':id', $id, PDO::PARAM_INT);
  $ps->execute();
}

foreach($_POST['id'] as $id){
  $sql = "DELETE FROM booking WHERE ban = :id ";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':id', $id, PDO::PARAM_INT);
  $ps->execute();
}

foreach(array_map(null, $_POST['id'], $_POST['row_night'], $_POST['reserve'] , $_POST['random']) as [$id, $night, $reserve, $random]){
echo $id;
$count = count($_POST['id']);
$reduce_night = $night - $count;
$sql = "UPDATE booking SET night = :night WHERE reserve_date = :reserve AND random_id = :random";
$ps = $dbh->prepare($sql);
$ps->bindValue(':night', $reduce_night, PDO::PARAM_INT);
$ps->bindValue(':reserve', $reserve, PDO::PARAM_STR);
$ps->bindValue(':random', $random, PDO::PARAM_INT);
$ps->execute();
}

header('Location: http://localhost:8569/customer/cus-delete-done.php');
exit();
}
?>
<head>
  <meta charset="utf-8">
  <link rel= "stylesheet" href="modify-delete.css" >
</head>
<body>
<?php if(!empty($_SESSION['confirm3'])):?>
  <div id="book-delete-done" class="wrapper">
  <p class="done-text">予約をキャンセルしました。</p>
  <a href="cus-reserve.php" class="back">メニューに戻る</a>
  </div>
  <?php unset($_SESSION['confirm3']) ?>
  <?php unset($_SESSION['token']) ?>
  <?php else: ?>
  <p>不正な画面遷移です。</p>
  <a href="cus-reserve.php">予約確認に戻る</a>
  <?php endif;?>
</body>

