<?php

class Measure
{
  
  // エスケープ処理をするためのメソッド
  public static function h($str)
  {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
  }
  
  // 管理画面用のCSRF対策token発行メソッド
  public static function create()
  {
    if(!isset($_SESSION['token'])) {
      $_SESSION['token'] = bin2hex(random_bytes(32));
    }
  }

  // 管理画面用のCSRF対策token判定メソッド
  public static function validate()
  {
    if (empty($_SESSION['token']) || $_SESSION['token'] !== filter_input(INPUT_POST, 'token')) 
      {
      exit('無効なリクエストです。');
      }
  }
  
  // 予約フォーム・お客様用ログインフォームのCSRF対策token発行メソッド
  public static function cus_create()
  {
    if(!isset($_SESSION['cus_token'])) {
      $_SESSION['cus_token'] = bin2hex(random_bytes(32));
    }
  }

  // 予約フォーム・お客様用ログインフォームのCSRF対策token判定メソッド
  public static function cus_validate()
  {
    if (
      empty($_SESSION['cus_token']) || $_SESSION['cus_token'] !== filter_input(INPUT_POST, 'cus_token')) 
      {
      exit('無効なリクエストです。');
      }
  }
	

  // POSTで受け取った部屋タイプとデータベースのテーブル名が一致した場合、変数に$roomtypeにテーブル名を文字列で直接代入するメソッド。（外部から不正な値を入力させないため。）
  public function getRoomtype()
  {
   if($_POST['type'] === 'singleroom'){
      $roomtype = 'singleroom';
    } 
    elseif($_POST['type'] === 'doubleroom') 
    {
      $roomtype = 'doubleroom';
    }
    return $roomtype;
  }

  // POSTで受け取った部屋タイプとデータベースのテーブル名が一致した場合、変数に$typeに部屋タイプの値を数字で直接代入するメソッド。（外部から不正な値を入力させないため。）
  public function getType()
  {
   if($_POST['type'] === 'singleroom'){
    $type = '0';
   } 
    elseif($_POST['type'] === 'doubleroom') 
   {
    $type = '1';
   }
    return $type;
  }
}

?>