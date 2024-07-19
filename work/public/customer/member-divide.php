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

$men = '男';
$women = '女';
?>
<body>
  <p><?= $rows['name'] ?></p>
  <input type="text" name="mail" value="<?= $rows['mail'] ?>"></p>
  <input type="text" name="mail" value="<?= $rows['tel'] ?>"></p>
  <p><?=$rows['gender'] ?></p>
  <p><?=$rows['birth_year'] ?></p>
  <p><?=$rows['birth_month'] ?></p>
  <p><?=$rows['birth_day'] ?></p>
  <p><?=$rows['prefecture'] ?></p>
  <p><?=$rows['address'] ?></p>
  <p><?=$rows['registrate_date'] ?></p>
  <form method="post" action="member-divide.php"> 
    <input type="hidden" name="id" value="<?= $id?>">
    <input type="submit">
  </form>
</body>