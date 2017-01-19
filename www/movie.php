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
	
	$id = (int)$_GET['id'];
	$query = "SELECT * FROM movies_ny 
				LEFT JOIN imdb_info ON movies_ny.imdb = imdb_info.imdb_info_id 
				LEFT JOIN users ON movies_ny.user_id = users.user_id
				LEFT JOIN places ON movies_ny.location = places.places_id
				WHERE id = $id";
				
	$res = db_query($query);
	$line = db_fetch_array($res);
?>
<div class="allofit">

<?php
	echo "<h3>$line[title]</h3>\n";
	echo getToolTipText($line);
	
	$query = "SELECT * FROM movies_ny 
				LEFT JOIN imdb_info ON movies_ny.imdb = imdb_info.imdb_info_id 
				LEFT JOIN users ON movies_ny.user_id = users.user_id
				LEFT JOIN places ON movies_ny.location = places.places_id
				WHERE movies_ny.imdb = '$line[imdb]' AND id != $id";
				
	$res = db_query($query);
	
	if(db_num_rows($res) > 0)
	{
		echo "<h3>Andra kopior</h3>";
		echo "<table class='list' cellspacing='0'>\n";
		$movieinfo = array();
	}
	$i =0;
	while($line = db_fetch_array($res))
	{
		$movieinfo[$line[id]] = $line;
		$icon = getIcon($line['format']);
		
		if(!($i & 1))
			echo "<tr class='odd'><td class='tableleft'>&nbsp;</td>";
		else
			echo "<tr><td></td>";
		
		//echo "<td><img width='50' src='$line[poster]' alt='' /></td>";
		
		echo "<td><a id='id$line[id]' href='movie.php?id=$line[id]' title='Info'>";
		if( strlen($line[title]) > 45)
			echo stripslashes(substr($line[title], 0, 45)).'..';
		else
			echo stripslashes($line[title]);
			
		echo "</a></td>";
		
		if($line['places_name'] != '')
			$place = $line['places_name'];
		else
			$place = '(osorterad)';
		
		echo "<td>$line[user_name]</td><td>$icon</td>\n";		
		echo "<td><a href='editmovie.php?id=$line[id]&amp;KeepThis=true&amp;TB_iframe=true&amp;height=220&amp;width=440' class='thickbox' title='Ändra'>
			<img src='img/icon/icon_edit.png' alt='' /></a></td>";
		
		if(!($i & 1))
			echo "<td class='tableright'>&nbsp;</td></tr>";
		else
			echo "<td></td></tr>";
			
		$i++;
	}
	if($i > 0)
		echo "</table>\n";
?>

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
<?php
	db_disconnect();
	include 'include/html/minifooter.php';
?>