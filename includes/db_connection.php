<?php
	define("DB_SERVER", "localhost"); //0.0.0.0
	define("DB_USER", "root"); //gorvic
	define("DB_PASS", "123"); // ""
	define("DB_NAME", "advertises");

  // 1. Create a database connection
  $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
  // Test if connection succeeded
  if(mysqli_connect_errno()) {
    die("Database connection failed: " . 
         mysqli_connect_error() . 
         " (" . mysqli_connect_errno() . ")"
    );
  }
  if (!mysqli_set_charset($connection, "utf8")) {
    die('Ошибка при загрузке набора символов utf8:'.mysqli_error($connection));
  } 
?>
