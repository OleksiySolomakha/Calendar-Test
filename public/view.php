<?php 

//make sure that you get id

if (isset($_GET['event_id']))
{

	//make sure that id is integer

	$id = preg_replace('/[^0-9]/', '', $_GET['event_id']);

	// if id don't exist, go to main page

	if (empty($id)) 
	{

		header("Location: ./");

		exit;

	}

}

else
{

	//if id don't found, go to main page

	header("Location: ./");

	exit;

}

// plug in necessery file

include_once '../sys/core/init.inc.php';

//show start page

$page_title = "Looking event";

$css_files = array("style.css");

include_once 'assets/common/header.inc.php';

//download Calendar

$cal = new Calendar($dbo);

?>


<div id = "content">
	<?php  echo $cal -> _displayEvent($id) ?>

	<a href="./">&laquo; Return Calendar </a>
	</div><!--end #content-->


<?php

	// show end part of page

	include_once 'assets/common/footer.inc.php'; 

 ?> 









