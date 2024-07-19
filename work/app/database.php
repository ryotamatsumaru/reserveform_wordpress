<?php

namespace MyApp;

class Database
{
  private static $instance;

  public static function getInstance()
  {
    try{
      if(!isset(self::$instance)) {
        self::$instance = new \PDO(
          DSN,
          USER,
          PASS,
          [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
            \PDO::ATTR_EMULATE_PREPARES => false,
          ]
        );
      }

      return self::$instance;
    } catch (\PDOException $e) {
      echo $e->getMessage();
      exit;
    }
  }

  public function getPdo2()
  {	  
    $pdo = new PDO(DSN, USER, PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	return $pdo;
  }

}