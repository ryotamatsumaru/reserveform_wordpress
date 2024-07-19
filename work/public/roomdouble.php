<?php

  require_once(__DIR__ . '/../app/calender-class2.php');
  require_once(__DIR__ . '/../app/config.php');
  use MyApp\database;

  $dbh = Database::getInstance();


  // session_start();
  if(isset($_SESSION['confirm'])){
  unset($_SESSION['confirm']);
  echo '削除済';
  }

  $calender2 = new Calender2();
  $setWeek2 = $calender2->setWeek();
  $timestamp2 = $calender2->timestamp;
  $selectday2 = $calender2->selectday;
  $afterday2 = $calender2->afterday;
  $period2 = $calender2->makeOneweek();
  $type = 'doubleroom';

  $price_array2 = $calender2->getprice();

  function price2($date,$price_array2){
    if(array_key_exists($date,$price_array2)){
      $price_display2 = "<br/>"."<span>"."¥".$price_array2[$date]."</span>";
      return $price_display2;
    }
  }

  $books_array2 = $calender2->getreserve();

  function reservation2($date,$books_array2){
    $type = 'doubleroom';
    $dbh = new PDO(DSN,USER,PASS);
    $stmt = $dbh->prepare("SELECT * FROM doubleroom WHERE day = :date");
    $stmt->bindValue(':date', $date, PDO::PARAM_STR);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if($rows != null){
      foreach($rows as $row){
      $inventory2 = $row['inventory'];
      }
      } else {
      $inventory2 = 0;
      }

    // var_dump($row);

    if(array_key_exists($date,$books_array2)){

      // var_dump($books_array2);

      if ($books_array2[$date] >= $inventory2){
        $books_display2 = "<br/>"."<span class='norest'>".'-'."</span>";
      } elseif($inventory2 - $books_array2[$date] == 2){
        $books_display2 = "<br/>"."<span class='rest2'>".'<a href="reserve-test.php?dates='. $date .'&type='. $type .'" >'.'残り2室'.'</a>'."</span>";
      } elseif($inventory2 - $books_array2[$date] == 1){
        $books_display2 = "<br/>"."<span class='rest1'>".'<a href="reserve-test.php?dates='. $date .'&type='. $type .'" >'.'残り1室'.'</a>'."</span>";
      } elseif($books_array2[$date] < $inventory2){
        $books_display2 = "<br/>"."<span  class='rest'>".'<a href="reserve-test.php?dates='.$date.'&type='. $type .'">'.'○'.'</a>'."</span>";
      } 
      return $books_display2;
    }
  }

  date_default_timezone_set('Asia/Tokyo');
  // $title = date('Y年n月', $timestamp);

  $doubles = [];
  $double = '';
  // $week3 .= '<td>ダブル</td>';

  foreach( $period2 as $ymd){
    $today = date('Y-m-d');
    $date = $ymd->format('Y-m-d');
    $days = $ymd->format('d');
    $price = price2(date("Y-m-d",strtotime($date)),$price_array2);
    $reservation = reservation2(date("Y-m-d",strtotime($date)),$books_array2);

    if($date < $today){
      $double .= '<td>' .'<span class="norest">'.'-'.'</span>';
    }
    elseif(price2(date("Y-m-d", strtotime($date)),$price_array2) && reservation2(date("Y-m-d", strtotime($date)),$books_array2)) {
      $double .= '<td>' . $days . $price . $reservation ;
    }
    elseif(price2(date("Y-m-d", strtotime($date)),$price_array2) == '') {
      $double .= '<td>'.'<span class="norest">'.'-'.'</span>';
    } else {
      $double .= '<td>'.'<span>'.$days.'日'.'</span>'.'<span class="price">'.$price.'</span>'.'<br>'.'<span class="rest">'.'<a href="reserve-test.php?dates='. $date .'&type='. $type .'">'.'○'.'</a>'.'</span>';
    }
    $double .= '</td>';
  }

  $doubles[] =  $double ;
  $double = '';

$year = date('Y', $timestamp2);
$month = date('m', $timestamp2);

// $weeks3 = $calender->dayOfWeek();

$prev = date('Y-m-d', strtotime('-7 day', $timestamp2));
$next = date('Y-m-d', strtotime('+7 day', $timestamp2));
?>