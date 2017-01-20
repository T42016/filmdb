<?php 
	/*
		CopyRight 2007 - Jonas Nilsson
		jonas.nilsson@ec.se
	*/

	
$pdo = 0;

function db_connect() 
{
	//?ndra till inst?llningar som passar de MySQL inst?llningar ni har.
	//
	global $pdo;
	$pdo = new PDO('mysql:host=localhost;dbname=ymdb;charset=utf8mb4', 'root', '');
}

function db_disconnect() 
{
	global $pdo;
	$pdo = null;
}

function db_query($query) 
{
	global $pdo;
	$stmt = $pdo->prepare($query);
	$stmt->execute();
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

/*
function db_get_insert_id()
{
	global $pdo;
	return mysql_insert_id($pdo);
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
*/
?>
