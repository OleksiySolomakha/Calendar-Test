<?php
//session start
session_start();

	//defender mark

	if (!isset($_SESSION['token']))
	{

		$_SESSION['token'] = sha1(uniqid(mt_rand(), TRUE));

	}


	// take needfull configuration information

	include_once '../sys/config/db-cred.inc.php';
	
	//define constant for configuration information

	foreach ($C as $name => $val) 
	{

		define($name, $val);

	}
	//Create PDO-object

	$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
	$dbo = new PDO($dsn, DB_USER, DB_PASS);
	//identify autoload function for classes

	// spl_autoload_register(function($class)
	// {
	// 	$filename = "../sys/class/class." . $class . " .inc.php";
	// 	if (file_exists($filename)) 
	// 	{
	// 		include_once $filename;
	// 	}
	// });

	function __autoload($class)
	{
		$class = mb_strtolower($class);
		$filename = "../sys/class/class." . $class . ".inc.php";
		if (file_exists($filename)) 
		{
			include_once $filename;
		}
	}

  ?>