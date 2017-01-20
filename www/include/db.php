<?php
	/*
		CopyRight 2007 - Jonas Nilsson
		jonas.nilsson@ec.se
	*/

	//$db_handle = 0;

	$db;

	function db_connect()
	{
		/*//�ndra till inst�llningar som passar de MySQL inst�llningar ni har.
		//

		$db_host = "localhost";
		$db_user = "root";
		$db_pass = "";
		$db_db = "ymdb";
		global $db_handle;
		$db_handle = mysql_connect($db_host, $db_user, $db_pass) or die("Couldn't connect to database : " . mysql_error());
		mysql_set_charset('utf8',$db_handle);
		mysql_select_db($db_db) or die("Couldn't select database");*/

		global $db;
		$db = new PDO('mysql:host=localhost;dbname=ymdb;charset=utf8mb4', 'root', '');
	}

	function db_disconnect()
	{
		global $db;
		$db = null;
		/*global $db_handle;
		mysql_close($db_handle);*/
	}

	function db_query($query, $bindings = null)
	{
		global $db;
		$stmt = $db->prepare($query);

		if ($bindings == null) {
			$stmt->execute();
		}
		else {
            $stmt->execute($bindings);
		}

		return $stmt;
		/*$ret = mysql_query($query) or die("query failed : " . mysql_error());
		return $ret;*/
	}

	function db_fetch_array($res)
	{
		return $res->fetchAll();
		//return mysql_fetch_array($res, $type);
	}

	function db_num_rows($res)
	{
		return $res->rowCount();
		//return mysql_num_rows($res);
	}

	function db_get_insert_id()
	{
		global $db;
		return $db->lastInsertId();;
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
