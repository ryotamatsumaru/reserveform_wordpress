<?php
//     Template Name: divide
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

  // CSRF対策用のtokenを発行するメソッド
  Measure::create();
  $token = $_SESSION['token'];

  
  $confirm = $_POST['confirm'];
  // idを受け取ってDBから予約情報を呼び出して配列に代入する。
  foreach($_POST['id'] as $id){
    $id = Measure::h($id);
    $dbh = Database::getPdo();
    $sql = "SELECT * FROM booking WHERE id = :id";
    $ps = $dbh->prepare($sql);
    $ps->bindValue(':id', $id, PDO::PARAM_INT);
    $ps->execute();
    $rows = $ps->fetchAll(PDO::FETCH_ASSOC);
    foreach($rows as $row){
      $ids[] = $row['id'];
      $types[] = $row['type'];
      $mails[] = $row['mail'];
      $romas[] = $row['roma'];
      $names[] = $row['name'];
      $tels[] = $row['tel'];
      $genders[] = $row['gender'];
      $numbers[] = $row['number'];
      $nights[] = $row['night'];
      $days[] = $row['day'];
      $prices[] = $row['price'];
      $randoms[] = $row['random_id'];
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
<?php if(!empty($_POST['confirm'])):?>
  <?php if(!empty($_POST['id'])):?>
    <div id="divide" class="wrapper">
      <h2>更新画面</h2>
      <a href="https://ro-crea.com/demo_hotel/search" class="back">検索画面に戻る</a>
      <ul class="field-flex">
        <li class="type">タイプ</li>
        <li class="date">日付</li>
        <li class="name">名前</li>
        <li class="room">室数</li>
        <li class="night">泊数</li>
        <li class="price">価格</li>
        <li class="tel">電話番号</li>
        <li class="mail">メール</li>
      </ul>
      <!-- 予約情報を代入した配列からループを回して表示する。 -->
      <form method="post" action="https://ro-crea.com/demo_hotel/modify" id="modify-form">
      <?php foreach(array_map(null,$ids,$types,$mails,$romas,$names,$tels,$genders,$numbers,$nights,$days,$prices,$randoms) as [$id2,$type,$mail,$roma,$name,$tel,$gender,$number,$night,$day,$price,$random]): ?>
        <ul class="info-flex">
        <?php if($row['type'] === 0):?>
          <li class="type">シングル</li>
        <?php elseif($row['type'] === 1):?>
          <li class="type">ダブル</li>  
        <?php endif; ?>
          <li class="date"><?= $day ?></li>
          <li class="name"><input class="name-bar" type="text" name="namae[]" value="<?= $name ?>"></li>
          <li class="room"><input class="room-bar" type="text" name="number[]" value="<?= $number ?>" >室</li>
          <li class="night"><?= $night ?>泊</li>
          <li class="price">¥<input class="price-bar" type="text" name="price[]" value="<?= $price ?>"></li>
          <li class="tel"><input class="tel-bar" type="text" name="tel[]" value="<?= $tel ?>"></li>
          <li class="mail"><input class="mail-bar" type="text" name="mail[]" value="<?= $mail ?>"></li>
        </ul>
        <input type="hidden" name="id[]" value="<?= $id2 ?>">
        <input type="hidden" name="day[]" value="<?= $day ?>">
        <input type="hidden" name="type[]" value="<?= $type ?>">
        <input type="hidden" name="night[]" value="<?= $night ?>">
        <input type="hidden" name="random[]" value="<?= $random ?>">
      <?php endforeach; ?>
      <input type="hidden" name="token" value="<?= $token ?>">
      <input type="hidden" name="confirm" value="<?= $confirm ?>">
      </form>

      <!-- 予約の更新ボタンと削除ボタンと紐付けしてる。 -->
      <form method="post" action="https://ro-crea.com/demo_hotel/delete" id="delete-form">
      <?php foreach($ids as $id): ?>
        <input type="hidden" name="id[]" value="<?= $id ?>">
        <input type="hidden" name="night[]" value="<?= $night ?>">
      <?php endforeach; ?>
      <input type="hidden" name="token" value="<?= $token ?>">
      <input type="hidden" name="confirm" value="<?= $confirm ?>">
      </form>

      <div class="button-area">
        <span><input type="submit" class="submit" value="更新" form="modify-form"></span>
        <span><input type="submit" class="delete" value="削除" form="delete-form"></span>
      </div>
    </div>
  <?php else: ?>
    <div id="not-set-value" class="wrapper">
      <p>予約を選択してください。</p>
      <a href="https://ro-crea.com/demo_hotel/search/">検索画面に戻る</a>
    </div>
  <?php endif;?>
<?php else: ?>
  <div id="invalid" class="wrapper">
    <p class="caution">不正な画面遷移です。</p>
    <a href="https://ro-crea.com/demo_hotel/search/">予約確認に戻る</a>
  </div>
<?php endif;?>
<footer>
</footer>
<?php wp_footer(); ?>
</body>
</html>