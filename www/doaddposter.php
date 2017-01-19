<?php
	require_once 'include/db.php';
	require_once 'include/class.mediainfo.php';
	require_once 'include/htmlfunctions.php';
	
	session_start();
	
	if(!($_SESSION['user_loggedin']) ) 
	{
		header('location: login.php');
		exit;
	}
	db_connect();
	$uid = $_SESSION['user_id'];
	$id = (int)$_GET['id'];
	$query = "SELECT * FROM movies_ny WHERE id = $id";
	$res = db_query($query);
	$line = db_fetch_array($res);
	
	//No valid imdb id?
	if($line['imdb'] == '')
	{
		header("location: showposters.php");
		exit;
	}
	
	//Update info including poster
	$m = new MediaInfo();
	$info = $m->getMovieInfoById($line['imdb']);
	
	$name = db_escape($info['title']);
	$rating = db_escape($info['rating']);
	$votes = db_escape($info['votes']);
	$year = db_escape($info['year']);
	//$genre = db_escape(implode($info['genres'], ' / ') );
	$genre = db_escape($info['genres']);
	
	if(substr($info['poster'], strlen($info['poster'])-3) == 'jpg')
	{
		$poster = db_escape($info['poster']);
		cachePoster($line['imdb'], $info['poster']);
	}
	else
	{
		$poster = "''";
	}
	
	$added = date("Y-m-d H:i:s");
	$imdb = db_escape($line['imdb']);
	
	$query = "UPDATE imdb_info SET lastupdated = '$added', name = $name, rating = $rating, votes = $votes, year = $year,
		genre = $genre, poster = $poster WHERE imdb_info_id = $imdb";
	db_query($query);
	db_disconnect();
	
	header("location: showposters.php");
	exit;
?>