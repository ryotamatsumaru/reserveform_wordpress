<?php
//     Template Name: delete_done
//     Template Post Type: page
//     Template Path: control/

session_start();
// sessionが一致しない場合、ログインの警告ページに遷移する
if(isset($_SESSION['mng_login'])==false){
  header('Location:https://ro-crea.com/demo_hotel/mng_login_caution_text');
  exit();
}

require_once(dirname(dirname(__FILE__)). '/measure/measure-class.php');
require_once(dirname(dirname(__FILE__)). '/prvdtb/pdo-class.php');


if(isset($_POST['id'])){
  // CSRF用対策のtokenが一致するかは判定するメソッド 
  Measure::validate();

  // idを受け取ってキャンセル用(cancel)テーブルにデータを挿入する。
  foreach($_POST['id'] as $id){
    Measure::h($id);
    $dbh = Database::getPdo();
    $sql = "INSERT INTO cancel(id,type, mail, roma, name, tel, gender, number, night, day, price, reserve_date, random_id) SELECT id, type, mail, roma, name, tel, gender, number, night, day, price, reserve_date, random_id FROM booking WHERE id = :id ";
    $ps = $dbh->prepare($sql);
    $ps->bindValue(':id', $id, PDO::PARAM_INT);
    $ps->execute();
  }

  // idを受け取って予約(booking)テーブルからデータを削除する。
  foreach($_POST['id'] as $id){
    $id = Measure::h($id);
    $dbh = Database::getPdo();
    $sql = "DELETE FROM booking WHERE id = :id ";
    $ps = $dbh->prepare($sql);
    $ps->bindValue(':id', $id, PDO::PARAM_INT);
    $ps->execute();
  }

  // 連泊予約のための処理
  // idを受け取って予約(booking)テーブルの泊数を1泊減らす。
  foreach(array_map(null, $_POST['id'], $_POST['row_night'], $_POST['reserve'] , $_POST['random']) as [$id, $night, $reserve, $random]){
    $id = Measure::h($id);
    $night = Measure::h($night);
    $reserve = Measure::h($reserve);
    $random = Measure::h($random);
    $count = count($_POST['id']);
    $reduce_night = $night - $count;
    $dbh = Database::getPdo();
    $sql = "UPDATE booking SET night = :night WHERE reserve_date = :reserve AND random_id = :random";
    $ps = $dbh->prepare($sql);
    $ps->bindValue(':night', $reduce_night, PDO::PARAM_INT);
    $ps->bindValue(':reserve', $reserve, PDO::PARAM_STR);
    $ps->bindValue(':random', $random, PDO::PARAM_INT);
    $ps->execute();
  }

  header('Location:https://ro-crea.com/demo_hotel/delete_done');
  exit();
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
<?php if(!empty($_SESSION['confirm2'])):?>
  <div id="delete-done" class="wrapper">
    <p>予約をキャンセルしました。</p>
    <a href="https://ro-crea.com/demo_hotel/search">メニューに戻る</a>
  </div>
  <!-- 不正アクセス防止用のSESSIONを消去する。 -->
  <?php unset($_SESSION['confirm2']) ?>
  <?php unset($_SESSION['token']) ?>
<?php else: ?>
  <div id="invalid" class="wrapper">
    <p class="caution">不正な画面遷移です。</p>
    <a href="https://ro-crea.com/demo_hotel/search">予約確認に戻る</a>
  </div>
<?php endif;?>
<footer>
</footer>
<?php wp_footer(); ?>
</body>
</html>
