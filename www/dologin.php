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
		$pass 	= $_POST['password'];
		$query = "SELECT * FROM users WHERE user_name = :userid AND user_passw = MD5(:password)";
		$res = db_query($query,
			array(
			 ':userid' => $user,
			 ':password' => $pass));


		if ($res->rowCount() > 0)
		{
				$row = db_fetch_array($res);
				print_r($row);

				$_SESSION['user_loggedin'] = 1;
				$_SESSION['user_id'] = $row[0];
				print_r($_SESSION);
				exit;
		    header('Location: index.php');
				exit;



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
