<?php 

require_once('/../work/app/config.php');
use MyApp\database;

class listKeep {

public function keepList(){

if(isset($_SESSION['date']) == true && isset($_SESSION['night']) == true && isset($_SESSION['member']) == true && isset($_SESSION['type'])){
  $date = $_SESSION['date'];
  $night = $_SESSION['night'];
  $member = $_SESSION['member'];
  $random = $_SESSION['random'];
  $type = $_SESSION['type'];
  $roomtype = $_SESSION['roomtype'];
  $max = count($date);
  $total = array();
  
  echo '<form method="post" action="delete-list.php">';
  foreach(array_map(null, $date, $night, $member, $roomtype) as $key => [$dates, $nights, $members, $roomtypes]){
  if($roomtypes == 'stock'){
    $roomname = 'シングル';
  } elseif($roomtypes == 'doubleroom') {
    $roomname = 'ダブル';
  }
  if(2 <= $nights){
  $newNights = $nights - 1;
  $date1 = $dates;
  $date2 = date('Y-m-d', strtotime('+'.$newNights.' day'.$dates));
  $dbh = new PDO(DSN,USER,PASS);
  $sql = "SELECT day,price FROM $roomtypes WHERE `day` BETWEEN :date1 AND :date2";
  $ps = $dbh->prepare($sql);
  $ps->bindValue(':date1', $date1, PDO::PARAM_STR);
  $ps->bindValue(':date2', $date2, PDO::PARAM_STR);
  $ps->execute();
  $rows = $ps->fetchAll(PDO::FETCH_ASSOC);
  } else {
    $dbh = new PDO(DSN,USER,PASS);
    $sql = "SELECT day,price FROM $roomtypes WHERE `day` = :date1";
    $ps = $dbh->prepare($sql);
    $ps->bindValue(':date1', $dates, PDO::PARAM_STR);
    $ps->execute();
    $rows = $ps->fetchAll(PDO::FETCH_ASSOC);
  }

  // var_dump($date);
  echo '<div id="list">';
  foreach($rows as $row){
  echo '<ul class="list-flex">';
  echo '<li>'.$row['day'].'</li>';
  echo '<li>'.$roomname.'</li>';
  echo '<li>'.'¥'.$row['price'].'</li>';
  echo '<li>'.$nights.'泊'.'</li>';
  echo '<li>'.$members.'室'.'</li>';
  echo '<li>'.'¥'.$row['price'] * $members.'</li>';
  echo '</ul>';
  $_SESSION['total'] = $row['price'] * $members;
  $total[] = $_SESSION['total'];
  }
  echo '<span class="delete-tag">'.'<input type="checkbox" name="delete',$key,'">'.'削除選択'.'</span>';
  echo '</div>';
  }
  if(!$total == ''){
  echo '<div class="delete-form wrapper">';
  echo '<input class="delete" type="submit" value="リストから削除">';
  echo '</div>';
  echo '<div class="total-area wrapper">';
  echo '<p class="total">'.'合計: ¥'.$total_price = array_sum($total). '</p>';
  echo '</div>';
  echo '</form>';
  }
}
}


public function reserveList(){
foreach(array_map(null, $date, $night, $member, $random, $roomtype) as $key => [$dates, $nights, $members, $randoms, $roomtypes])
{
$newNights = $nights - 1;
$date1 = $dates;
$send_date[] = $dates;
$members;
echo $roomtypes;
$send_member[] = $members;
$date2 = date('Y-m-d', strtotime('+'.$newNights.' day'.$dates));
$sql = "SELECT day,price FROM $roomtypes WHERE `day` BETWEEN :date1 AND :date2";
$ps = $dbh->prepare($sql);
$ps->bindValue(':date1', $date1, PDO::PARAM_STR);
$ps->bindValue(':date2', $date2, PDO::PARAM_STR);
$ps->execute();
$rows = $ps->fetchAll(PDO::FETCH_ASSOC);

echo '<div style="border: solid 1px;">';
foreach($rows as $row){
  echo $row['day'];
  echo '<input type="hidden" name="date[]" value="',$row['day'],'">';
  echo '<input type="hidden" name="night[]" value="',$nights,'">';
  echo '&nbsp;';
  echo '¥'.$row['price']; 
  echo '<input type="hidden" name="price[]" value="',$row['price'],'">';
  echo '&nbsp;';
  echo $members.'室';
  echo '<input type="hidden" name="member[]" value="',$members,'">';
  echo '<input type="text" name="random[]" value="',$randoms,'">';
  echo '<input type="hidden" name="type[]" value="',$types,'">';
  echo '&nbsp;';
  echo '¥'.$row['price'] * $members;
  echo '&nbsp;';
  $_SESSION['total'] = $row['price'] * $members;
  echo '¥'.$total[] = $_SESSION['total'];
  echo '<br>';
}
echo '</div>';
echo '<br>';
}
echo $total_price = array_sum($total);
}

} 
?>