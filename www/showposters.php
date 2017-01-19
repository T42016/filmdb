<?php
	require_once 'include/db.php';
	require_once 'include/htmlfunctions.php';
	
	session_start();
	
	if(!($_SESSION['user_loggedin']) ) 
	{
		header('location: login.php');
		exit;
	}
	$uid = $_SESSION['user_id'];
		
	include 'include/html/miniheader.php';
	db_connect();
	
	$query = $_SESSION['query'];
	$res = db_query($query);
?>
<div class="allofit">

<?php
	while($line = db_fetch_array($res))
	{
		if(posterExists($line['imdb']) )
			echo "<img src='".getPoster($line['imdb'])."' title='".addslashes($line['name'])."' alt='poster' width='96' height='140'/>\n";
		else
			echo "<a href='doaddposter.php?id=$line[id]' title='Lägg till poster - ".addslashes($line['title'])."'><img src='".getPoster($line['imdb'])."' alt='noposter' /></a>\n";
	}
?>

</div> <!-- end allofit -->
<?php
	db_disconnect();
	include 'include/html/minifooter.php';
?>