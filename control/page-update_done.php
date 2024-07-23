<?php
//     Template Name: update_done
//     Template Post Type: page
//     Template Path: control/

require_once(dirname(dirname(__FILE__)). '/prvdtb/pdo-class.php');
require_once(dirname(dirname(__FILE__)). '/measure/measure-class.php');

session_start();
// sessionが一致しない場合、ログインの警告ページに遷移する
if(isset($_SESSION['mng_login'])==false){
  header('Location:https://ro-crea.com/demo_hotel/mng_login_caution_text');
  exit();
}

if(!empty($_POST['confirm']) && $_POST['confirm'] === '1'){
  // シングル(singleroomテーブル)の更新記述
  // 料金の値と在庫の値が入力して更新する際の処理
  if(isset($_POST['update_value']) && isset($_POST['update_stock']) && isset($_POST['date'])){

    // 0-9の数字で6桁の数字の入力のみ受け付ける。
    foreach($_POST['update_value'] as $check){
      if(preg_match("/^[0-9]{1,6}$/u", $check) == 1){
        $check_array[] = 1;
      } else {
        $check_array[] = 0;
      } 
    }

    // 更新する料金の日数を配列$value_countに代入する。
    $rec_value = count($_POST['update_value']);
    for($count=1; $count <= $rec_value; $count++){
      $value_count[] = 1;
    }

    // 0-9の数字で4桁の数字の入力のみ受け付ける。
    foreach($_POST['update_stock'] as $check){
      if(preg_match("/^[0-9]{1,4}$/u", $check) == 1){
        $check_array2[] = 1;
      } else {
        $check_array2[] = 0;
      }  
    }

    // 更新する在庫の日数を配列$value_countに代入する。
    $rec_stock = count($_POST['update_stock']);
    for($count=1; $count <= $rec_value; $count++){
      $stock_count[] = 1;
    }

    // 数字以外が入力されている配列は受け付けない。
    if($check_array != $value_count || $check_array2 != $stock_count) {
      echo '入力した値が不正です';
    } else {
      $dates = $_POST['date'];
      $update_value = $_POST['update_value'];
      $update_stock = $_POST['update_stock'];
	
      // データベースへのデータ挿入の際、他利用者とのデータ挿入のバッティングを防ぐ。
      $dbh = Database::getPdo();
      $sql = 'LOCK TABLES singleroom WRITE';
      $ps = $dbh->prepare($sql);
      $ps->execute();

      foreach(array_map(null, $update_value, $update_stock, $dates) as [$value, $stock, $date]){
        // エスケープ処理
        $value = Measure::h($value);
        $stock = Measure::h($stock);
        $date = Measure::h($date);
        $dbh = Database::getPdo();
        $sql = "UPDATE singleroom SET price = :price, inventory = :inventory WHERE day = :day";
        $ps = $dbh->prepare($sql);
        $ps->bindValue(':price', $value, PDO::PARAM_INT);
        $ps->bindValue(':inventory', $stock, PDO::PARAM_INT);
        $ps->bindValue(':day', $date, PDO::PARAM_STR);
        $ps->execute();
      }
      $sql = 'UNLOCK TABLES';
      $ps = $dbh->prepare($sql);
      $ps->execute();
      $dbh = null;
    }
  }  
  
  //ダブル(doubleroomテーブル)の更新記述
  // 料金の値と在庫の値が入力して更新する際の処理
  if(isset($_POST['update_value2']) && isset($_POST['update_stock2']) && isset($_POST['date2'])){
    foreach($_POST['update_value2'] as $check){
      // 0-9の数字で6桁の数字の入力のみ受け付ける。
      if(preg_match("/^[0-9]{1,6}$/u", $check) == 1){
        $check_array[] = 1;
      } else {
        $check_array[] = 0;
      } 
    }

    // 更新する料金の日数を配列$value_countに代入する。
    $rec_value = count($_POST['update_value2']);
    for($count=1; $count <= $rec_value; $count++){
      $value_count[] = 1;
    }

    // 0-9の数字で4桁の数字の入力のみ受け付ける。
    foreach($_POST['update_stock2'] as $check){
      if(preg_match("/^[0-9]{1,4}$/u", $check) == 1){
        $check_array2[] = 1;
      } else {
        $check_array2[] = 0;
      } 
    }

    // 更新する在庫の日数を配列$value_countに代入する。
    $rec_stock = count($_POST['update_stock2']);
    for($count=1; $count <= $rec_value; $count++){
      $stock_count[] = 1;
    }

    // 数字以外が入力されている配列は受け付けない。
    if($check_array != $value_count || $check_array2 != $stock_count) {
      echo '入力した値が不正です';
    } else {
      $dates2 = $_POST['date2'];
      $update_value = $_POST['update_value2'];
      $update_stock = $_POST['update_stock2'];

      // データベースへのデータ挿入の際、他利用者とのデータ挿入のバッティングを防ぐ。
      $dbh = Database::getPdo();
      $sql = 'LOCK TABLES doubleroom WRITE';
      $ps = $dbh->prepare($sql);
      $ps->execute();	
      foreach(array_map(null, $update_value, $update_stock, $dates2) as [$value, $stock, $date]){
        // エスケープ処理
        $value = Measure::h($value);
        $stock = Measure::h($stock);
        $date = Measure::h($date);
        $dbh = Database::getPdo();
        $sql = "UPDATE doubleroom SET price = :price, inventory = :inventory WHERE day = :day";
        $ps = $dbh->prepare($sql);
        $ps->bindValue(':price', $value, PDO::PARAM_INT);
        $ps->bindValue(':inventory', $stock, PDO::PARAM_STR);
        $ps->bindValue(':day', $date, PDO::PARAM_STR);
        $ps->execute();
      }
      $sql = 'UNLOCK TABLES';
      $ps = $dbh->prepare($sql);
      $ps->execute();
      $dbh = null;
    }
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
    <div id="update-check" class="wrapper">
      <p>更新が完了しました。</p>
      <a href="https://ro-crea.com/demo_hotel/update" class="back">設定画面に戻る</a>
    </div>
  <?php else: ?>
    <div id="invalid" class="wrapper">
      <p class="caution">不正な画面遷移です。</p>
      <a href="https://ro-crea.com/demo_hotel/mng_menu" class="back">メニューに戻る</a>
    </div>
  <?php endif; ?>
</section>
<footer>
</footer>
<?php wp_footer(); ?>
</body>
</html>