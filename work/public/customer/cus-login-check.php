<?php


require_once('/../work/app/config.php');
use MyApp\database; 

try{
if(isset($_POST['mail']) && isset($_POST['password'])){
$mail = $_POST['mail'];
$password = $_POST['password'];

$dbh = new PDO(DSN,USER,PASS);
$sql = "SELECT * FROM member WHERE mail = :mail";
$ps = $dbh->prepare($sql);
$ps->bindValue(':mail', $mail, PDO::PARAM_STR);
$ps->execute();
$rows = $ps->fetch();
$password2 = $rows['password'];
$id = $rows['id'];
}

} catch(Exception $e){
  print 'ただいま障害により大変ご迷惑をお掛けしております。';
  exit();
}
?>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="customer-login.css">
</head>
<body>
<?php if(!empty($_POST['confirm'])): ?>
<?php if(!empty($_POST['mail']) && !empty($_POST['password'])): ?>
<?php if($rows == false): ?>
  <p>メールアドレスが間違っています。</p>
  <a href="cus-login.php">戻る</a>
<?php else: ?>
<?php if(password_verify($password,$password2)): ?>
  <?php 
    session_start();
    $_SESSION['cus_login']=1;
    $_SESSION['cus_id'] = $id;
    header('Location:cus-menu.php');
    exit();?>
<?php else: ?>
  <div id="login-check" class="wrapper">
  <p class="caution">入力しているパスワードが一致しません。</p>
  <a href="cus-login.php" class="back">ログイン画面に戻る</a>
  </div>
<?php endif; ?>
<?php endif; ?>
<?php else: ?>
  <div id="login-check" class="wrapper">
  <p class="caution">メールアドレスとパスワードを入力してください。</p>
  <a href="cus-login.php" class="back">ログイン画面に戻る</a>
  </div>
<?php endif; ?>
<?php else: ?>
  <p>不正な画面遷移です。</p>
<?php endif; ?>
</body>



