<?php

// session_start();
// session_regenerate_id(true);
// if(isset($_SESSION['login'])==false){
//   print 'ログインされてません <br>';
//   print '<a href="maneger-login.html">ログイン画面へ</a>';
//   exit();
// }

require_once('/../work/app/stock-class2.php');
require_once('/../work/app/config.php');
use MyApp\database;

$dbh = Database::getInstance();

$insert2 = new Stock2();
$setWeek2 = $insert2->setWeek();
$timestamp2 = $insert2->timestamp;
$selectday2 = $insert2->selectday;
$afterday2 = $insert2->afterday;
$period2 = $insert2->makeOneweek();

$price_array2 = $insert2->getprice();

function price2($date,$price_array2){
  if(array_key_exists($date,$price_array2)){
    $price_display2 = $price_array2[$date];
    return $price_display2;
  }
}

$stock_array2 = $insert2->getstock();

function stock2($date,$stock_array2){
  if(array_key_exists($date,$stock_array2)){
    $stock_display2 = $stock_array2[$date];
    return $stock_display2;
  }
}

$books_array2 = $insert2->getreserve();

function reservation2($date,$books_array2){
  $type = 1;
  if($date < 'Y-m-d'){
    $date = date('Y-m-d');
  }
  $dbh = new PDO(DSN,USER,PASS);
  $stmt = $dbh->prepare("SELECT * FROM `doubleroom` WHERE day = '$date'");
  $stmt->execute();
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  foreach($rows as $row){
  $row['inventory'];
  }

  if(array_key_exists($date,$books_array2)){
    if ($row['inventory'] - $books_array2[$date] <= 0){
      $books_display2 = "<span class='rest-text'>残室: 0 </span>";
    } else {
      $rest = $row['inventory'] - $books_array2[$date];
      $books_display2 = "<span class='rest-text'>残室: $rest</span>";
    }
    return $books_display2;
  } else {
    $books_display2 = "<span class='rest-text'>残室: 全空</span>";
    return $books_display2;
  }
}

date_default_timezone_set('Asia/Tokyo');
$title = date('Y年n月', $timestamp2);

$values2 = [];
$value2 = '';
$rests2 = [];
$rest2 = '';

foreach( $period2 as $ymd){
  $today = date('Y-m-d');
  $date = $ymd->format('Y-m-d');
  $days = $ymd->format('d');
  $price = price2(date("Y-m-d",strtotime($date)),$price_array2);
  $stock = stock2(date("Y-m-d", strtotime($date)), $stock_array2);
  $reservation = reservation2(date("Y-m-d",strtotime($date)),$books_array2);
  $type = 1;

  $sql = "SELECT * FROM `doubleroom` WHERE day = :date";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':date', $date, PDO::PARAM_STR);
  $ps->execute();
  $rows = $ps->fetchAll(PDO::FETCH_ASSOC);
  foreach($rows as $row){
    $row['inventory_copy'];
    $row['inventory'];
    $id_css2[] = $row['id'];
  }

  if($date < $today){
    $value2 .= '<td class="price">'.'<br>'.'<span class="price-text">'.'¥'.$price.'</span>';
    $rest2 .= '<td class="rest">'.'<span class="rest-text">'.$reservation .'</span>'.'<span class="none">'.'-'.'</span>';
  }
  elseif(price2(date("Y-m-d", strtotime($date)),$price_array2) && reservation2(date("Y-m-d", strtotime($date)),$books_array2)) {
    $value2 .= '<td class="price">'.'<br>'.'<span class="price-text">¥'.$price.'</span>';
    $rest2 .= '<td class="rest">'.$reservation.'<span class="set-text">設定数:'.$stock.'</span>';
    if($row['inventory_copy'] == 0){
    $rest2.= '<div class="off2"><span class="toggle"><input type="checkbox" name="id2[]" value="'.$row['id'].'" id="id2'.$row['id'].'"><label for="id2'.$row['id'].'"><span></span><input type="hidden" name="set_stock_copy2[]" value="0"></label></span></div>';
    } else {
    $rest2.= '<div class="on2"><span class="toggle"><input type="checkbox" name="id2[]" value="'.$row['id'].'" id="id2'.$row['id'].'"><label for="id2'.$row['id'].'"><span></span><input type="hidden" name="set_stock_copy2[]" value="0"></label></span></div>';
    }
  }
  elseif(price2(date("Y-m-d", strtotime($date)),$price_array2) == '') {
    $value2 .= '<td class="price">'.'<span class="no-set">金額未設定</span>'.'<span class="set-bar">¥<input class="set_value" type="text" name="set_value2[]" ></span>'.'<input type="hidden" name="date2[]" value="'.$date.'" >'.'<input type="hidden" name="type[]" value="'.$type.'" >';
    $rest2 .= '<td class="rest">'.$reservation.'<span class="set-bar">'.'<input class="set_stock" type="text" name="set_stock2[]" value="'.$stock.'">室</span>'.'<input type="hidden" name="set_stock_copy2[]" value="0">';
  }
  $value2 .= '</td>';
  $rest2 .= '</td>';
}

$values2[] =  $value2;
$value2 = '';

$rests2[] = $rest2;
$rest2 = '';

?>
