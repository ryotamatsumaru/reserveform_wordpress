<?php
//     Template Name: search
//     Template Post Type: page
//     Template Path: control/

session_start();
session_regenerate_id(true);
// sessionが一致しない場合、ログインの警告ページに遷移する
if(isset($_SESSION['mng_login'])==false){
  header('Location:https://ro-crea.com/demo_hotel/mng_login_caution_text');
  exit();
}

require_once('search-class.php');

$search = new Search();

// search-class.phpより予約状況の検索用配列を返すメソッドを変数に代入する
$select_list = $search->selectArray();

// page-search.phpの部屋タイプ用の検索用配列を返すメソッドを変数に代入する
$select_type = $search->selectType();

// 予約情報を返すメソッドを変数に代入する
$rows = $search->SearchBook();

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
<header>
</header>
  <div id="search" class="wrapper">
    <a href="https://ro-crea.com/demo_hotel/mng_menu/" class="back">メニューに戻る</a>
    <div class="box">
      <div class="search-form">
        <form method="post" action="https://ro-crea.com/demo_hotel/search/">
        <select name="select" class="reserve-bar">
        <!-- $select_listにはbookingとcancelというDBのテーブル名が代入されてる。 -->
        <?php foreach($select_list as $row): ?>
          <?php if($_POST['select'] == $row['check']): ?>
            <option value="<?= $row['check']?>" selected><?= $row['text'] ?></option>
          <?php else:?>
            <option value="<?= $row['check']?>"><?= $row['text'] ?></option>
          <?php endif; ?>
        <?php endforeach; ?>
        </select>

        <select name="type" class="type-bar">
        <!-- $select_typeには0=>シングルと1=>ダブルというDBのtypeカラムの値が代入されてる。 -->
        <?php foreach($select_type as $row): ?>
          <?php if($_POST['type'] == $row['check']): ?>
            <option value="<?= $row['check']?>" selected><?= $row['text'] ?></option>
          <?php else:?>
            <option value="<?= $row['check']?>"><?= $row['text'] ?></option>
          <?php endif; ?>
        <?php endforeach; ?>
        </select>
        <input type="date" name="date" class="date-bar">
        <input type="submit" class="submit">
        </form>
      </div>

      <!-- 予約中の予約状況を表示 -->
      <?php if(isset($_POST['select'])):?>
        <?php if($_POST['select'] == 'booking'):?>  
          <p class="search-result">予約状態：予約中</p>
          <p class="search-result">検索日：<?= $_POST['date'] ?></p>
          <ul class="field-flex">
            <li class="type">部屋</li>
            <li class="date">日付</li>
            <li class="name">名前</li>
            <li class="room">室数</li>
            <li class="night">泊数</li>
            <li class="price">料金</li>
            <li class="random">予約ID</li>
            <li class="tel">電話番号</li>
            <li class="mail">メール</li>
          </ul>
          <?php foreach($rows as $row): ?>
            <ul class="info-flex">
              <?php if($row['type'] === 0):?>
                <li class="type">シングル</li>
              <?php elseif($row['type'] === 1):?>
                <li class="type">ダブル</li>  
              <?php endif; ?>
              <li class="date"><?= $row['day'] ?></li>
              <li class="name"><?= $row['name'] ?></li>
              <li class="room"><?= $row['number'] ?>室</li>
              <li class="night"><?= $row['night'] ?>泊</li>
              <li class="price">¥<?= $row['price'] ?></li>
              <li class="random"><?= $row['random_id'] ?></li>
              <li class="tel"><?= $row['tel'] ?></li>
              <li class="mail"><?= $row['mail'] ?></li>
            </ul>
            <form  method="post" action="https://ro-crea.com/demo_hotel/select/">
            <input type="hidden" name="show" value="<?= $row['id'] ?>">
            <input type="hidden" name="reserve_date" value="<?= $row['reserve_date']?>">
            <input type="hidden" name="random" value="<?= $row['random_id']?>">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <input type="submit" class="refer" value="参照">
            </form>
          <?php endforeach; ?>

        <!-- キャンセルの予約状況を表示 -->
        <?php elseif($_POST['select'] == 'cancel'):?>
          <p class="search-result">予約状態：キャンセル</p>
          <p class="search-result">検索日：<?= $_POST['date'] ?></p>
          <ul class="field-flex">
            <li class="type">部屋</li>
            <li class="date">日付</li>
            <li class="name">名前</li>
            <li class="room">室数</li>
            <li class="night">泊数</li>
            <li class="price">料金</li>
            <li class="random">予約ID</li>
            <li class="tel">電話番号</li>
            <li class="mail">メール</li>
          </ul>
          <?php foreach($rows as $row): ?>
            <ul class="info-flex">
              <?php if($row['type'] === 0):?>
                <li class="type">シングル</li>
              <?php elseif($row['type'] === 1):?>
                <li class="type">ダブル</li>  
              <?php endif; ?>
              <li class="date"><?= $row['day'] ?></li>
              <li class="name"><?= $row['name'] ?></li>
              <li class="room"><?= $row['number'] ?>室</li>
              <li class="night"><?= $row['night'] ?>泊</li>
              <li class="price">¥<?= $row['price'] ?></li>
              <li class="random"><?= $row['random_id'] ?></li>
              <li class="tel"><?= $row['tel'] ?></li>
              <li class="mail"><?= $row['mail'] ?></li>
            </ul>
          <?php endforeach; ?>
        <?php endif;?>
      <?php else:?>
        <p class="note">日付を入力して検索します。</p>
      <?php endif;?>
    </div>
  </div>
<footer>
</footer>
<?php wp_footer(); ?>
</body>
</html>