<?php

class TSApi extends CComponent
{
	public static $host = 'localhost';
	public static $user = 'ts3';
	public static $password = '************';
	public static $dbname = 'ts3';
	public static $db = false;
	
	public static function connect()
	{	
		if (self::$db)
			return true;
		self::$db = new mysqli(self::$host, self::$user, self::$password, self::$dbname);
		if (self::$db->connect_errno)
			return false;
		self::$db->set_charset('latin1');
		return true;
	}
	
	function getClient($name)
	{
		$result = self::$db->query("SELECT*FROM `clients` where `client_nickname` LIKE '{$name}%' ORDER BY `client_lastconnected` DESC");
		if ($result->num_rows == 0)
			return false;
		$row = $result->fetch_assoc();
		return $row;
	}
}