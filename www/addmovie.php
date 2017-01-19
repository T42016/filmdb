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
?>
<div class="allofit">

<form action="doaddmovie.php" method="post" target="_top">
<table cellspacing="0">
	<tr>
		<td>Titel</td><td><input type="text" class="text" id="title" name="title" /> </td><td></td>
	</tr>
	<tr>
		<td>IMDB</td><td><input type="text" class="text" id="imdb" name="imdb" /></td><td><!--<span class="info">Lämna blank för automatisk identifiering</span>--></td>
	</tr>
	<tr>
		<td>Format</td>
		<td>
			<select name="format">
			<?php
				$res = db_query("SELECT * FROM format");
				while($line = db_fetch_array($res))
				{
					if($line['format_key'] == 16)
						echo "<option value='$line[format_key]' selected='selected'>$line[format]</option>";
					else
						echo "<option value='$line[format_key]'>$line[format]</option>";
				}
			?>
			</select>
		</td><td></td>
	</tr>
	<tr>
		<td>Lagring</td>
		<td>
		<select name="location">
			<option value="0">(unsorted)</option>
		<?php
			$res = db_query("SELECT * FROM places WHERE places_userid = $uid ORDER BY places_name");
			while($line = db_fetch_array($res))
			{
				echo "<option value='$line[places_id]'>$line[places_name]</option>";
			}
		?>
		</select>
		</td><td></td>
	</tr>
	<tr>
		<td></td><td></td><td><input type="submit" name="Submit" value="Lägg till" class="loginbutton"/></td>
	</tr>
</table>
</form>

</div> <!-- end allofit -->

<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js"></script>
<script src="js/moviescript.js"></script>

<?php
	db_disconnect();
	include 'include/html/minifooter.php';
?>