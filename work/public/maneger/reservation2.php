<?php


session_start();
session_regenerate_id(true);

if(isset($_SESSION['login'])==false){
  print 'ログインされてません <br>';
  print '<a href="maneger-login.html">ログイン画面へ</a>';
  exit();
}

require_once('/../work/public/maneger/reservation2-class.php');
require_once('/../work/app/config.php');
use MyApp\database; 

$search = new Search();
$select_list = $search->selectArray();
$select_type = $search->selectType();
$rows = $search->SearchBook();
// var_dump($select_list);

require('header2.php');
?>
<body>

  <div id="search" class="wrapper">
  <a href="menu.php" class="back">メニューに戻る</a>
  <div class="box">
  <div class="search-form">
  <form method="post" action="../maneger/reservation2.php">
    <select name="select" class="reserve-bar">
      <?php foreach($select_list as $row): ?>
      <?php if($_POST['select'] == $row['check']): ?>
      <option value="<?= $row['check']?>" selected><?= $row['text'] ?></option>
      <?php else:?>
      <option value="<?= $row['check']?>"><?= $row['text'] ?></option>
      <?php endif; ?>
      <?php endforeach; ?>
    </select>
    <select name="type" class="type-bar">
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

  <?php if(isset($_POST['select'])):?>
  <?php if($_POST['select'] == 'booking'):?>  
  <p>予約状態：予約中</p>
  <p>検索日：<?= $_POST['date'] ?></p>
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
    <?php if($row['type'] === '0'):?>
    <li class="type">シングル</li>
    <?php elseif($row['type'] === '1'):?>
    <li class="type">ダブル</li>  
    <?php endif; ?>
    <li class="date"><?= $row['day'] ?></li>
    <li class="name"><?= $row['name'] ?></li>
    <li class="room"><?= $row['member'] ?>室</li>
    <li class="night"><?= $row['night'] ?>泊</li>
    <li class="price">¥<?= $row['price'] ?></li>
    <li class="random"><?= $row['random_id'] ?></li>
    <li class="tel"><?= $row['tel'] ?></li>
    <li class="mail"><?= $row['mail'] ?></li>
    </ul>
    <form  method="post" action="reserve-show.php">
    <input type="hidden" name="show" value="<?= $row['ban'] ?>">
    <input type="hidden" name="day" value="<?= $row['day']?>">
    <input type="hidden" name="name" value="<?= $row['name']?>">
    <input type="hidden" name="member" value="<?= $row['member']?>">
    <input type="hidden" name="night" value="<?= $row['night']?>">
    <input type="hidden" name="price" value="<?= $row['price']?>">
    <input type="hidden" name="tel" value="<?= $row['tel']?>">
    <input type="hidden" name="mail" value="<?= $row['mail']?>">
    <input type="hidden" name="reserve_date" value="<?= $row['reserve_date']?>">
    <input type="hidden" name="random" value="<?= $row['random_id']?>">
    <input type="hidden" name="id" value="<?= $row['ban'] ?>">
    <input type="submit" class="refer" value="参照">
    </form>
    <br>
  <?php endforeach; ?>

  <?php elseif($_POST['select'] == 'cancel'):?>
  <p>予約状態：キャンセル</p>
  <p>検索日：<?= $_POST['date'] ?></p>
  <ul class="field-flex">
  <li>部屋</li>
  <li>日付</li>
  <li>名前</li>
  <li>室数</li>
  <li>泊数</li>
  <li>料金</li>
  <li>予約ID</li>
  <li>電話番号</li>
  <li>メール</li>
  </ul>
  <br>
  <?php foreach($rows as $row): ?>
    <ul class="info-flex">
    <?php if($row['type'] === '0'):?>
    <li>シングル</li>
    <?php elseif($row['type'] === '1'):?>
    <li>ダブル</li>  
    <?php endif; ?>
    <li><?= $row['day'] ?></li>
    <li><?= $row['name'] ?></li>
    <li><?= $row['member'] ?>室</li>
    <li><?= $row['night'] ?>泊</li>
    <li>¥<?= $row['price'] ?></li>
    <li><?= $row['random_id'] ?></li>
    <li><?= $row['tel'] ?></li>
    <li><?= $row['mail'] ?></li>
    </ul>
  <?php endforeach; ?>
  <?php endif;?>
  <?php else:?>
  <p class="note">日付を入力して検索します。</p>
  <?php endif;?>
  </div>
  </div>
</body>