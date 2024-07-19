<?php

require_once('header.php');

?>

<body>
<div id="login" class="wrapper">
<div class="center-box">
<form action="login-check.php" method="post">
  <label for="id">ログインID：</label>
  <p class="info"><input type="text" name="login_id" id="id"></p>
  <label for="password">パスワード：</label>
  <p  class="info"><input type="password" name="password" id="password"></p>
  <input type="hidden" name="confirm" value="1">
  <input type="submit" class="submit" value="確定">
</form>
</div>
</div>
</body>

<?php require('footer.php'); ?>