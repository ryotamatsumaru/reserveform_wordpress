<?php
session_start();
session_regenerate_id(true);

if(isset($_SESSION['login'])==false){
  print 'ログインされてません <br>';
  print '<a href="maneger-login.html">ログイン画面へ</a>';
  exit();
}

require_once('header.php');

?>

<body>
<div id="menu">
<div class="menu-board">
<h2 class="menu-title">予約・在庫管理</h2>
<ul class="menu-list">
<li><a href="stock-insert3.php">料金・室数の設定</a></li>
<li><a  href="stock-update3.php">料金・室数の変更</a></li>
<li><a href="reservation2.php">予約の確認</a></li>
<li><a href="reserve-downroad.php">予約リスト</a></li>
<li><a href="reserve-make.php">予約を作成する</a></li>
<li><a href="logout.php">ログアウト</a></li>
</ul>
</div>
</div>
</body>