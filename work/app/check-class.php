<?php

// require('../app/config.php');
// use MyApp\database;
require_once(__DIR__ . '/../app/measure-class.php');

class Check{

public function reserveList(){

foreach(array_map(null, $_POST['date'], $_POST['night'], $_POST['member'], $_POST['random'], $_POST['type'], $_POST['roomtype']) as $key => [$dates, $nights, $members, $randoms, $types, $roomtypes])
{
$dates = Measure::h($dates);
$nights = Measure::h($nights);
$members = Measure::h($members);
$randoms = Measure::h($randoms);
$types = Measure::h($types);
$roomtypes = Measure::h($roomtypes);
if($roomtypes == 'stock'){
  $roomname = 'シングル';
} elseif($roomtypes == 'doubleroom') {
  $roomname = 'ダブル';
}
$newNights = $nights - 1;
$date1 = $dates;
$date2 = date('Y-m-d', strtotime('+'.$newNights.' day'.$dates));
$dbh = new PDO(DSN,USER,PASS);
$sql = "SELECT day,price FROM $roomtypes WHERE `day` BETWEEN :date1 AND :date2 ";
$ps = $dbh->prepare($sql);
$ps->bindValue(':date1', $date1, PDO::PARAM_STR);
$ps->bindValue(':date2', $date2, PDO::PARAM_STR);
$ps->execute();
$rows = $ps->fetchAll(PDO::FETCH_ASSOC);

echo '<div id="list">';
foreach($rows as $row){
  echo '<ul class="list-flex">';
  echo '<li>'.$row['day'].'</li>';
  echo '<li>'.$roomname.'</li>';
  echo '<li>'.'¥'.$row['price'].'</li>'; 
  echo '<li>'.$nights.'泊</li>';
  echo '<li>'.$members.'室'.'</li>';
  echo '<li>'.'¥'.$row['price'] * $members.'</li>';
  echo '<input type="hidden" name="date[]" value="',$row['day'],'">';
  echo '<input type="hidden" name="night[]" value="',$nights,'">';
  echo '<input type="hidden" name="price[]" value="',$row['price'],'">';
  echo '<input type="hidden" name="member[]" value="',$members,'">';
  echo '<input type="hidden" name="random[]" value="',$randoms,'">';
  echo '<input type="hidden" name="type[]" value="',$types,'">';
  echo '</ul>';
  $_SESSION['total'] = $row['price'] * $members;
  $total[] = $_SESSION['total'];
}
echo '</div>';
}
echo '<p class="total">'.'合計: ¥'.$total_price = array_sum($total).'</p>';
}
}

?>