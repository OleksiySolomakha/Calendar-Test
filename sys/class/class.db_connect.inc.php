<?php
//Пдключение к БД
Class DB_Connect
{
	
	protected $db;

	protected function __construct($db = NULL)
	{
		if ( is_object($db))
		{
			$this -> db = $db;
		}
		else
		{
			/**
			*константы определены в файле /sys/config/db-cred.inc.php
			*/
			$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
			try 
			{
				$this->db = new PDO($dsn, DB_USER, DB_PASS);
			}
			catch(Exception $e)
			{
				die($e -> getMessage());
			}
		}

	}

}

?>