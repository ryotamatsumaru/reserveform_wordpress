<?php
session_start();
session_regenerate_id(true);

// if(isset($_SESSION['login'])==false){
//   print 'ログインされてません <br>';
//   print '<a href="maneger-login.html">ログイン画面へ</a>';
//   exit();
// }

require_once('/../work/app/measure-class.php');
require_once('/../work/app/config.php');
use MyApp\database; 
$dbh = new PDO(DSN,USER,PASS);

if(isset($_POST['id'])){

Measure::validate();
$token = $_SESSION['token'];
$_SESSION['confirm3'] = $_POST['confirm'];
$confirm = $_SESSION['confirm3'];

foreach($_POST['id'] as $id){
  $id;
  // そもそもbookingにデータがないと日付も合計値も呼び出せない。
  $sql = "SELECT * FROM booking WHERE ban = :id";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':id', $id, PDO::PARAM_STR);
  $ps->execute();
  $rows = $ps->fetchAll(PDO::FETCH_ASSOC);
  // 予約がない日には0を代入してる。
  // if($rows['member'] == ''){
  //   $rows['member'] = (int)'0';
  // }
  foreach($rows as $row){
  $reserved_member[] = $row['member'];
  $reserve_day[] = $row['day'];
  }
}

foreach($reserve_day as $day){
  // そもそもbookingにデータがないと日付も合計値も呼び出せない。
  $sql = "SELECT COUNT(*),SUM(member),day FROM (SELECT * FROM booking WHERE day = :day) as t";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':day', $day, PDO::PARAM_STR);
  $ps->execute();
  $row = $ps->fetch(PDO::FETCH_ASSOC);
  // 予約がない日には0を代入してる。
  if($row['SUM(member)'] == ''){
    $row['SUM(member)'] = (int)'0';
  }
  $day_out = strtotime((string)$row['day']);
  $book_out = (string)$row['SUM(member)'];
  $books[date('Y-m-d', $day_out)] = $book_out;
}

foreach(array_map(null, $books, $reserved_member, $_POST['member']) as [$book, $reserve_member, $member]){
$now_books[] = ($book - $reserve_member) + $member;
// (該当日予約の合計 - 既に予約した室数) + 今変更したい室数
// データベースからの合計値には今調整したい室数も含まれているから一度、
// 操作している予約の室数を引いて、新しく変更したい室数を足す
}
// var_dump($now_books);


foreach($reserve_day as $day){
  $sql = "SELECT * FROM stock WHERE day = :day";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':day', $day, PDO::PARAM_STR);
  $ps->execute();
  $rows = $ps->fetchAll(PDO::FETCH_ASSOC);
  foreach($rows as $row){
    $stocks[] = $row['inventory'];
  }
}

// var_dump($stocks);

// 設定している上限室数より室数変更後の合計室数が大きい場合、処理を弾く。
// オーバーブッキング予防。
foreach(array_map(null, $now_books, $stocks) as [$now_book, $stock]){
  if($stock < $now_book){
    // echo '不可';
    $check[] = 0;
  } else {
    // echo '可能';
    $check[] = 1;
  }
}

$count = count($_POST['id']);
for($roop=1; $roop<=$count; $roop++){
  $check2[] = 1;
}
}
?>
<head>
  <meta charset="utf-8">
  <link rel= "stylesheet" href="modify-delete.css" >
</head>
<body>
  <div id="book-modify" class="wrapper">
  <?php if(!empty($_POST['confirm'])):?>
  <?php if($check == $check2):?>
    <ul class="field-flex">
    <li class="date">日程</li>
    <li class="name">名前</li>
    <li class="room">室数</li>
    <li class="price">価格</li>
    <li class="subtotal">小計</li>
    </ul>
    
    <form method="post" action="cus-modify-done.php">
    <?php foreach(array_map(null, $_POST['id'],$_POST['day'],$_POST['name'],$_POST['member'],$_POST['night'],$_POST['price']) as [$id, $day, $name, $member, $night, $price]): ?>
    <ul class="info-flex">
    <li class="date"><?= $day ?></li>
    <li class="name"><?= $name ?></li>
    <li class="room"><?= $member ?>室</li>
    <li class="price">¥<?= $price ?></li>
    <li class="subtotal">¥<?= $price * $member ?></li>
    </ul>
    <input type="hidden" name="id[]" value="<?= $id ?>">
    <input type="hidden" name="name[]" value="<?= $name ?>">
    <input type="hidden" name="member[]" value="<?= $member ?>">
    <input type="hidden" name="price[]" value="<?= $price ?>">
    <?php endforeach;?>
    <input type="hidden" name="token" value="<?= $token ?>">
    <input type="hidden" name="confirm" value="<?= $confirm ?>"> 
    <p class="note">上記の内容で予約情報を更新します。</p>
    <input type="submit" class="submit" value="更新">
    </form>
  </div>
  <?php else: ?>
    <p>予約不可</p>
  <?php endif; ?>
  <?php else:?>
    <p>不正な画面遷移です。</p>
    <a href="reservation2.php">予約確認に戻る</a>
  <?php endif;?>
</body>