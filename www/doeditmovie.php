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
	$id = (int) $_POST['id'];
	$url = $_SESSION['url'];
	
	include 'include/movielogic.php';
	
	$query = "UPDATE movies_ny SET title = $title, imdb = $imdb, format = $format, location = $location 
		WHERE id = $id";
	db_query($query);
	db_disconnect();
	
	header("location: index.php?$url");
	exit;
?>