<?php
session_start();
session_regenerate_id(true);
if(isset($_SESSION['login'])==false){
  print 'ログインされてません <br>';
  print '<a href="maneger-login.html">ログイン画面へ</a>';
  exit();
}

require_once('header2.php');
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

header('Location: http://localhost:8569/maneger/reserve-delete-done.php');
exit();
}
?>
<body>

<?php if(!empty($_SESSION['confirm2'])):?>
  <div id="delete-done" class="wrapper">
  <p>予約をキャンセルしました。</p>
  <a href="reservation2.php">メニューに戻る</a>
  </div>
  <?php unset($_SESSION['confirm2']) ?>
  <?php unset($_SESSION['token']) ?>
  <?php else: ?>
  <div id="invalid" class="wrapper">
  <p>不正な画面遷移です。</p>
  <a href="reservation2.php">予約状況に戻る</a>
  </div>
  <?php endif;?>
</body>

