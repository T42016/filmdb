<?php	
	require_once 'include/db.php';

	session_start();
		
	if(isset($_POST['userid']) && isset($_POST['password']))
	{
        $db = new PDO('mysql:host=localhost;dbname=ymdb;charset=utf8mb4', 'root', '');
        $stmt = $db->prepare('SELECT * FROM users WHERE user_name = :user AND user_passw = :password');
        $stmt->bindParam(':user', $_POST['userid'], PDO::PARAM_INT);
        $stmt->bindParam('password', md5($_POST['password']), PDO::PARAM_STR);
        $stmt->execute();
        $res = $stmt->fetch();

		if(count($res) > 0)
		{
			$_SESSION['user_id'] = (int) $res['user_id'];
			//$_SESSION['user_level'] = $line['user_level'];
			//$_SESSION['user_nick'] = $line['user_nick'];
			$_SESSION['user_loggedin'] = 1;
		}
		else
		{
			$_SESSION['login_error'] = true;
		}
	}

	header("Location: login.php");
	exit;
?>