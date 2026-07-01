<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class ticketsModel extends Model {

	public function createTicket($data) {
		$sql = "INSERT INTO `tickets` SET ";
		$sql .= "user_id = '" . (int)$data['user_id'] . "', ";
		$sql .= "ticket_name = '" . $this->db->escape($data['ticket_name']) . "', ";
		$sql .= "ticket_status = '" . (int)$data['ticket_status'] . "', ";
		$sql .= "ticket_date_add = NOW(),";
		$sql .= "category_id = '" . (int) @$data['category_id'] . "'";
		$this->db->query($sql);
		return $this->db->getLastId();
	}
	
	public function deleteTicket($ticketid) {
		$sql = "DELETE FROM `tickets` WHERE ticket_id = '" . (int)$ticketid . "'";
		$this->db->query($sql);
	}
	
	public function dateCreateInfo($userid)
	{
		$sql = 'SELECT COUNT(*) AS count FROM `tickets` WHERE `user_id` = ' . $userid . ' AND DATE_FORMAT(`ticket_date_add`, \'%Y-%m-%d\') BETWEEN DATE_SUB(CURDATE(),INTERVAL 1 DAY) AND CURDATE()';
		$query = $this->db->query($sql);
		return $query->row['count'];
	}
	
	public function spam($userid)
	{
		$sql = 'SELECT COUNT(*) AS count FROM `tickets_messages` WHERE `user_id` = ' . $userid . ' AND DATE_FORMAT(`ticket_message_date_add`, \'%Y-%m-%d %H:%i:%s\') BETWEEN DATE_SUB(NOW(),INTERVAL 10 SECOND) AND NOW()';
		$query = $this->db->query($sql);
		return $query->row['count'];
	}
	
	public function updateTicket($ticketid, $data = array()) {
		$sql = "UPDATE `tickets`";
		if(!empty($data)) {
			$count = count($data);
			$sql .= " SET";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= ",";
			}
		}
		$sql .= " WHERE `ticket_id` = '" . (int)$ticketid . "'";
		$query = $this->db->query($sql);
		return true;
	}
	
	public function getTickets($data = array(), $joins = array(), $sort = array(), $options = array()) {
		$sql = "SELECT * FROM `tickets`";
		foreach($joins as $join) {
			$sql .= " LEFT JOIN $join";
			switch($join) {
				case "users":
					$sql .= " ON tickets.user_id=users.user_id";
					break;
				case "tickets_category":
					$sql .= " ON tickets.category_id=tickets_category.category_id";
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
	
	public function getTicketById($ticketid, $joins = array()) {
		$sql = "SELECT * FROM `tickets`";
		foreach($joins as $join) {
			$sql .= " LEFT JOIN $join";
			switch($join) {
				case "users":
					$sql .= " ON tickets.user_id=users.user_id";
					break;
			}
		}
		$sql .=  " WHERE `ticket_id` = '" . (int)$ticketid . "' LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getTotalTickets($data = array()) {
		$sql = "SELECT COUNT(*) AS count FROM `tickets`";
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
