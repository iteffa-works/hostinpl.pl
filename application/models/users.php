<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class usersModel extends Model {
	public function createUser($data) {
		$sql = "INSERT INTO `users` SET ";
		$sql .= "user_email = '" . $this->db->escape($data['user_email']) . "', ";
		$sql .= "user_password = '" . $this->db->escape($data['user_password']) . "', ";
		$sql .= "user_firstname = '" . $this->db->escape($data['user_firstname']) . "', ";
		$sql .= "user_lastname = '" . $this->db->escape($data['user_lastname']) . "', ";
		$sql .= "user_status = '" . (int)$data['user_status'] . "', ";
		$sql .= "user_balance = '" . (float)$data['user_balance'] . "', ";
		$sql .= "user_access_level = '" . (int)$data['user_access_level'] . "', ";
		$sql .= "ref = '" . (int) @$data['ref'] . "', ";
		$sql .= "rmoney = '" . (float)$data['rmoney'] . "', ";
		$sql .= "bonuses = '0', ";
		$sql .= "user_activate = '" . (int)$data['user_activate'] . "', ";
		$sql .= "key_activate = '" . $data['key_activate'] . "', ";
		$sql .= "user_date_reg = NOW()";
		$this->db->query($sql);
		return $this->db->getLastId();
	}
	
	public function getUserByKey($key) {
		$sql = "SELECT * FROM `users` WHERE `key_activate` = '" . $this->db->escape($key) . "' LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row;
	}	
	
	public function deleteTicketMessage($userid) {
		$sql = "DELETE FROM `users` WHERE user_id = '" . (int)$userid . "'";
		$this->db->query($sql);
	}
	
	public function deleteAuth($authid) {
		$sql = "DELETE FROM `users_auth` WHERE `auth_id` = '" . (int)$authid . "'";
		$this->db->query($sql);
	}

	public function getAuthByKey($key) {
		$sql = "SELECT * FROM `users_auth` WHERE `auth_key` = '" . $this->db->escape($key) . "' LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getAuths($data = array(), $joins = array(), $sort = array(), $options = array()) {
		$sql = "SELECT * FROM `users_auth`";
		foreach($joins as $join) {
			$sql .= " LEFT JOIN $join";
			switch($join) {
				case "users":
					$sql .= " ON users_auth.user_id=users.user_id";
				break;
			}
		}
			
		if(!empty($data)) {
			$count = count($data);
			$sql .= " WHERE";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
					
				$count--;
				if($count > 0) $sql .= " AND";
			}
		}
			
		if(!empty($sort)) {
			$count = count($sort);
			$sql .= " ORDER BY";
			foreach($sort as $key => $value) {
				$sql .= " $key " . $value;
					
				$count--;
				if($count > 0) $sql .= ",";
			}
		}
			
		if(!empty($options)) {
			if ($options['start'] < 0) {
				$options['start'] = 0;
			}
			if ($options['limit'] < 1) {
				$options['limit'] = 20;
			}
			$sql .= " LIMIT " . (int)$options['start'] . "," . (int)$options['limit'];
			}
		$query = $this->db->query($sql);
		return $query->rows;
	}
		
	
	public function updateUser($userid, $data = array()) {
		$sql = "UPDATE `users`";
		if(!empty($data)) {
			$count = count($data);
			$sql .= " SET";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= ",";
			}
		}
		$sql .= " WHERE `user_id` = '" . (int)$userid . "'";
		$query = $this->db->query($sql);
		return true;
	}
	
	public function getUsers($data = array(), $sort = array(), $options = array()) {
		$sql = "SELECT * FROM `users`";
		if(!empty($data)) {
			$count = count($data);
			$sql .= " WHERE";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= " AND";
			}
		}
		
		if(!empty($sort)) {
			$count = count($sort);
			$sql .= " ORDER BY";
			foreach($sort as $key => $value) {
				$sql .= " $key " . $value;
				
				$count--;
				if($count > 0) $sql .= ",";
			}
		}
		
		if(!empty($options)) {
			if($options['start'] < 0) {
				$options['start'] = 0;
			}
			if($options['limit'] < 1) {
				$options['limit'] = 20;
			}
			$sql .= " LIMIT " . (int)$options['start'] . "," . (int)$options['limit'];
		}
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getUserById($userid) {
		$sql = "SELECT * FROM `users` WHERE `user_id` = '" . (int)$userid . "' LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getUserByEmail($useremail) {
		$sql = "SELECT * FROM `users` WHERE `user_email` = '" . $this->db->escape($useremail) . "' LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getTotalUsers($data = array()) {
		$sql = "SELECT COUNT(*) AS count FROM `users`";
		if(!empty($data)) {
			$count = count($data);
			$sql .= " WHERE";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= " AND";
			}
		}
		$query = $this->db->query($sql);
		return $query->row['count'];
	}
	
	public function upUserBalance($userid, $value) {
	  	$query = $this->db->query("UPDATE `users` SET user_balance = user_balance+" . (float)$value . " WHERE user_id = '" . (int)$userid . "'");
	}
	public function upUserBonuses($userid, $value) {
	  	$query = $this->db->query("UPDATE `users` SET bonuses = bonuses+" . (float)$value . " WHERE user_id = '" . (int)$userid . "'");
	}
	public function downUserBonuses($userid, $value) {
	  	$query = $this->db->query("UPDATE `users` SET bonuses = bonuses-" . (float)$value . " WHERE user_id = '" . (int)$userid . "'");
	}
	public function upUserRMoney($userid, $value) {
	  	$query = $this->db->query("UPDATE `users` SET rmoney = rmoney+" . (float)$value . " WHERE user_id = '" . (int)$userid . "'");
	}
	public function downUserBalance($userid, $value) {
	  	$query = $this->db->query("UPDATE `users` SET user_balance = user_balance-" . (float)$value . " WHERE user_id = '" . (int)$userid . "'");
	}
	public function createAuthLog($userid, $ip, $status, $password) {
		$ipDetail=array();
		$f = file_get_contents("http://ip-api.com/xml/".$ip."?lang=ru");
		 
		//Получаем название города
		preg_match("@<city>(.*?)</city>@si", $f, $city);
		//$ipDetail['city'] = ($city AND $city[2]) ? $city[2] : ''; 
		 $ipDetail['city'] = $city[1];
		//Получаем название страны
		preg_match("@<country>(.*?)</country>@si", $f, $country);
		$ipDetail['country'] = $country[1];
		 
		//Получаем код страны
		preg_match("@<countryCode>(.*?)</countryCode>@si", $f, $countryCode);
		$ipDetail['countryCode'] = $countryCode[1];
		
	  	$query=$this->db->query("INSERT INTO `authlog` (`id`, `user`, `ip`, `city`, `country`, `code`, `datetime`, `status`, `password`) VALUES (NULL, '".$userid."', '".$ip."', '".$ipDetail['city']."', '".$ipDetail['country']."', '".$ipDetail['countryCode']."', NOW(), '".$status."', '".$password."');");
	}
	
	public function getAuthLog($userid) {
		$sql = "SELECT * FROM `authlog` WHERE `user` = '".(int)$userid."' ORDER BY(`id`) DESC LIMIT 20";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getIdByEmail($email) {
		$sql = "SELECT `user_id` FROM `users` WHERE `user_email` = '" . $this->db->escape($email) . "' LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getUserByUser_vk_id($user_vk_id) {
		$sql = "SELECT * FROM `users` WHERE `user_vk_id` = '" . (int)$user_vk_id . "' LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getSkidkaByCode($code, $rewrite) {
		$sql = "SELECT `skidka` FROM `promo` WHERE `cod` = '" . $this->db->escape($code) . "' AND `used` < `uses` LIMIT 1";
		$query = $this->db->query($sql);
		
		if($rewrite == true){
			$zapros = $this->db->query("UPDATE `promo` SET `used`= `used`+1 WHERE `cod` = '" . $this->db->escape($code) . "' AND `used` < `uses` LIMIT 1");
		}
		return $query->row;
	}
	
	public function getStatisticsRegistersById() {
        $sql = "SELECT count(user_id), DATE(`user_date_reg`) mydate FROM users GROUP BY mydate;";
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function deleteUser($user_id) {
		$sql = "DELETE FROM `users` WHERE user_id = '" . (int)$user_id . "'";
		$this->db->query($sql);
	}

	public function getUserMods($userid) {
		$sql = "SELECT * FROM `users_mods` WHERE `user_id` = '" . (int)$userid . "' ";
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function checkFreeMode($userid, $modid) {
		$sql = "SELECT * FROM `users_mods` WHERE `user_id` = '" . (int)$userid . "' and `mod_id` = '". (int)$modid ."' LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row;
	}
	public function addMode($data) {
		$sql = "INSERT INTO `users_mods` SET ";
		$sql .= "user_id = '" . (int)$data['user_id'] . "', ";
		$sql .= "mod_id = '" . (int)$data['mod_id'] . "'";
		$this->db->query($sql);
		return $this->db->getLastId();
	}
	
	public function getUserRepos($userid) {
		$sql = "SELECT * FROM `users_repo` WHERE `user_id` = '" . (int)$userid . "' ";
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function addRepos($data) {
		$sql = "INSERT INTO `users_repo` SET ";
		$sql .= "user_id = '" . (int)$data['user_id'] . "', ";
		$sql .= "repo_id = '" . (int)$data['repo_id'] . "'";
		$this->db->query($sql);
		return $this->db->getLastId();
	}
}
?>
