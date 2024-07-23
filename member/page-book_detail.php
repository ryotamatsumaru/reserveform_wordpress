<?php
//     Template Name: book_detail
//     Template Post Type: page
//     Template Path: member/

session_start();
session_regenerate_id(true);
if(isset($_SESSION['cus_login'])==false){
  header('Location:https://ro-crea.com/demo_hotel/login_caution_text/');
  exit();
}

get_header();

require_once(dirname(dirname(__FILE__)). '/prvdtb/pdo-class.php');

function getAddress() {  
  $id = $_SESSION['cus_id'];
  $dbh = Database::getPdo();
  $sql = "SELECT mail FROM member WHERE id = :id";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':id', $id, PDO::PARAM_INT);
  $ps->execute();
  $rows = $ps->fetch();
  $mail2 = $rows['mail'];
  return $mail2;
}

$mail = getAddress();
$dbh = Database::getPdo();
$sql = "SELECT * FROM booking WHERE mail = :mail ORDER BY reserve_date DESC";
$ps = $dbh->prepare($sql);
$ps->bindValue(':mail', $mail, PDO::PARAM_STR);
$ps->execute();
$rows = $ps->fetchAll(PDO::FETCH_ASSOC);
?>

<section>
  <div id="book-detail" class="wrapper">
    <h1 class="title">予約一覧</h1>
    <p class="text">既に予約が完了している一覧です。詳細を見るから予約詳細の変更が可能です。</p>
    <p class="caution">※過去予約の変更はできません。</p>
    <a href="https://ro-crea.com/demo_hotel/menu/" class="back">メニューに戻る</a>
    <div id="list">
      <ul class="field-flex">  
        <li class="field">日付</li>
        <li class="field">タイプ</li>
        <li class="field">価格</li>
        <li class="field">室数</li>
        <li class="field">泊数</li>
        <li class="field">予約日</li>
      </ul>
      <?php foreach($rows as $row): ?>
        <ul class="list-flex">
          <li><?= $row['day']?></li>
          <?php if($row['type'] === 0):?>
            <li>シングル</li>
          <?php elseif($row['type'] === 1):?>
            <li>ダブル</li>  
          <?php endif; ?>
          <li>¥<?= $row['price']?></li>
          <li><?= $row['number']?>室</li>
          <li><?= $row['night']?>泊</li>
          <li><?= $row['reserve_date']?></li>
          <li><a href="https://ro-crea.com/demo_hotel/book_select?num=<?= $row['random_id']?>">詳細を見る</a></li>
        </ul>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php get_footer("3"); ?>