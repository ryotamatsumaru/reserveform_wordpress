<?php

require_once('/../work/app/stock-class2.php');
require_once('/../work/app/config.php');
use MyApp\database; 

$update2 = new Stock2();
$setWeek2 = $update2->setWeek();
$timestamp2 = $update2->timestamp;
$selectday2 = $update2->selectday;
$afterday2 = $update2->afterday;
$period2 = $update2->makeOneweek();

$price_array2 = $update2->getprice();

function price2($date,$price_array2){
  if(array_key_exists($date,$price_array2)){
    $price_display2 = $price_array2[$date];
    return $price_display2;
  }
}

$stock_array2 = $update2->getstock();

function stock2($date,$stock_array2){
  if(array_key_exists($date,$stock_array2)){
    $stock_display2 = $stock_array2[$date];
    return $stock_display2;
  }
}

$stock_copy_array2 = $update2->getstockcopy();
function stock_copy2($date,$stock_copy_array2){
  if(array_key_exists($date,$stock_copy_array2)){
    $stock_copy_display2 = $stock_copy_array2[$date];
    return $stock_copy_display2;
  }
}

$books_array2 = $update2->getreserve();

function reservation2($date,$books_array2){
  if($date < 'Y-m-d'){
    $date = date('Y-m-d');
  }
  $dbh = new PDO(DSN,USER,PASS);
  $stmt = $dbh->prepare("SELECT * FROM doubleroom WHERE day = :date");
  $stmt->bindValue(':date', $date, PDO::PARAM_STR);
  $stmt->execute();
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  foreach($rows as $row){
  $row['inventory'];
  }

  if(array_key_exists($date,$books_array2)){
    if ($row['inventory'] - $books_array2[$date] <= 0){
      $books_display2 = "<span class='rest-text'>残室: 0 </span>".'<input type="hidden" name="date[]" value="'.$date.'" >';

    } else {
      $rest2 = $row['inventory'] - $books_array2[$date];
      $books_display2 = "<span class='rest-text'>残室: $rest2</span>".'<input type="hidden" name="date[]" value="'.$date.'" >';
    }
    return $books_display2;
  } else {
      $books_display2 = "<span class='rest-text'>残室: 全空</span>".'<input type="hidden" name="date[]" value="'.$date.'" >';
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
  $stock_copy = stock_copy2(date("Y-m-d", strtotime($date)), $stock_copy_array2);

  if($date < $today){
    $value2 .= "<td class='price'>"."<span class='price-text'>¥ $price </span>".'<span class="none">'.'-'.'</span>';
    $rest2 .= "<td class='rest'>".$reservation .'<span class="none">'.'-'.'</span>';
  }
  elseif(stock_copy2(date("Y-m-d", strtotime($date)),$stock_copy_array2)){
    $value2 .= '<td class="price">'."<span class='price-text'> ¥ $price </span>"."<span class='price-text'>¥$price <input class='set_value' type='hidden' name='update_value2[]' value='$price' ></span>";
    $rest2 .= '<td class="rest">'.$reservation.'<span class="none">売止中<input class="set_stock" type="hidden" name="update_stock2[]" value="'.$stock.'"></span>';
  }
  elseif(price2(date("Y-m-d", strtotime($date)),$price_array2) && reservation2(date("Y-m-d", strtotime($date)),$books_array2)) {
    $value2 .= "<td class='price'><span class='price-text'>¥ $price </span>"."<span class='set-bar'>¥<input class='set_value' type='text' name='update_value2[]' value='$price'></span>";
    $rest2 .= '<td class="rest">'.$reservation."<span class='set-bar'><input class='set_stock' type='text' name='update_stock2[]' value='$stock'>室</span>";
  }
  elseif(price(date("Y-m-d", strtotime($date)),$price_array2) == '') {
    $value2 .= '<td class="price">' .'<br>'."<span class='no-set'>料金未設定</span>";
    $rest2 .= '<td class="rest">'.'<br>'."<span class='no-set'>室数未設定</span>";
  }

  $value2 .= '</td>';
  $rest2 .= '</td>';
}

$values2[] =  $value2;
$value2 = '';

$rests2[] =  $rest2;
$rest2 = '';

$year = date('Y', $timestamp2);
$month = date('m', $timestamp2);
?>