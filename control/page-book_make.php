<?php
//     Template Name: book_make
//     Template Post Type: page
//     Template Path: control/

session_start();
session_regenerate_id(true);

// sessionが一致しない場合、ログインの警告ページに遷移する
if(isset($_SESSION['mng_login'])==false){
  header('Location:https://ro-crea.com/demo_hotel/mng_login_caution_text');
  exit();
}

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
<div id="book-make" class="wrapper">
  <h2 class="sub-title">予約作成フォーム</h2>
  <div class="box">
    <form method="post" action="https://ro-crea.com/demo_hotel/book_info/">
    <p class="date">
    <label for="date">チェックイン日</label>
    <input type="date" name="date" id="date" class="data-bar">
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
    <label for="number">室数</label>
    <select name="number" id="number">
    <?php for($i=1; $i<=10; $i++ ):?>
      <option><?php echo $i; ?></option>
    <?php endfor; ?>
    </select>
    </p>

    <input type="hidden" name="confirm" value="1">
    <input type="submit" class="submit" value="確定">
    </form>
  </div>
  <a href="https://ro-crea.com/demo_hotel/mng_menu/" class="back">メニューに戻る</a>
</div>
<footer>
</footer>
<?php wp_footer(); ?>
</body>
</html>