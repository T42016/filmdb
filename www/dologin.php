<?php
	require_once 'include/db.php';
	session_start();
	db_connect();
	echo "<pre>";
	print_r($_SESSION);
	echo "</pre>";


	if(isset($_POST['userid']) && isset($_POST['password']))
	{
		$user = ($_POST['userid']);
		$pass = ($_POST['password']);
		$query = "SELECT * FROM users WHERE user_name = :userid AND user_passw = MD5(:password)";
		$res = db_query($query, array(':userid' => $user, ':password' => $pass));


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
				?>
				<script type="text/javascript">
						alert("This account doesnt exist");
					 </script>
					 <?php
	 		}
				//Rerouting

	}


?>
