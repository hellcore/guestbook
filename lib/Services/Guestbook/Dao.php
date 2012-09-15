<?php

require_once 'Services/Generic/Dao.php';

class Services_Guestbook_Dao extends Services_Generic_Dao {

	public function findById($id) {
		$query = "select * from guestbook where id = :id limit 1";

		$db = self::getDatabaseInstance();
		$sth = $db->prepare($query);
		$sth->execute(array(':id' => $id));

		return $sth->fetch();
	}

	public function fetchPage($offset, $limit = 100) {
		if (!is_int($offset) || !is_int($limit)) throw new Exception ("offset and limit need be integer");
		$query = "select * from guestbook order by id asc limit $offset, $limit";

		$db = self::getDatabaseInstance();
		$sth = $db->prepare($query);
		$sth->execute();
		return $sth->fetchAll();
	}

	public function deleteById($id) {
		$query = "delete from guestbook where id = :id limit 1";

		$db = self::getDatabaseInstance();
		$sth = $db->prepare($query);
		return $sth->execute(array(':id' => $id));
	}

	public function insert(Array $hash) {
		
		$query = "insert into guestbook (name, email, comment, datetime) values(:name, :email, :comment, NOW())";

		$parameters = array();
		$parameters[':name'] 		= $hash['name'];
		$parameters[':email'] 		= $hash['email'];
		$parameters[':comment'] 	= $hash['comment'];
		#$parameters[':datetime'] 	= $hash['datetime']; 

		$db 	= self::getDatabaseInstance();
		$sth 	= $db->prepare($query);
		return $sth->execute($parameters);
	}

	public function updateById($id, Array $hash) {
		if (empty($id)) throw new Exception("fail update: need id");
		if (empty($hash)) throw new Exception("no data to update");

		$parameters = array();
		$query_parameters = array();
		foreach($hash as $prop => $value) {
			$parameters[':'.$prop] = $value;
			$query_parameters[] = sprintf("%s = :%s", $prop, $prop); 
		}
		$query = sprintf("update guestbook set %s where id = :id limit 1", implode(", ", $query_parameters));
		$parameters[':id'] = $id;

		$db 	= self::getDatabaseInstance();
		$sth 	= $db->prepare($query);
		return $sth->execute($parameters);
	}
}