<?php	
	require_once 'include/db.php';
	
	db_connect();
	session_start();
	
	    // om man trycker på knappen sign-in.
        if (isset($_POST['userid']) && isset($_POST['password'])) 
        {
            // hämtar och sätter variabler.
            $user = $_POST['userid'];
			$pass = $_POST['password'];	
            
            //jämnför variablerna med information från databasen.
            $stmt = $pdo->prepare("SELECT * FROM users WHERE user_name = '$user' AND user_passw = MD5('$pass')");
            
			$stmt->bindParam(':username',$user);
            $stmt->bindParam(':password',$pass);
            
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($stmt->rowCount() > 0) 
            {
                $_SESSION['user_loggedin'] = 1;
				$_SESSION['user_id'] = $row['user_id'];
                header('Location:index.php');
            }
            else
            {
				
            }
        }
		
	header("Location: login.php");
	exit;
?>