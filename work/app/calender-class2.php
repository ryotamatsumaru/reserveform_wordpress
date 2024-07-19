<?php

require_once(__DIR__ . '/../app/calender-class.php');
require_once('/../work/app/config.php');
use MyApp\database;

class Calender2 extends Calender{

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

  public function getreserve(){
    $books_display2 = array();
    foreach ($this->period as $ymd){
      $type = 1;
      $date = $ymd->format('Y-m-d');
      $dbh = new PDO(DSN,USER,PASS);
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
      $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $sql = "SELECT COUNT(*),SUM(member),day FROM (SELECT * FROM booking WHERE day = :date AND type = :type) as booktotal2 GROUP BY day";
      $ps = $dbh->prepare($sql);
      $ps->bindValue(':date', $date, PDO::PARAM_STR);
      $ps->bindValue(':type', $type, PDO::PARAM_INT);
      $ps->execute();
      $row = $ps->fetch(PDO::FETCH_ASSOC);
      if($row['day'] == '0'){
        $row_date = '0';
      } else {
        $row_date = $row['day'];
      }
      $day_out = strtotime((string)$row_date);
      $book_out = (string)$row['SUM(member)'];
      $books_display2[date('Y-m-d', $day_out)] = $book_out;
    }
  
    return $books_display2;
  }
}
?>