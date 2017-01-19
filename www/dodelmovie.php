<?php
	require_once 'include/db.php';
	
	session_start();
	
	if(!($_SESSION['user_loggedin']) ) 
	{
		header('location: login.php');
		exit;
	}
	$uid = $_SESSION['user_id'];
	$url = $_SESSION['url'];
	
	db_connect();
	
	$id = (int)$_GET['id'];

	
	$query = "DELETE FROM movies_ny WHERE id = $id";
	db_query($query);
	db_disconnect();
	
	header("location: index.php?$url");
	exit;
?>