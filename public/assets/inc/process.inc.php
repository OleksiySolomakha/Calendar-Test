<?php
// start session
session_start();

	include_once '../../../sys/config/db-cred.inc.php';

	// find constante 

	foreach ($C as $name => $val)
	{
	
		define($name, $val);

	}
		
	

	//searching massive for ations

	$ations = array(
				'event_edit' => array(

					'object' => 'Calendar',

					'method' => 'processForm',

					'header' => 'Location: ../../'
						)
					);
	

	if ($_POST['token']==$_SESSION['token'] && isset($ations[$_POST['action']])) 
	{

		$use_array = $ations[$_POST['action']];
		
		$obj = new $use_array['object']($dbo);


		if (TRUE === $msg=$obj->$use_array['method']())
		{
		
			header($use_array['header']);
				
			exit;
			
		}
		

		else

		{
			die($msg);

		}
		
		
	}
	else
	{

		header("Location:../../");

		exit;

	}

	function __autoload($class_name)
	{

		$filename = '../../../sys/class/class.' . strtolower($class_name) . '.inc.php';

		if (file_exists($filename))
		{

			include_once $filename;

		}

	}
 ?>