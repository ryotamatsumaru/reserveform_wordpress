<?php
//     Template Name: update
//     Template Post Type: page
//     Template Path: control/

session_start();
session_regenerate_id(true);
// sessionが一致しない場合、ログインの警告ページに遷移する
if(isset($_SESSION['mng_login'])==false){
  header('Location:https://ro-crea.com/demo_hotel/mng_login_caution_text');
  exit();
}

require_once('single_stock_update.php');
require_once('double_stock_update.php');

$prev = date('Ymd', strtotime('-7 day', $timestamp));
$next = date('Ymd', strtotime('+7 day', $timestamp));

// 次のページにアクセスするための判定用変数。
$confirm = 1;
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
<section>
  <div class="container">
  <!-- 検索機能 -->
  <div class="searchform">
  <form method="post" action="https://ro-crea.com/demo_hotel/update">
  <input type="date" class="bar" name="search_date" value="<?= date('Y-m-d') ?>" id="search">
  <input type="submit" class="button" value="検索する">
  </form>
  </div>
  <form method="post" action="https://ro-crea.com/demo_hotel/update_done">
  <!-- １週間ごとの期間を変更できる機能 -->
  <div class="title-area">
  <a href="?ym=<?php echo $prev;?>" class="prev">&#12298 前へ</a><h3><?php echo $title; ?></h3><a href="?ym=<?php echo $next;?>" class="next">次へ &#12299</a>
  </div>

  <!-- 予約カレンダー -->
  <table class="table table-bordered">
    <tr>
    <th></th>
    <?php foreach($weeks as $week){
      echo $week;
    }
    ?>
    </tr>

    <!-- single_stock_update.phpより配列single_valuesに保管したシングルの料金を表示 -->
    <tr>
    <td class="type"><span class="type-text">シングル</span></td>
    <?php 
      foreach($single_values as $single_value){
        echo $single_value;
      }
    ?>
    </tr>

    <!-- single_stock_update.phpより配列single_restに保管したシングルの在庫を表示 -->
    <tr>
    <td class="type"><span class="type-text">シングル</span></td>
    <?php 
      foreach($single_rests as $single_rest){
        echo $single_rest;
      }
    ?>
    </tr>
    <!-- double_stock_update.phpより配列double_valuesに保管したシングルの料金を表示 -->
    <tr>
    <td class="type"><span class="type-text">ダブル</span></td>
    <?php 
      foreach($double_values as $double_value){
        echo $double_value;
      }
    ?>
    </tr>
    <!-- double_stock_update.phpより配列double_restに保管したシングルの在庫を表示 -->
    <tr>
    <td class="type"><span class="type-text">ダブル</span></td>
    <?php 
      foreach($double_rests as $double_rest){
        echo $double_rest;
      }
    ?>
    </tr>
    </table>
    <input type="hidden" name="confirm" value="<?= $confirm ?>">
    <input type="submit" class="submit" value="更新">
    </form>
    <a href="https://ro-crea.com/demo_hotel/mng_menu" class="back">メニューに戻る</a>
  </div>
</section>
</body>
<footer>
</footer>
<?php wp_footer(); ?>
</body>
</html>