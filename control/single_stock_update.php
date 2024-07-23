<?php

require_once('single_stock-class.php');
require_once(dirname(dirname(__FILE__)). '/prvdtb/pdo-class.php');
$dbh = Database::getPdo();

$update = new singleStock();
$setWeek = $update->setWeek();
$timestamp = $update->timestamp;
$selectday = $update->selectday;
$afterday = $update->afterday;
$period = $update->makeOneweek();

$price_array = $update->getprice();
// 日付をキーとした料金を配列に代入する関数
function price($date,$price_array){
  if(array_key_exists($date,$price_array)){
    $price_display = $price_array[$date];
    return $price_display;
  }
}

$stock_array = $update->getstock();
// 日付をキーとした在庫を配列に代入する関数
function stock($date,$stock_array){
  if(array_key_exists($date,$stock_array)){
    $stock_display = $stock_array[$date];
    return $stock_display;
  }
}

$stock_copy_array = $update->getstockcopy();
// 日付をキーとした売止用の値0を配列に代入する関数
function stock_copy($date,$stock_copy_array){
  if(array_key_exists($date,$stock_copy_array)){
    $stock_copy_display = $stock_copy_array[$date];
    return $stock_copy_display;
  }
}

$books_array = $update->getreserve();
function reservation($date,$books_array){
  $dbh = Database::getPdo();
  $stmt = $dbh->prepare("SELECT * FROM singleroom WHERE day = :date");
  $stmt->bindValue(':date', $date, PDO::PARAM_STR);
  $stmt->execute();
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  foreach($rows as $row){
  $inventory = $row['inventory'];
  $inventory_copy = $row['inventory_copy'];
  }

  //シングル(singleroomテーブル)の在庫から１日の合計予約数を引いた値を残室として残室数に応じた処理をifで分岐する。
  if(array_key_exists($date,$books_array)){
     if($inventory <= 0 && 0 < $inventory_copy) {
      $rest = $row['inventory_copy'] - $books_array[$date];
      $books_display = "<span class='rest-text'>残室: $rest</span>".'<input type="hidden" name="date[]" value="'.$date.'" >';
    }
	  else {
      $rest = $inventory - $books_array[$date];
      $books_display = "<span class='rest-text'>残室: $rest</span>".'<input type="hidden" name="date[]" value="'.$date.'" >';
    }
    return $books_display;
  } else {
      $books_display = "<span class='rest-text'>残室: 全空</span>".'<input type="hidden" name="date[]" value="'.$date.'" >';
      return $books_display;
  }
}

$title = date('Y年n月', $timestamp);

// シングルの料金用配列
$single_values = [];
$single_value = '';
// シングルの在庫用配列
$single_rests = [];
$single_rest = '';

foreach( $period as $ymd){
  $today = date('Y-m-d');
  $date = $ymd->format('Y-m-d');
  $days = $ymd->format('d');
  $price = price(date("Y-m-d",strtotime($date)),$price_array);
  $stock = stock(date("Y-m-d", strtotime($date)), $stock_array);
  $stock_copy = stock_copy(date("Y-m-d", strtotime($date)), $stock_copy_array);
  $reservation = reservation(date("Y-m-d",strtotime($date)),$books_array);

  //日付が過去日、予約がある日、価格が設定されていない日に応じた処理をifで分岐している。
  if($date < $today){
    $single_value .= "<td class='price'>"."<span class='price-text'>¥ $price </span>".'<span class="none">'.'-'.'</span>';
    $single_rest .= "<td class='rest'>".$reservation .'<span class="none">'.'-'.'</span>';
  }
  elseif(stock_copy(date("Y-m-d", strtotime($date)),$stock_copy_array)){
    $single_value .= '<td class="price">'."<span class='price-text'> ¥ $price </span>"."<span class='price-text'>¥$price <input class='set_value' type='hidden' name='update_value[]' value='$price' ></span>";
    $single_rest .= '<td class="rest">'.$reservation.'<span class="none">売止中<input class="set_stock" type="hidden" name="update_stock[]" value="'.$stock.'"></span>';
  }
  elseif(price(date("Y-m-d", strtotime($date)),$price_array) && reservation(date("Y-m-d", strtotime($date)),$books_array)) {
    $single_value .= "<td class='price'><span class='price-text'>¥ $price </span>"."<span class='set-bar'>¥<input class='set_value' type='text' name='update_value[]' value='$price'></span>";
    $single_rest .= '<td class="rest">'.$reservation."<span class='set-bar'><input class='set_stock' type='text' name='update_stock[]' value='$stock'>室</span>";
  }
  elseif(price(date("Y-m-d", strtotime($date)),$price_array) == '') {
    $single_value .= '<td class="price">' .'<br>'."<span class='no-set'>料金未設定</span>";
    $single_rest .= '<td class="rest">'.'<br>'."<span class='no-set'>室数未設定</span>";
  }

  $single_value .= '</td>';
  $single_rest .= '</td>';
}

// シングルの料金用配列
$single_values[] =  $single_value;
$single_value = '';
// シングルの在庫用配列
$single_rests[] =  $single_rest;
$single_rest = '';

$year = date('Y', $timestamp);
$month = date('m', $timestamp);

// 曜日を求めるメソッド変数に代入。
$weeks = $update->dayOfWeek();
?>
