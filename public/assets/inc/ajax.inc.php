<?php
//start session
session_start();
//include DB information
include_once '../../../sys/config/db-cred.inc.php';

//find constants for configuration information

foreach ( $C as $name => $val)
{
	define($name, $val);
}

	//create search massive for actions 

	$actions = array(
			'event_view' => array(
					'object' => 'Calendar',
					'method' => '_displayEvent'
			),

			'edit_event' => array(
					'object' => 'Calendar',
					'method' => 'displayForm'
			),

			'event_edit' => array(
					'object' => 'Calendar',
					'method' => 'processForm'
			),

			'delete_event' => array(
					'object' => 'Calendar',
					'method' => 'confirmDelete'
			),

			'confirm_delete' => array(
					'object' => 'Calendar',
					'method' => 'confirmDelete'
			)
	);

	if (isset($actions[$_POST['action']]))
	{	
		$use_array = $actions[$_POST['action']];

		$obj = new $use_array['object']($dbo);

		//check identify ID and do needfull corection

		if (isset($_POST['event_id']))
		{
			$id = (int) $_POST['event_id'];
		}
		else{$id = NULL;}

		echo $obj->$use_array['method']($id);
	}


function __autoload($class_name)
{
	$filename = '../../../sys/class/class.'
			.strtolower($class_name).'.inc.php';

	if (file_exists($filename))
	{
		include_once $filename;
	}

}
 ?>