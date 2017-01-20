<?php
	require_once 'include/db.php';
	session_start();
	db_connect();
	echo "<pre>";
	print_r($_SESSION);
	echo "</pre>";


	if(isset($_POST['userid']) && isset($_POST['password']))
	{
		$user   = $_POST['userid'];
		$pass 	= md5($_POST['password']);

		$res = db_query("SELECT * FROM users WHERE user_name = :userid AND user_passw = :password",
			array(
			 ':userid' => $user,
			 ':password' => $pass));


		if ($res->rowCount() > 0)
		{
				$row = db_fetch_array($res);
		    $_SESSION['user_id'] = $row['user_id'];
			  $_SESSION['user_loggedin'] = 1;
		    header('Location: index.php');



		}
				//Rerouting
		else
		{
		    ?>
		    <script type="text/javascript">
		        alert("This account doesnt exist");
		       </script>
		       <?php
		}

	}


?>
