<?
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class webLocationsModel extends Model {
	public function createLocation($data) {
		$sql = "INSERT INTO `web_locations` SET ";
		$sql .= "location_name = '" . $this->db->escape($data['location_name']) . "', ";
		$sql .= "location_ip = '" . $this->db->escape($data['location_ip']) . "', ";
		$sql .= "location_url = '" . $this->db->escape($data['location_url']) . "', ";
		$sql .= "location_user = '" . $this->db->escape($data['location_user']) . "', ";
		$sql .= "location_password = '" . $this->db->escape($data['location_password']) . "', ";
		$sql .= "ns_servers = '" . $this->db->escape($data['ns_servers']) . "', ";
		$sql .= "location_status = '" . (int)$data['location_status'] . "'";
		$this->db->query($sql);
		return $this->db->getLastId();
	}
	
	public function deleteLocation($locationid) {
		$sql = "DELETE FROM `web_locations` WHERE location_id = '" . (int)$locationid . "'";
		$this->db->query($sql);
	}
	
	public function updateLocation($locationid, $data = array()) {
		$sql = "UPDATE `web_locations`";
		if(!empty($data)) {
			$count = count($data);
			$sql .= " SET";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= ",";
			}
		}
		$sql .= " WHERE `location_id` = '" . (int)$locationid . "'";
		$query = $this->db->query($sql);
		return true;
	}
	
	public function getLocations($data = array(), $sort = array(), $options = array()) {
		$sql = "SELECT * FROM `web_locations`";
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
	
	public function getLocationById($locationid) {
		$sql = "SELECT * FROM `web_locations` WHERE `location_id` = '" . (int)$locationid . "' LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getTotalLocations($data = array()) {
		$sql = "SELECT COUNT(*) AS count FROM `web_locations`";
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
