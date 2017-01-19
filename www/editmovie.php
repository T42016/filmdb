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
	
	$id = (int)$_GET['id'];
	$query = "SELECT * FROM movies_ny WHERE id = $id";
	$res = db_query($query);
	$line = db_fetch_array($res);
?>
<div class="allofit">

<form action="doeditmovie.php" method="post" target="_top">
<input type="hidden" name="id" value="<?php echo $line['id']; ?>" />
<table cellspacing="0">
	<tr>
		<td>Titel</td><td><input type="text" class="text" id="title" name="title" value="<?php echo stripslashes($line['title']); ?>" /></td><td></td>
	</tr>
	<tr>
		<td>IMDB</td><td><input type="text" class="text" id="imdb" name="imdb" value="<?php echo $line['imdb']; ?>" /></td><td><!--<span class="info">Lämna blank för automatisk identifiering</span>--></td>
	</tr>
	<tr>
		<td>Format</td>
		<td>
			<select name="format">
			<?php
				$res = db_query("SELECT * FROM format");
				while($f = db_fetch_array($res))
				{
					if($f['format_key'] == $line['format'])
						echo "<option value='$f[format_key]' selected='selected'>$f[format]</option>";
					else
						echo "<option value='$f[format_key]'>$f[format]</option>";
				}
			?>
			</select>
		</td><td></td>
	</tr>
	<tr>
		<td>Lagring</td>
		<td>
		<select name="location">
			<option value="<?php echo $line['location']; ?>">(unchanged)</option>
		<?php
			$res = db_query("SELECT * FROM places WHERE places_userid = $uid ORDER BY places_name");
			while($l = db_fetch_array($res))
			{
				if($l['places_id'] == $line['location'])
					echo "<option value='$l[places_id]' selected='selected'>$l[places_name]</option>";
				else
					echo "<option value='$l[places_id]'>$l[places_name]</option>";
			}
		?>
		</select>
		</td><td></td>
	</tr>
	<tr>
		<td>
			<a href="dodelmovie.php?id=<?php echo $line['id'];?>" title="Ta bort permanent" target="_top" onclick="return confirm('Vill du verkligen ta bort filmen?');">
				<img src='img/icon/icon_del.png' alt='' />
			</a>
		</td><td></td><td><input type="submit" name="Submit" value="Uppdatera" class="loginbutton"/></td>
	</tr>
</table>
</form>

</div> <!-- end allofit -->

<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js"></script>
<script src="js/moviescript.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	setTimeout(function() {
		if($('#imdb').val() == '')
			$('#title').autocomplete('search', $('#title').val());
	},250);
});
</script>

<?php
	db_disconnect();
	include 'include/html/minifooter.php';
?>