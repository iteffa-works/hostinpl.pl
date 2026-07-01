<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class User {
	private $registry;

	private $user_id;
	private $email;
	private $firstname;
	private $lastname;
	private $balance;
	private $access_level;
    private $user_img;
	private $test_server;
	private $user_vk_id;
	
  	public function __construct($registry) {
		$this->registry = $registry;
		if(isset($this->registry->cookie->data['uid'])) {
			$query = $this->registry->db->query("SELECT * FROM `users_auth` LEFT JOIN `users` ON users_auth.user_id=users.user_id WHERE `auth_key` = '" . $this->registry->db->escape(@$this->registry->cookie->data['uid']) . "'");	
			if ($query->num_rows) {
				$this->user_id = $query->row['user_id'];
				$this->email = $query->row['user_email'];
				$this->firstname = $query->row['user_firstname'];
				$this->lastname = $query->row['user_lastname'];
				$this->balance = $query->row['user_balance'];
				$this->access_level = $query->row['user_access_level'];
				$this->user_img = $query->row['user_img'];
				$this->test_server = $query->row['test_server'];
				$this->user_vk_id = $query->row['user_vk_id'];
				
				$this->registry->db->query("UPDATE `users_auth` SET `user_ip` = '" . $this->registry->db->escape($this->getRealIpAdress()) . "', `user_last_activity` = NOW() WHERE `auth_key` = '" . @$this->registry->db->escape($this->registry->cookie->data['uid']) . "'");
			} else {
				$this->logout();
			}
		}
  	}
	
  	public function login($email, $password) {
		$query = $this->registry->db->query("SELECT * FROM users WHERE user_email = '" . $this->registry->db->escape($email) . "' AND user_password = '" . $this->registry->db->escape($password) . "' AND user_status = '1'");

		if($query->num_rows) {
			$key = sha1(mt_rand(11111, 99999) . $this->GenerateHash(mt_rand(50, 200)) . microtime()) . sha1(mt_rand(11111, 99999) . $this->GenerateHash(mt_rand(50, 200)) . $query->row['user_password']) . sha1(mt_rand(11111, 99999) . $this->GenerateHash(mt_rand(50, 200)) . $query->row['user_email']) . sha1(mt_rand(11111, 99999) . $this->GenerateHash(mt_rand(50, 200)) . $query->row['user_id']);
			$this->registry->cookie->set('uid', $key);
					
			$this->user_id = $query->row['user_id'];
			$this->email = $query->row['user_email'];
			$this->firstname = $query->row['user_firstname'];
			$this->lastname = $query->row['user_lastname'];
			$this->balance = $query->row['user_balance'];
			$this->access_level = $query->row['user_access_level'];
			$this->user_img = $query->row['user_img'];	
			$this->test_server = $query->row['test_server'];	
			$this->registry->db->query("INSERT INTO `users_auth` SET `user_id` = '" . $query->row['user_id'] . "', `user_ip` = '" . $this->registry->db->escape($this->getRealIpAdress()) . "', `user_last_activity` = NOW(), `auth_user_email` = '" . $this->registry->db->escape($query->row['user_email']) . "', `auth_user_password` = '" . $this->registry->db->escape($query->row['user_password']) . "', `auth_key` = '" . $this->registry->db->escape($key) . "', `auth_type` = 'Password', `auth_date_add` = NOW()");		
	  		return true;
		} else {
	  		return false;
		}
  	}
	
	public function loginVk($vkid) {
		$query = $this->registry->db->query("SELECT * FROM users WHERE user_vk_id = '" . (int)$vkid . "'");
		if($query->num_rows) {
			$key = sha1(mt_rand(11111, 99999) . $this->GenerateHash(mt_rand(50, 200)) . microtime()) . sha1(mt_rand(11111, 99999) . $this->GenerateHash(mt_rand(50, 200)) . $query->row['user_password']) . sha1(mt_rand(11111, 99999) . $this->GenerateHash(mt_rand(50, 200)) . $query->row['user_email']) . sha1(mt_rand(11111, 99999) . $this->GenerateHash(mt_rand(50, 200)) . $query->row['user_id']);
			$this->registry->cookie->set('uid', $key);
					
			$this->user_id = $query->row['user_id'];
			$this->email = $query->row['user_email'];
			$this->firstname = $query->row['user_firstname'];
			$this->lastname = $query->row['user_lastname'];
			$this->balance = $query->row['user_balance'];
			$this->access_level = $query->row['user_access_level'];
			$this->user_img = $query->row['user_img'];	
			$this->test_server = $query->row['test_server'];	
			$this->registry->db->query("INSERT INTO `users_auth` SET `user_id` = '" . $query->row['user_id'] . "', `user_ip` = '" . $this->registry->db->escape($this->getRealIpAdress()) . "', `user_last_activity` = NOW(), `auth_user_email` = '" . $this->registry->db->escape($query->row['user_email']) . "', `auth_user_password` = '" . $this->registry->db->escape($query->row['user_password']) . "', `auth_key` = '" . $this->registry->db->escape($key) . "', `auth_type` = 'Vk', `auth_date_add` = NOW()");		
	  		return true;
		} else {
	  		return false;
		}
	}
	
	public function correctData() {
		$query = $this->registry->db->query("SELECT * FROM `users_auth` LEFT JOIN `users` ON users_auth.user_id=users.user_id WHERE `auth_key` = '" . $this->registry->db->escape(@$this->registry->cookie->data['uid']) . "'");
		if($query->num_rows) {
			if($query->row['auth_user_email'] == $query->row['user_email'] && $query->row['auth_user_password'] == $query->row['user_password']) return true;
		}
	}
	
	public function isLogged() {
		if($this->correctData()) {
			$query = $this->registry->db->query("SELECT * FROM users WHERE user_id = '" . $this->user_id . "'");
			if ($query->num_rows) {
				if($query->row['user_status'] != 1) {
					$this->logout();
				} elseif($query->row['user_activate'] != 1) {
					$this->logout();
				} else {
					return $this->user_id;
				}
			} else {
				$this->logout();
			}
		} else {
			$this->logout();
			return false;
		}
	}	

  	public function logout() {
		$this->registry->db->query("DELETE FROM `users_auth` WHERE `auth_key` = '" . $this->registry->db->escape(@$this->registry->cookie->data['uid']) . "'");
		$this->registry->cookie->remove('uid');
			
		$this->user_id = null;
		$this->email = null;
		$this->firstname = null;		
		$this->lastname = null;
		$this->balance = null;
		$this->access_level = 0;
		$this->user_vk_id = null;
  	}
  
  	public function getId() {
		return $this->user_id;
  	}
	
  	public function getEmail() {
		return $this->email;
  	}
	
  	public function getFirstname() {
		return $this->firstname;
  	}
  	public function getLastname() {
		return $this->lastname;
  	}
	public function getUser_img() {
		return $this->user_img;
  	}
  	public function getBalance() {
		return $this->balance;
  	}
	public function getUser_vk_id() {
		return $this->user_vk_id;
  	}
	
  	public function getAccessLevel() {
		return $this->access_level;
  	}

  	public function getTest_server() {
		return $this->test_server;
	}
	
	public function getRealIpAdress() {
		if (!empty($_SERVER["HTTP_CF_CONNECTING_IP"])) {
			return $_SERVER["HTTP_CF_CONNECTING_IP"];
		}
			
		foreach (array('REMOTE_ADDR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED') as $key) {
			if (array_key_exists($key, $_SERVER) === true) {
				foreach (explode(',', $_SERVER[$key]) as $ip) {
					$ip = trim($ip);
						
					if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
						return $ip;
					}
				}
			}
		}	
		return @$_SERVER['REMOTE_ADDR'];
	}
	
	private function GenerateHash($length = 12) {
		$chars="qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP0!@#$%^&*()_+";
		$size = strlen($chars) - 1;

		$password = null;
			
		while($length--) {
			$password .= $chars[rand(0, $size)];
		}
		return $password;
	}
}
?>
