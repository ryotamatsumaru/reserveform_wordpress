<?php

require_once('/../work/app/config.php');
use MyApp\database;

class Sql{

public $prices;
public $dbh;

public function __construct($dbh){
  $this->dbh = $dbh;
}

public function selectStock(){
  $ps = $this->dbh->query("SELECT * FROM stock");
  $prices = $ps->fetchAll(PDO::FETCH_ASSOC);
  return $prices;
}
}

?>