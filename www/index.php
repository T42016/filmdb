<?php
	session_start();

    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";

	if(!isset($_SESSION['user_loggedin']) ) 
	{
		header('location: login.php');
		exit;
	}
	require_once 'include/db.php';
	require_once 'include/htmlfunctions.php';
	
	$uid = $_SESSION['user_id'];
	$_SESSION['url'] = $_SERVER['QUERY_STRING'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">  
<head> 
	<meta http-equiv="Expires" content="0" /> 
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>YMDB</title> 
	<link rel="SHORTCUT ICON" href="favicon.ico" />
	<link rel="stylesheet" href="css/default.css" type="text/css" /> 
	<link rel="stylesheet" type="text/css" href="css/thickbox.css" media="screen" />
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/thickbox.js"></script>
	<script type="text/javascript" src="js/jquery.simpletip-1.3.1.js"></script>	
</head> 

<body>
<div id="allofit">
<div><img src="img/panel_top.png" alt="top" /></div>
<div id="top_content">
	<div id="logo">
		<div class="menu">
			<ul>
				<li class="medium"><a href="index.php">Hem</a></li>
				<li class="large"><a href="addmovie.php?KeepThis=true&amp;TB_iframe=true&amp;height=220&amp;width=440" class="thickbox" title="Lägg till">Lägg till</a></li>
				<li class="large"><a href="locations.php?KeepThis=true&amp;TB_iframe=true&amp;height=420&amp;width=740" class="thickbox" title="Hantera lagring">Lagring</a></li>
				<li class="large"><a href="compare.php?KeepThis=true&amp;TB_iframe=true&amp;height=120&amp;width=340" class="thickbox" title="Jämför">Jämför</a></li>
				<li class="large"><a href="logout.php">Logga ut</a></li>
			</ul><br style="clear: both" />
			<form action="index.php" method="get" class="search">
				<input type="radio" name="tag" value="my"  />Mina
				<input type="radio" checked="checked" name="tag" value="all" />Alla&nbsp;
				<input class="search" name="text" type="text" />
			</form>
			<a href="addmultiplemovies.php?KeepThis=true&amp;TB_iframe=true&amp;height=320&amp;width=440" class="thickbox" title="Lägg till"><small>+flera</small></a>
		</div>
	</div>
</div> <!-- end top_content -->
<div class="divider"></div>

<div id="content">

<div class ="left">
	<h1>Resultat</h1>
	<p>
		<a href="showposters.php?KeepThis=true&amp;TB_iframe=true&amp;height=620&amp;width=740" class="thickbox" title="Posters">Visa posters</a>
	</p>
<?php
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
		echo "<p>Sökresultat efter '<b>". $_GET['text']."</b>'</p>";
		
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
	
	echo "<table class='list' cellspacing='0'>\n";
	$i =0;
	while($line = db_fetch_array($res))
	{
		$movieinfo[$line['id']] = $line;
		
		$rating = strpos($line['rating'],'.') === false && $line['rating'] != '' ? $line['rating'].'.0' : $line['rating'];
		$icon = getIcon($line['format']);
		$imdb = getImdbLink($line['imdb']);
		
		if($showHeaders == true && $header != $line['places_name'])
		{
			$header = $line['places_name'];
			echo "<tr><td colspan='5'><h1>$header</h1></td></tr>\n";
		}
		
		if(!($i & 1))
			echo "<tr class='odd'><td class='tableleft'>&nbsp;</td>";
		else
			echo "<tr><td></td>";
		
		//echo "<td><img width='50' src='$line[poster]' alt='' /></td>";
		
		echo "<td><a id='id$line[id]' href='movie.php?id=$line[id]&amp;KeepThis=true&amp;TB_iframe=true&amp;height=380&amp;width=640' class='thickbox' title='Info'>";
		if( strlen($line['title']) > 45)
			echo stripslashes(substr($line['title'], 0, 45)).'..';
		else
			echo stripslashes($line['title']);
			
		echo "</a></td>";
		
		if($line['places_name'] != '')
			$place = $line['places_name'];
		else
			$place = '(osorterad)';
			
		//echo "<td><img src='img/icon/icon_info.png' alt='' title='$line[user_name] - $place' /></td>";
		echo "<td>$rating</td><td>$imdb</td><td>$icon</td>\n";
		echo "<td><a href='editmovie.php?id=$line[id]&amp;KeepThis=true&amp;TB_iframe=true&amp;height=220&amp;width=440' class='thickbox' title='Ändra'>
			<img src='img/icon/icon_edit.png' alt='' /></a></td>";
		
		if(!($i & 1))
			echo "<td class='tableright'>&nbsp;</td></tr>";
		else
			echo "<td></td></tr>";
			
		$i++;
	}
	
	echo "</table>\n";
	
	//Add paging
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
	}
	
	echo "</p>";
	
	//db_disconnect();
?>
	
</div> <!-- end left -->

<div class="feature">
	<div class="featuretop"><img src="img/fetop.gif" alt="" /></div>
	<div class="featurecontent">
	<h1>Min lagring</h1>
	
	<?php
		//Unsorted?
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
		}
		
		db_disconnect();
	?>

	<div class="clear">&nbsp;</div>
	</div> <!-- end featurecontent -->
	<div class="featurebottom"><img src="img/febottom.gif" alt="" /></div>
</div> <!-- end feature -->


<div id="footer">&copy;2009 Jonas Nilsson.</div>

</div> <!-- end content -->

<div><img src="img/panel_bot.gif" alt="bottom" /></div>
</div> <!-- end allofit -->

<script type="text/javascript">
<!--
$(document).ready(function(){
	<?php
		//Generate tooltip text
		foreach($movieinfo as $key => $val)
		{
			echo "$('#id$key').simpletip({ fixed: false, showEffect: 'none', hideEffect: 'none', content: '".getToolTipText($val)."' });\n";
		}
	?>
});
// -->
</script>
	
</body>

</html>

