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
	$id = (int)$_POST['id'];

	
	$query = "UPDATE places set places_name = $name, places_show=$show WHERE places_id = $id";
	db_query($query);
	db_disconnect();
	
	header('location: locations.php');
	exit;
?>