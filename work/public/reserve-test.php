<?php
session_start();
if(isset($_SESSION['confirm'])){
  unset($_SESSION['confirm']);
  echo '削除済';
}

require_once(__DIR__ . '/../app/measure-class.php');

if(isset($_GET['type'])){
echo $type = Measure::h($_GET['type']);
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <link rel= "stylesheet" href="calender.css" >
</head>
<body>
  <?php if(!empty($_GET['dates'])): ?>
  <div id="reserve-date" class="wrapper">
  <p class="note">泊数と室数を選択してください。</p>
  
  <p class="info">宿泊日: <?= $dates = Measure::h($_GET['dates'])?></p>
  <?php if($_GET['type'] == 'stock'): ?>
  <p class="info">部屋タイプ: シングル</p>
  <?php elseif($_GET['type'] == 'doubleroom'): ?>
  <p class="info">部屋タイプ: ダブル</p>
  <?php endif; ?>
  <form method="post" action="confirm-test.php">
  <span>泊数:</span>
  <select name="night">
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    <option value="4">4</option>
    <option value="5">5</option>
  </select>
  <br>

  <span>室数:</span>
  <select name="member">
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    <option value="4">4</option>
    <option value="5">5</option>
  </select>
  <br>
  <input type="hidden" name="type" value="<?php echo $type; ?>" >
  <input type="hidden" name="dates" value="<?php echo $dates; ?>" >
  <div><input class="reset" type="reset" value="リセット"></div>
  <div><input class="submit" type="submit" value="確定"></div>
  </form>
  <p class="coution">※満室及び販売を停止している日付を含んだ予約はできません。</p>
  </div>

  <?php else: ?>
  <p>不正な画面遷移です。</p>
  <a href="calender-res.php">予約状況に戻る</a>
  <?php endif; ?>
</body>
</html>