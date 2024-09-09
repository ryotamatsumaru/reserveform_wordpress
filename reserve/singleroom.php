<?php

  require_once('single-class.php');

  require_once(dirname(dirname(__FILE__)). '/prvdtb/pdo-class.php');
  $dbh = Database::getPdo();

  $calender = new Single();
  $setWeek = $calender->setWeek();
  $timestamp = $calender->timestamp;
  $selectday = $calender->selectday;
  $afterday = $calender->afterday;
  $period = $calender->makeOneweek();

  $type = 'singleroom';

  $price_array = $calender->getprice();

  function price($date,$price_array){
    if(array_key_exists($date,$price_array)){
      $price_display = "<br/>"."<span>"."¥".$price_array[$date]."</span>";
      return $price_display;
    }
  }

  $books_array = $calender->getreserve();

  function reservation($date,$books_array){
    $type = 'singleroom';
	  if($date < date('Y-m-d')){
      $date = date('Y-m-d');
    }
    $dbh = Database::getPdo();
    $stmt = $dbh->prepare("SELECT * FROM singleroom WHERE day = :date");
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
    $type = 'singleroom';

    // 在庫の残室を表示する
    if(array_key_exists($date,$books_array)){
      if($inventory - $books_array[$date] == 2){
        $books_display = "<br/>"."<span class='rest2'>".'<a href="https://ro-crea.com/demo_hotel/reserve_date?dates='. $date .'&type='.$type.'">'.'残り2室'.'</a>'."</span>";
      } elseif($inventory - $books_array[$date] == 1){
        $books_display = "<br/>"."<span class='rest1'>".'<a href="https://ro-crea.com/demo_hotel/reserve_date?dates='. $date .'&type='.$type.'">'.'残り1室'.'</a>'."</span>";
      } elseif($books_array[$date] < $row['inventory']){
        $books_display = "<br/>"."<span class='rest'>".'<a href="https://ro-crea.com/demo_hotel/reserve_date?dates='. $date .'&type='.$type.'">'.'○'.'</a>'."</span>";
      } else {
        $books_display = "<br/>"."<span class='norest'>".'&#xFF0D;'."</span>";
      }
      return $books_display;
    }
    elseif( $inventory == 0){
      $books_display = "<br/>"."<span class='setzerostock'>".'&#xFF0D;'."</span>";
      return $books_display;
    }
  }

  $title = date('Y年n月', $timestamp);
  $singles = [];
  $single = '';

  // 在庫の残室を表示する
  foreach( $period as $ymd){
    $today = date('Y-m-d');
    $date = $ymd->format('Y-m-d');
    $days = $ymd->format('d');
    $price = price(date("Y-m-d",strtotime($date)),$price_array);
    $reservation = reservation(date("Y-m-d",strtotime($date)),$books_array);
   
    if($date < $today){
      $single .= '<td>' . '<span class="past">'.'&#xFF0D;'.'</span>';
    }
    elseif(price(date("Y-m-d", strtotime($date)),$price_array) && reservation(date("Y-m-d", strtotime($date)),$books_array)) {
      $single .= '<td>' . $days . $price . $reservation ;
    }
    elseif(price(date("Y-m-d", strtotime($date)),$price_array) == '') {
      $single .= '<td>' . '<span class="noset">'. '&#xFF0D;' . '</span>';
    } 
    else 
    {
      $single .= '<td>'.'<span>'.$days .'</span>'.'<span class="price">'. $price .'</span>'.'<br>'.'<span class="rest">'.'<a href="https://ro-crea.com/demo_hotel/reserve_date?dates='. $date .'&type='.$type.'">'.'○'.'</a>'.'</span>';
    }
    $single .= '</td>';
  }

  $singles[] =  $single ;
  $single = '';

  $year = date('Y', $timestamp);
  $month = date('m', $timestamp);
  
  $weeks = $calender->dayOfWeek();

  $prev = date('Ymd', strtotime('-7 day', $timestamp));
  $next = date('Ymd', strtotime('+7 day', $timestamp));
?>
