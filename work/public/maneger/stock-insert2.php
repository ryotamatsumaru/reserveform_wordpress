<?php

session_start();
session_regenerate_id(true);
if(isset($_SESSION['login'])==false){
  print 'ログインされてません <br>';
  print '<a href="maneger-login.html">ログイン画面へ</a>';
  exit();
}

require_once('/../work/app/stock-class.php');
require_once('/../work/app/config.php');
use MyApp\database;

$dbh = Database::getInstance();

$insert = new Stock();
$setWeek = $insert->setWeek();
$timestamp = $insert->timestamp;
$selectday = $insert->selectday;
$afterday = $insert->afterday;
$period = $insert->makeOneweek();

$price_array = $insert->getprice();

function price($date,$price_array){
  if(array_key_exists($date,$price_array)){
    $price_display = $price_array[$date];
    return $price_display;
  }
}

$stock_array = $insert->getstock();

function stock($date,$stock_array){
  if(array_key_exists($date,$stock_array)){
    $stock_display = $stock_array[$date];
    return $stock_display;
  }
}

$books_array = $insert->getreserve();

function reservation($date,$books_array){
  if($date < 'Y-m-d'){
    $date = date('Y-m-d');
  }
  $dbh = new PDO(DSN,USER,PASS);
  $stmt = $dbh->prepare("SELECT * FROM stock WHERE day = '$date'");
  $stmt->execute();
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  foreach($rows as $row){
  $row['inventory'];
  }
  $type = 0;

  if(array_key_exists($date,$books_array)){
    if ($row['inventory'] - $books_array[$date] <= 0){
      $books_display = "<span class='rest-text'>残室: 0 </span>";
    } else {
      $rest = $row['inventory'] - $books_array[$date];
      $books_display = "<span class='rest-text'>残室: $rest</span>";
    }
    return $books_display;
  } else {
    $books_display = "<span class='rest-text'>".'残室: '."</span>"."<span>".'全空'."</span>";
    return $books_display;
  }
}

date_default_timezone_set('Asia/Tokyo');
$title = date('Y年n月', $timestamp);

$values = [];
$value = '';
$rests = [];
$rest = '';

foreach( $period as $ymd){
  $today = date('Y-m-d');
  $date = $ymd->format('Y-m-d');
  $days = $ymd->format('d');
  $price = price(date("Y-m-d",strtotime($date)),$price_array);
  $stock = stock(date("Y-m-d", strtotime($date)), $stock_array);
  $reservation = reservation(date("Y-m-d",strtotime($date)),$books_array);
  
  $sql = "SELECT * FROM stock WHERE day = :date";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':date', $date, PDO::PARAM_STR);
  $ps->execute();
  $rows = $ps->fetchAll(PDO::FETCH_ASSOC);
  foreach($rows as $row){
    $row['inventory_copy'];
    $row['inventory'];
    $id_css[] = $row['id'];
  }
  $type = 0;

  if($date < $today){
    $value .= '<td class="price">'.'<br>'.'<span class="price-text">'.'¥'.$price .'</span>';
    $rest .= '<td class="rest">'.'<span class="rest-text">'.$reservation .'</span>'.'<span class="none">'.'-'.'</span>';
  }
  elseif(price(date("Y-m-d", strtotime($date)),$price_array) && reservation(date("Y-m-d", strtotime($date)),$books_array)) {
    $value .= '<td class="price">'.'<br>'.'<span class="price-text">¥'.$price.'</span>';
    $rest .= '<td class="rest">'.$reservation.'<span class="set-text">設定数:'.$stock.'</span>';
    if($row['inventory_copy'] == 0){
    $rest.= '<div class="off"><span class="toggle"><input type="checkbox" name="id[]" value="'.$row['id'].'" id="id'.$row['id'].'"><label for="id'.$row['id'].'"><span></span><input type="hidden" name="set_stock_copy[]" value="0"></label></span></div>';
    } else {
    $rest.= '<div class="on"><span class="toggle"><input type="checkbox" name="id[]" value="'.$row['id'].'" id="id'.$row['id'].'"><label for="id'.$row['id'].'"><span></span><input type="hidden" name="set_stock_copy[]" value="0"></label></span></div>';
    }
  }
  elseif(price(date("Y-m-d", strtotime($date)),$price_array) == '') {
    $value .= '<td class="price">'.'<span class="no-set">金額未設定</span>'.'<span class="set-bar">¥<input class="set_value" type="text" name="set_value[]" ></span>'.'<input type="hidden" name="date[]" value="'.$date.'" >'.'<input type="hidden" name="type[]" value="'.$type.'" >';

    $rest .= '<td class="rest">'.$reservation.'<span class="set-bar"><input class="set_stock" type="text" name="set_stock[]" value="'.$stock.'"></span>'.'<input type="hidden" name="set_stock_copy[]" value="0">';
  }
  $value .= '</td>';
  $rest .= '</td>';
}

$values[] =  $value;
$value = '';

$rests[] =  $rest;
$rest = '';

$year = date('Y', $timestamp);
$month = date('m', $timestamp);


$weeks2 = $insert->dayOfWeek();

// $prev = date('Y-m-d', strtotime('-7 day', $timestamp));
// $next = date('Y-m-d', strtotime('+7 day', $timestamp));
?>