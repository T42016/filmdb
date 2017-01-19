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
	
	$title = $_POST['title'];
	$format = (int)$_POST['format'];
	$location = (int)$_POST['location'];
	$imdb = $_POST['imdb'];
	$url = $_SESSION['url'];
	
	include 'include/movielogic.php';
	
	$query = "INSERT INTO movies_ny (title, imdb, format, location, added, user_id)
		VALUES( $title, $imdb, $format, $location, '$added', $uid)";
	db_query($query);
	db_disconnect();
	
	header("location: index.php?$url");
	exit;
?>