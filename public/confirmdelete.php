<?php 
//check ID
if (isset($_POST['event_id'])) 
{

	$id=(int) $_POST['event_id'];

}
else
{

	header("Location: ./");

	exit;

}

include_once '../sys/core/init.inc.php';

$cal = new Calendar($dbo);

$markup = $cal->confirmDelete($id);

$page_title = "Watch events";

$css_files = array("style.css", "admin.css");

include_once 'assets/common/header.inc.php';
 
 ?>

<div id="content">

<?php echo $markup; ?>

</div><!--end content-->

<?php 

include_once 'assets/common/footer.inc.php';

 ?>