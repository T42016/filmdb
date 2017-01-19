<?php
require_once 'include/class.mediainfo.php';
require_once 'include/htmlfunctions.php';

$m = new MediaInfo();

if($imdb == '')
{
	$str = "";
	$bits = explode('.', $title);
	foreach ($bits as $b) 
	{
		if($b == '720p' || $b == '1080p' || $b == 'BluRay' || $b == 'DVD9' || $b == 'DC' || $b == 'REPACK' || $b == 'PROPER'
			|| $b == 'HDDVD' || $b=='DVD5' || $b == 'HD-DVD' || $b == 'Blu-ray' || $b == 'DirCut' || $b == 'HDDVDRiP' || preg_match('/^\d{4}$/', $b) )
			break;
		$str .= $b.' ';
	}
	$info = $m->getMovieInfo(trim($str) );
	$imdb = $info['id'];
}
else
{
	$imdb = trim($imdb, 't');
	$info = $m->getMovieInfoById($imdb);
}

$name = db_escape($info['title']);
$rating = db_escape($info['rating']);
$votes = db_escape($info['votes']);
$year = db_escape($info['year']);
//$genre = db_escape(implode($info['genres'], ' / ') );
$genre = db_escape($info['genres']);

if(substr($info['poster'], strlen($info['poster'])-3) == 'jpg')
{
		$poster = db_escape($info['poster']);
		cachePoster($imdb, $info['poster']);
}
else
	$poster = "''";

$added = date("Y-m-d H:i:s");
$title = db_escape($title);
$imdb = db_escape($imdb);


//Add imdb_info or update?
$query = "SELECT * FROM imdb_info WHERE imdb_info_id = $imdb";
$res = db_query($query);
if(db_num_rows($res) > 0)
{
	$query = "UPDATE imdb_info SET lastupdated = '$added', name = $name, rating = $rating, votes = $votes, year = $year,
		genre = $genre, poster = $poster WHERE imdb_info_id = $imdb";
	db_query($query);	
}
else
{
	if($imdb != "''")
	{
		$query = "INSERT INTO imdb_info (imdb_info_id, lastupdated, name, rating, votes, year, genre, poster) 
			VALUES($imdb, '$added', $name, $rating, $votes, $year, $genre, $poster)";
		db_query($query);
	}
}

?>