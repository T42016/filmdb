<?php 
	/*
		CopyRight 2007 - Jonas Nilsson
		jonas.nilsson@ec.se
	*/

$db_handle = 0;

function db_connect() 
{
	//�ndra till inst�llningar som passar de MySQL inst�llningar ni har.
	//
	global $db_handle;
	$db_handle = new PDO('mysql:host=localhost;dbname=ymdb;charset=utf8mb4', 'root', '');
}

function db_disconnect() 
{
	global $db_handle;
	$db_handle = null;
}

function db_query($query, $bindings = null) 
{
	global $db_handle;
	$stmt = $db_handle->prepare($query);

	if($bindings == null)
		$stmt->execute();
	else
		$stmt->execute($bindings);

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
	global $db_handle;
	return mysql_insert_id($db_handle);
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
