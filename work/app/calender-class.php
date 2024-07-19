<?php

require_once('/../work/app/config.php');
use MyApp\database;

class Calender{
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

  public function setWeek(){
    $test5 = date('Ym31');
    if(!empty($_GET['ym']) && strlen($_GET['ym']) == 8 )
    {
      $this->ym = $_GET['ym'];
      $this->timestamp = strtotime($this->ym);
      $this->selectday= date('Y-m-d', $this->timestamp);
      $this->afterday = date('Y-m-d', strtotime('+7 day', $this->timestamp));
    } elseif(isset($_POST['search_date'])){
      $this->ym = date('Y-m', strtotime($_POST['search_date']));
      $this->d = date('-d', strtotime($_POST['search_date']));
      $this->timestamp = strtotime($this->ym. $this->d);
      $this->selectday= date('Y-m-d', $this->timestamp);
      $this->afterday = date('Y-m-d', strtotime('+7 day', $this->timestamp));
    } else {
      $this->ym = date('Y-m');
      $this->d = date('-d');
      $this->timestamp = strtotime($this->ym. $this->d);
      $this->selectday= date('Y-m-d', $this->timestamp);
      $this->afterday = date('Y-m-d', strtotime('+7 day', $this->timestamp));
    }
  }

  public function makeOneweek(){
    $this->start = new DateTime($this->selectday);
    $this->end = new DateTime($this->afterday);
    $this->interval = new DateInterval('P1D');
    $this->period = new DatePeriod($this->start, $this->interval, $this->end);
    return $this->period;
  }

  public function getprice(){
    // $dbh = Database::getInstance();
    $dbh = new PDO(DSN,USER,PASS);
    $prices = $dbh->query("SELECT day,price FROM stock");
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
    $stocks = $dbh->query("SELECT day,inventory FROM stock");
    $stock_display = array();
  
    foreach($stocks as $out){
      $day_out = strtotime((string) $out['day']);
      $stock_out = (string) $out['inventory'];
      $stock_display[date('Y-m-d', $day_out)] = $stock_out;
    }
    return $stock_display;
  }

  function getreserve(){
    $books_display = array();
  
    foreach ($this->period as $ymd){
      $type = 0;
      $date = $ymd->format('Y-m-d');
      $dbh = new PDO(DSN,USER,PASS);
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
      $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $sql = "SELECT COUNT(*),SUM(member),day FROM (SELECT * FROM booking WHERE day = :date AND type =:type) as booktotal GROUP BY day";
      $ps = $dbh->prepare($sql);
      $ps->bindValue(':date', $date, PDO::PARAM_STR);
      $ps->bindValue(':type', $type, PDO::PARAM_STR);
      $ps->execute();
      $row = $ps->fetch(PDO::FETCH_ASSOC);
      if($row['day'] == '0'){
        $row_date = '0';
      } else {
        $row_date = $row['day'];
      }
      $day_out = strtotime((string)$row_date);
      $book_out = (string)$row['SUM(member)'];
      $books_display[date('Y-m-d', $day_out)] = $book_out;
    }
  
    return $books_display;
  }

  public function dayOfWeek(){
  $wday=array("日","月","火","水","木","金","土");
  foreach($this->period as $ymd){
  $date = $ymd->format('Y-m-d');
  $year = $ymd->format('Y');
  $month = $ymd->format('m');
  $day = $ymd->format('d');

  $timestamp2 = mktime(0,0,0,$month,$day,$year);
  $w = $wday[date("w", $timestamp2)];
  $date2 = date("m/d", $timestamp2);
  $week2 = '<th class="wbox">'. $date2 .'('.$w.')'.'</th>';
  $weeks2[] =  $week2 ;
  }
  return $weeks2;
  }
}
?>