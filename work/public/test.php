<?php


require_once(__DIR__ . '/../app/config.php');
require_once(__DIR__ . '/../app/measure-class.php');

use MyApp\database;
// $dbh = Database->getPdo2();

$test8 = 'ryota0918';

echo $new_pass = password_hash($test8, PASSWORD_DEFAULT,['cost' => 8]);
echo'<br>';
// $dbh = new PDO(DSN,USER,PASS);

// $list = new listKeep();
// $check = new Check();

$dbh = new PDO(DSN,USER,PASS);
$sql = "SELECT * FROM maneger WHERE id = 4";
$ps = $dbh->prepare($sql);
$ps->execute();
$rows = $ps->fetchAll(PDO::FETCH_ASSOC);

foreach($rows as $row){
  $password2 = $row['password'];
}

$password = 'roma0918';

if(password_verify($password,$password2)){
  echo '成功';
}


// $date = array();
$date[] = '2024-06-12';
$date[] = '2024-06-13';
$date[] = '2024-06-14';
$date[] = '2024-06-15';
$date[] = '2024-06-16';
$date[] = '2024-06-17';
$date[] = '2024-06-18';

$type = '0';


foreach($date as $dates){

$dbh = new PDO(DSN,USER,PASS);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$sql = "SELECT COUNT(*),SUM(member),day FROM (SELECT * FROM booking WHERE day = :date AND type =:type) as booktotal GROUP BY day";
$ps = $dbh->prepare($sql);
$ps->bindValue(':date', $dates, PDO::PARAM_STR);
$ps->bindValue(':type', $type, PDO::PARAM_INT);
$ps->execute();
$rows = $ps->fetchAll(PDO::FETCH_ASSOC);

foreach($rows as $row){
  if($row['SUM(member)'] == '0'){
    $sum = '0';
  } {
    $sum = $row['SUM(member)'];
  }
  echo $sum;
  echo '<br>';
  echo $row['day'];
  echo '<br>';
}
}


// $day_out = strtotime((string)$row['day']);
// $book_out = (string)$row['SUM(member)'];





?>