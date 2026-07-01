<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class webhostModel extends Model {
	public function createWebhost($data) 
	{	
		$sql = "INSERT INTO `webhost` SET ";
		$sql .= "`user_id` = '" . (int)$data['user_id'] . "', ";
		$sql .= "`web_password` = '" . $data['web_password'] . "', ";
		$sql .= "`web_domain` = 'webhost.installing', ";
		$sql .= "`tarif_id` = '" . (int)$data['tarif_id'] . "', ";
		$sql .= "`web_status` = '" . (int)$data['web_status'] . "', ";
		$sql .= "`web_date_reg` = NOW(), ";
		$sql .= "`web_date_end` = NOW() + INTERVAL " . (int)$data['web_days'] . " DAY,";
		$sql .= "`location_id` = '" . (int)$data['location_id'] . "'";
		$this->db->query($sql);
		$return=$this->db->getLastId();		
		return $return;
	}
	
	public function installWebhost($webid, $password, $package)
	{
		$web = $this->getWebhostById($webid, array('web_locations'));
		$domain = 'ws'.$webid.'.webhosting';
		$url = 'https://'. $web['location_ip'] .':1500/ispmgr?authinfo='. $web['location_user'] .':'. $web['location_password'] .'&out=json';
		$postdata = '&' . http_build_query(array('func' => 'user.add', 'name' => 'ws'.$webid.'', 'domain' => $domain, 'fullname' => $this->user->getLastname(). ' ' .$this->user->getFirstname(), 'preset' => $package, 'status' => 1, 'passwd' => $password, 'sok' => 'ok'));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url . $postdata);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        $response = curl_exec($curl);
		$response_end = json_decode($response, true);
		$error = curl_error($curl);
		if ($error) {
			return array('status' => 'error', 'description' => 'Возникла ошибка запроса к серверу. Код ошибки: ' . $error);
		} else {
			if (isset($response_end['doc']['error']) && isset($response_end['doc']['error']['$object'])) {
                if ($response_end['doc']['error']['$object']  == 'name' && $response_end['doc']['error']['$type']  == 'value') {
					$sql = "DELETE FROM `webhost` WHERE web_id = '" . (int)$webid . "'";
					$this->db->query($sql);
					return array('status' => 'error', 'description' => 'На сервер был отправлен неверный USERNAME!');
                } elseif ($response_end['doc']['error']['$object'] == 'passwd') {
					$sql = "DELETE FROM `webhost` WHERE web_id = '" . (int)$webid . "'";
					$this->db->query($sql);
					return array('status' => 'error', 'description' => 'Пароль отправленный на сервер не соответствует нормам!');
                } elseif ($response_end['doc']['error']['$object']  == 'user' && $response_end['doc']['error']['$type'] == 'exists') {
					$sql = "DELETE FROM `webhost` WHERE web_id = '" . (int)$webid . "'";
					$this->db->query($sql);
                    return array('status' => 'error', 'description' => 'Пользователь ws'.$webid.' уже существует на сервере!');
                } else {
					$sql = "DELETE FROM `webhost` WHERE web_id = '" . (int)$webid . "'";
					$this->db->query($sql);
                    return array('status' => 'error', 'description' => $response_end['doc']['error']['msg']['$']);
				}
            }
            if (isset($response_end['doc']['ok'])) {
				$this->updateWebhost($webid, array('web_domain' => $domain));
				return array('status' => 'success');
            }
		}
	}

	public function deleteWebhost($webid)
	{
		$web = $this->getWebhostById($webid, array('web_locations'));
		if($web['web_status'] == 0) {
			$result = $this->unblockWebhost($webid);
			if($result["status"] != "success") {
				return array('status' => 'error', 'description' => $result["description"]);
			}
		}
		$url = 'https://'. $web['location_ip'] .':1500/ispmgr?authinfo='. $web['location_user'] .':'. $web['location_password'] .'&out=json';
		$postdata = '&' . http_build_query(array('func' => 'user.delete', 'elid' => 'ws'.$webid.''));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url . $postdata);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        $response = curl_exec($curl);
		$response_end = json_decode($response, true);
		$error = curl_error($curl);
		if ($error) {
			return array('status' => 'error', 'description' => 'Возникла ошибка запроса к серверу. Код ошибки: ' . $error);
		} else {
            if (isset($response_end['doc']['ok'])) {
				$sql = "DELETE FROM `webhost` WHERE web_id = '" . (int)$webid . "'";
				$this->db->query($sql);
				return array('status' => 'success');
            }
			
            if ($response_end['doc']['error']['$object']  == 'elid' && $response_end['doc']['error']['$type'] == 'value') {
                return array('status' => 'error', 'description' => 'Пользователь ws'.$webid.' не существует на сервере!');
			} else {
                return array('status' => 'error', 'description' => $response_end['doc']['error']['msg']['$']);
			}
		}
	}
	
	public function blockWebhost($webid)
	{
		$web = $this->getWebhostById($webid, array('web_locations'));
		$url = 'https://'. $web['location_ip'] .':1500/ispmgr?authinfo='. $web['location_user'] .':'. $web['location_password'] .'&out=json';
		$postdata = '&' . http_build_query(array('func' => 'user.suspend', 'elid' => 'ws'.$webid.''));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url . $postdata);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        $response = curl_exec($curl);
		$response_end = json_decode($response, true);
		$error = curl_error($curl);
		if ($error) {
			return array('status' => 'error', 'description' => 'Возникла ошибка запроса к серверу. Код ошибки: ' . $error);
		} else {
			if (isset($response_end['doc']['error'])) {
                if ($response_end['doc']['error']['$object']  == 'users' && $response_end['doc']['error']['$type'] == 'missed') {
                    return array('status' => 'error', 'description' => 'Пользователь ws'.$webid.' не существует на сервере!');
				} else {
					return array('status' => 'error', 'description' => $response_end['doc']['error']['msg']['$']);
				}
            }
            if (isset($response_end['doc']['ok'])) {
				$this->updateWebhost($webid, array('web_status' => 0));
				return array('status' => 'success');
            }
		}
	}
	
	public function unblockWebhost($webid)
	{
		$web = $this->getWebhostById($webid, array('web_locations'));
		$url = 'https://'. $web['location_ip'] .':1500/ispmgr?authinfo='. $web['location_user'] .':'. $web['location_password'] .'&out=json';
		$postdata = '&' . http_build_query(array('func' => 'user.resume', 'elid' => 'ws'.$webid.''));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url . $postdata);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        $response = curl_exec($curl);
		$response_end = json_decode($response, true);
		$error = curl_error($curl);
		if ($error) {
			return array('status' => 'error', 'description' => 'Возникла ошибка запроса к серверу. Код ошибки: ' . $error);
		} else {
            if (isset($response_end['doc']['ok'])) {
				$this->updateWebhost($webid, array('web_status' => 1));
				return array('status' => 'success');
            }
			
			if (isset($response_end['doc']['error'])) {
                if ($response_end['doc']['error']['$object']  == 'users' && $response_end['doc']['error']['$type'] == 'missed') {
                    return array('status' => 'error', 'description' => 'Пользователь ws'.$webid.' не существует на сервере!');
				} else {
					return array('status' => 'error', 'description' => $response_end['doc']['error']['msg']['$']);
				}
            }
		}
	}

	public function updatepassUser($webid, $password)
	{
		$web = $this->getWebhostById($webid, array('web_locations'));
		$url = 'https://'. $web['location_ip'] .':1500/ispmgr?authinfo='. $web['location_user'] .':'. $web['location_password'] .'&out=json';
		$postdata = '&' . http_build_query(array('func' => 'user.edit', 'elid' => 'ws'.$webid.'', 'sok'  => 'ok', 'passwd' => $password));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url . $postdata);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        $response = curl_exec($curl);
		$response_end = json_decode($response, true);
		$error = curl_error($curl);
		if ($error) {
			return array('status' => 'error', 'description' => 'Возникла ошибка запроса к серверу. Код ошибки: ' . $error);
		} else {
			if (isset($response_end['doc']['error'])) {
                if ($response_end['doc']['error']['$object']  == 'elid' && $response_end['doc']['error']['$type'] == 'value') {
                    return array('status' => 'error', 'description' => 'Пользователь ws'.$webid.' не существует на сервере!');
                } elseif ($response_end['doc']['error']['$object'] == 'passwd' || $response_end['doc']['error']['$type'] == 'value') {
					return array('status' => 'error', 'description' => 'Пароль отправленный на сервер не соответствует нормам!');
				} else {
					return array('status' => 'error', 'description' => $response_end['doc']['error']['msg']['$']);
				}
			}
            if (isset($response_end['doc']['ok'])) {
				return array('status' => 'success');
            }				
		}
	}
	
	public function updateWebhost($webid, $data = array()) 
	{
		$sql = "UPDATE `webhost`";
		if(!empty($data)) {
			$count = count($data);
			$sql .= " SET";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= ",";
			}
		}
		$sql .= " WHERE `web_id` = '" . (int)$webid . "'";
		$query = $this->db->query($sql);
	}
	
	public function extendWebhost($webid, $days, $fromCurrent) 
	{
		$sql = "UPDATE `webhost` SET web_date_end = ";
		if($fromCurrent)
			$sql .= "NOW()";
		else
			$sql .= "web_date_end";
		$sql .= "+INTERVAL " . (int)$days . " DAY WHERE web_id = '" . (int)$webid . "'";
		
		$this->db->query($sql);
	}
	
	public function getWebhosts($data = array(), $joins = array(), $sort = array(), $options = array()) 
	{
		$sql = "SELECT * FROM `webhost`";
		foreach($joins as $join) {
			$sql .= " LEFT JOIN $join";
			switch($join) {
				case "users":
					$sql .= " ON webhost.user_id=users.user_id";
					break;
				case "web_tarifs":
					$sql .= " ON webhost.tarif_id=web_tarifs.tarif_id";
					break;
				case "web_locations":
					$sql .= " ON webhost.location_id=web_locations.location_id";
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
	
	public function getWebhostById($webid, $joins = array()) 
	{
		$sql = "SELECT * FROM `webhost`";
		foreach($joins as $join) {
			$sql .= " LEFT JOIN $join";
			switch($join) {
				case "users":
					$sql .= " ON webhost.user_id=users.user_id";
					break;
				case "web_tarifs":
					$sql .= " ON webhost.tarif_id=web_tarifs.tarif_id";
					break;
				case "web_locations":
					$sql .= " ON webhost.location_id=web_locations.location_id";
					break;
			}
		}
		$sql .=  " WHERE `web_id` = '" . (int)$webid . "' LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getTotalWebhosts($data = array()) 
	{
		$sql = "SELECT COUNT(*) AS count FROM `webhost`";
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
}
?>