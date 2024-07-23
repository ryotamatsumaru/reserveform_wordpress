<?php

  require_once('double-class.php');

  $calender2 = new Double();
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
	  if($date < date('Y-m-d')){
      $date = date('Y-m-d');
    }

    $dbh = Database::getPdo();
    $sql = "SELECT * FROM doubleroom WHERE day = :date";
    $stmt = $dbh->prepare($sql);
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

    // 在庫の残室を表示する
    if(array_key_exists($date,$books_array2)){
      if ($books_array2[$date] >= $inventory2){
        $books_display2 = "<br/>"."<span class='norest'>".'&#xFF0D;'."</span>";
      } elseif($inventory2 - $books_array2[$date] == 2){
        $books_display2 = "<br/>"."<span class='rest2'>".'<a href="https://ro-crea.com/demo_hotel/reserve_date?dates='. $date .'&type='.$type.'" >'.'残り2室'.'</a>'."</span>";
      } elseif($inventory2 - $books_array2[$date] == 1){
        $books_display2 = "<br/>"."<span class='rest1'>".'<a href="https://ro-crea.com/demo_hotel/reserve_date?dates='. $date .'&type='.$type.'" >'.'残り1室'.'</a>'."</span>";
      } elseif($books_array2[$date] < $inventory2){
        $books_display2 = "<br/>"."<span  class='rest'>".'<a href="https://ro-crea.com/demo_hotel/reserve_date?dates='. $date .'&type='.$type.'">'.'○'.'</a>'."</span>";
      } 
      return $books_display2;
    }
    elseif( $inventory2 == 0){
      $books_display2 = "<br/>"."<span class='setzerostock'>".'&#xFF0D;'."</span>";
      return $books_display2;
    }
  }

  $doubles = [];
  $double = '';

  // 在庫の残室を表示する
  foreach( $period2 as $ymd){
    $today = date('Y-m-d');
    $date = $ymd->format('Y-m-d');
    $days = $ymd->format('d');
    $price = price2(date("Y-m-d",strtotime($date)),$price_array2);
    $reservation = reservation2(date("Y-m-d",strtotime($date)),$books_array2);

    if($date < $today){
      $double .= '<td>' .'<span class="past">'. '&#xFF0D;'.'</span>';
    }
    elseif(price2(date("Y-m-d", strtotime($date)),$price_array2) && reservation2(date("Y-m-d", strtotime($date)),$books_array2)) {
      $double .= '<td>' . $days . $price . $reservation ;
    }
    elseif(price2(date("Y-m-d", strtotime($date)),$price_array2) == '') {
      $double .= '<td>' .'<span class="noset">'. '&#xFF0D;' .'</span>';
    } else {
      $double .= '<td>'.'<span>'.$days .'</span>'.'<span class="price">'. $price .'</span>'.'<br>'.'<span class="rest">'.'<a href="https://ro-crea.com/demo_hotel/reserve_date?dates='. $date .'&type='.$type.'">'.'○'.'</a>'.'</span>';
    }
    $double .= '</td>';
  }

  $doubles[] =  $double ;
  $double = '';

?>