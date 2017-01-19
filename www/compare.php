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
	
	$query = "SELECT * FROM users";
	$res = db_query($query);
	
	$options = "";
	while($line = db_fetch_array($res))
	{
		$options .= "<option value='".$line['user_id']."'>".$line['user_name']."</option>";
	}
?>
<div class="allofit">


<h1>Jämför mellan användare</h1>
<form action="index.php" method="get" target="_top">
	<select name="user1">
		<?php echo $options; ?>
	</select>
	&nbsp;och&nbsp;
	<select name="user2">
		<?php echo $options; ?>
	</select>
	<input type="submit" value="jämför" class="loginbutton"/>
</form>

</div> <!-- end allofit -->
<?php
	db_disconnect();
	include 'include/html/minifooter.php';
?>