<?php
require_once('/../work/app/config.php');
use MyApp\database; 


session_start();
session_regenerate_id(true);

if(isset($_SESSION['login'])==false){
  print 'ログインされてません <br>';
  print '<a href="maneger-login.html">ログイン画面へ</a>';
  exit();
}

?>

<body>
<?php
try{

  $dbh = new PDO(DSN,USER,PASS);

  echo $date = $_POST['date'];

  $sql = "SELECT * FROM booking WHERE day = '$date'";

  $ps = $dbh->prepare($sql);
  // $ps->bindValue(':date', $_POST['date'], PDO::PARAM_INT);
  $ps->execute();

  $dbh = null;
  
  $csv = 'ID,注文日時,会員番号,お名前,メール,郵便番号,住所,TEL,商品コード,商品名,価格,数量';
  // $csv.= "¥n";
  while(true)
  {
    $rows = $ps->fetch(PDO::FETCH_ASSOC);
    if($rows == false)
    {
      break;
    }
    $csv.= $rows['ban'];
    $csv.= ',';
    $csv.= $rows['day'];
    $csv.= ',';
    $csv.= $rows['name'];
    $csv.= ',';
    $csv.= $rows['member'];
    $csv.= ',';
    $csv.= $rows['price'];
    $csv.= ',';
    $csv.= $rows['gender'];
    $csv.= ',';
    $csv.= $rows['mail'];
    $csv.= ',';
    $csv.= $rows['tel'];
  }

  // print nl2br($csv);

  $file = fopen('./reserve-list.csv','w');
  // $csv = mb_convert_encoding($csv,'SJIS','UTF-8');
  $csv = mb_convert_encoding($csv, "UTF-8", "ASCII,JIS,UTF-8,CP51932,SJIS-win");
  fputs($file, $csv);
  fclose($file);
}
catch (Exception $e)
{
  print 'ただいま障害により大変ご迷惑をおかけします';
  exit();
}
?>
<br>

<a href="reserve-list.csv">注文データのダウンロード</a><br>
<br>
<a href="reserve-downroad.php">日付選択へ</a><br>
<br>
<a href="../maneger/menu.php">トップメニューへ</a>

</body>
