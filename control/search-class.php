<?php

require_once('search-class.php');
require_once(dirname(dirname(__FILE__)). '/prvdtb/pdo-class.php');
require_once(dirname(dirname(__FILE__)). '/measure/measure-class.php');

class Search
{
  // page-search.phpの予約状況の検索用配列を返すメソッド
  public function selectArray(){
    $select_list = array( 
      ['check' => 'booking', 'text' => '予約中'],
      ['check' => 'cancel', 'text' => 'キャンセル'],
    );
    return $select_list;
  }

  // page-search.phpの部屋タイプ用の検索用配列を返すメソッド
  public function selectType(){
    $select_type = array( 
      ['check' => 'All', 'text' => '全て'],
      ['check' => '0', 'text' => 'シングル'],
      ['check' => '1', 'text' => 'ダブル'],
    );
    return $select_type;
  }
  
  // 日付と予約状況の変数を受け取り、bookingかcancelどちらか一致するテーブルと該当する日付から予約情報を返すメソッド
  public function SearchBook(){
    if(!empty($_POST['date'])){
      $day = Measure::h($_POST['date']); 
	  } else {
	    $day = date('Y-m-d');
   	}
	  
    // sql文のテーブル名に該当するため外部から不正な値を受け付けないように文字列を変数に代入する
    if(isset($_POST['select'])){
	    if($_POST['select'] == 'booking'){
	      $select = 'booking';
	    } 
	    elseif($_POST['select'] == 'cancel') {
	      $select = 'cancel';
	    }
	  } else {
      $select = 'booking';	
	  }

    $dbh = Database::getPdo();
    $sql = "SELECT * FROM $select WHERE day = :date ORDER BY reserve_date DESC";
    // substr_replace関数でsql文に’AND type = 部屋タイプ’を追加することで部屋タイプでの条件検索を行う
	  if(isset($_POST['type'])){
	    if($_POST['type'] == '0'){
        $sql = substr_replace($sql, ' AND type = 0 ', 39, 0);
      } 
      elseif($_POST['type'] == '1'){
        $sql = substr_replace($sql, ' AND type = 1 ', 39, 0);
      }
	  }
    $ps = $dbh->prepare($sql);
    $ps->bindValue(':date', $day, PDO::PARAM_STR);
    $ps->execute();
    $rows = $ps->fetchAll(PDO::FETCH_ASSOC);
    return $rows;
  }
}

?>