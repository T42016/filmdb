<?php 
	/*
		CopyRight 2007 - Jonas Nilsson
		jonas.nilsson@ec.se
	*/

	
function db_connect() 
{
	//�ndra till inst�llningar som passar de MySQL inst�llningar ni har.
	//
	global $pdo;
	$pdo = new PDO('mysql:host=localhost;dbname=ymdb;charset=utf8mb4', 'root', '');
}

function db_query($query) 
{
	global $pdo;
	$ret = $pdo->prepare($query);
	return $ret;
}

function db_fetch_array($res, $type=MYSQL_ASSOC) 
{
	return mysql_fetch_array($res, $type);
}

function db_num_rows($res) 
{
	return mysql_num_rows($res);
}

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
?>
