<?php

class Measure
{
  
  public static function h($str)
  {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
  }

  public static function create()
  {
    if(!isset($_SESSION['token'])) {
      $_SESSION['token'] = bin2hex(random_bytes(32));
    }
  }

  public static function validate()
  {
    if (
      empty($_SESSION['token']) || $_SESSION['token'] !== filter_input(INPUT_POST, 'token')) 
      {
      exit('無効なリクエストです。');
      }
  }

  // public function getPost(){
  //   $date = Measure::h($_POST['dates']);
  //   $night = Measure::h($_POST['night']);
  //   $member = Measure::h($_POST['member']);
  // }
  public function getRoomtype(){
    if($_POST['type'] === 'stock'){
      echo $roomtype = 'stock';
    } 
    elseif($_POST['type'] === 'doubleroom') 
    {
      echo $roomtype = 'doubleroom';
    }
    return $roomtype;
  }

  public function getType(){
    if($_POST['type'] === 'stock'){
      echo $type = '0';
    } 
    elseif($_POST['type'] === 'doubleroom') 
    {
      echo $type = '1';
    }
    return $type;
  }

}


?>