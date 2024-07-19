<?php

session_start();
session_regenerate_id(true);
if(isset($_SESSION['login'])==false){
  print 'ログインされてません <br>';
  print '<a href="maneger-login.html">ログイン画面へ</a>';
  exit();
}

// require_once('/../work/app/measure-class.php');
require_once('/../work/app/config.php');
use MyApp\database;
$dbh = new PDO(DSN,USER,PASS);

if(isset($_POST['id'])){
  
  // Measure::validate();
  $id = $_POST['id'];

  $sql = "DELETE FROM member WHERE id = :id ";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':id', $id, PDO::PARAM_INT);
  $ps->execute();


header('Location: http://localhost:8569/customer/member-cancel-done.php');
exit();
}
?>
<head>
  <meta charset="utf-8">
  <link rel= "stylesheet" href="member-info.css" >
</head>
<body>

  <div id="leave-done" class="wrapper">
  <p class="done-text">退会手続きが完了しました。</p>
  <a href="cus-login.php" class="back">メニューに戻る</a>
  </div>
  <?php unset($_SESSION['cus_id']) ?>
</body>

