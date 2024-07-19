<?php

session_start();

require_once(__DIR__ . '/../app/config.php');
use MyApp\database;
$dbh = new PDO(DSN,USER,PASS);

$date = $_SESSION['date'];
$night = $_SESSION['night'];
$member = $_SESSION['member'];


?>

<body>
<?php 
if(isset($_SESSION['date']) == true && isset($_SESSION['night']) == true && isset($_SESSION['member']) == true){
  $date = $_SESSION['date'];
  $night = $_SESSION['night'];
  $member = $_SESSION['member'];

  $max = count($date);

  $total = array();

  // echo '<form method="post" action="test6.php">';
  echo '<form method="post" action="delete-list.php">';
  foreach(array_map(null, $date, $night, $member) as $key => [$dates, $nights, $members])
  {
  $newNights = $nights - 1;
  echo $date1 = $dates;
  echo '<br>';
  $members;
  echo $date2 = date('Y-m-d', strtotime('+'.$newNights.' day'.$dates));
  echo '<br>';
  echo $key;
  echo '<br>';
  $sql = "SELECT day,price FROM stock WHERE `day` BETWEEN '$date1' AND '$date2'";
  $ps = $dbh->prepare($sql);
  $ps->execute();
  $rows = $ps->fetchAll(PDO::FETCH_ASSOC);

  // var_dump($date);

  echo '<div style="border: solid 1px;">';
  // $_SESSION['rows'] = $rows;
  foreach($rows as $row){
  echo $row['day'];
  echo '&nbsp;';
  echo $row['price'];
  echo '&nbsp;';
  echo $members;
  echo '&nbsp;';
  echo $row['price'] * $members;
  echo '&nbsp;';
  echo '<br>';
  $_SESSION['total'] = $row['price'] * $members;
  $total[] = $_SESSION['total'];
  }

  echo '<input type="checkbox" name="delete',$key,'">';
  echo '</div>';
  }
  if($total == ''){
  echo $total_price = array_sum($total);
  echo '<br>';
  }

  echo '<input type="submit">';
  echo '</form>';

  // // var_dump($total);
  if(isset($_SESSION['']))
  echo $total_price = array_sum($total);
  echo '<br>';

  print '<a href="calender-res.php">予約状況に戻る</a>';
} 
?>
</body>