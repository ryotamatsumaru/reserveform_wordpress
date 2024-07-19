<?php

require_once('/../work/app/stock-class.php');
require_once('/../work/app/config.php');
use MyApp\database;

class Stock2 extends stock{

  public function getprice(){
    // $dbh = Database::getInstance();
    $dbh = new PDO(DSN,USER,PASS);
    $prices = $dbh->query("SELECT day,price FROM doubleroom");
    $price_display = array();
  
    foreach($prices as $key => $out){
      $day_out = strtotime((string) $out['day']);
      $price_out = (string) $out['price'];
      $price_display[date('Y-m-d', $day_out)] = $price_out;
    }
    return $price_display;
  }

  public function getstock(){ 
    $dbh = new PDO(DSN,USER,PASS);
    $stocks = $dbh->query("SELECT day,inventory FROM doubleroom");
    $stock_display = array();
  
    foreach($stocks as $out){
      $day_out = strtotime((string) $out['day']);
      $stock_out = (string) $out['inventory'];
      $stock_display[date('Y-m-d', $day_out)] = $stock_out;
    }
    return $stock_display;
  }

  public function getstockcopy(){ 
    $dbh = new PDO(DSN,USER,PASS);
    $stocks = $dbh->query("SELECT day,inventory_copy FROM doubleroom");
    $stock_copy_display = array();
  
    foreach($stocks as $out){
      $day_out = strtotime((string) $out['day']);
      $stock_out = (string) $out['inventory_copy'];
      $stock_copy_display[date('Y-m-d', $day_out)] = $stock_out;
    }
    return $stock_copy_display;
  }

  public function getreserve(){
    $books_display = array();
  
    foreach ($this->period as $ymd){
      $type = 1;
      $date = $ymd->format('Y-m-d');
      $dbh = new PDO(DSN,USER,PASS);
      $sql = "SELECT COUNT(*),SUM(member),day FROM (SELECT * FROM booking WHERE day = :date AND type = :type) as t";
      $ps = $dbh->prepare($sql);
      $ps->bindValue(':date', $date, PDO::PARAM_STR);
      $ps->bindValue(':type', $type, PDO::PARAM_INT);
      $ps->execute();
      $row = $ps->fetch(PDO::FETCH_ASSOC);
      $day_out = strtotime((string)$row['day']);
      $book_out = (string)$row['SUM(member)'];
      $books_display[date('Y-m-d', $day_out)] = $book_out;
    }
  
    return $books_display;
  }
}
?>