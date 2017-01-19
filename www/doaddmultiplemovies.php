<?php
	require_once 'include/db.php';
	
	session_start();
	
	if(!($_SESSION['user_loggedin']) ) 
	{
		header('location: login.php');
		exit;
	}
	$uid = $_SESSION['user_id'];
	
	db_connect();
	
	$titles = $_POST['titles'];
	$format = (int)$_POST['format'];
	$location = (int)$_POST['location'];
	$added = date("Y-m-d H:i:s");
	
	$url = $_SESSION['url'];
	
	$arr = explode(PHP_EOL, $titles);
	
	foreach($arr as $title)
	{
		if(strlen(trim($title)) <= 0)
			continue;
		
		$title = db_escape(trim($title));
		$query = "INSERT INTO movies_ny (title, imdb, format, location, added, user_id)
			VALUES( $title, '', $format, $location, '$added', $uid)";
		db_query($query);
	}

	db_disconnect();
	
	header("location: index.php?$url");
	exit;
?>