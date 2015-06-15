<?php
	require_once ('constants.php');
	
	//DBSimple
	require_once(LIBPATH.'/dbsimple/config.php');
	require_once(LIBPATH.'/dbsimple/DBSimple/Generic.php');

	
// Устанавливаем соединение.
	$connection = DbSimple_Generic::connect('mysqli://'. DB_USER. ':' . DB_PASS.'@'.DB_SERVER .'/'.DB_NAME);
	$connection->setErrorHandler('dbErrorHandler');
	$connection->setLogger('dbLogger');
		
	
	/*
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
  } */
	
	

