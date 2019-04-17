<?php 
	
	//turn on needfull files

	include_once '../sys/core/init.inc.php';
	include_once '../sys/class/class.calendar.inc.php';
	include_once '../sys/class/class.db_connect.inc.php';
	
	//download calendar for January

	$cal = new Calendar($dbo, "2019-01-01 12:00:00");


	//named page

	$page_title = "Calendar of Events";

	$css_files = array('style.css');

	// plug in start of page
	
	include_once 'assets/common/header.inc.php';

	?>
	
	<div id = "content">

	<?php
		
	echo $cal -> _buildCalendar();
	//echo"<pre>", var_dump($cal), "</pre>";

	/*if (is_object ($cal))
	{
		echo "<pre>", var_dump($cal), "</pre>";
	}
	*/
	?>

	</div><!--end #content-->

	<?php

	//plug in end of page

	include_once 'assets/common/footer.inc.php';

	?>

 