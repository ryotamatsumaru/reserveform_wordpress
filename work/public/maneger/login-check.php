<?php


require_once('header2.php');

require_once('/../work/app/config.php');
use MyApp\database; 

try{

$login_id = $_POST['login_id'];
$password = $_POST['password'];

$dbh = new PDO(DSN,USER,PASS);
$sql = "SELECT * FROM maneger WHERE login_id = :login_id";
$ps = $dbh->prepare($sql);
$ps->bindValue(':login_id', $login_id, PDO::PARAM_STR);
// $ps->bindValue(':password', $password, PDO::PARAM_STR);
$ps->execute();
$rows = $ps->fetch();
$password2 = $rows['password'];

  
// if($rows == false){
//   print 'スタッフIDかパスワードが間違っています。<br>';
//   print '<a href="maneger-login.html">戻る</a>';
// } else {
//   session_start();
//   $_SESSION['login']=1;
//   $_SESSION['login_id']=$login_id;
//   header('Location:menu.php');
//   exit();
// }


} catch(Exception $e){
  print 'ただいま障害により大変ご迷惑をお掛けしております。';
  exit();
}

// require_once('/../work/app/config.php');
// use MyApp\database; 

// try{
// if(isset($_POST['mail']) && isset($_POST['password'])){
// $mail = $_POST['mail'];
// $password = $_POST['password'];

// $dbh = new PDO(DSN,USER,PASS);
// $sql = "SELECT * FROM member WHERE mail = :mail";
// $ps = $dbh->prepare($sql);
// $ps->bindValue(':mail', $mail, PDO::PARAM_STR);
// $ps->execute();
// $rows = $ps->fetch();
// $password2 = $rows['password'];
// $id = $rows['id'];
// }

// } catch(Exception $e){
//   print 'ただいま障害により大変ご迷惑をお掛けしております。';
//   exit();
// }

?>
<body>
<?php if(!empty($_POST['confirm'])): ?>
<?php if(!empty($_POST['login_id']) && !empty($_POST['password'])): ?>
<?php if($rows == false): ?>
  <div id="login-check" class="wrapper">
  <p>ログインIDが間違っています。</p>
  <a href="maneger-login.php">ログインに戻る</a>
  </div>
<?php else: ?>
<?php if(password_verify($password,$password2)): ?>
  <?php 
    session_start();
    $_SESSION['login']=1;
    $_SESSION['cus_id'] = $id;
    header('Location:menu.php');
    exit();?>
<?php else: ?>
  <div id="login-check" class="wrapper">
  <p>入力しているパスワードが一致しません。</p>
  <a href="maneger-login.php">ログインに戻る</a>
  </div>
<?php endif; ?>
<?php endif; ?>
<?php else: ?>
  <div id="login-check" class="wrapper">
  <p>値が入力されてません</p>
  <a href="maneger-login.php">ログインに戻る</a>
  </div>
<?php endif; ?>
<?php else: ?>
  <p>不正な画面遷移です。</p>
<?php endif; ?>
</body>






