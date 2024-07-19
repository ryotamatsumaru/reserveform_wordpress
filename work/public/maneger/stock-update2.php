<?php

// session_start();
// session_regenerate_id(true);
// if(isset($_SESSION['login'])==false){
//   print 'ログインされてません <br>';
//   print '<a href="maneger-login.html">ログイン画面へ</a>';
//   exit();
// }


require_once('/../work/app/stock-class.php');
require_once('/../work/app/config.php');
use MyApp\database; 

$update = new Stock();
$setWeek = $update->setWeek();
$timestamp = $update->timestamp;
$selectday = $update->selectday;
$afterday = $update->afterday;
$period = $update->makeOneweek();

$price_array = $update->getprice();

function price($date,$price_array){
  if(array_key_exists($date,$price_array)){
    $price_display = $price_array[$date];
    return $price_display;
  }
}

$stock_array = $update->getstock();

function stock($date,$stock_array){
  if(array_key_exists($date,$stock_array)){
    $stock_display = $stock_array[$date];
    return $stock_display;
  }
}

$stock_copy_array = $update->getstockcopy();

function stock_copy($date,$stock_copy_array){
  if(array_key_exists($date,$stock_copy_array)){
    $stock_copy_display = $stock_copy_array[$date];
    return $stock_copy_display;
  }
}

$books_array = $update->getreserve();

function reservation($date,$books_array){
  if($date < 'Y-m-d'){
    $date = date('Y-m-d');
  }
  $dbh = new PDO(DSN,USER,PASS);
  $stmt = $dbh->prepare("SELECT * FROM stock WHERE day = :date");
  $stmt->bindValue(':date', $date, PDO::PARAM_STR);
  $stmt->execute();
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  foreach($rows as $row){
  $row['inventory'];
  }

  if(array_key_exists($date,$books_array)){
    if ($row['inventory'] - $books_array[$date] <= 0){
      $books_display = "<span class='rest-text'>残室: 0 </span>".'<input type="hidden" name="date[]" value="'.$date.'" >';

    } else {
      $rest = $row['inventory'] - $books_array[$date];
      $books_display = "<span class='rest-text'>残室: $rest</span>".'<input type="hidden" name="date[]" value="'.$date.'" >';
    }
    return $books_display;
  } else {
      $books_display = "<span class='rest-text'>残室: 全空</span>".'<input type="hidden" name="date[]" value="'.$date.'" >';
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
  $stock_copy = stock_copy(date("Y-m-d", strtotime($date)), $stock_copy_array);
  $reservation = reservation(date("Y-m-d",strtotime($date)),$books_array);

  if($date < $today){
    $value .= "<td class='price'>"."<span class='price-text'>¥ $price </span>".'<span class="none">'.'-'.'</span>';
    $rest .= "<td class='rest'>".$reservation .'<span class="none">'.'-'.'</span>';
  }
  elseif(stock_copy(date("Y-m-d", strtotime($date)),$stock_copy_array)){
    $value .= '<td class="price">'."<span class='price-text'> ¥ $price </span>"."<span class='price-text'>¥$price <input class='set_value' type='hidden' name='update_value[]' value='$price' ></span>";
    $rest .= '<td class="rest">'.$reservation.'<span class="none">売止中<input class="set_stock" type="hidden" name="update_stock[]" value="'.$stock.'"></span>';
  }
  elseif(price(date("Y-m-d", strtotime($date)),$price_array) && reservation(date("Y-m-d", strtotime($date)),$books_array)) {
    $value .= "<td class='price'><span class='price-text'>¥ $price </span>"."<span class='set-bar'>¥<input class='set_value' type='text' name='update_value[]' value='$price'></span>";
    $rest .= '<td class="rest">'.$reservation."<span class='set-bar'><input class='set_stock' type='text' name='update_stock[]' value='$stock'>室</span>";
  }
  elseif(price(date("Y-m-d", strtotime($date)),$price_array) == '') {
    $value .= '<td class="price">' .'<br>'."<span class='no-set'>料金未設定</span>";
    $rest .= '<td class="rest">'.'<br>'."<span class='no-set'>室数未設定</span>";
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


// $weeks2 = [];
// $week2 = '';
$weeks2 = $update->dayOfWeek();

// $week2 = '';


?>
