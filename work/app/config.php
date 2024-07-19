<?php

// session_start();

define('DSN', 'mysql:host=db;dbname=reserve;charset=utf8');
define('USER', 'testuser');
define('PASS', 'testpass');

spl_autoload_register(function ($class) {
  $prefix = 'MyApp\\';

if(strpos($class, $prefix) === 0){
  $fileName = sprintf(__DIR__ . '/%s.php', substr($class, strlen($prefix)));

    if(file_exists($fileName)) {
      require($fileName);
    } else {
      echo ' File not found: ' . $fileName;
      exit;
    }
  }
});

