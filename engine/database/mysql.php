<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
final class mysqlDriver {
	private $link;
	private $count = 0;
		
	public function __construct($hostname, $username, $password, $database) {
		if (!$this->link = @mysqli_connect($hostname, $username, $password)) {
			exit('Ошибка: Не удалось соединиться с сервером базы данных!');
		}

		if (!mysqli_select_db($this->link, $database)) {
			exit('Ошибка: Не удалось соединиться с базой ' . $database);
		}
			
		mysqli_query($this->link, "SET NAMES 'utf8'");
		mysqli_query($this->link, "SET CHARACTER SET utf8");
		mysqli_query($this->link, "SET CHARACTER_SET_CONNECTION=utf8");
		mysqli_query($this->link, "SET SQL_MODE = ''");
	}
			
	public function query($sql) {
		$resource = mysqli_query($this->link, $sql);
			
		$this->count++;
			
		if($resource) {
			if($resource instanceof mysqli_result) {
				$i = 0;
				$data = array();
					
				while($result = mysqli_fetch_assoc($resource)) {
					$data[$i] = $result;
					$i++;
				}
					
				mysqli_free_result($resource);
					
				$query = new stdClass();
				$query->row = isset($data[0]) ? $data[0] : array();
				$query->rows = $data;
				$query->num_rows = $i;
					
				unset($data);
				return $query;	
			} else {
				return true;
			}
		} else {
			exit('Ошибка: ' . mysqli_error($this->link) . '<br>Номер ошибки: ' . mysqli_errno($this->link) . '<br>' . $sql);
		}
	}
		
	public function escape($value) {
		$value = str_replace(array("'", "\"", "%", "`", "縗", "\xbf\x27"), "", $value);
		return mysqli_real_escape_string($this->link, $value);
	}
		
	public function countAffected() {
		return mysqli_affected_rows($this->link);
	}

	public function getLastId() {
		return mysqli_insert_id($this->link);
	}	
		
	public function getCount() {
		return $this->count;
	}
		
	public function __destruct() {
		mysqli_close($this->link);
	}
}	
?>