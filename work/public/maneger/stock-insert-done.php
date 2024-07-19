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

if(!empty($_POST['confirm'])){
if(isset($_POST['id']) && isset($_POST['confirm'])){

  foreach($_POST['day'] as $test1){
    echo $test1;
  }

  $dbh = new PDO($dsn,$user,$pass);
  $sql = 'LOCK TABLES stock WRITE';
  $ps = $dbh->prepare($sql);
  $ps->execute();

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
  foreach(array_map(null,$inventorys, $inventory_copys, $_POST['id']) as [$inventory, $inventory_copy, $set_id]){
  $dbh = new PDO($dsn,$user,$pass);
  $sql = "UPDATE stock SET inventory = :inventory_copy, inventory_copy = :inventory WHERE id = :id";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':inventory', $inventory, PDO::PARAM_STR);
  $ps->bindValue(':inventory_copy', $inventory_copy, PDO::PARAM_INT);
  $ps->bindValue(':id', $set_id, PDO::PARAM_STR);
  $ps->execute();
  }
  
  $sql = 'UNLOCK TABLES';
  $ps = $dbh->prepare($sql);
  $ps->execute();
  // $dbh = null;
}
elseif(isset($_POST['set_value']) && isset($_POST['set_stock']) && isset($_POST['set_stock_copy']) && isset($_POST['date']) && $_POST['stock'] === '0' ){
  $set_value = preg_grep("/^[0-9]{1,6}$/", $_POST['set_value']);
  $set_stock = preg_grep("/^[0-9]{1,4}$/", $_POST['set_stock']);
  $set_stock_copy = $_POST['set_stock_copy'];
  $dates = $_POST['date'];

  $dbh = new PDO($dsn,$user,$pass);
  $sql = 'LOCK TABLES stock WRITE';
  $ps = $dbh->prepare($sql);
  $ps->execute();

  foreach(array_map(null, $set_value, $set_stock, $set_stock_copy, $dates) as [$value, $stock, $stock_copy,  $date]){
    if(!empty($date) && $stock != '' && $value != '' && 0 < $stock && 0 < $value){
    echo $date;
    echo $value;
    $dbh = new PDO($dsn,$user,$pass);
    $sql = "INSERT INTO stock (price, day, inventory, inventory_copy) VALUES (:price,:day,:inventory,:inventory_copy)";
    $ps = $dbh->prepare($sql);
    $ps->bindValue(':price', $value, PDO::PARAM_INT);
    $ps->bindValue(':day', $date, PDO::PARAM_STR);
    $ps->bindValue(':inventory', $stock, PDO::PARAM_INT);
    $ps->bindValue(':inventory_copy', $stock_copy, PDO::PARAM_INT);
    $ps->execute();
    }
  }

  $sql = 'UNLOCK TABLES';
  $ps = $dbh->prepare($sql);
  $ps->execute();
  $dbh = null;
}

if(isset($_POST['id2']) && isset($_POST['confirm'])){

  $sql = 'LOCK TABLES doubleroom WRITE';
  $ps = $dbh->prepare($sql);
  $ps->execute();

  foreach($_POST['id2'] as $id){
  $dbh = new PDO($dsn,$user,$pass);
  $sql = "SELECT * FROM doubleroom WHERE id = :id";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':id', $id, PDO::PARAM_INT);
  $ps->execute();
  $rows = $ps->fetchAll(PDO::FETCH_ASSOC);
  foreach($rows as $row){
  $inventorys[] = $row['inventory'];
  $inventory_copys[] = $row['inventory_copy'];
  }
  }
  foreach(array_map(null,$inventorys, $inventory_copys, $_POST['id2']) as [$inventory, $inventory_copy,  $set_id]){
  $dbh = new PDO($dsn,$user,$pass);
  $sql = "UPDATE doubleroom SET inventory = :inventory_copy, inventory_copy = :inventory WHERE id = :id";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':inventory', $inventory, PDO::PARAM_STR);
  $ps->bindValue(':inventory_copy', $inventory_copy, PDO::PARAM_INT);
  $ps->bindValue(':id', $set_id, PDO::PARAM_STR);
  $ps->execute();
  }

  $sql = 'UNLOCK TABLES';
  $ps = $dbh->prepare($sql);
  $ps->execute();
  }
  elseif(isset($_POST['set_value2']) && isset($_POST['set_stock2']) && isset($_POST['set_stock_copy2']) && isset($_POST['date2']) && $_POST['double'] === '1' ){
  
    $set_value = preg_grep("/^[0-9]{1,6}$/", $_POST['set_value2']);
    $set_stock = preg_grep("/^[0-9]{1,4}$/", $_POST['set_stock2']);
    $set_stock_copy = $_POST['set_stock_copy2'];
    $dates = $_POST['date2'];
  
    foreach(array_map(null, $set_value, $set_stock, $set_stock_copy, $dates) as [$value, $stock, $stock_copy, $date]){
      if(!empty($date) && $stock != '' && $value != '' && 0 < $stock && 0 < $value){
      $dbh = new PDO($dsn,$user,$pass);
      $sql = "INSERT INTO doubleroom (price, day, inventory, inventory_copy) VALUES (:price,:day,:inventory,:inventory_copy)";
      $ps = $dbh->prepare($sql);
      $ps->bindValue(':price', $value, PDO::PARAM_INT);
      $ps->bindValue(':day', $date, PDO::PARAM_STR);
      $ps->bindValue(':inventory', $stock, PDO::PARAM_INT);
      $ps->bindValue(':inventory_copy', $stock_copy, PDO::PARAM_INT);
      $ps->execute();
      }
    }
  } 
}


?>
<body>
  <?php if(!empty($_POST['confirm'])): ?>
  <div id="insert-check" class="wrapper">
  <p>設定が完了しました。</p>
  <a href="stock-insert3.php">設定画面に戻る</a>
  </div>
  <?php else: ?>
  <div id="invalid" class="wrapper">
  <p>不正な画面遷移です。</p>
  <a href="menu.php">メニューに戻る</a>
  </div>
  <?php endif; ?>
</body>
