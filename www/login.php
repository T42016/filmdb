<?php	
	require_once 'include/db.php';

	session_start();
	
	if(isset($_SESSION['user_loggedin'])) 
	{
		header('location: index.php');
		exit;
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">  
<head> 
	<meta http-equiv="Expires" content="0" /> 
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title>YMDB</title> 
	<link rel="SHORTCUT ICON" href="favicon.ico" />
	<link rel="stylesheet" href="css/default.css" type="text/css" /> 
	
	<link rel="stylesheet" type="text/css" href="css/thickbox.css" media="screen" />
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/thickbox.js"></script>
</head> 

<body>
<div id="allofit">
<div><img src="img/panel_top.png" alt="top" /></div>
<div id="top_content">
	<div id="logo">
		<div class="login">
		<form method="post" action="dologin.php">
		<table>
			<tr>
			<td>Anv&auml;ndarnamn &nbsp;</td><td><input name="userid" type="text" id="userid" class="text" maxlength="255" /></td>
			</tr>
			<tr>
			<td>L&ouml;senord</td><td><input name="password" type="password" id="password" class="text" maxlength="255" /> </td>
			</tr>
			<tr><td></td><td>
		<p class="right">
			<input type="submit" name="Submit" value="Logga in" class="loginbutton"/> 
		</p></td></tr>
		</table>
		</form>
		<?php
		if(isset($_SESSION['login_error']))
		{
			echo "<p style='margin-top: 10px;'><span class='error'>Felaktigt användarnamn eller lösenord!</span></p>";
			unset($_SESSION['login_error']);
		}
		?>
		</div> <!-- end login -->
	</div>
</div> <!-- end top_content -->
<div class="divider2"><img src="img/panel_bot2.gif" alt="mid" /></div>

</div> <!-- end allofit -->
</body>

</html>

