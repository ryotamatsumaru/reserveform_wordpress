<?php

session_start();
session_regenerate_id(true);

if(isset($_SESSION['login'])==false){
  print 'ログインされてません <br>';
  print '<a href="maneger-login.html">ログイン画面へ</a>';
  exit();
}

require_once('header2.php');
require_once('/../work/app/config.php');
use MyApp\database; 


if(isset($_POST['show'])){

  $confirm = 1;

  $reserve_date = $_POST['reserve_date'];
  $random = $_POST['random'];
  $dbh = new PDO(DSN,USER,PASS);
  $sql = "SELECT * FROM booking WHERE reserve_date = :reserve_date AND random_id = :random";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':reserve_date', $reserve_date, PDO::PARAM_STR);
  $ps->bindValue(':random', $random, PDO::PARAM_STR);
  $ps->execute();
  $rows = $ps->fetchAll(PDO::FETCH_ASSOC);

// $today = date('Y/m/d H:i:s');
  $today = date('Y-m-d');

  foreach($rows as $row){
    $id_array[] = $row['ban'];
    $checkdates[] = $row['day'];
  }

  $count = count($id_array);

  // $check2 = array();
  for($i=1; $i <= $count; $i++){
    $check2[] = 0;
  }

  foreach($checkdates as $checkdate){
    if($checkdate < $today){
    $check[] = 0;
    } else {
    $check[] = 1;
    }
  }
}

// $today = date('Y/m/d H:i:s');

?>
<body>
<?php if(isset($_POST['show'])):?>
<div id="select" class="wrapper">
  <h2>予約状況</h2>
  <ul class="field-flex">
  <li class="checkbox">選択</li>
  <li class="random">予約ID</li>
  <li class="type">タイプ</li>
  <li class="date">日付</li>
  <li class="name">名前</li>
  <li class="room">室数</li>
  <li class="night">泊数</li>
  <li class="price">価格</li>
  </ul>
  <form method="post" action="reserve-divide2.php" id="reserve-divide">
  <?php foreach($rows as $row): ?>

  <ul class="info-flex">
  <?php if( $today <= $row['day']):?>
  <li class="checkbox"><input type="checkbox" name="id[]" value="<?= $row['ban']?>"></li>
  <?php else: ?>
  <li class="checkbox"></li>
  <?php endif; ?>
  <li class="random"><?= $row['random_id'] ?></li>
  <?php if($row['type'] === '0'):?>
    <li class="type">シングル</li>
    <?php elseif($row['type'] === '1'):?>
    <li class="type">ダブル</li>  
  <?php endif; ?>
  <li class="date"><?= $row['day'] ?></li>
  <li class="name"><?= $row['name'] ?></li>
  <li class="room"><?= $row['member'] ?>室</li>
  <li class="night"><?= $row['night'] ?>泊</li>
  <li class="price">¥<?= $row['price'] ?></li>
  </ul>
  <?php endforeach; ?>

  <?php if($check !== $check2): ?>
  <input type="hidden" name="confirm" value="<?= $confirm?>">
  <input type="submit" class="submit" value="確定" form="reserve-divide">
  <?php endif; ?>
  </form>
</div>
<?php else:?>
  <div id="invalid" class="wrapper">
  <p>不正な画面遷移です。</p>
  <a href="reservation2.php">予約状況に戻る</a>
  </div>
<?php endif;?>



</body>