<?php

session_start();
session_regenerate_id(true);
if(isset($_SESSION['login'])==false){
  print 'ログインされてません <br>';
  print '<a href="maneger-login.html">ログイン画面へ</a>';
  exit();
}

require_once('header3.php');

$dsn='mysql:host=db;dbname=reserve;charset=utf8';
$user='testuser';
$pass='testpass';

if(!empty($_POST['confirm']) && $_POST['confirm'] === 1){  
if(isset($_POST['update_value']) && isset($_POST['update_stock']) && isset($_POST['date'])){

  foreach($_POST['update_value'] as $check){
    if(preg_match("/^[0-9]{1,6}$/u", $check) == 1){
    $check_array[] = 1;
    } else {
    $check_array[] = 0;
    } 
  }

  $rec_value = count($_POST['update_value']);
  for($count=1; $count <= $rec_value; $count++){
    $value_count[] = 1;
  }

  foreach($_POST['update_stock'] as $check){
    if(preg_match("/^[0-9]{1,4}$/u", $check) == 1){
    $check_array2[] = 1;
    } else {
    $check_array2[] = 0;
    } 
  }

  $rec_stock = count($_POST['update_stock']);
  for($count=1; $count <= $rec_value; $count++){
    $stock_count[] = 1;
  }

  if($check_array != $value_count || $check_array2 != $stock_count) {
    echo '入力した値が不正です';
  } else {
  $dates = $_POST['date'];
  $update_value = $_POST['update_value'];
  $update_stock = $_POST['update_stock'];
  foreach(array_map(null, $update_value, $update_stock, $dates) as [$value, $stock, $date]){
  $dbh = new PDO($dsn,$user,$pass);
  $sql = "UPDATE stock SET price = :price, inventory = :inventory WHERE day = :day";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':price', $value, PDO::PARAM_INT);
  $ps->bindValue(':inventory', $stock, PDO::PARAM_STR);
  $ps->bindValue(':day', $date, PDO::PARAM_STR);
  $ps->execute();
  }
  }
}  

if(isset($_POST['update_value2']) && isset($_POST['update_stock2']) && isset($_POST['date2'])){

  foreach($_POST['update_value2'] as $check){
    if(preg_match("/^[0-9]{1,6}$/u", $check) == 1){
    $check_array[] = 1;
    } else {
    $check_array[] = 0;
    } 
  }
  $rec_value = count($_POST['update_value2']);
  for($count=1; $count <= $rec_value; $count++){
    $value_count[] = 1;
  }
  foreach($_POST['update_stock2'] as $check){
    if(preg_match("/^[0-9]{1,4}$/u", $check) == 1){
    $check_array2[] = 1;
    } else {
    $check_array2[] = 0;
    } 
  }
  $rec_stock = count($_POST['update_stock2']);
  for($count=1; $count <= $rec_value; $count++){
    $stock_count[] = 1;
  }

  if($check_array != $value_count || $check_array2 != $stock_count) {
    echo '入力した値が不正です';
  } else {
  $dates = $_POST['date'];
  $update_value = $_POST['update_value2'];
  $update_stock = $_POST['update_stock2'];
  foreach(array_map(null, $update_value, $update_stock, $dates) as [$value, $stock, $date]){
  $dbh = new PDO($dsn,$user,$pass);
  $sql = "UPDATE doubleroom SET price = :price, inventory = :inventory WHERE day = :day";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':price', $value, PDO::PARAM_INT);
  $ps->bindValue(':inventory', $stock, PDO::PARAM_STR);
  $ps->bindValue(':day', $date, PDO::PARAM_STR);
  $ps->execute();
  }
  }
}
}
?>
<body>
  <?php if(!empty($_POST['confirm'])): ?>
  <div id="update-check" class="wrapper">
  <p>更新が完了しました。</p>
  <a href="stock-insert3.php">設定画面に戻る</a>
  </div>
  <?php else: ?>
  <div id="invalid" class="wrapper">
  <p>不正な画面遷移です。</p>
  <a href="menu.php">メニューに戻る</a>
  </div>
  <?php endif; ?>
</body>