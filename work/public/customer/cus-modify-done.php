<?php 
// session_start();
// session_regenerate_id(true);

// if(isset($_SESSION['login'])==false){
//   print 'ログインされてません <br>';
//   print '<a href="maneger-login.html">ログイン画面へ</a>';
//   exit();
// }

require_once('/../work/app/measure-class.php');
require_once('/../work/app/config.php');
use MyApp\database; 

if(isset($_POST['id'])){

Measure::validate();
$today = date('Y/m/d H:i:s');

foreach(array_map(null, $_POST['id'],$_POST['name'],$_POST['member']) as [$id, $name, $member, $price, $tel, $mail]){
  $dbh = new PDO(DSN,USER,PASS);
$sql = "UPDATE booking SET name = :name, member = :member, update_date = :update WHERE ban = :id";
$ps = $dbh->prepare($sql);
$ps->bindValue(':id', $id, PDO::PARAM_INT);
$ps->bindValue(':name', $name, PDO::PARAM_STR);
$ps->bindValue(':member', $member, PDO::PARAM_INT);
$ps->bindValue(':update', $today, PDO::PARAM_STR);
$ps->execute();
}

header('Location: http://localhost:8569/customer/cus-modify-done.php');
exit();
}

?>
<head>
  <meta charset="utf-8">
  <link rel= "stylesheet" href="modify-delete.css" >
</head>
<body>
  <div id="book-modify-done" class="wrapper">
  <?php if(!empty($_SESSION['confirm3'])):?>
  <p class="done-text">更新が完了しました。</p>
  <a href="cus-reserve.php" class="back">予約確認に戻る</a>
  <?php unset($_SESSION['confirm3']) ?>
  <?php unset($_SESSION['token']) ?>
  </div>
  <?php else: ?>
  <p>不正な画面遷移です。</p>
  <a href="cus-reserve.php">予約確認に戻る</a>
  <?php endif;?>
</body>