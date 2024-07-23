<?php
//     Template Name: list_delete
//     Template Post Type: page
//     Template Path: reserve/

session_start();
if(!empty($_SESSION['date'])){
  $max = count($_SESSION['date']);

  // page-list.phpで選択した予約（SESSION）を削除する
  for($i=$max; 0<=$i; $i--){
    if (isset($_POST['delete'.$i]) == true)
    {
      array_splice($_SESSION['date'], $i,1);
      array_splice($_SESSION['night'], $i,1);
      array_splice($_SESSION['number'], $i,1);
      array_splice($_SESSION['random'], $i,1);
      array_splice($_SESSION['type'], $i,1);
      array_splice($_SESSION['roomtype'], $i,1);
    }
  }

  header('Location:https://ro-crea.com/demo_hotel/list');
  exit();
} else {
  header('Location:https://ro-crea.com/demo_hotel/list');
  exit();
}
?>