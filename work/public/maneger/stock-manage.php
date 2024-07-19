<?php


$dsn='mysql:host=db;dbname=reserve;charset=utf8';
$user='testuser';
$pass='testpass';     


function getprice(){
  $dsn='mysql:host=db;dbname=reserve;charset=utf8';
  $user='testuser';
  $pass='testpass';     
  $dbh = new PDO($dsn,$user,$pass);
  $prices = $dbh->query("SELECT day,price FROM stock");
  $price_display = array();

  foreach($prices as $out){
    $day_out = strtotime((string) $out['day']);
    $price_out = (string) $out['price'];
    $price_display[date('Y-m-d', $day_out)] = $price_out;
  }
  return $price_display;
}
$price_array = getprice();

function price($date,$price_array){
  if(array_key_exists($date,$price_array)){
    $price_display = $price_array[$date];
    return $price_display;
  }
}


function getstock(){
  $dsn='mysql:host=db;dbname=reserve;charset=utf8';
  $user='testuser';
  $pass='testpass';     
  $dbh = new PDO($dsn,$user,$pass);
  $stocks = $dbh->query("SELECT day,inventory FROM stock");
  $stock_display = array();

  foreach($stocks as $out){
    $day_out = strtotime((string) $out['day']);
    $stock_out = (string) $out['inventory'];
    $stock_display[date('Y-m-d', $day_out)] = $stock_out;
  }
  return $stock_display;
}
$stock_array = getstock();

function stock($date,$stock_array){
  if(array_key_exists($date,$stock_array)){
    $stock_display = $stock_array[$date];
    return $stock_display;
  }
}


if(isset($_GET['ym'])){
  $ym = $_GET['ym'];
  $timestamp = strtotime($ym);
} else {
  $ym = date('Y-m');
  $d = date('-d');
  // $ym = date('Y-m', strtotime($_POST['date']));
  // $d = date('-d', strtotime($_POST['date']));
  $timestamp = strtotime($ym. $d);
  $selectday= date('Y-m-d', $timestamp);
  $afterday = date('Y-m-d', strtotime('+7 day', $timestamp));
}

function getreserve(){
  $dsn='mysql:host=db;dbname=reserve;charset=utf8';
  $user='testuser';
  $pass='testpass';     
  $dbh = new PDO($dsn,$user,$pass);
  $books_display = array();

  if(isset($_GET['ym'])){
    $ym = $_GET['ym'];
    $timestamp = strtotime($ym);
  } else {
    $ym = date('Y-m');
    $d = date('-d');
    // $ym = date('Y-m', strtotime($_POST['date']));
    // $d = date('-d', strtotime($_POST['date']));
    $timestamp = strtotime($ym. $d);
  }

  $selectday= date('Y-m-d', $timestamp);
  echo '<br>';
  $afterday = date('Y-m-d', strtotime('+7 day', $timestamp));
  echo '<br>';
  echo '<br>';

  $start = new DateTime($selectday);
  $end = new DateTime($afterday);
  $interval = new DateInterval('P1D');

  $period =new DatePeriod($start, $interval, $end);
  foreach ($period as $ymd){
    $date = $ymd->format('Y-m-d');
    // echo "<br>";
    $sql = "SELECT COUNT(*),SUM(member),day FROM (SELECT * FROM booking WHERE day = '$date') as t";
    $ps = $dbh->prepare($sql);
    $ps->execute();
    $row = $ps->fetch(PDO::FETCH_ASSOC);
    $day_out = strtotime((string)$row['day']);
    $book_out = (string)$row['SUM(member)'];
    $books_display[date('Y-m-d', $day_out)] = $book_out;
  }

  return $books_display;
}
  $books_array = getreserve();
// var_dump($books_array);

function reservation($date,$books_array){
  $dsn='mysql:host=db;dbname=reserve;charset=utf8';
  $user='testuser';
  $pass='testpass';     
  $dbh = new PDO($dsn,$user,$pass);
  $stmt = $dbh->prepare("SELECT * FROM stock WHERE day = '$date'");
  $stmt->execute();
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  foreach($rows as $row){
  $row['inventory'];
  }

  if(array_key_exists($date,$books_array)){
    if ($row['inventory'] - $books_array[$date] <= 0){
      $books_display = "<br/>"."<span>".'残室: '."</span>"."<span>".'0'."</span>".'<input type="hidden" name="date[]" value="'.$date.'" >'."<br/>";
    } else {
      $rest = $row['inventory'] - $books_array[$date];
      $books_display = "<br/>"."<span>".'残室: '."</span>"."<span>".$rest."</span>".'<input type="hidden" name="date[]" value="'.$date.'" >'."<br/>";
    }
    return $books_display;
  } else {
      $books_display = "<br/>"."<span>".'残室: '."</span>"."<span>".'100'."</span>".'<input type="hidden" name="date[]" value="'.$date.'" >'."<br/>";
      return $books_display;
  }
}

date_default_timezone_set('Asia/Tokyo');

