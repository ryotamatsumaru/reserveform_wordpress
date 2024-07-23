<?php 
require_once(dirname(dirname(__FILE__)). '/prvdtb/pdo-class.php');
$database = new Database();

class listKeep {

  // page-list.phpのリストを表示するメソッド。
  public function keepList(){
    if(isset($_SESSION['date']) == true && isset($_SESSION['night']) == true && isset($_SESSION['number']) == true && isset($_SESSION['type'])){
      $date = $_SESSION['date'];
      $night = $_SESSION['night'];
      $number = $_SESSION['number'];
      $random = $_SESSION['random'];
      $type = $_SESSION['type'];
      $roomtype = $_SESSION['roomtype'];
      $max = count($date);
      $total = array();

      echo '<form method="post" action="https://ro-crea.com/demo_hotel/list_delete">';
      foreach(array_map(null, $date, $night, $number, $roomtype) as $key => [$dates, $nights, $numbers, $roomtypes]){
  
        if($roomtypes == 'singleroom'){
          $roomname = 'シングル';
        } elseif($roomtypes == 'doubleroom') {
          $roomname = 'ダブル';
        }

        //泊数が２泊以上の場合、DBの部屋タイプテーブルから宿泊期間分の日付と料金を呼び出す。
        if(2 <= $nights){
          $newNights = $nights - 1;
          $date1 = $dates;
          $date2 = date('Y-m-d', strtotime('+'.$newNights.' day'.$dates));
          $dbh = new PDO(PDO_DSN,DB_USER,DB_PASSWORD);
          $sql = "SELECT day,price FROM $roomtypes WHERE `day` BETWEEN :date1 AND :date2";
          $ps = $dbh->prepare($sql);
          $ps->bindValue(':date1', $date1, PDO::PARAM_STR);
          $ps->bindValue(':date2', $date2, PDO::PARAM_STR);
          $ps->execute();
          $rows = $ps->fetchAll(PDO::FETCH_ASSOC);
        } else {
          //泊数が1泊の場合、DBの部屋タイプテーブルから1泊分のみ日付と料金を呼び出す。
          $dbh = new PDO(PDO_DSN,DB_USER,DB_PASSWORD);
          $sql = "SELECT day,price FROM $roomtypes WHERE `day` = :date1";
          $ps = $dbh->prepare($sql);
          $ps->bindValue(':date1', $dates, PDO::PARAM_STR);
          $ps->execute();
          $rows = $ps->fetchAll(PDO::FETCH_ASSOC);
        }

        // foreachの中で更にforeachを使ってループを回すことで泊数に応じた一纏まりの予約として表示できる。
        echo '<div id="list">';
        foreach($rows as $row){
          echo '<ul class="list-flex">';
          echo '<li>'.$row['day'].'</li>';
          echo '<li>'.$roomname.'</li>';
          echo '<li>'.'¥'.$row['price'].'</li>';
          echo '<li>'.$nights.'泊'.'</li>';
          echo '<li>'.$numbers.'室'.'</li>';
          echo '<li>'.'¥'.$row['price'] * $numbers.'</li>';
          echo '</ul>';
          $_SESSION['total'] = $row['price'] * $numbers;
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
        // 合計はリストに入ってる全予約の合計料金
        echo '<p class="total">'.'合計: ¥'.$total_price = array_sum($total). '</p>';
        echo '</div>';
        echo '</form>';
      }  
    }
  }

  // page-list.php以外のページでリストを表示したいとき用のメソッド。（上の機能からリスト削除を排除したメソッド）
  public function reserveList(){
    foreach(array_map(null, $_POST['date'], $_POST['night'], $_POST['number'], $_POST['random'], $_POST['type'], $_POST['roomtype']) as $key => [$dates, $nights, $numbers, $randoms, $types, $roomtypes]){
      $dates = Measure::h($dates);
      $nights = Measure::h($nights);
      $numbers = Measure::h($numbers);
      $randoms = Measure::h($randoms);
      $types = Measure::h($types);
      $roomtypes = Measure::h($roomtypes);

      if($roomtypes == 'singleroom'){
        $roomname = 'シングル';
      } elseif($roomtypes == 'doubleroom') {
        $roomname = 'ダブル';
      }

      $newNights = $nights - 1;
      $date1 = $dates;
      $date2 = date('Y-m-d', strtotime('+'.$newNights.' day'.$dates));
      $database = new Database();
      $dbh = Database::getPdo();
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
        echo '<li>'.$numbers.'室'.'</li>';
        echo '<li>'.'¥'.$row['price'] * $numbers.'</li>';
        echo '<input type="hidden" name="date[]" value="',$row['day'],'">';
        echo '<input type="hidden" name="night[]" value="',$nights,'">';
        echo '<input type="hidden" name="price[]" value="',$row['price'],'">';
        echo '<input type="hidden" name="number[]" value="',$numbers,'">';
        echo '<input type="hidden" name="random[]" value="',$randoms,'">';
        echo '<input type="hidden" name="type[]" value="',$types,'">';
        echo '</ul>';
        $_SESSION['total'] = $row['price'] * $numbers;
        $total[] = $_SESSION['total'];
      }
      echo '</div>';
    }
    echo '<p class="total">'.'合計:¥ '.$total_price = array_sum($total).'</p>';
  }

  // control/page-book_check.phpのページで予約を表示したいとき用のメソッド。（リスト削除を排除したメソッド）
  public function reserveStrList(){
    if(isset($_POST['date'])){
      $date = Measure::h($_POST['date']);
      $night = Measure::h($_POST['night']);
      $number = Measure::h($_POST['number']);
      if($_POST['type'] === '0'){
        $roomtype = 'singleroom';
        $roomname = 'シングル';
      } elseif($_POST['type'] === '1') {
        $roomtype = 'doubleroom';
        $roomname = 'ダブル';
      }
      $newNights = $night - 1;
      $date1 = $date;
      $date2 = date('Y-m-d', strtotime('+'.$newNights.' day'.$date));
      $dbh = Database::getPdo();
      $sql = "SELECT day,price FROM $roomtype WHERE `day` BETWEEN :date1 AND :date2";
      $ps = $dbh->prepare($sql);
      $ps->bindValue(':date1', $date1, PDO::PARAM_STR);
      $ps->bindValue(':date2', $date2, PDO::PARAM_STR);
      $ps->execute();
      $rows = $ps->fetchAll(PDO::FETCH_ASSOC);
    }
    
    echo '<div id="list">';
    foreach($rows as $row){
      echo '<ul class="list-flex">';
      echo '<li>'.$row['day'].'</li>';
      echo '<li>'.$roomname.'</li>';
      echo '<li>'.'¥'.$row['price'].'</li>'; 
      echo '<li>'.$night.'泊</li>';
      echo '<li>'.$number.'室'.'</li>';
      echo '<li>'.'¥'.$total[] = $row['price'] * $number.'</li>';
      echo '<input type="hidden" name="date[]" value="',$row['day'],'">';
      echo '<input type="hidden" name="night[]" value="',$night,'">';
      echo '<input type="hidden" name="price[]" value="',$row['price'],'">';
      echo '<input type="hidden" name="number[]" value="',$number,'">';
      echo '</ul>';
    }
    echo '</div>';
    echo '<p class="total">'.'合計:¥ '.$total_price = array_sum($total).'</p>';
  }
} 
?>