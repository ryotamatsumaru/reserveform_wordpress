<?php
session_start();
session_regenerate_id(true);

if(isset($_SESSION['login'])==false){
  print 'ログインされてません <br>';
  print '<a href="maneger-login.html">ログイン画面へ</a>';
  exit();
}

require('header2.php');
require_once('/../work/app/measure-class.php');
require_once('/../work/app/config.php');
use MyApp\database; 
$dbh = new PDO(DSN,USER,PASS);

if(isset($_POST['id'])){

Measure::validate();
$token = $_SESSION['token'];
 
$_SESSION['confirm2'] = $_POST['confirm'];
$confirm = $_SESSION['confirm2'];
foreach($_POST['id'] as $id){
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
  echo $book_out = (string)$row['SUM(member)'];
  echo $books[date('Y-m-d', $day_out)] = $book_out;
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

// var_dump($check);
$count = count($_POST['id']);
for($roop=1; $roop<=$count; $roop++){
  $check2[] = 1;
}
}
?>
<body>
    <?php if(!empty($_POST['confirm'])):?>
    <?php if($check == $check2):?>
    <div id="modify" class="wrapper">
    <h2>予約更新</h2>
    <ul class="field-flex">
    <li class="random">予約ID</li>
    <li class="date">日付</li>
    <li class="name">名前</li>
    <li class="room">室数</li>
    <li class="night">泊数</li>
    <li class="price">価格</li>
    <li class="tel">電話番号</li>
    <li class="mail">メール</li>
    </ul>
    <form method="post" action="reserve-modify-done.php">
    <?php foreach(array_map(null, $_POST['id'],$_POST['day'],$_POST['name'],$_POST['member'],$_POST['night'],$_POST['price'],$_POST['tel'],$_POST['mail'],$_POST['random']) as [$id, $day, $name, $member, $night, $price, $tel, $mail, $random]): ?>
    <ul class="info-flex">
    <li class="random"><?= $random ?></li>
    <li class="date"><?= $day ?></li>
    <li class="name"><?= $name ?></li>
    <li class="room"><?= $member ?>室</li>
    <li class="night"><?= $night ?>泊</li>
    <li class="price">¥<?= $price ?></li>
    <li class="tel"><?= $tel ?></li>
    <li class="mail"><?= $mail ?></li>
    </ul>
    <input type="hidden" name="id[]" value="<?= $id ?>">
    <input type="hidden" name="name[]" value="<?= $name ?>">
    <input type="hidden" name="member[]" value="<?= $member ?>">
    <input type="hidden" name="price[]" value="<?= $price ?>">
    <input type="hidden" name="mail[]" value="<?= $mail ?>">
    <input type="hidden" name="tel[]" value="<?= $tel ?>">
    <?php endforeach;?>
    <input type="hidden" name="token" value="<?= $token ?>">
    <input type="hidden" name="confirm" value="<?= $confirm ?>"> 
    <p class="note">上記の内容で更新します。</p>
    <input type="submit" class="submit">
    </form>
  </div>
  <?php else: ?>
    <div id="modify-check" class="wrapper">
    <p class="caution">変更した条件での更新はできません。</p>
    </div>
  <?php endif; ?>
  <?php else:?>
  <div id="invalid" class="wrapper">
  <p>不正な画面遷移です。</p>
  <a href="reservation2.php">予約状況に戻る</a>
  </div>
  <?php endif;?>
</body>