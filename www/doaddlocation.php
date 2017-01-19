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
	
	$name = db_escape($_POST['name']);
	$show = (int)$_POST['show'];

	
	$query = "INSERT INTO places (places_userid, places_name, places_show)
		VALUES( $uid, $name, $show)";
	db_query($query);
	db_disconnect();
	
	header('location: locations.php');
	exit;
?>