<?php

session_start();
session_regenerate_id(true);

if(isset($_SESSION['login'])==false){
  print 'ログインされてません <br>';
  print '<a href="maneger-login.html">ログイン画面へ</a>';
  exit();
}

require_once('header2.php');
?>
<body>
<div id="book-make" class="wrapper">
<h2 class="sub-title">予約作成フォーム</h2>
<div class="box">
<form method="post" action="reserve-make-check.php">
<p class="date">
<label for="day">チェックイン日</label>
<input type="date" name="day" id="day" class="data-bar">
</p>

<p class="type">
<label for="type">部屋タイプ</label>
<select name="type" id="type">
    <option value="0">シングル</option>
    <option value="1">ダブル</option>
</select>
</p>

<p class="night">
<label for="night">泊数</label>
<select name="night" id="night">
  <?php for($i=1; $i<=10; $i++ ):?>
    <option><?php echo $i; ?></option>
  <?php endfor; ?>
</select>
</p>

<p class="room">
<label for="member">室数</label>
<select name="member" id="member">
  <?php for($i=1; $i<=10; $i++ ):?>
    <option><?php echo $i; ?></option>
  <?php endfor; ?>
</select>
</p>

<input type="hidden" name="confirm" value="1">
<input type="submit" class="submit" value="確定">
</form>
</div>
<a href="menu.php" class="back">メニューに戻る</a>
</div>
</body>