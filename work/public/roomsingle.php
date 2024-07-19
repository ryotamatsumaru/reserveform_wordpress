<?php

  require_once(__DIR__ . '/../app/calender-class.php');
  require_once(__DIR__ . '/../app/config.php');
  use MyApp\database;

  $dbh = Database::getInstance();


  session_start();
  if(isset($_SESSION['confirm'])){
  unset($_SESSION['confirm']);
  echo '削除済';
  }

  $calender = new Calender();
  $setWeek = $calender->setWeek();
  $timestamp = $calender->timestamp;
  $selectday = $calender->selectday;
  $afterday = $calender->afterday;
  $period = $calender->makeOneweek();

  $type = 'stock';

  $price_array = $calender->getprice();

  function price($date,$price_array){
    if(array_key_exists($date,$price_array)){
      $price_display = "<br/>"."<span>"."¥".$price_array[$date]."</span>";
      return $price_display;
    }
  }

  $books_array = $calender->getreserve();

  function reservation($date,$books_array){
    $type = 'stock';
    if($date < date('Y-m-d')){
      $date = date('Y-m-d');
    }
    $dbh = new PDO(DSN,USER,PASS);
    $stmt = $dbh->prepare("SELECT * FROM stock WHERE day = :date");
    $stmt->bindValue(':date', $date, PDO::PARAM_STR);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if($rows != null){
      foreach($rows as $row){
      $inventory = $row['inventory'];
      }
      } else {
      $inventory = 0;
      }
    
    if(array_key_exists($date,$books_array)){
      if($inventory - $books_array[$date] == 2){
        $books_display = "<br/>"."<span class='rest2'>".'<a href="reserve-test.php?dates='. $date .'&type='. $type .'">'.'残り2室'.'</a>'."</span>";
      } elseif($inventory - $books_array[$date] == 1){
        $books_display = "<br/>"."<span class='rest1'>".'<a href="reserve-test.php?dates='. $date .'&type='. $type .'">'.'残り1室'.'</a>'."</span>";
      } elseif($books_array[$date] < $row['inventory']){
        $books_display = "<br/>"."<span class='rest'>".'<a href="reserve-test.php?dates='.$date.'&type='. $type .'">'.'○'.'</a>'."</span>";
      } else {
        $books_display = "<br/>"."<span class='norest'>".'-'."</span>";
      }
      return $books_display;
    }
    elseif( $inventory == 0){
      $books_display = "<br/>"."<span class='setzerostock'>".'-'."</span>";
      return $books_display;
    }
  }

  date_default_timezone_set('Asia/Tokyo');
  $title = date('Y年n月', $timestamp);

  $singles = [];
  $single = '';
  // $week .= '<td>シングル</td>';

  foreach( $period as $ymd){
    $today = date('Y-m-d');
    $date = $ymd->format('Y-m-d');
    $days = $ymd->format('d');
    $price = price(date("Y-m-d",strtotime($date)),$price_array);
    $reservation = reservation(date("Y-m-d",strtotime($date)),$books_array);
   
    if($date < $today){
      $single .= '<td>'.'<span class="norest">'.'-'.'</span>';
    }
    elseif(price(date("Y-m-d", strtotime($date)),$price_array) && reservation(date("Y-m-d", strtotime($date)),$books_array)) {
      $single .= '<td>' . $days . $price . $reservation ;
    }
    elseif(price(date("Y-m-d", strtotime($date)),$price_array) == '') {
      $single .= '<td>' .'<span class="norest">'.'-'.'</span>';
    } else {
      $single .= '<td>'.'<span>'.$days .'日'.'</span>'.'<span class="price">'. $price .'</span>'.'<br>'.'<span class="rest">'.'<a href="reserve-test.php?dates='. $date .'&type='.$type.'">'.'○'.'</a>'.'</span>';
    }
    $single .= '</td>';
  }

  $singles[] =  $single ;
  $single = '';

$year = date('Y', $timestamp);
$month = date('m', $timestamp);

$weeks2 = $calender->dayOfWeek();

$prev = date('Ymd', strtotime('-7 day', $timestamp));
$next = date('Ymd', strtotime('+7 day', $timestamp));
?>