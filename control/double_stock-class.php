<?php

require_once(dirname(dirname(__FILE__)). '/prvdtb/pdo-class.php');
// ダブル(doubleroomテーブル)のdouble_stock-insert.phpとdouble_stock-update.php用のclass
class doubleStock extends singleStock{

  // データベースから料金を呼び出すメソッド
  public function getprice(){
    $dbh = Database::getPdo();
    $prices = $dbh->query("SELECT day,price FROM doubleroom");
    $price_display = array();
  
    foreach($prices as $key => $out){
      $day_out = strtotime((string) $out['day']);
      $price_out = (string) $out['price'];
      $price_display[date('Y-m-d', $day_out)] = $price_out;
    }
    return $price_display;
  }

  // データベースから在庫を呼び出すメソッド
  public function getstock(){ 
    $dbh = Database::getPdo();
    $stocks = $dbh->query("SELECT day,inventory FROM doubleroom");
    $stock_display = array();
  
    foreach($stocks as $out){
      $day_out = strtotime((string) $out['day']);
      $stock_out = (string) $out['inventory'];
      $stock_display[date('Y-m-d', $day_out)] = $stock_out;
    }
    return $stock_display;
  }

  // データベースから売止用の値0を呼び出すメソッド
  public function getstockcopy(){ 
    $dbh = Database::getPdo();
    $stocks = $dbh->query("SELECT day,inventory_copy FROM doubleroom");
    $stock_copy_display = array();
  
    foreach($stocks as $out){
      $day_out = strtotime((string) $out['day']);
      $stock_out = (string) $out['inventory_copy'];
      $stock_copy_display[date('Y-m-d', $day_out)] = $stock_out;
    }
    return $stock_copy_display;
  }

  // データベースから1日の合計予約数を求めるメソッド
  public function getreserve(){
    $books_display = array();
  
    foreach ($this->period as $ymd){
      $type = 1;
      $date = $ymd->format('Y-m-d');
      $dbh = Database::getPdo();
      $sql = "SELECT COUNT(*),SUM(number),day FROM (SELECT * FROM booking WHERE day = :date AND type = :type) as booktotal GROUP BY day";
      $ps = $dbh->prepare($sql);
      $ps->bindValue(':date', $date, PDO::PARAM_STR);
      $ps->bindValue(':type', $type, PDO::PARAM_INT);
      $ps->execute();
      $row = $ps->fetch(PDO::FETCH_ASSOC);
      if($row == '') {
	$row_date = '0';
        $sum_number = '0';
      } else {
	$row_date = $row['day'];
        $sum_number = $row['SUM(number)'];
      }
      $day_out = strtotime((string)$row_date);
      $book_out = (string)$sum_number;
      $books_display[date('Y-m-d', $day_out)] = $book_out;
    }
    return $books_display;
  }
}

?>
