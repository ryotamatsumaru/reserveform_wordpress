<?php

session_start();

  $max = count($_SESSION['date']);

  for($i=$max; 0<=$i; $i--){
    if (isset($_POST['delete'.$i]) == true)
    {
      array_splice($_SESSION['date'], $i,1);
      array_splice($_SESSION['night'], $i,1);
      array_splice($_SESSION['member'], $i,1);
      array_splice($_SESSION['random'], $i,1);
      array_splice($_SESSION['type'], $i,1);
      array_splice($_SESSION['roomtype'], $i,1);
    }
  }

  // session_destroy();
    
echo '<a href="list.php">戻る</a>';


?>