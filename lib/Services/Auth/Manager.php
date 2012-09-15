<?php

require_once 'Services/Generic/Dao.php';

class Services_Auth_Manager extends Services_Generic_Dao {

	public function validateUser($username, $password) {
		$query = "select count(1) as count from auth where username = :username and password = sha1(concat(:password, salt)) limit 1";

		$db = self::getDatabaseInstance();
		$sth = $db->prepare($query);
		$sth->execute(array(':username' => $username, ':password' => $password));

		return $sth->fetch();
	}
}