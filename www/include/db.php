<?php
	/*
		CopyRight 2007 - Jonas Nilsson
		jonas.nilsson@ec.se
	*/


	//ändra till instöllningar som passar de MySQL inställningar ni har.
	//
function db_connect(){
	$db_host = "localhost";
	$db_user = "root";
	$db_pass = "";
	$db_db = "ymdb";
	try {
		 global $dbh;
		 $dbh = new PDO("mysql:host=$db_host;dbname=$db_db", $db_user, $db_pass);
		// set the PDO error mode to exception
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e)
	{
			echo "Connection failed: " . $e->getMessage();
	}
}

function db_disconnect(){
	global $dbh;
	$dbh = null;
}

function db_query($query, $v = null)
{
	global $dbh;

	$stmt = $dbh->prepare($query);
	#
	if($v != null){
		$stmt->execute($v);
	}
	else {
		$stmt->execute();
	}
		return $stmt;

}

function db_fetch_array($res)
{
	return $res->fetchAll();
}

function db_num_rows($res)
{
	return $res->rowCount();
}

function db_get_insert_id()
{
	global $dbh;
	return mysql_insert_id($dbh);
}
function db_escape($text)
{
	return "'".mysql_escape_string($text)."'";
}

function get_param($var, $type = "post")
{
	if ($type == "post") $temp = $_POST[$var];
	else $temp = $_GET[$var];
	// escape input string
	return htmlspecialchars($temp);
}


?>
