<?php

//     Template Name: mng_login_check
//     Template Post Type: page
//     Template Path: control/

session_start();
require_once(dirname(dirname(__FILE__)). '/prvdtb/pdo-class.php');
require_once(dirname(dirname(__FILE__)). '/measure/measure-class.php');

// CSRF対策用$tokenが合致しているか確認するためのメソッド
Measure::validate();

if(!empty($_POST['login_id']) && !empty($_POST['password'])){
  $login_id = Measure::h($_POST['login_id']);
  $password = Measure::h($_POST['password']);

// ログインIDを最初にデータベースと照合する。
  $dbh = Database::getPdo();
  $sql = "SELECT * FROM maneger WHERE login_id = :login_id";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':login_id', $login_id, PDO::PARAM_STR);
  $ps->execute();
  $rows = $ps->fetch();
// ログインIDがデータベースと合致したときパスワードを変数に代入する。
  if($rows == true){
    $password2 = $rows['password'];
  }
}

if(!empty($password) && !empty($password2)){
  // パスワードは暗号化しているのでpassword_verifyで受け取ったパスワードと合致させる。
  if(password_verify($password,$password2)){
    session_start();
    $_SESSION['mng_login']=1;
    $_SESSION['mng_id'] = $login_id;
    header('Location:https://ro-crea.com/demo_hotel/mng_menu/');
    exit();
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
<?php if(!empty($_POST['confirm'])): ?>
  <?php if(!empty($_POST['login_id']) && !empty($_POST['password'])): ?>
    <?php if($rows == false): ?>
      <div id="mng-login-check" class="wrapper">
        <p>ログインIDが間違っています。</p>
        <a href="https://ro-crea.com/demo_hotel/mng_login/">ログインに戻る</a>
      </div>
    <?php else: ?>
      <?php if(password_verify($password,$password2) ==false): ?>
        <div id="mng-login-check" class="wrapper">
          <p>入力しているパスワードが一致しません。</p>
          <a href="https://ro-crea.com/demo_hotel/mng_login/">ログインに戻る</a>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  <?php else: ?>
    <div id="mng-login-check" class="wrapper">
      <p>ログインIDとパスワードを入力してください。</p>
      <a href="https://ro-crea.com/demo_hotel/mng_login/">ログインに戻る</a>
    </div>
  <?php endif; ?>
<?php else: ?>
  <p>不正な画面遷移です。</p>
<?php endif; ?>
<footer>
</footer>
<?php wp_footer(); ?>
</body>
</html>