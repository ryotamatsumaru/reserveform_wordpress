<?php
session_start();
session_regenerate_id(true);
if(isset($_SESSION['cus_login'])==false){
  print 'ログインされてません <br>';
  print '<a href="maneger-login.html">ログイン画面へ</a>';
  exit();
}

require_once('/../work/app/config.php');
use MyApp\database;

$id = $_SESSION['cus_id'];

function getAddress() {  
  $id = $_SESSION['cus_id'];
  $dbh = new PDO(DSN,USER,PASS);
  $sql = "SELECT mail FROM member WHERE id = :id";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':id', $id, PDO::PARAM_INT);
  $ps->execute();
  $rows = $ps->fetch();
  $mail2 = $rows['mail'];
  return $mail2;
}

$mail = getAddress();
$dbh = new PDO(DSN,USER,PASS);
$sql = "SELECT * FROM booking WHERE mail = :mail ORDER BY reserve_date DESC";
$ps = $dbh->prepare($sql);
$ps->bindValue(':mail', $mail, PDO::PARAM_STR);
$ps->execute();
$rows = $ps->fetchAll(PDO::FETCH_ASSOC);


?>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="customer-reserve.css">
</head>
<body>
<div class="text-area wrapper">
<h1 class="title">予約一覧</h1>
<p class="text">既に予約が完了している一覧です。詳細を見るから予約詳細の変更が可能です。</p>
<p class="caution">※過去予約の変更はできません。</p>
<a href="cus-menu.php">メニューに戻る</a>
</div>
<div id="list">
<ul class="field-flex">  
<li class="field">日付</li>
<li class="field">価格</li>
<li class="field">人数</li>
<li class="field">泊数</li>
<li class="field">予約ID</li>
<li class="field">予約日</li>
</ul>
<form action="cus-reserve-divide.php" method="post" id="divide">
<?php foreach($rows as $row): ?>
  <ul class="list-flex">
  <li><?= $row['day']?></li>
  <li>¥<?= $row['price']?></li>
  <li><?= $row['member']?>室</li>
  <li><?= $row['night']?>泊</li>
  <li><?= $row['random_id']?></li>
  <li><?= $row['reserve_date']?></li>
  <li><a href="cus-reserve-con.php?num=<?= $row['random_id']?>">詳細を見る</a></li>
  </ul>
<?php endforeach; ?>
</form>
</div>
</body>
