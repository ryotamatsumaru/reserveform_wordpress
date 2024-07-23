<?php
//     Template Name: calendar
//     Template Post Type: page
//     Template Path: reserve/

session_start();
session_regenerate_id(true);

// CSRF対策用の$token削除のためのコード
if(isset($_SESSION['confirm'])){
  unset($_SESSION['confirm']);  
  echo '削除済';
}

require_once(__DIR__ . '/singleroom.php');
require_once(__DIR__ . '/doubleroom.php');
require_once(dirname(dirname(__FILE__)). '/prvdtb/pdo-class.php');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>デモサイト(ホテル)</title>
  <?php wp_head(); ?>
</head>
<body>
<header id="header2">
  <h1 class="title-name">予約状況</h1>
</header>
<main>
<section id="calendar" class="wrapper">
  <div class="searchform">
    <?php $setday = date('Y-m-d'); ?>
    <form method="post" action="https://ro-crea.com/demo_hotel/calendar/">
    <input type="date" name="search_date" value="<?= date('Y-m-d') ?>">
    <input type="submit" class="button" value="検索">
    </form>
    <p>※ご希望の日付で検索できます。</p>
  </div>
  <!-- １週間ごとの期間を変更できる機能 -->
  <div class="title-area">
    <a href="?ym=<?php echo $prev;?>">&#12298 前の期間</a><h3 class="month"><?php echo $title; ?></h3><a href="?ym=<?php echo $next;?>">次の期間 &#12299</a>
  </div>

  <!-- 予約カレンダー -->
  <table class="table table-bordered">
  <tr>
  <th class="type-genre">室タイプ</th>
  <?php 
  foreach($weeks as $week){
    echo $week;
  }
  ?>
  </tr>

  <!-- singleroom.phpより配列$singlesに保管したシングルの在庫と料金を表示 -->
  <tr>
  <td><span class="room-tag">シングル</span></td>
  <?php 
  foreach($singles as $single){
    echo $single;
  }
  ?>
  </tr>
    
  <!-- doubleroom.phpより配列$doublesに保管したダブルの在庫と料金を表示 -->
  <tr>
  <td><span  class="room-tag">ダブル</span></td>
  <?php 
  foreach($doubles as $double){
    echo $double;
  }
  ?>
  </tr>
  </table>

  <p class="note">※上記のカレンダーより○もしくは残室数が記載されている日付が予約可能となります。クリックして泊数と室数選択画面に進んでください。</p>
  <p class="note"> ※ - マークは予約ができません。満室か販売を停止しています。</p>
  <a href="https://ro-crea.com/demo_hotel" class="calendarback">トップに戻る</a>
</section>
</main>
<?php get_footer("2");?>