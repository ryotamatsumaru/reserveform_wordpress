<?php
require_once('stock-insert2.php');
require_once('stock-insert4.php');

$prev = date('Ymd', strtotime('-7 day', $timestamp));
$next = date('Ymd', strtotime('+7 day', $timestamp));

$confirm = 1;
require_once('header3.php');
?>
  <body>
  <div class="container">
  <div class="searchform">
  <form method="post" action="stock-insert3.php">
  <input type="date" class="bar" name="search_date" id="search">
  <input type="submit" class="button" value="検索する">
  </form>
  </div>
    <!-- <form method="post" action="stock-check.php"> -->
    <!-- <form method="post" action="test2.php"> -->
    <form method="post" action="stock-insert-done.php">
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
      <input type="hidden" name="stock" value="0" >
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
      <input type="hidden" name="double" value="1" >
      </tr>
    </table>
    <input type="hidden" name="confirm" value="<?= $confirm ?>" >
    <input type="submit" class="submit" value="確定">
    </form>
    <a href="menu.php">メニューに戻る</a>
  </div>
  </body>