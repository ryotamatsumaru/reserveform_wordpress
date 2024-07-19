<?php
session_start();

require_once('/../work/app/measure-class.php');
require_once('/../work/app/config.php');
use MyApp\database; 
$dbh = new PDO(DSN,USER,PASS);

$id = $_SESSION['cus_id'];

$dbh = new PDO(DSN,USER,PASS);
$sql = "SELECT * FROM member WHERE id = :id";
$ps = $dbh->prepare($sql);
$ps->bindValue(':id', $id, PDO::PARAM_INT);
$ps->execute();
$rows = $ps->fetch();
?>
<head>
  <meta charset="utf-8">
  <link rel= "stylesheet" href="member-info.css" >
</head>
<body>
  <div id="member-info" class="wrapper">
  <h1 class="title"> 会員情報</h1>
  <ul class="field">
  <li>氏名： <?= $rows['name'] ?></li>
  <li>メールアドレス： <?= $rows['mail'] ?></li>
  <li>ローマ字： <?= $rows['roma'] ?></li>
  <li>電話番号： <?=$rows['tel'] ?></li>
  <li>性別： <?=$rows['gender'] ?></li>
  <li>生年月日：
  <?=$rows['birth_year'] ?>年
  <?=$rows['birth_month'] ?>月
  <?=$rows['birth_day'] ?>日</li>

  <li>住所：
  <?=$rows['prefecture'] ?>
  <?=$rows['address'] ?></li>

  <li>登録日： <?=$rows['registrate_date'] ?></li>
  </ul>
  <form method="post" action="member-divide.php"> 
    <input type="hidden" name="id" value="<?= $id?>">
    <input type="submit">
  </form>
  <a href="cus-menu.php" class="back">メニューに戻る</a>
  </div>
</body>