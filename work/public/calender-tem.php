<?php
require_once(__DIR__ . '/../public/roomdouble.php');
require_once(__DIR__ . '/../public/roomsingle.php');
require_once(__DIR__ . '/../app/calender-class.php');
require_once(__DIR__ . '/../app/config.php');
use MyApp\database;

// $dbh = Database::getInstance();


?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <link rel= "stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <link rel= "stylesheet" href="calender.css" >
</head>
<body>
  <div class="container">
  <div class="searchform">
  <form method="post" action="calender-tem.php">
  <input type="date" class="bar" name="search_date" id="search">
  <input type="submit" class="button" value="検索する">
  </form>
  <p>※ご希望の日付で検索できます。</p>
  </div>
    <div class="title-area">
    <a href="?ym=<?php echo $prev;?>">&#12298 前の期間</a><h3><?php echo $title; ?></h3><a href="?ym=<?php echo $next;?>">次の期間 &#12299</a>
    </div>

    <table class="table table-bordered">
      
    <div class="calendar">
      <tr>
      <th>室タイプ</th>
      <?php foreach($weeks2 as $week2){
      echo $week2;
      }
      ?>
      </tr>
      <tr>
      <td><span class="type">シングル</span></td>
      <?php 
        foreach($singles as $single){
          echo $single;
        }
      ?>
      </tr>
      <tr>
      <td><span class="type">ダブル</span></td>
      <?php 
        foreach($doubles as $double){
          echo $double;
        }
      ?>
      </tr>
    </div>
    </table>
    <p class="note">※上記のカレンダーより○もしくは残室数が記載されている日付が予約可能となります。クリックして泊数と室数選択画面に進んでください。</p>
    <p class="note"> ※ - マークは予約ができません。満室か販売を停止しています。</p>
  </div>
</body>