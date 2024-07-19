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

Measure::validate();
$today = date('Y/m/d H:i:s');

foreach(array_map(null, $_POST['id'],$_POST['name'],$_POST['member'],$_POST['price'],$_POST['tel'],$_POST['mail']) as [$id, $name, $member, $price, $tel, $mail]){
  $dbh = new PDO(DSN,USER,PASS);
$sql = "UPDATE booking SET name = :name, member = :member, price = :price, tel = :tel, mail = :mail ,update_date = :update WHERE ban = :id";
$ps = $dbh->prepare($sql);
$ps->bindValue(':id', $id, PDO::PARAM_INT);
$ps->bindValue(':name', $name, PDO::PARAM_STR);
$ps->bindValue(':member', $member, PDO::PARAM_INT);
$ps->bindValue(':price', $price, PDO::PARAM_INT);
$ps->bindValue(':tel', $tel, PDO::PARAM_STR);
$ps->bindValue(':mail', $mail, PDO::PARAM_STR);
$ps->bindValue(':update', $today, PDO::PARAM_STR);
$ps->execute();
}

header('Location: http://localhost:8569/maneger/reserve-modify-done.php');
exit();
}

?>
<body>
  <?php if(!empty($_SESSION['confirm2'])):?>
  <div id="modify-done" class="wrapper">
  <p>更新が完了しました。</p>
  <a href="reservation2.php">予約確認に戻る</a>
  </div>
  <?php unset($_SESSION['confirm2']) ?>
  <?php unset($_SESSION['token']) ?>
  <?php else: ?>
  <div id="invalid" class="wrapper">
  <p>不正な画面遷移です。</p>
  <a href="reservation2.php">予約状況に戻る</a>
  </div>
  <?php endif;?>
</body>