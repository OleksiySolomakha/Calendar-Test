<?php
/**
 * admin  task class
 */
class Admin extends DB_Connect
{
	private $_saltLength = 7;

	//create or saved db object 
	
	public function __construct($db=NULL, $_saltLength=NULL)
	{
		parent::__construct($db);

		//if we had int , set length for option

		if (is_int($saltLength))
		{
			$this->_saltLength = $saltLength;
		}
	}

	//check users data 
	// return true if they are wright

	public function processLoginForm()
	{

		// end if action is wrong

		if ($_POST['action']!='user_login')
		{
			return"IN processLoginForm we hae ivalid parametrs";
		}

		//hover ussers input 

		$uname = htmlentities($_POST['uname'], ENT_QUOTES);

		$pword = htmlentities($_POST['pword'], ENT_QUOTES);

		//take information from DB if it exist

		$sql = "SELECT `user_id`, `user_name`, `user_email`, `user_pass`
		FROM `users` WHERE `user_name` =:uname LIMIT 1";
	
		try
		{
		 	$stmt = $this->db->prepare($sql);

		 	$stmt->bindParam(':uname', $uname, PDO::PARAM_STR);

		 	$stmt->execute();

		 	$user = array_shift($stmt->fetchAll());

		 	$stmt->closeCursor();
		} 
		catch (Exception $e)
		{
		 	die($e->getMessage());
		} 

		//criticall shutdown if user_name dont match withuser_name from DB

		if (!isset($user)) 
		{
			return "Wrong DUDE name!!!";
		}
		
		//take hash code from user

		$hash = $this->_getSaltedHash($pword, $user['user_pass']);

		//saved users data in session

		if ($user['user_pass']==$hash)
		{	

		$_SESSION['user']=array(
				'id'=> $user['user_id'],
				'name'=> $user['user_name'],
				'email'=> $user['user_emal']
				
			);

		//print_r($user);

			return TRUE;

		}

		//critacal end if passwods didm`t match

		else
		{
			return "Wrong name or password";
		}

	}

	// end user session

	public function processLogout()
	{
		if ($_POST['action']!='user_logout') 
		{
			return "Wrong parameter in attribute Action";
		}

		// delete massive user from this session

		session_destroy();

		return TRUE;
	}

	//generate special hash code

	 private function _getSaltedHash($string, $salt=NULL)
	{

		//generate salt , if it won`t be able

		if ($salt==NULL)
		{
			$salt = substr(md5(time()), 0, $this->_saltLength);
		}

		//take salt from string if it where send
		else
		{
			$salt = substr($salt, 0, $this->_saltLength);
		}

		//edit salt in hash-code and return

		return $salt . sha1($salt . $string); 
	}
	//create  hash-code for password

 // 	public function testSaltedHash($string, $salt=NULL)
	// {
	// 	return $this->_getSaltedHash($string, $salt);
	// }
}

?>