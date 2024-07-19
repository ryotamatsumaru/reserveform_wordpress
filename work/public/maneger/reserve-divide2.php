<?php

session_start();
session_regenerate_id(true);
if(isset($_SESSION['login'])==false){
  print 'ログインされてません <br>';
  print '<a href="maneger-login.html">ログイン画面へ</a>';
  exit();
}

require_once('header2.php');
require_once('/../work/app/measure-class.php');
require_once('/../work/app/config.php');
use MyApp\database; 
if(isset($_POST['id'])){

  Measure::create();
  $token = $_SESSION['token'];

  $confirm = $_POST['confirm'];
  foreach($_POST['id'] as $id){
  $dbh = new PDO(DSN,USER,PASS);
  $sql = "SELECT * FROM booking WHERE ban = :id";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':id', $id, PDO::PARAM_STR);
  $ps->execute();
  $rows = $ps->fetchAll(PDO::FETCH_ASSOC);
  foreach($rows as $row){
    $ban[] = $row['ban'];
    $type[] = $row['type'];
    $mail[] = $row['mail'];
    $roma[] = $row['roma'];
    $name[] = $row['name'];
    $tel[] = $row['tel'];
    $gender[] = $row['gender'];
    $member[] = $row['member'];
    $night[] = $row['night'];
    $day[] = $row['day'];
    $price[] = $row['price'];
    $random[] = $row['random_id'];
  }
  }
}


?>
<body>
<?php if(!empty($_POST['confirm'])):?>
<?php if(!empty($_POST['id'])):?>
  <div id="divide" class="wrapper">
  <h2>更新画面</h2>
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
  <form method="post" action="modify-test.php" id="reserve-modify">
  <?php foreach(array_map(null, $ban, $type, $mail, $roma, $name, $tel, $gender, $member, $night, $day, $price, $random) as [$bans, $types, $mails, $romas, $names, $tels, $genders, $members, $nights, $days, $prices, $randoms]): ?>
  <ul class="info-flex">
  <?php if($row['type'] === '0'):?>
  <li class="type">シングル</li>
  <?php elseif($row['type'] === '1'):?>
  <li class="type">ダブル</li>  
  <?php endif; ?>
  <li class="date"><?= $days ?></li>
  <li class="name"><input class="name-bar" type="text" name="name[]" value="<?= Measure::h($names) ?>"></li>
  <li class="room"><input class="room-bar" type="text" name="member[]" value="<?= $members ?>" >室</li>
  <li class="night"><?= $nights ?>泊</li>
  <li class="price">¥<input class="price-bar" type="text" name="price[]" value="<?= $prices ?>"></li>
  <li class="tel"><input class="tel-bar" type="text" name="tel[]" value="<?= $tels ?>"></li>
  <li class="mail"><input class="mail-bar" type="text" name="mail[]" value="<?= $mails ?>"></li>
  </ul>
  <input type="hidden" name="id[]" value="<?= $bans ?>">
  <input type="hidden" name="day[]" value="<?= $days ?>">
  <input type="hidden" name="type[]" value="<?= $types ?>">
  <input type="hidden" name="night[]" value="<?= $nights ?>">
  <input type="hidden" name="random[]" value="<?= $randoms ?>">
  <?php endforeach; ?>
  <input type="hidden" name="token" value="<?= $token ?>">
  <input type="hidden" name="confirm" value="<?= $confirm ?>">
  </form>

  <form method="post" action="delete-test.php" id="reserve-delete">
  <?php foreach($ban as $bans): ?>
  <input type="hidden" name="id[]" value="<?= $bans ?>">
  <input type="hidden" name="night[]" value="<?= $nights ?>">
  <?php endforeach; ?>
  <input type="hidden" name="token" value="<?= $token ?>">
  <input type="hidden" name="confirm" value="<?= $confirm ?>">
  </form>

  <div class="button-area">
  <span><input type="submit" class="submit" value="更新" form="reserve-modify"></span>
  <span><input type="submit" class="delete" value="削除" form="reserve-delete"></span>
  </div>

  </div>
  <?php else: ?>
  <div id="not-set-value" class="wrapper">
  <p>予約を選択してください。</p>
  </div>
  <?php endif;?>
  <?php else: ?>
  <div id="invalid" class="wrapper">
  <p>不正な画面遷移です。</p>
  <a href="reservation2.php">予約状況に戻る</a>
  </div>
  <?php endif;?>
  
</body>