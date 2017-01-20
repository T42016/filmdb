<?php
	session_start();
	
	if(!isset($_SESSION['user_loggedin']) ) 
	{
		header('location: login.php');
		exit;
	}
	require_once 'include/db.php';
	require_once 'include/htmlfunctions.php';
	
	$uid = $_SESSION['user_id'];
	$_SESSION['url'] = $_SERVER['QUERY_STRING'];

	db_connect();
	
	$order = "ORDER BY added DESC";
	if(isset($_GET['imdb']))
		$order = "ORDER BY rating DESC";
		
	//standard
	$query = "SELECT * FROM movies_ny 
				LEFT JOIN imdb_info ON movies_ny.imdb = imdb_info.imdb_info_id
				LEFT JOIN users ON movies_ny.user_id = users.user_id
				LEFT JOIN places ON movies_ny.location = places.places_id
				$order";
				
	if(isset($_GET['place']))
	{
		$place = (int)$_GET['place'];
		
		$order = "ORDER BY movies_ny.title";
		if(isset($_GET['imdb']))
			$order = "ORDER BY rating DESC";
		else if(isset($_GET['latest']))
			$order = "ORDER BY added DESC";
			
		$query = "SELECT * FROM movies_ny 
				LEFT JOIN imdb_info ON movies_ny.imdb = imdb_info.imdb_info_id
				LEFT JOIN users ON movies_ny.user_id = users.user_id
				LEFT JOIN places ON movies_ny.location = places.places_id
				WHERE movies_ny.location = $place
				$order";
	}
	if(isset($_GET['unsorted']))
	{	
		$query = "SELECT * FROM movies_ny 
				LEFT JOIN imdb_info ON movies_ny.imdb = imdb_info.imdb_info_id
				LEFT JOIN users ON movies_ny.user_id = users.user_id
				LEFT JOIN places ON movies_ny.location = places.places_id
				WHERE places_id IS NULL AND movies_ny.user_id = $uid
				ORDER BY movies_ny.title";
	}
	if(isset($_GET['text']))
	{
		#echo "<p>Sökresultat efter '<b>". $_GET['text']."</b>'</p>";
		
		$text = mysql_escape_string($_GET['text']);
		
		$query = "SELECT * FROM movies_ny
				LEFT JOIN imdb_info ON movies_ny.imdb = imdb_info.imdb_info_id 
				LEFT JOIN users ON movies_ny.user_id = users.user_id
				LEFT JOIN places ON movies_ny.location = places.places_id
				WHERE (title LIKE '%$text%' OR imdb_info.name LIKE '%$text%')";
		
		if($_GET['tag'] == 'my')
			$query .= " AND movies_ny.user_id = $uid ";
		$query .= "ORDER BY movies_ny.title";
	}
	if(isset($_GET['user1']) && isset($_GET['user2']))
	{
		$user1 = (int)$_GET['user1'];
		$user2 = (int)$_GET['user2'];
		$showHeaders = true;
		
		$query= "SELECT * FROM `movies_ny` 
				LEFT JOIN imdb_info ON movies_ny.imdb = imdb_info.imdb_info_id 
				LEFT JOIN users ON movies_ny.user_id = users.user_id
				LEFT JOIN places ON movies_ny.location = places.places_id
				WHERE movies_ny.user_id = $user1 AND format = 16
				AND imdb NOT IN
				(SELECT m2.imdb FROM `movies_ny` m2
				WHERE m2.user_id = $user2 AND m2.format = 16)
				ORDER BY movies_ny.location, movies_ny.title";
	}
	
	$pagesize = 50;
	if(isset($_GET['page']))
		$page = (int) $_GET['page'];	
	else
		$page = 0;
		
	$offset = $pagesize * $page;
	
	//Paging calc
	$res = db_query($query);
	$hits = db_num_rows($res);
	
	//Add limit
	$query .= " LIMIT $offset, $pagesize";
	$res = db_query($query);
	$_SESSION['query'] = $query;
	$movieinfo = array();
	$header = "";
	$data = db_fetch_array($res);

	foreach($data as $line)
	{
		//$movieinfo[$line['id']] = $line;
		$line['rating'] = strpos($line['rating'],'.') === false && $line['rating'] != '' ? $line['rating'].'.0' : $line['rating'];
		$line['icon'] = getIcon($line['format']);
		$line['imdb'] = getImdbLink($line['imdb']);
	}
		/*if($showHeaders == true && $header != $line['places_name'])
		{
			$header = $line['places_name'];
			echo "<tr><td colspan='5'><h1>$header</h1></td></tr>\n";
		}*/
		
			
	require __DIR__ . '/vendor/autoload.php';
	$loader = new Twig_Loader_Filesystem(__DIR__ .'/templates');
	$twig = new Twig_Environment($loader, array('cache' => __DIR__ .'/cache', 'debug' => true));
	echo $twig->render('start.twig', array('movies' => $data,));
	//Add paging
	/*
	$totalpages = ceil($hits / $pagesize);
	if($totalpages > 1)
	{
		echo "<p>sida ";
		for($i = 1; $i <= $totalpages; $i++)
		{
			if($i == $page + 1)
				echo "$i ";
			else
			{
				$url = getQueryUrl("index.php", "page", $i -1);
				echo "<a href='$url'>$i</a> ";
			}
		}
	}*/
	
	//echo "</p>";

		//Unsorted?
		/*
		$query = "SELECT places_name, places_id, COUNT(*) AS antal,
					COUNT(CASE movies_ny.format WHEN 8 THEN movies_ny.id ELSE null END) AS svcd,
					COUNT(CASE movies_ny.format WHEN 9 THEN movies_ny.id ELSE null END) AS divx,
					COUNT(CASE movies_ny.format WHEN 10 THEN movies_ny.id ELSE null END) AS dvd,
					COUNT(CASE movies_ny.format WHEN 11 THEN movies_ny.id ELSE null END) AS dvdr,
					COUNT(CASE movies_ny.format WHEN 16 THEN movies_ny.id ELSE null END) AS hd
					FROM places 
					RIGHT JOIN movies_ny ON places_id = movies_ny.location 
					WHERE places_id IS NULL AND movies_ny.user_id = $uid
					GROUP BY places_id
					ORDER BY places_name";
		$res = db_query($query);
		
		if(db_num_rows($res) > 0)
		{
			$line = db_fetch_array($res);
			echo "<h2><a href='index.php?unsorted=true'>(osorterat)</a></h2>\n";
			echo "<p>Totalt: $line[antal] st <br />";
			if($line['svcd'] > 0)
				echo " svcd ($line[svcd]st) ";
			if($line['divx'] > 0)
				echo " divx ($line[divx]st) ";
			if($line['dvd'] > 0)
				echo " dvd ($line[dvd]st) ";
			if($line['dvdr'] > 0)
				echo " dvd-r ($line[dvdr]st) ";
			if($line['hd'] > 0)
				echo " hd ($line[hd]st) ";
			echo "</p>";
		}
		
		$query = "SELECT places_name, places_id, COUNT(*) AS antal,
					COUNT(CASE movies_ny.format WHEN 8 THEN movies_ny.id ELSE null END) AS svcd,
					COUNT(CASE movies_ny.format WHEN 9 THEN movies_ny.id ELSE null END) AS divx,
					COUNT(CASE movies_ny.format WHEN 10 THEN movies_ny.id ELSE null END) AS dvd,
					COUNT(CASE movies_ny.format WHEN 11 THEN movies_ny.id ELSE null END) AS dvdr,
					COUNT(CASE movies_ny.format WHEN 16 THEN movies_ny.id ELSE null END) AS hd
					FROM places 
					INNER JOIN movies_ny ON places_id = movies_ny.location AND places_userid = $uid
					GROUP BY places_id
					ORDER BY places_name";
		$res = db_query($query);
		while($line = db_fetch_array($res))
		{
			echo "<h2><a href='index.php?place=$line[places_id]'>$line[places_name]</a></h2>\n";
			echo "<p>Totalt: $line[antal] st <br />";
			if($line['svcd'] > 0)
				echo " svcd ($line[svcd]st) ";
			if($line['divx'] > 0)
				echo " divx ($line[divx]st) ";
			if($line['dvd'] > 0)
				echo " dvd ($line[dvd]st) ";
			if($line['dvdr'] > 0)
				echo " dvd-r ($line[dvdr]st) ";
			if($line['hd'] > 0)
				echo " hd ($line[hd]st) ";
			echo "</p>";
		}*/
		
		db_disconnect();

		/*	
		//Generate tooltip text
		foreach($movieinfo as $key => $val)
		{
			echo "$('#id$key').simpletip({ fixed: false, showEffect: 'none', hideEffect: 'none', content: '".getToolTipText($val)."' });\n";
		}*/
?>
