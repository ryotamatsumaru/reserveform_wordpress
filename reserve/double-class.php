<?php

class Double extends Single{

    // 料金を求めるメソッド
  public function getprice(){
    $dbh = Database::getPdo();
    $sql = "SELECT day,price FROM doubleroom";
    $prices = $dbh->query($sql);
    $price_display = array();
  
    foreach($prices as $key => $out){
      $day_out = strtotime((string) $out['day']);
      $price_out = (string) $out['price'];
      $price_display[date('Y-m-d', $day_out)] = $price_out;
    }
    return $price_display;
  }

  // 予約数を求めるメソッド
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