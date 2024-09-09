<?php

require_once(dirname(dirname(__FILE__)). '/prvdtb/pdo-class.php');

$dbh = Database::getPdo();

class Single{
  public $ym;
  public $d;
  public $timestamp;
  public $selectday;
  public $afterday;
  public $start;
  public $end;
  public $interval;
  public $period;
  public $searchdate;
  public $searchdate2;

  // 日付検索、期間変更、デフォルト時の指定期間を求めるメソッド
  public function setWeek(){
    if(!empty($_GET['ym']) && strlen($_GET['ym']) == 8 )
    {
      // 期間変更
      $this->ym = $_GET['ym'];
      $this->timestamp = strtotime($this->ym);
      $this->selectday= date('Ymd', $this->timestamp);
      $this->afterday = date('Ymd', strtotime('+7 day', $this->timestamp));
    } elseif(isset($_POST['search_date'])){
      // 日付検索
      $this->ym = date('Y-m', strtotime($_POST['search_date']));
      $this->d = date('-d', strtotime($_POST['search_date']));
      $this->timestamp = strtotime($this->ym. $this->d);
      $this->selectday= date('Y-m-d', $this->timestamp);
      $this->afterday = date('Y-m-d', strtotime('+7 day', $this->timestamp));
    } else {
      // デフォルト
      $this->ym = date('Y-m');
      $this->d = date('-d');
      $this->timestamp = strtotime($this->ym. $this->d);
      $this->selectday= date('Y-m-d', $this->timestamp);
      $this->afterday = date('Y-m-d', strtotime('+7 day', $this->timestamp));
    }
  }

  // 指定した日付から7日分を求めるメソッド
  public function makeOneweek(){
    $this->start = new DateTime($this->selectday);
    $this->end = new DateTime($this->afterday);
    $this->interval = new DateInterval('P1D');
    $this->period = new DatePeriod($this->start, $this->interval, $this->end);
    return $this->period;
  }

  // データベースから料金を呼び出すメソッド
  public function getprice(){
    $dbh = Database::getPdo();
    $prices = $dbh->query("SELECT day,price FROM singleroom");
    $price_display = array();
  
    foreach($prices as $key => $out){
      $day_out = strtotime($out['day']);
      $price_out = $out['price'];
      $price_display[date('Y-m-d', $day_out)] = $price_out;
    }
    return $price_display;
  }

  // データベースから1日の合計予約数を求めるメソッド
  function getreserve(){
    $books_display = array();
  
    foreach ($this->period as $ymd){
      $type = 0;
      $date = $ymd->format('Y-m-d');
      $dbh = Database::getPdo();
      $sql = "SELECT COUNT(*),SUM(number),day FROM (SELECT * FROM booking WHERE day = :date AND type =:type) as booktotal GROUP BY day";
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

  // 曜日を求めるメソッド
  public function dayOfWeek(){
    $wday=array("日","月","火","水","木","金","土");
    foreach($this->period as $ymd){
      $date = $ymd->format('Y-m-d');
      $year = $ymd->format('Y');
      $month = $ymd->format('m');
      $day = $ymd->format('d');

      $weekstamp = mktime(0,0,0,$month,$day,$year);
      $w = $wday[date("w", $weekstamp)];
      $date2 = date("m/d", $weekstamp);
      $week = '<th class="wbox">'. $date2 .'('.$w.')'.'</th>';
      $weeks[] =  $week ;
    }
    return $weeks;
  }
}
?>
