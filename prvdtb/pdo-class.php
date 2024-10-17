<?php

class Database
{
  private static $instance;

  public static function getPdo()
  {
    try {
      if (!isset(self::$instance)) {
        self::$instance = new PDO(
          PDO_DSN,
          DB_USER,
          DB_PASSWORD,
          [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
          ]
        );
      }
      return self::$instance;
    } catch (PDOException $e) {
      echo $e->getMessage();
      exit;
    }
  }
}
