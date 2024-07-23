<?php

require_once('double_stock-class.php');
require_once(dirname(dirname(__FILE__)). '/prvdtb/pdo-class.php');
$dbh = Database::getPdo();

$insert2 = new doubleStock();
$setWeek2 = $insert2->setWeek();
$timestamp2 = $insert2->timestamp;
$selectday2 = $insert2->selectday;
$afterday2 = $insert2->afterday;
$period2 = $insert2->makeOneweek();

$price_array2 = $insert2->getprice();
// 日付をキーとした料金を配列に代入する関数
function price2($date,$price_array2){
  if(array_key_exists($date,$price_array2)){
    $price_display2 = $price_array2[$date];
    return $price_display2;
  }
}

$stock_array2 = $insert2->getstock();
// 日付をキーとした在庫を配列に代入する関数
function stock2($date,$stock_array2){
  if(array_key_exists($date,$stock_array2)){
    $stock_display2 = $stock_array2[$date];
    return $stock_display2;
  }
}

$books_array2 = $insert2->getreserve();
function reservation2($date,$books_array2){
  $type = 1;
  $dbh = Database::getPdo();
  $sql = "SELECT * FROM doubleroom WHERE day = :date";
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

  //ダブル(doubleroomテーブル)の在庫から１日の合計予約数を引いた値を残室として残室数に応じた処理をifで分岐する。
  if(array_key_exists($date,$books_array2)){
    if ($inventory - $books_array2[$date] <= 0){
      $books_display2 = "<span class='rest-text'>残室: 0 </span>";
    } else {
      $rest = $inventory - $books_array2[$date];
      $books_display2 = "<span class='rest-text'>残室: $rest</span>";
    }
    return $books_display2;
  } else {
    $books_display2 = "<span class='rest-text'>残室: 全空</span>";
    return $books_display2;
  }
}

$title = date('Y年n月', $timestamp2);

// ダブルの料金用配列
$double_values = [];
$double_value = '';
// ダブルの在庫用配列
$double_rests = [];
$double_rest = '';

foreach( $period2 as $ymd){
  $today = date('Y-m-d');
  $date = $ymd->format('Y-m-d');
  $days = $ymd->format('d');
  $price = price2(date("Y-m-d",strtotime($date)),$price_array2);
  $stock = stock2(date("Y-m-d", strtotime($date)), $stock_array2);
  $reservation = reservation2(date("Y-m-d",strtotime($date)),$books_array2);
  $type = 1;

  $dbh = Database::getPdo();
  $sql = "SELECT * FROM doubleroom WHERE day = :date";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':date', $date, PDO::PARAM_STR);
  $ps->execute();
  $rows = $ps->fetchAll(PDO::FETCH_ASSOC);
  foreach($rows as $row){
    $row['inventory_copy'];
    $row['inventory'];
    $id_css2[] = $row['id'];
  }

  //日付が過去日、予約がある日、価格が設定されていない日に応じた処理をifで分岐している。
  if($date < $today){
    $double_value .= '<td class="price">'.'<br>'.'<span class="price-text">'.'¥'.$price.'</span>';
    $double_rest .= '<td class="rest">'.'<span class="rest-text">'.$reservation .'</span>'.'<span class="none">'.'-'.'</span>';
  }
  elseif(price2(date("Y-m-d", strtotime($date)),$price_array2) && reservation2(date("Y-m-d", strtotime($date)),$books_array2)) {
    $double_value .= '<td class="price">'.'<br>'.'<span class="price-text">¥'.$price.'</span>';
    $double_rest .= '<td class="rest">'.$reservation.'<span class="set-text">設定数:'.$stock.'</span>';
    if($row['inventory_copy'] == 0){
      $double_rest.= '<div class="off2"><span class="toggle"><input type="checkbox" name="id2[]" value="'.$row['id'].'" id="id2'.$row['id'].'"><label for="id2'.$row['id'].'"><span></span><input type="hidden" name="set_stock_copy2[]" value="0"></label></span></div>';
    } else {
      $double_rest.= '<div class="on2"><span class="toggle"><input type="checkbox" name="id2[]" value="'.$row['id'].'" id="id2'.$row['id'].'"><label for="id2'.$row['id'].'"><span></span><input type="hidden" name="set_stock_copy2[]" value="0"></label></span></div>';
    }
  }
  elseif(price2(date("Y-m-d", strtotime($date)),$price_array2) == '') {
    $double_value .= '<td class="price">'.'<span class="no-set">金額未設定</span>'.'<span class="set-bar">¥<input class="set_value" type="text" name="set_value2[]" ></span>'.'<input type="hidden" name="date2[]" value="'.$date.'" >'.'<input type="hidden" name="type[]" value="'.$type.'" >';
    $double_rest .= '<td class="rest">'.$reservation.'<span class="set-bar">'.'<input class="set_stock" type="text" name="set_stock2[]" value="'.$stock.'">室</span>'.'<input type="hidden" name="set_stock_copy2[]" value="0">';
  }
  $double_value .= '</td>';
  $double_rest .= '</td>';
}

// ダブルの料金用配列
$double_values[] =  $double_value;
$double_value = '';
// ダブルの在庫用配列
$double_rests[] = $double_rest;
$double_rest = '';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<!-- doubleroomテーブルのidカラムに対応させるための販売・売止ボタンのphpとcss記述 -->
<style>
  <?php 
  foreach($id_css2 as $ids2){
    echo '.id'.$ids2.'{color: red;}';
    echo '.off2'.' #id2'.$ids2.':checked + label span:after'.'{content: "売止"; color: red;}';
    echo '.off2'.' #id2'.$ids2.' + label span:after'.'{content: "販売"; color: #333;}';
    echo '.on2'.' #id2'.$ids2.' + label span:after'.'{content: "売止"; color: red;}';
    echo '.on2'.' #id2'.$ids2.':checked + label span:after'.'{content: "販売"; color: #333;}';
  }
  ?>

  .off2 label,
  .on2 label {
    box-sizing: border-box;
    border: 1px solid #ccc;
    height: 20px;
    line-height: 20px;
    font-weight: normal;  
    background: #eee;
    box-shadow: 2px 2px 6px #888;  
    transition: .3s;  
  }

  .off2 input[type="checkbox"],
  .on2 input[type="checkbox"] {
    display : none;
  }
  </style>
</html>
