<?php	
	require_once 'include/db.php';

    require __DIR__.'/vendor/autoload.php';
    $loader = new Twig_Loader_Filesystem(__DIR__.'/templates');
    $twig = new Twig_Environment($loader, array('debug' => true));

	session_start();
	
	if(isset($_SESSION['user_loggedin'])) 
	{
		header('location: index.php');
		exit;
	}

	if(isset($_SESSION['login_error']))
    {
        unset($_SESSION['login_error']);
        echo $twig->render('login.twig', array('error' => 'Felaktigt användarnamn eller lösenord!'));
    }
    else {
        echo $twig->render('login.twig');
    }
?>

