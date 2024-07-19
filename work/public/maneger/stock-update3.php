<?php
session_start();
session_regenerate_id(true);
if(isset($_SESSION['login'])==false){
  print 'ログインされてません <br>';
  print '<a href="maneger-login.html">ログイン画面へ</a>';
  exit();
}

require_once('stock-update2.php');
require_once('stock-update4.php');

$prev = date('Ymd', strtotime('-7 day', $timestamp));
$next = date('Ymd', strtotime('+7 day', $timestamp));

$confirm = 1;
require_once('header3.php');
?>

<body>
  <div class="container">
  <div class="searchform">
  <form method="post" action="stock-update3.php">
  <input type="date" class="bar" name="search_date" id="search">
  <input type="submit" class="button" value="検索する">
  </form>
  </div>
    <form method="post" action="stock-update-check.php">
    <div class="title-area">
    <a href="?ym=<?php echo $prev;?>">&#12298 前へ</a><h3><?php echo $title; ?></h3><a href="?ym=<?php echo $next;?>">次へ &#12299</a>
    </div>
    <table class="table table-bordered">
      <tr>
      <th></th>
      <?php foreach($weeks2 as $week2){
      echo $week2;
      }
      ?>
      </tr>
      <tr>
      <td class="type"><span class="type-text">シングル</span></td>
      <?php 
        foreach($values as $value){
          echo $value;
        }
      ?>
      </tr>
      <tr>
      <td class="type"><span class="type-text">シングル</span></td>
      <?php 
        foreach($rests as $rest){
          echo $rest;
        }
      ?>
      </tr>
      <tr>
      <td class="type"><span class="type-text">ダブル</span></td>
      <?php 
        foreach($values2 as $value2){
          echo $value2;
        }
      ?>
      </tr>
      <tr>
      <td class="type"><span class="type-text">ダブル</span></td>
      <?php 
        foreach($rests2 as $rest2){
          echo $rest2;
        }
      ?>
      </tr>
    </table>
    <input type="hidden" name="confirm" value="<?= $confirm ?>">
    <input type="submit" class="submit" value="更新">
    </form>
    <a href="menu.php" class="back">メニューに戻る</a>
  </div>
</body>