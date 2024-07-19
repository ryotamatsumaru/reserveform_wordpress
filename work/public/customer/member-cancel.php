<?php
session_start();
$id = $_SESSION['cus_id'];
?>
<head>
  <meta charset="utf-8">
  <link rel= "stylesheet" href="member-info.css" >
</head>
<body>
<h1 class="title">退会手続き</h1>
<div id="leave" class="wrapper">
<div class="text-area">
<p class="text">退会手続きに進みます。</p>
<p  class="text">一度退会した場合、登録したお客様情報は削除されます。</p>
<p  class="text">もう一度、会員制度を利用される場合は、再度登録が必要になります。</p>
<p>問題なければ下記の退会するを押してください。</p>
</div>
<form method="post" action="member-cancel-done.php">
  <input type="hidden" name="id" value="<?= $id?>">
  <input type="submit" class="submit" value="退会する">
</form>
<a href="cus-menu.php" class="back">メニューに戻る</a>
</div>





</body>