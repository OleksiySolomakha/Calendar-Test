<?php
session_start();
	include_once '../../../sys/config/db-cred.inc.php';

	// find constante 

	foreach ($C as $name => $val)
	{
	
		define($name, $val);

	}

	//searching massive for actions

	$ations = array(
				'event_edit' => array(

					'object' => 'Calendar',

					'method' => 'processForm',

					'header' => 'Location: ../../'
						),

				//modificate for log in

				'user_login' => array(

					'object' => 'Admin',

					'method' => 'processLoginForm',

					'header' => 'Location: ../../'	
						),
				//end session array

				'user_logout' => array(

					'object' => 'Admin',

					'method' => 'processLogout',

					'header' => 'Location: ../../'
					
						)	
					);
	

	if ($_POST['token']==$_SESSION['token'] && isset($ations[$_POST['action']])) 
	{

		$use_array = $ations[$_POST['action']];
		
		$obj = new $use_array['object']($dbo);

		// or use this, no header erroor 
		// if (TRUE === $msg=$obj->$use_array['method'])

		if (TRUE === $msg=$obj->$use_array['method']())
		{ 
			
			header($use_array['header']);
			//print_r($use_array['header']);
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