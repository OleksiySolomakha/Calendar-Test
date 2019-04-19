<?php
// include needfull files
include_once '../sys/core/init.inc.php';

//show first part of page 

$page_title = "Please sing in!!!";

$css_files = array("style.css", "admin.css");

include_once 'assets/common/footer.inc.php';
 
?>

<div id="content">
	<form action="assets/inc/process.inc.php" method="post">
		<fieldset>
			
			<legend>Please, if you have a ..</legend>
			
			<label for="uname">User Name</label>
			<input type="text" name="uname" id="uname" value="" />

			<label for="pword">Password DUDE</label>
			<input type="password" name="pword" id="pword" value="" />

			<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
			<input type="hidden" name="action" value="user_login" />
			<input type="submit" name="login_submit" value="ENTER HERE" /> or if you scared
			<a href="./">CANCEL IT</a>

		</fieldset>
	</form>

</div><!--end cntent-->

<?php

include_once 'assets/common/footer.inc.php';

 ?>	


	