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
	
	$id = (int)$_GET['id'];

	
	$query = "DELETE FROM places WHERE places_id = $id";
	db_query($query);
	db_disconnect();
	
	header('location: locations.php');
	exit;
?>