$title = date('Y年n月', $timestamp);
// echo '<br>';

$values = [];
$value = '';
$rests = [];
$rest = '';

$selectday= date('Y-m-d', $timestamp);
$afterday = date('Y-m-d', strtotime('+7 day', $timestamp));

$start = new DateTime($selectday);
$end = new DateTime($afterday);
$interval = new DateInterval('P1D');

$period =new DatePeriod($start, $interval, $end);

foreach( $period as $ymd){
  $today = date('Y-m-d');
  $date = $ymd->format('Y-m-d');
  $days = $ymd->format('d');
  // echo '<br>';
  $price = price(date("Y-m-d",strtotime($date)),$price_array);
  $stock = stock(date("Y-m-d", strtotime($date)), $stock_array);
  $reservation = reservation(date("Y-m-d",strtotime($date)),$books_array);
  // $recept = $_POST['number'];
  if($date < $today){
    $value .= '<td>'.'¥'.$price .'<br>'.'<span>'.'-'.'</span>';
    $rest .= '<td>'.$reservation .'<br>'.'<span>'.'-'.'</span>';
  }
  elseif(price(date("Y-m-d", strtotime($date)),$price_array) && reservation(date("Y-m-d", strtotime($date)),$books_array)) {
    $value .= '<td>'.'¥'.$price .'<br>'.'<span>'.'¥'.'<input class="set_value" type="text" name="update_value[]" value="'.$price.'" >'.'</span>';
    $rest .= '<td>'.$reservation.'<span>'.'<input class="set_stock" type="text" name="update_stock[]" value="'.$stock.'">'.'室'.'</span>';
  }
  elseif(price(date("Y-m-d", strtotime($date)),$price_array) == '') {
    $value .= '<td>' . '金額未設定'.'<br>'.'<span>'.'¥'.'<input class="set_value" type="text" name="set_value[]" >'.'</span>';
    $rest .= '<td>'.$reservation.'<span>'.'<input class="set_stock" type="text" name="set_stock[]" value="'.$stock.'">'.'室'.'</span>';
  } 
  // else {
  //   $value .= '<td>'.'<span>'.'¥'.'<input class="set_value" type="text" name="update_value[]" value="'.$price.'">' .'<br>'.'<span>'.'<input class="set_stock" type="text" name="update_stock[]" value="'.$stock.'">'.'室'.'</span>';
  //   $rest .= '<td>'.$reservation.'<span>'.'<input class="set_stock" type="text" name="update_stock[]" value="'.$stock.'">'.'室'.'</span>';
  // }
  $value .= '</td>';
  $rest .= '</td>';
}

$values[] =  $value;
$value = '';

$rests[] =  $rest;
$rest = '';

$year = date('Y', $timestamp);
$month = date('m', $timestamp);

$weeks2 = [];
$week2 = '';

$wday=array("日","月","火","水","木","金","土");

foreach($period as $ymd){
  $date = $ymd->format('Y-m-d');
  // echo '<br>';
  $year = $ymd->format('Y');
  // echo '<br>';
  $month = $ymd->format('m');
  // echo '<br>';
  $day = $ymd->format('d');
  // echo '<br>';

  $timestamp2 = mktime(0,0,0,$month,$day,$year);
  $w = $wday[date("w", $timestamp2)];
  $date2 = date("m/d", $timestamp2);
  $week2 .= '<th class="wbox">'. $date2 .'('.$w.')';
  $week2 .= '</th>';

  $weeks2[] =  $week2 ;
  $week2 = '';
}

$prev = date('Y-m-d', strtotime('-7 day', $timestamp));
// echo '<br>';

$next = date('Y-m-d', strtotime('+7 day', $timestamp));
// echo '<br>';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <link rel= "stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <style>
    .container{
      margin-top: 80px;
    }
    th{
      height: 30px;
      text-align: center;
    }
    td{
      /* border: solid 1px; */
      height: 100px;
    }
    .today{
      background: aqua;
      font-size: 20px;
      color: black;
    }
    th:nth-of-type(1){
      color: red;
    }
    th:nth-of-type(7){
      color: blue;
    }
    .date{
      font-size: 20px;
      color: black;
    }
    .set_value{
      width: 68px;
    }
    .set_stock{
      width: 40px;
    }


  </style>
<body>
  <div class="container">
    <form method="post" action="stock-check.php">
    <h3><a href="?ym=<?php echo $prev;?>">&lt;</a><?php echo $title; ?><a href="?ym=<?php echo $next;?>">&gt;</a></h3>
    <table class="table table-bordered">
      <tr>
      <?php foreach($weeks2 as $week2){
      echo $week2;
      }
      ?>
      </tr>
      <?php 
        foreach($values as $value){
          echo $value;
        }
      ?>
      <tr>
      <?php 
        foreach($rests as $rest){
          echo $rest;
        }
      ?>
      </tr>
    </table>
    <input type="submit" value="確定">
    </form>
  </div>
</body>