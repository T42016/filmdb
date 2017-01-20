<?php	
	require_once 'include/db.php';
	
	db_connect();
	session_start();
		
	if(isset($_POST['userid']) && isset($_POST['password']))
	{
		$user = ($_POST['userid']);
		$pass = ($_POST['password']);		
		$query = "SELECT * FROM users WHERE user_name = '$user' AND user_passw = MD5('$pass')";

		$res = db_query($query);
		if(db_num_rows($res) == 1)
		{
			$line = db_fetch_array($res);
			$_SESSION['user_id'] = $line['user_id'];
			//$_SESSION['user_level'] = $line['user_level'];
			//$_SESSION['user_nick'] = $line['user_nick'];
			$_SESSION['user_loggedin'] = 1;
		}
		else
		{
			$_SESSION['login_error'] = true;
		}
			
	}
	
	db_disconnect();
	
	header("Location: login.php");
	exit;
?>