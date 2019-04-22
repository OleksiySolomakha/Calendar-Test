<?php
//include necessary files 
include_once '../sys/core/init.inc.php';

	//redirect unregistered user to main page

	if (!isset($_SESSION['user']))
	{
		header("Location: ./");
		exit;
	}

	// display start part of  page

	$page_title="Добавить / Редактировать событие";

	$css_files= array("style.css", "admin.css");

	include_once 'assets/common/header.inc.php';

	// download calendar

	$cal = new Calendar($dbo);

 ?>

 	<div id="content">
 		<?php echo $cal->displayForm(); ?>
	</div><!--end content-->

<?php 

	//display end ppart of page 

	include_once'assets/common/footer.inc.php'; 

?>
