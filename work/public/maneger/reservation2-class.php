<?php
class Search
{
  // public $select_list = array( 
  //   ['check' => 'booking', 'text' => '予約中'],
  //   ['check' => 'cancel', 'text' => 'キャンセル'],
  // );


  public function selectArray(){
    $select_list = array( 
      ['check' => 'booking', 'text' => '予約中'],
      ['check' => 'cancel', 'text' => 'キャンセル'],
    );
    return $select_list;
  }

  public function selectType(){
    $select_type = array( 
      ['check' => 'All', 'text' => '全て'],
      ['check' => '0', 'text' => 'シングル'],
      ['check' => '1', 'text' => 'ダブル'],
    );
    return $select_type;
  }

  public function SearchBook(){
    $select_list = array( 
      ['check' => 'booking', 'text' => '予約中'],
      ['check' => 'cancel', 'text' => 'キャンセル'],
    );
    
    if(isset($_POST['date'])){
    $day = $_POST['date']; 
    $select = $_POST['select'];
    $dbh = new PDO(DSN,USER,PASS);
    $sql = "SELECT * FROM $select WHERE day = :date";
    if($_POST['type'] == '0'){
      $sql.= " AND type = 0";
    } 
    elseif($_POST['type'] == '1'){
      $sql.= " AND type = 1";
    }
    $ps = $dbh->prepare($sql);
    $ps->bindValue(':date', $day, PDO::PARAM_STR);
    $ps->execute();
    $rows = $ps->fetchAll(PDO::FETCH_ASSOC);
    return $rows;
    }
  }
}



?>