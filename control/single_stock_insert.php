<?php

require_once('single_stock-class.php');
require_once(dirname(dirname(__FILE__)). '/prvdtb/pdo-class.php');
$dbh = Database::getPdo();

$insert = new singleStock();
$setWeek = $insert->setWeek();
$timestamp = $insert->timestamp;
$selectday = $insert->selectday;
$afterday = $insert->afterday;
$period = $insert->makeOneweek();

$price_array = $insert->getprice();
// 日付をキーとした料金を配列に代入する関数
function price($date,$price_array){
  if(array_key_exists($date,$price_array)){
    $price_display = $price_array[$date];
    return $price_display;
  }
}

$stock_array = $insert->getstock();
// 日付をキーとした在庫を配列に代入する関数
function stock($date,$stock_array){
  if(array_key_exists($date,$stock_array)){
    $stock_display = $stock_array[$date];
    return $stock_display;
  }
}

$books_array = $insert->getreserve();
function reservation($date,$books_array){
  $dbh = Database::getPdo();
  $sql = "SELECT * FROM singleroom WHERE day = :date";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':date', $date, PDO::PARAM_STR);
  $ps->execute();
  $rows = $ps->fetchAll(PDO::FETCH_ASSOC);
  if($rows != null){
    foreach($rows as $row){
      $inventory = $row['inventory'];
    }
  } else {
    $inventory = 0;
  }
  $type = 0;

  //シングル(singleroomテーブル)の在庫から１日の合計予約数を引いた値を残室として残室数に応じた処理をifで分岐する。
  if(array_key_exists($date,$books_array)){
    if($inventory - $books_array[$date] <= 0){
      $books_display = "<span class='rest-text'>".'残室: '.'0'."</span>";
    } else {
      $rest = $inventory - $books_array[$date];
      $books_display = "<span class='rest-text'>".'残室: '.$rest."</span>";
    }
    return $books_display;
  } else {
    $books_display = "<span class='rest-text'>".'残室: '.'全空'."</span>";
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
  $reservation = reservation(date("Y-m-d",strtotime($date)),$books_array);
  $type = 0;
  
  $sql = "SELECT * FROM singleroom WHERE day = :date";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':date', $date, PDO::PARAM_STR);
  $ps->execute();
  $rows = $ps->fetchAll(PDO::FETCH_ASSOC);
  foreach($rows as $row){
    $row['inventory_copy'];
    $row['inventory'];
    $id_css[] = $row['id'];
  }

  //日付が過去日、予約がある日、価格が設定されていない日に応じた処理をifで分岐している。
  if($date < $today){
    $single_value .= '<td class="price">'.'<br>'.'<span class="price-text">'.'¥'.$price .'</span>';
    $single_rest .= '<td class="rest">'.'<span class="rest-text">'.$reservation .'</span>'.'<span class="none">'.'-'.'</span>';
  }
  elseif(price(date("Y-m-d", strtotime($date)),$price_array) && reservation(date("Y-m-d", strtotime($date)),$books_array)) {
    $single_value .= '<td class="price">'.'<br>'.'<span class="price-text">¥'.$price.'</span>';
    $single_rest .= '<td class="rest">'.$reservation.'<span class="set-text">設定数:'.$stock.'</span>';
    if($row['inventory_copy'] == 0){
      $single_rest.= '<div class="off"><span class="toggle"><input type="checkbox" name="id[]" value="'.$row['id'].'" id="id'.$row['id'].'"><label for="id'.$row['id'].'"><span></span><input type="hidden" name="set_stock_copy[]" value="0"></label></span></div>';
    } else {
      $single_rest.= '<div class="on"><span class="toggle"><input type="checkbox" name="id[]" value="'.$row['id'].'" id="id'.$row['id'].'"><label for="id'.$row['id'].'"><span></span><input type="hidden" name="set_stock_copy[]" value="0"></label></span></div>';
    }
  }
  elseif(price(date("Y-m-d", strtotime($date)),$price_array) == '') {
    $single_value .= '<td class="price">'.'<span class="no-set">金額未設定</span>'.'<span class="set-bar">¥<input class="set_value" type="text" name="set_value[]" ></span>'.'<input type="hidden" name="date[]" value="'.$date.'" >'.'<input type="hidden" name="type[]" value="'.$type.'" >';
    $single_rest .= '<td class="rest">'.$reservation.'<span class="set-bar"><input class="set_stock" type="text" name="set_stock[]" value="'.$stock.'">室</span>'.'<input type="hidden" name="set_stock_copy[]" value="0">';
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
$weeks = $insert->dayOfWeek();

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<!-- singleroomテーブルのidカラムに対応させるための販売・売止ボタンのphpとcss記述 -->
<style>
  <?php 
  foreach($id_css as $ids){
    echo '.id'.$ids.'{color: red;}';
    echo '.off'.' #id'.$ids.':checked + label span:after'.'{content: "売止"; color: red;}';
    echo '.off'.' #id'.$ids.' + label span:after'.'{content: "販売"; color: #333;}';
    echo '.on'.' #id'.$ids.' + label span:after'.'{content: "売止"; color: red;}';
    echo '.on'.' #id'.$ids.':checked + label span:after'.'{content: "販売"; color: #333;}';
  }
  ?>

  .off label,
  .on label {
    box-sizing: border-box;
    border: 1px solid #ccc;
    height: 20px;
    line-height: 20px;
    font-weight: normal;  
    background: #eee;
    box-shadow: 2px 2px 6px #888;  
    transition: .3s;  
  }

  .off input[type="checkbox"],
  .on input[type="checkbox"] {
    display : none;
  }
</style>
</head>