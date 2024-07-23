<?php
//     Template Name: modify_done
//     Template Post Type: page
//     Template Path: control/

session_start();
session_regenerate_id(true);
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
  $today = date('Y/m/d H:i:s');

  foreach(array_map(null, $_POST['id'],$_POST['namae'],$_POST['number'],$_POST['price'],$_POST['tel'],$_POST['mail']) as [$id, $name, $number, $price, $tel, $mail]){
    $id = Measure::h($id);
    $name = Measure::h($name);
    $number = Measure::h($number);
    $price = Measure::h($price);
    $tel = Measure::h($tel);
    $mail = Measure::h($mail);
    $dbh = Database::getPdo();
    $sql = "UPDATE booking SET name = :name, number = :number, price = :price, tel = :tel, mail = :mail ,update_date = :update WHERE id = :id";
    $ps = $dbh->prepare($sql);
    $ps->bindValue(':id', $id, PDO::PARAM_INT);
    $ps->bindValue(':name', $name, PDO::PARAM_STR);
    $ps->bindValue(':number', $number, PDO::PARAM_INT);
    $ps->bindValue(':price', $price, PDO::PARAM_INT);
    $ps->bindValue(':tel', $tel, PDO::PARAM_STR);
    $ps->bindValue(':mail', $mail, PDO::PARAM_STR);
    $ps->bindValue(':update', $today, PDO::PARAM_STR);
    $ps->execute();
  }

  header('Location:https://ro-crea.com/demo_hotel/modify_done');
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
  <div id="modify-done" class="wrapper">
    <p>更新が完了しました。</p>
    <a href="https://ro-crea.com/demo_hotel/search">予約確認に戻る</a>
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