<?php
class Services_Generic_Dao {
	private static $instance;

	public static function getDatabaseInstance() {
		return Services_Generic_Dao_DB::connect();
	}

	public static function disconnect() {
		Services_Generic_Dao_DB::disconnect();
	}

	public static function getInstance() {
		if (empty(self::$instance)) {
			self::$instance = new static;
		}
		return self::$instance;
	}
}

final class Services_Generic_Dao_DB {
	private static $db;

	protected function __construct() {}
	
	protected function __clone() {}

	public static function connect($retry = false) {
		if (!self::isConnect()) {
			self::$db = new PDO('mysql:host='. DB_HOST . ';dbname=' . DB_NAME, 
									DB_USER, 
									DB_PASS
								);

			self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			self::$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		}
		return self::$db;
	}

	public static function isConnect() {
		if (null === self::$db) return false;
		try {
			self::$db->query("Select 1");
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
	
	public static function disconnect() {
		self::$db = null;
	}
}