<?php

session_start();
session_regenerate_id(true);
if(isset($_SESSION['login'])==false){
  print 'ログインされてません <br>';
  print '<a href="maneger-login.html">ログイン画面へ</a>';
  exit();
}

$dsn='mysql:host=db;dbname=reserve;charset=utf8';
$user='testuser';
$pass='testpass';


if(isset($_POST['id'])){
  foreach($_POST['id'] as $id){
  $dbh = new PDO($dsn,$user,$pass);
  $sql = "SELECT * FROM stock WHERE id = :id";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':id', $id, PDO::PARAM_INT);
  $ps->execute();
  $rows = $ps->fetchAll(PDO::FETCH_ASSOC);
  foreach($rows as $row){
  $inventorys[] = $row['inventory'];
  $inventory_copys[] = $row['inventory_copy'];
  }
  }
  foreach(array_map(null,$inventorys, $inventory_copys, $_POST['id']) as [$inventory, $inventory_copy,  $set_id]){
  $dbh = new PDO($dsn,$user,$pass);
  $sql = "UPDATE stock SET inventory = :inventory_copy, inventory_copy = :inventory WHERE id = :id";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':inventory', $inventory, PDO::PARAM_STR);
  $ps->bindValue(':inventory_copy', $inventory_copy, PDO::PARAM_INT);
  $ps->bindValue(':id', $set_id, PDO::PARAM_STR);
  $ps->execute();
  }
  echo '更新が完了しました。';
  echo '<br>';
  echo '<br>';
  echo '<a href="menu.php">メニューに戻る</a>';
}
elseif(isset($_POST['set_value']) && isset($_POST['set_stock']) && isset($_POST['set_stock_copy']) && isset($_POST['date'])){

  $set_value = preg_grep("/^[0-9]{1,6}$/", $_POST['set_value']);
  $set_stock = preg_grep("/^[0-9]{1,4}$/", $_POST['set_stock']);
  $set_stock_copy = $_POST['set_stock_copy'];
  $dates = $_POST['date'];

  // var_dump($set_stock_copy);

  foreach(array_map(null, $set_value, $set_stock, $set_stock_copy, $dates) as [$value, $stock, $stock_copy,  $date]){
    $dbh = new PDO($dsn,$user,$pass);
    $sql = "INSERT INTO stock (price, day, inventory, inventory_copy) VALUES (:price,:day,:inventory,:inventory_copy)";
    $ps = $dbh->prepare($sql);
    $ps->bindValue(':price', $value, PDO::PARAM_INT);
    $ps->bindValue(':day', $date, PDO::PARAM_STR);
    $ps->bindValue(':inventory', $stock, PDO::PARAM_INT);
    $ps->bindValue(':inventory_copy', $stock_copy, PDO::PARAM_INT);
    $ps->execute();
  }
  echo '設定が完了しました。';
  echo '<br>';
  echo '<br>';
  echo '<a href="menu.php">メニューに戻る</a>';
} else {
  echo'値が入力されてません';
}


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
  
  if(isset($_POST['id'])){
  foreach($_POST['id'] as $id){
  $sql = "SELECT * FROM stock WHERE id = :id";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':id', $id, PDO::PARAM_INT);
  $ps->execute();
  $rows = $ps->fetchAll(PDO::FETCH_ASSOC);
  foreach($rows as $row){
  $inventorys[] = $row['inventory'];
  $inventory_copys[] = $row['inventory_copy'];
  }
  }
  foreach(array_map(null,$inventorys, $inventory_copys, $_POST['id']) as [$inventory, $inventory_copy,  $set_id]){
  $dbh = new PDO($dsn,$user,$pass);
  $sql = "UPDATE stock SET inventory = :inventory_copy, inventory_copy = :inventory WHERE id = :id";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':inventory', $inventory, PDO::PARAM_STR);
  $ps->bindValue(':inventory_copy', $inventory_copy, PDO::PARAM_INT);
  $ps->bindValue(':id', $set_id, PDO::PARAM_STR);
  $ps->execute();
  }
  }
             
  echo '更新が完了しました。';
  echo '<br>';
  echo '<br>';
  echo '<a href="menu.php">メニューに戻る</a>';
  }

}

// if(isset($_POST['id'])){
//   foreach($_POST['id'] as $id){
//   $dbh = new PDO($dsn,$user,$pass);
//   $sql = "SELECT * FROM stock WHERE id = :id";
//   $ps = $dbh->prepare($sql);
//   $ps->bindValue(':id', $id, PDO::PARAM_INT);
//   $ps->execute();
//   $rows = $ps->fetchAll(PDO::FETCH_ASSOC);
//   foreach($rows as $row){
//   $inventorys[] = $row['inventory'];
//   $inventory_copys[] = $row['inventory_copy'];
//   }
//   }
//   foreach(array_map(null,$inventorys, $inventory_copys, $_POST['id']) as [$inventory, $inventory_copy,  $set_id]){
//   $dbh = new PDO($dsn,$user,$pass);
//   $sql = "UPDATE stock SET inventory = :inventory_copy, inventory_copy = :inventory WHERE id = :id";
//   $ps = $dbh->prepare($sql);
//   $ps->bindValue(':inventory', $inventory, PDO::PARAM_STR);
//   $ps->bindValue(':inventory_copy', $inventory_copy, PDO::PARAM_INT);
//   $ps->bindValue(':id', $set_id, PDO::PARAM_STR);
//   $ps->execute();
//   }
//   echo '更新が完了しました。';
//   echo '<br>';
//   echo '<br>';
//   echo '<a href="menu.php">メニューに戻る</a>';
// }

?>