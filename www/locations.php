<?php
	require_once 'include/db.php';
	session_start();
	
	if(!($_SESSION['user_loggedin']) ) 
	{
		header('location: login.php');
		exit;
	}
	$uid = $_SESSION['user_id'];
		
	include 'include/html/miniheader.php';
	db_connect();
	$edit = false;
	
	if(isset($_GET['edit']))
	{
		$edit = true;
		$id = (int)$_GET['id'];
		$query = "SELECT * FROM places WHERE places_id = $id";
		$res = db_query($query);
		$info = db_fetch_array($res);
	}
	
	$query = "SELECT * FROM places WHERE places_userid = $uid ORDER BY places_name";
	$res = db_query($query);
	
?>
<div class="allofit">

<div class ="left">
<h1>Mina lagringsplatser</h1>
<table class="list" cellspacing="0">
<?php
$i =0;
while($line = db_fetch_array($res))
{
	if($i & 1)
		echo "<tr class='odd'><td class='tableleft'>&nbsp;</td>";
	else
		echo "<tr><td></td>";
			
	echo "<td>$line[places_name]</td>";
	if($line['places_show'] == 1)
		echo "<td>visas</td>";
	else
		echo "<td></td>";
		
	echo "<td>
			<a href='locations.php?edit=true&amp;id=$line[places_id]' title='Ändra'><img src='img/icon/icon_edit.png' alt='' /></a>
			<a href='dodellocation.php?id=$line[places_id]' title='Ta bort' onclick=\"return confirm('Vill du verkligen ta bort lagringsplatsen?');\">
				<img src='img/icon/icon_del.png' alt='' />
			</a>
		</td>";
	
	if($i & 1)
		echo "<td class='tableright'>&nbsp;</td></tr>";
	else
		echo "<td></td></tr>";
	$i++;
}
?>

</table>
<p>&nbsp;</p>
<?php
	$query = "SELECT * FROM places 
		INNER JOIN users ON users.user_id = places_userid 
		WHERE places_userid <> $uid ORDER BY user_name, places_name";
	$res = db_query($query);
	$user = -1;
	
	while($line = db_fetch_array($res))
	{
		if($user != $line['user_id'])
		{
			echo "<h1 style='font-size: 18px;'>$line[user_name]</h1>";
			echo '<table class="list" cellspacing="0">';
			if($user != -1)
				echo '</table>';
			$user = $line['user_id'];
		}
		
		if($i & 1)
			echo "<tr class='odd'><td class='tableleft'>&nbsp;</td>";
		else
			echo "<tr><td></td>";
				
		echo "<td><a href='index.php?place=$line[places_id]' target='_top' class='movie'>$line[places_name]</a></td>";
		/*if($line['places_show'] == 1)
			echo "<td>visas</td>";
		else
			echo "<td></td>";
			
		echo "<td>
				<a href='locations.php?edit=true&amp;id=$line[places_id]' title='Ändra'><img src='img/icon/icon_edit.png' alt='' /></a>
				<a href='dodellocation.php?id=$line[places_id]' title='Ta bort' onclick=\"return confirm('Vill du verkligen ta bort lagringsplatsen?');\">
					<img src='img/icon/icon_del.png' alt='' />
				</a>
			</td>";*/
		
		if($i & 1)
			echo "<td class='tableright'>&nbsp;</td></tr>";
		else
			echo "<td></td></tr>";
		$i++;
	}
?>
</table>
</div> <!-- end left-->

<div class="feature">
	<div class="featuretop"><img src="img/fetop.gif" alt="" /></div>
	<div class="featurecontent">
	
	<?php
		if($edit == true)
		{
			echo '<h1>Ändra lagring</h1>';
			echo '<form action="doeditlocation.php" method="post" >';
			echo "<input type='hidden' name='id' value='$info[places_id]' />";
		}
		else
		{
			$info['places_name'] = "";
			echo '<h1>Lägg till lagring</h1>';
			echo '<form action="doaddlocation.php" method="post">';
		}
	?>
		<table>
		<tr><td>Namn</td><td><input type="text" name="name" value="<?php echo $info['places_name']; ?>" class="text2" /></td></tr>
		<tr><td>Visas</td><td><input type="checkbox" name="show" value="1" 
		<?php 
			if($edit==false || $info['places_show'] == 1) 
				echo 'checked="checked"'; 
		?> /></td></tr>
		<tr><td></td><td><input type="submit" value="<?php if($edit == true) echo "Uppdatera"; else echo "Lägg till"; ?>" class="loginbutton" /></td></tr>
		</table>
	</form>
	<div class="clear">&nbsp;</div>
	</div> <!-- end featurecontent -->
	<div class="featurebottom"><img src="img/febottom.gif" alt="" /></div>
</div> <!-- end feature -->


</div> <!-- end allofit -->
<?php
	db_disconnect();
	include 'include/html/minifooter.php';
?>