<?php
//     Template Name: insert_done
//     Template Post Type: page
//     Template Path: control/

require_once(dirname(dirname(__FILE__)). '/measure/measure-class.php');
require_once(dirname(dirname(__FILE__)). '/prvdtb/pdo-class.php');

session_start();
// sessionが一致しない場合、ログインの警告ページに遷移する
if(isset($_SESSION['mng_login'])==false){
  header('Location:https://ro-crea.com/demo_hotel/mng_login_caution_text/');
  exit();
}

if(!empty($_POST['confirm'])){
//シングル(singleroomテーブル)の設定記述 
//売止ボタン押して更新した際の処理
if(isset($_POST['id']) && isset($_POST['confirm'])){
  // データベースへのデータ登録の際、他利用者とのデータ登録のバッティングを防ぐ。
  $dbh = Database::getPdo();
  $sql = 'LOCK TABLES singleroom WRITE';
  $ps = $dbh->prepare($sql);
  $ps->execute();
	// 日付ごとのidからinventoryカラムとinventory_copyカラムをそれぞれの配列に代入
  foreach($_POST['id'] as $id){
    $id = Measure::h($id);
    $dbh = Database::getPdo();
    $sql = "SELECT * FROM singleroom WHERE id = :id";
    $ps = $dbh->prepare($sql);
    $ps->bindValue(':id', $id, PDO::PARAM_INT);
    $ps->execute();
    $rows = $ps->fetchAll(PDO::FETCH_ASSOC);
  foreach($rows as $row){
    $inventorys[] = $row['inventory'];
    $inventory_copys[] = $row['inventory_copy'];
  }
  }
  // 売止を押して更新するとき、inventoryに設定された値とinventory_copyに設定された値0を入れ替えてupdateがされ在庫の有無を切り替えるできる。
  foreach(array_map(null,$inventorys, $inventory_copys, $_POST['id']) as [$inventory, $inventory_copy, $set_id]){
    $set_id = Measure::h($set_id);
    $dbh = Database::getPdo();
    $sql = "UPDATE singleroom SET inventory = :inventory_copy, inventory_copy = :inventory WHERE id = :id";
    $ps = $dbh->prepare($sql);
    $ps->bindValue(':inventory', $inventory, PDO::PARAM_STR);
    $ps->bindValue(':inventory_copy', $inventory_copy, PDO::PARAM_INT);
    $ps->bindValue(':id', $set_id, PDO::PARAM_STR);
    $ps->execute();
  }
  $sql = 'UNLOCK TABLES';
  $ps = $dbh->prepare($sql);
  $ps->execute();
  $dbh = null;
}

// 料金の値と在庫の値を設定する際の処理
elseif(isset($_POST['set_value']) && isset($_POST['set_stock']) && isset($_POST['set_stock_copy']) && isset($_POST['date']) && $_POST['single'] === '0' ){
  
  // 配列の要素に対して0-9の数字で6桁、4桁の数字の入力のみ受け付ける。
  $set_value = preg_grep("/^[0-9]{1,6}$/", $_POST['set_value']);
  $set_stock = preg_grep("/^[0-9]{1,4}$/", $_POST['set_stock']);
  $set_stock_copy = $_POST['set_stock_copy'];
  $dates = $_POST['date'];
	
  // データベースへのデータ挿入の際、他利用者とのデータ挿入のバッティングを防ぐ。
  $dbh = Database::getPdo();
  $sql = 'LOCK TABLES singleroom WRITE';
  $ps = $dbh->prepare($sql);
  $ps->execute();

  // 更新をすると料金の変数、在庫の変数、売止更新用の$set_stock_copy変数（0が設定されてる）、日付の変数の配列をループを回して日付毎にsingleroomテーブルに登録する。
  foreach(array_map(null, $set_value, $set_stock, $set_stock_copy, $dates) as [$value, $stock, $stock_copy, $date]){
    if($value != '' && $stock != '' && 0 < $value && 0 < $stock){
      $value = Measure::h($value);
      $stock = Measure::h($stock);
      $stock_copy = Measure::h($stock_copy);
      $date = Measure::h($date);
      $dbh = Database::getPdo();
      $sql = "INSERT INTO singleroom (price, day, inventory, inventory_copy) VALUES (:price,:day,:inventory,:inventory_copy)";
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

//ダブル(doubleroomテーブル)の設定記述 
//売止ボタン押して更新した際の処理
if(isset($_POST['id2']) && isset($_POST['confirm'])){
  // データベースへのデータ登録の際、他利用者とのデータ登録のバッティングを防ぐ。
  $dbh = Database::getPdo();
  $sql = 'LOCK TABLES doubleroom WRITE';
  $ps = $dbh->prepare($sql);
  $ps->execute();

	// 日付ごとのid2からinventoryカラムとinventory_copyカラムをそれぞれの配列に代入
  foreach($_POST['id2'] as $id){
    $id = Measure::h($id);
    $dbh = Database::getPdo();
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

  // 売止を押して更新するとき、inventoryに設定された値とinventory_copyに設定された値0を入れ替えてupdateがされ在庫の有無を切り替えるできる。
  foreach(array_map(null,$inventorys, $inventory_copys, $_POST['id2']) as [$inventory, $inventory_copy,  $set_id]){
    $set_id = Measure::h($set_id);
    $dbh = Database::getPdo();
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
  $dbh = null;
}

  // 料金の値と在庫の値が入力して更新した際の処理
  elseif(isset($_POST['set_value2']) && isset($_POST['set_stock2']) && isset($_POST['set_stock_copy2']) && isset($_POST['date2']) && $_POST['double'] === '1' ){
  
    // 配列の要素に対して0-9の数字で6桁、4桁の数字の入力のみ受け付ける。
    $set_value = preg_grep("/^[0-9]{1,6}$/", $_POST['set_value2']);
    $set_stock = preg_grep("/^[0-9]{1,4}$/", $_POST['set_stock2']);
    $set_stock_copy = $_POST['set_stock_copy2'];
    $dates = $_POST['date2'];
	  
    // データベースへのデータ挿入の際、他利用者とのデータ挿入のバッティングを防ぐ。
    $dbh = Database::getPdo();
    $sql = 'LOCK TABLES doubleroom WRITE';
    $ps = $dbh->prepare($sql);
    $ps->execute();
  
    // 更新をすると料金の変数、在庫の変数、売止更新用の$set_stock_copy変数（0が設定されてる）、日付の変数の配列をループを回して日付毎にdoubleroomテーブルに登録する。
    foreach(array_map(null, $set_value, $set_stock, $set_stock_copy, $dates) as [$value, $stock, $stock_copy, $date]){
      if($value != '' && $stock != '' && 0 < $value && 0 < $stock){
	      $value = Measure::h($value);
	      $stock = Measure::h($stock);
	      $stock_copy = Measure::h($stock_copy);
	      $date = Measure::h($date);
        $dbh = Database::getPdo();
        $sql = "INSERT INTO doubleroom (price, day, inventory, inventory_copy) VALUES (:price,:day,:inventory,:inventory_copy)";
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
}


?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>管理画面(デモサイト)</title>
  <?php wp_head(); ?>
</head>
<body>
<header>
</header>
<section>
  <?php if(!empty($_POST['confirm'])): ?>
    <div id="insert-check" class="wrapper">
      <p>設定が完了しました。</p>
      <a href="https://ro-crea.com/demo_hotel/insert/" class="back">設定画面に戻る</a>
    </div>
  <?php else: ?>
    <div id="invalid" class="wrapper">
      <p class="caution">不正な画面遷移です。</p>
	    <a href="https://ro-crea.com/demo_hotel/mng_menu/" class="back">メニューに戻る</a>
    </div>
  <?php endif; ?>
<section>
<footer>
</footer>
<?php wp_footer(); ?>
</body>
</html>
