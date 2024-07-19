<?php 
session_start();
session_regenerate_id(true);
if(isset($_SESSION['cus_login'])==false){
  print 'ログインされてません <br>';
  print '<a href="maneger-login.html">ログイン画面へ</a>';
  exit();
}


require_once('/../work/app/config.php');
use MyApp\database;

// echo $_GET['num'];

if(!empty($_GET['num'])){
$random = $_GET['num'];
$confirm = 1;
$today = date('Y-m-d');

$dbh = new PDO(DSN,USER,PASS);
$sql = "SELECT * FROM booking WHERE random_id = :random";
$ps = $dbh->prepare($sql);
$ps->bindValue(':random', $random, PDO::PARAM_STR);
$ps->execute();
$rows = $ps->fetchAll(PDO::FETCH_ASSOC);
foreach($rows as $row){
  $night = $row['night'];
  $reserve = $row['reserve_date'];
  $date[] = $row['day'];
  $id_array[] = $row['ban'];
  $checkdates[] = $row['day'];
}
if( $rows == true) {
$timestamp = strtotime($date[0]);
$out = date('Y-m-d', strtotime('+ '.$night.' day', $timestamp));

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

}
?>

<?php if(!empty($_GET['num'])): ?>
<?php if($rows == true): ?>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="customer-reserve.css">
</head>
<body>
<div id="book-select">
<h1 class="title">予約詳細</h1>
  <ul class="info-field-flex">
  <li>チェックイン</li>
  <li>チェックアウト</li>
  <li>泊数</li>
  <li>予約日</li>
  </ul>
  <ul class="info-flex">
  <li><?= $date[0]?></li>
  <li><?= $out?></li>
  <li><?= $night?>泊</li>
  <li><?= $reserve?></li>
  </ul>
  <form method="post" action="cus-reserve-divide.php" id="divide">
  <ul class="list-flex">
  <?php foreach($rows as $row): ?>
    <div class="flex">
    <li class="date"><?= $row['day']?></li>
    <li class="name"><?= $row['name']?></li>
    <li class="room"><?= $row['member']?>室</li>
    <li class="price">¥<?= $row['price']?></li>
    <li class="subtotal">¥<?= $row['price'] * $row['member']?></li>
    <?php if($today <= $row['day']): ?>
    <li class="check"><input type="checkbox" name="id[]" value="<?= $row['ban']?>" ></li>
    <?php else: ?>
    <li class="check"></li>
    <?php endif; ?>
    </div>
  <?php endforeach; ?>
  </ul>
    <input type="hidden" name="confirm" value="<?= $confirm?>">
  </form>
  <?php if($check !== $check2): ?>
  <input type="submit" class="submit" form="divide" value="確定">
  <?php endif; ?>
</div>
  <?php else:?>
    <p>不正な画面遷移です。</p>
  <?php endif; ?>
  <?php else:?>
    <p>不正な画面遷移です。</p>
  <?php endif; ?>
</body>