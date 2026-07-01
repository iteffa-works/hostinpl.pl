<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)

serversModel:
- Игровые сервера
- Планировщик задач
- Друзья
- Firewall
- Автоустановка модов
- Репозиторий
- Статистика игровых серверов
- Логи игрового сервера

*/
class serversModel extends Model {
	/* =================== Игровые сервера =================== */
	public function createServer($data) {
		$sql = "INSERT INTO `servers` SET ";
		$sql .= "`user_id` = '" . (int)$data['user_id'] . "', ";
		$sql .= "`game_id` = '" . (int)$data['game_id'] . "', ";
		$sql .= "`location_id` = '" . (int)$data['location_id'] . "', ";
		$sql .= "`server_mysql` = '0', ";
		$sql .= "`server_slots` = '" . (int)$data['server_slots'] . "', ";
		$sql .= "`server_port` = '" . (int)$data['server_port'] . "', ";
		$sql .= "`server_password` = '" . $this->db->escape($data['server_password']) . "', ";
		$sql .= "`server_status` = '" . (int)$data['server_status'] . "', ";
		$sql .= "`server_work` = '1', ";
		$sql .= "server_date_reg = NOW(), ";
		
		if($data['test_periud'] == false){
            $sql .= "server_date_end = NOW() + INTERVAL " . (int)$data['server_days'] . " DAY";
		} 
		elseif($data['test_periud'] == true) {
			$sql .= "`server_date_end` = NOW() + INTERVAL 3 DAY";
			$this->db->query("UPDATE `users` SET `test_server` = '2' WHERE `user_id` = '{$this->user->getId()}'");
		}
		$this->db->query($sql);
		$return=$this->db->getLastId();		
		return $return;
	}
	
	public function promisedServer($serverid) {		
		$this->action($serverid, "updatepassm");
		$this->action($serverid, "updatepass");
		$this->db->query("UPDATE `servers` SET server_date_end = server_date_end +INTERVAL 3 DAY WHERE server_id = '" . (int)$serverid . "'");
		$this->updateServer($serverid, array('server_status' => 1));
		return array('status' => 'success');
	}
	
	public function deleteServer($serverid) {
		$this->db->query("DELETE FROM `servers` WHERE server_id = '" . (int)$serverid . "'");
	}
	
	public function blockServer($serverid) {
		$sql = "DELETE FROM `servers` WHERE server_id = '" . (int)$serverid . "'";
		$this->db->query($sql);
	}
	
	public function updateServer($serverid, $data = array()) {
		$sql = "UPDATE `servers`";
		if(!empty($data)) {
			$count = count($data);
			$sql .= " SET";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= ",";
			}
		}
		$sql .= " WHERE `server_id` = '" . (int)$serverid . "'";
		$query = $this->db->query($sql);
	}
	
	public function getServers($data = array(), $joins = array(), $sort = array(), $options = array()) {
		$sql = "SELECT * FROM `servers`";
		foreach($joins as $join) {
			$sql .= " LEFT JOIN $join";
			switch($join) {
				case "users":
					$sql .= " ON servers.user_id=users.user_id";
					break;
				case "games":
					$sql .= " ON servers.game_id=games.game_id";
					break;
				case "locations":
					$sql .= " ON servers.location_id=locations.location_id";
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
	
	public function getServerById($serverid, $joins = array()) {
		$sql = "SELECT * FROM `servers`";
		foreach($joins as $join) {
			$sql .= " LEFT JOIN $join";
			switch($join) {
				case "users":
					$sql .= " ON servers.user_id=users.user_id";
					break;
				case "games":
					$sql .= " ON servers.game_id=games.game_id";
					break;
				case "locations":
					$sql .= " ON servers.location_id=locations.location_id";
					break;
			}
		}
		$sql .=  " WHERE `server_id` = '" . (int)$serverid . "' LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getTotalServerOwners($data = array()) {
		$sql = "SELECT COUNT(*) AS count FROM `servers_owners`";
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
	
	public function getTotalServers($data = array()) {
		$sql = "SELECT COUNT(*) AS count FROM `servers`";
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

	public function getServerNewPort($locationid, $min, $max) {
		for($i = $min; $i < $max; $i += 2) {
			$sql = "SELECT COUNT(*) AS total FROM `servers` WHERE location_id = '" . (int)$locationid . "' AND server_port = '" . (int)$i . "' LIMIT 1";
			$query = $this->db->query($sql);
			if($query->row['total'] == 0) {
				return $i;
			}
		}
		return null;
	}
	
	public function getGameServerPortList($locationid, $min, $max) {		
		for($i = $min; $i < $max; $i += 2) {
			$query = $this->db->query("SELECT COUNT(*) AS count FROM `servers` WHERE location_id = '" . (int)$locationid . "' AND server_port = '" . (int)$i . "'");
			if(!$query->row['count']) {
				$ports[] = $i;
			}
		}
		
		return $ports;
	}

	public function getServerSystemLoad($serverid) {
		$this->load->library('ssh2');
		$ssh2Lib = new ssh2Library();
		$server = $this->getServerById($serverid, array('users', 'locations', 'games'));
		$link = $ssh2Lib->connect($server["location_ip"], $server["location_user"], $server["location_password"]);
		$stats = @explode(' ', explode(PHP_EOL, $ssh2Lib->execute($link, 'docker stats --all --no-stream gs' . $server['server_id'] . ' | awk \'{print $3" "$7}\''))[1]);
		$cpu = @$stats[0];
		$ram = @$stats[1];
		$output['ssd'] = 0;
		$ssd = $ssh2Lib->execute($link, 'du -scm /home/gs' . $server['server_id'] . ' | tail -1 | sed \'s/[^0-9]//g\'');
		if($ssd) {
			$output['ssd'] = $ssd;
		}	
		$output['ram'] = $ram;
		$output['cpu'] = $cpu;
		$output['ssd'] = $output['ssd'];
		$ssh2Lib->disconnect($link);
		return $output;
	}
	
	public function extendServer($serverid, $month, $fromCurrent) {
		$sql = "UPDATE `servers` SET server_date_end = ";
		if($fromCurrent)
			$sql .= "NOW()";
		else
			$sql .= "server_date_end";
		$sql .= "+INTERVAL " . (int)$month . " DAY WHERE server_id = '" . (int)$serverid . "'";
		$this->db->query($sql);
	}
	
	public function slotsServer($serverid, $slots) {
		$sql = "UPDATE `servers` SET server_slots = '" . (int)$slots . "' WHERE server_id = '" . (int)$serverid . "'";		
		$this->db->query($sql);
	}

	public function getHDD($serverid) {
		$this->load->library('ssh2');
		$ssh2Lib = new ssh2Library();
		$server = $this->getServerById($serverid, array('users', 'locations', 'games'));
		$link = $ssh2Lib->connect($server['location_ip'], $server['location_user'], $server['location_password']);
		$output = (int)$ssh2Lib->execute($link, "du -scm /home/gs".$server['server_id']." | tail -1 | sed 's/[^0-9]//g'");
		$ssh2Lib->disconnect($link);
		return $output;
	}	
	
	public function gameConfigs($serverid) {
		$this->load->library('ssh2');	
		$ssh2Lib = new ssh2Library();
		$server = $this->getServerById($serverid, array('users', 'locations', 'games'));
		$link = $ssh2Lib->connect($server['location_ip'], $server['location_user'], $server['location_password']);	
		
		$configs = array();
		if($server['game_query'] == 'samp') {
			$configs = array(
				array(
					'File' => '/server.cfg',
					'ExecPattern' => false,
					'Required' => 1,
					'Values' => array(
						array(
							'Pattern' => 'maxplayers <value>',
							'Value' => $server['server_slots'],
							'Required' => 1
						),
						array(
							'Pattern' => 'bind <value>',
							'Value' => $server['location_ip2'],
							'Required' => 1
						),
						array(
							'Pattern' => 'port <value>',
							'Value' => $server['server_port'],
							'Required' => 1
						)
					)
				)
			);
		} else if($server['game_code'] == 'cs') {
			if($server['server_fps'] == 0) {
				$server_fps = 300;
			} else {
				$server_fps = $server['server_fps'];
			}
			$configs = array(
				array(
					'File' => '/cstrike/server.cfg',
					'ExecPattern' => 'exec <value>',
					'Required' => 1,
					'Values' => array(
						array(
							'Pattern' => 'sys_ticrate "<value>',
							'Value' => ''.$server_fps.'"',
							'Required' => 1
						)
					)
				)
			);
		} else if($server['game_code'] == 'css') {
			$configs = array();
		} else if($server['game_code'] == 'csgo') {
			$configs = array();
		} else if($server['game_code'] == 'mcpe') {
			$configs = array(
				array(
					'File' => '/server.properties',
					'Required' => 1,
					'Values' => array(
						array(
							'Pattern' => 'max-players=<value>',
							'Value' => $server['server_slots'],
							'Required' => 1
						),
						array(
							'Pattern' => 'server-port=<value>',
							'Value' => $server['server_port'],
							'Required' => 1
						),
						array(
							'Pattern' => 'memory-limit=<value>',
							'Value' => $server['game_ram'] . 'M',
							'Required' => 1
						),
						array(
							'Pattern' => 'server-ip=<value>',
							'Value' => $server['location_ip2'],
							'Required' => 1
						),
						array(
							'Pattern' => 'query.port=<value>',
							'Value' => $server['server_port'],
							'Required' => 1
						),
						array(
							'Pattern' => 'enable-ipv6=<value>',
							'Value' => 'off',
							'Required' => 1
						)
					)
				)
			);
		} else if($server['game_code'] == 'mine') {
			$configs = array(
				array(
					'File' => '/server.properties',
					'ExecPattern' => false,
					'Required' => 2,
					'Values' => array(
						array(
							'Pattern' => 'max-players=<value>',
							'Value' => $server['server_slots'],
							'Required' => 1
						),
						array(
							'Pattern' => 'server-ip=<value>',
							'Value' => $server['location_ip2'],
							'Required' => 1
						),
						array(
							'Pattern' => 'server-port=<value>',
							'Value' => $server['server_port'],
							'Required' => 1
						),
						array(
							'Pattern' => 'query.port=<value>',
							'Value' => $server['server_port'],
							'Required' => 1
						),
						array(
							'Pattern' => 'enable-ipv6=<value>',
							'Value' => 'off',
							'Required' => 1
						)
					)
				)
			);
		} else if($server['game_query'] == 'mtasa') {
			$http_port = $server['server_port'] + 1;
			$configs = array(
				array(
					'File' => '/mods/deathmatch/mtaserver.conf',
					'ExecPattern' => false,
					'Required' => 1,
					'Values' => array(
						array(
							'Pattern' => '<serverip><value>',
							'Value' => ''.$server['location_ip2'].'</serverip>',
							'Required' => 1
						),
						array(
							'Pattern' => '<serverport><value>',
							'Value' => ''.$server['server_port'].'</serverport>',
							'Required' => 1
						),
						array(
							'Pattern' => '<maxplayers><value>',
							'Value' => ''.$server['server_slots'].'</maxplayers>',
							'Required' => 1
						),
						array(
							'Pattern' => '<httpport><value>',
							'Value' => ''.$http_port.'</httpport>',
							'Required' => 1
						),
						array(
							'Pattern' => '<bandwidth_reduction><value>',
							'Value' => 'medium</bandwidth_reduction>',
							'Required' => 0
						),
						array(
							'Pattern' => '<fpslimit><value>',
							'Value' => '36</fpslimit>',
							'Required' => 0
						)
					)
				)
			);
		} else if($server['game_code'] == 'ragemp') {	
			$configs = array(
				array(
					'File' => '/conf.json',
					'ExecPattern' => false,
					'Required' => 1,
					'Values' => array(
						array(
							'Pattern' => '"bind" : "<value>',
							'Value' => ''.$server['location_ip2'].'",',
							'Required' => 1
						),
						array(
							'Pattern' => '"port" : <value>',
							'Value' => ''.$server['server_port'].',',
							'Required' => 1
						),
						array(
							'Pattern' => '"maxplayers" : <value>',
							'Value' => ''.$server['server_slots'].',',
							'Required' => 1
						)
					)
				)
			);
		} else {
			return array('status' => 'error', 'description' => 'Для данной игры не найдены настройки конфигурации!');
		}
				
		$this->load->library('sftp');		
		$sftpLib = new sftpLibrary();		
		$sftpLink = $sftpLib->connect($server['location_ip'], $server['location_user'], $server['location_password']);

		foreach($configs as $cfg) {
			$file = $sftpLib->open($sftpLink, '/home/gs' . $serverid . '/' . $cfg['File']);
			if(empty($file) && $cfg['Required'] == 1) {
				$ssh2Lib->execute($link, 'cp -Rp /home/cp/gameservers/files/' . $server['game_code'] .'/'. $cfg['File'] . ' /home/gs' . $serverid . '/');
				$ssh2Lib->execute($link, 'chown gs' . $serverid . ':gameservers -Rf /home/gs' . $serverid .'/'. $cfg['File']);
			}
		}
							
		foreach($configs as $cfg) {
			$file = $sftpLib->open($sftpLink, '/home/gs' . $serverid . '/' . $cfg['File']);

			if(empty($file) && $cfg['Required'] == 1) {
				return array('status' => 'error', 'description' => 'Ошибка конфигурации!');
			}
								
			foreach($cfg['Values'] as $value) {
				$pattern = str_replace('<value>', '(.*)', $value['Pattern']);
				$replace = str_replace('<value>', $value['Value'], $value['Pattern']);

				if($value['Required'] == 1 && !preg_match('/' . $pattern . '/', $file)) {
					$file .= "\r\n" . $pattern;
				} else if($value['Required'] == -1 && preg_match('/' . $pattern . '/', $file)) {
					return False;
				}
				$file = preg_replace('/' . $pattern . '/', $replace, $file);
			}
			if($file != null) $sftpLib->write($sftpLink, '/home/gs' . $serverid . '/' . $cfg['File'], $file);
		}
		$ssh2Lib->disconnect($link);
	}
	
	public function gameFiles($serverid) {
		$server = $this->getServerById($serverid, array('users', 'locations', 'games'));

		if($server['game_code'] == 'samp') {
			$check_files = 'samp03svr announce samp-npc';
		} else if($server['game_code'] == 'crmp') {
			$check_files = 'samp03svr-cr announcr samp-npc';
		} else if($server['game_code'] == 'crmp037') {
			$check_files = 'samp03svr-cr announcr adap-npc';	
		} else if($server['game_code'] == 'unit') {
			$check_files = 'samp03svr-u2 announu2 adap-npc';	
		} else if($server['game_code'] == 'cs') {
			$check_files = 'hlds_run hlds_linux';	
		} else if($server['game_code'] == 'css') {
			$check_files = 'srcds_linux srcds_run';
		} else if($server['game_code'] == 'csgo') {
			$check_files = 'srcds_linux srcds_run';
		} else if($server['game_code'] == 'mcpe') {
			if($server['server_binary_version'] == '7.0' || $server['server_binary_version'] == '7.2' || $server['server_binary_version'] == '7.3' || $server['server_binary_version'] == '7.4' || $server['server_binary_version'] == '8.0') {
				$check_files = 'bin/php7/bin/php PocketMine-MP.phar';
			} else if($server['server_binary_version'] == 'Java') {
				$check_files = 'Java/bin/java src/nukkit/nukkit.jar';
			} else if ($server['server_binary_version'] == 'VanillaCpp') {
				$check_files = 'bedrock_server';
			}
		} else if($server['game_code'] == 'mine') {
			$check_files = 'Java/bin/java minecraft.jar';
		} else if($server['game_code'] == 'mta') {
			$check_files = 'mta-server';
		} else if($server['game_code'] == 'ragemp') {	
			$check_files = 'server';
		}
		return $check_files;
	}
	
	public function gameBin($serverid) {
		$server = $this->getServerById($serverid, array('users', 'locations', 'games'));

		if($server['game_code'] == 'mcpe') {
			if($server['server_binary_version'] == '7.0') {
				$bin = 'php7.0/bin';
			} else if($server['server_binary_version'] == '7.2') {
				$bin = 'php7.2/bin';
			} else if($server['server_binary_version'] == '7.3') {
				$bin = 'php7.3/bin';
			} else if($server['server_binary_version'] == '7.4') {
				$bin = 'php7.4/bin';
			} else if($server['server_binary_version'] == '8.0') {
				$bin = 'php8.0/bin';
			} else if($server['server_binary_version'] == 'Java') {
				$bin = 'Java';
			}
		} else if($server['game_code'] == 'mine') {
			if($server['server_binary_version'] == 'Java') {
				$bin = 'Java';
			} 
		}
		return $bin;
	}

	public function action($serverid, $action = "", $data = array()) {
		$this->load->library('ssh2');	
		$ssh2Lib = new ssh2Library();
		$server = $this->getServerById($serverid, array('users', 'locations', 'games'));
		$link = $ssh2Lib->connect($server['location_ip'], $server['location_user'], $server['location_password']);
				
		if($link == false) {
			return array('status' => 'error', 'description' => 'Ошибка: Не удалось соединиться с сервером '.$server['location_ip'].'!');
		}
		
		$action = mb_strtolower($action);

		switch($action) {	
			case "start": {
				$cores_loc = $ssh2Lib->execute($link, 'lscpu | grep -E \'^CPU\(\' | awk \'{print $2}\'');
				if(round($cores_loc, 2) < $server['game_cores']) {
					return array('status' => 'error', 'description' => 'Для сервера указан слишком большой процент CPU, превышающий рамки игровой локации. Обратитесь к администрации хостинга для устранения проблемы.');
				}
					
				$this->gameConfigs($serverid);

				if($server['game_code'] == 'samp') {
					$execCmd = './samp03svr';
				} else if($server['game_code'] == 'crmp') {
					$execCmd = './samp03svr-cr';
				} else if($server['game_code'] == 'crmp037') {
					$execCmd = './samp03svr-cr';	
				} else if($server['game_code'] == 'unit') {
					$execCmd = './samp03svr-u2';	
				} else if($server['game_code'] == 'cs') {
					if($server['server_fps'] == 0) {
						$server_fps = 300;
					} else {
						$server_fps = $server['server_fps'];
					}
					$execCmd = './hlds_run -debug -game cstrike -norestart -condebug -sys_ticrate '. $server_fps .' +servercfgfile server.cfg +sys_ticrate '. $server_fps .' +map de_dust2 +maxplayers '.$server['server_slots'].' +ip '. $server['location_ip2'] .' +port '.$server['server_port'].' +sv_lan 0';	
				} else if($server['game_code'] == 'css') {	
					if($server['server_tickrate'] == 0) {
						$server_tickrate = 66;
					} else {
						$server_tickrate = $server['server_tickrate'];
					}
					$execCmd = './srcds_run -debug -game cstrike -norestart -condebug console.log -tickrate '.$server_tickrate.' +map de_dust2 +maxplayers '.$server['server_slots'].' +ip '.$server['location_ip2'].' +port '.$server['server_port'].' -sv_lan 0';
				} else if($server['game_code'] == 'csgo') {
					if($server['server_tickrate'] == 0) {
						$server_tickrate = 64;
					} else {
						$server_tickrate = $server['server_tickrate'];
					}
					$execCmd = './srcds_run -debug -game csgo -norestart -usercon -condebug console.log -tickrate '. $server_tickrate .' +map de_dust2 -maxplayers_override '.$server['server_slots'].' +ip '. $server['location_ip2'] .' +net_public_adr '. $server['location_ip2'] .' +port '.$server['server_port'].' -sv_lan 0';
				} else if($server['game_code'] == 'mcpe') {
					if($server['server_binary_version'] == '7.0' || $server['server_binary_version'] == '7.2' || $server['server_binary_version'] == '7.3' || $server['server_binary_version'] == '7.4' || $server['server_binary_version'] == '8.0') {
						$execCmd = './bin/php7/bin/php ./PocketMine-MP.phar';
					} else if($server['server_binary_version'] == 'Java') {
						$execCmd = './Java/bin/java -jar nukkit.jar';
					} else if ($server['server_binary_version'] == 'VanillaCpp') {
						$execCmd = './bedrock_server';
					}
				} else if($server['game_code'] == 'mine') {
					$execCmd = './Java/bin/java -Xmx' . $server['game_ram'] . 'M -Xms' . $server['game_ram'] . 'M -jar ./minecraft.jar --nojline';
				} else if($server['game_code'] == 'mta') {
					$execCmd = './mta-server';
				} else if($server['game_code'] == 'ragemp') {	
					$execCmd = './server';
				}

				$user = explode(':', $ssh2Lib->execute($link, 'cat /etc/passwd | grep gs' . $serverid . ':'));
				$ssh2Lib->execute($link, 'docker run -d -t --rm --name=gs' . $serverid . ' --network=host --cpus="' . $server['game_cores'] . '" --memory=' . $server['game_ram'] . 'M --memory-swap=-1 --volume="/home/gs' . $serverid . '/:/home/container/" --workdir=/home/container hostinpl:games');
				$ssh2Lib->execute($link, 'docker exec gs' . $serverid . ' groupadd -g ' . $user[3] . ' gameservers');
				$ssh2Lib->execute($link, 'docker exec gs' . $serverid . ' useradd -u ' . $user[2] . ' -g gameservers -p ' . crypt(mt_rand(111111111, 999999999), 'tlas') . ' -d /home/container/ gs' . $serverid);
				$ssh2Lib->execute($link, 'docker exec gs' . $serverid . ' chmod -Rf 700 /home/container | docker exec gs' . $serverid . ' chown -Rf gs' . $serverid . ':gameservers /home/container');
				if($server['game_code'] == 'cs' || $server['game_code'] == 'css' || $server['game_code'] == 'csgo') {
					if($server['server_vac'] == 1) {
						$execCmd .= ' -secure';
					} else {
						$execCmd .= ' -insecure';
					}
					if($server['fastdl_status'] == 1) {
						$this->action($serverid, "fastdl_on");
					}
				}
				sleep(1);
				if($server['game_query'] == 'mtasa' || $server['game_code'] == 'mcpe' || $server['game_code'] == 'cs' || $server['game_code'] == 'css' || $server['game_code'] == 'csgo') {
					$ssh2Lib->execute($link, 'docker exec -d gs' . $serverid . ' sudo -u gs' . $serverid . ' screen -dmS gs' . $serverid . ' ' . $execCmd);
				} else {
					$ssh2Lib->execute($link, 'docker exec -d gs' . $serverid . ' sudo -u gs' . $serverid . ' screen -L -dmS gs' . $serverid . ' ' . $execCmd);
				}
				$this->deleteTask(array('task_name' => 'enable', 'server_id' => $serverid));
				sleep(1);
				$result = array('status' => 'success');
				break;
			}
			
			case "stop": {
				$ssh2Lib->execute($link, 'docker stop -t=3 gs'. $serverid. '');
				if($server['game_code'] == 'cs' || $server['game_code'] == 'css' || $server['game_code'] == 'csgo') {
					if($server['fastdl_status'] = 1) {
						if($server['game_code'] == 'csgo') {
							$folder = "csgo/cfg";
						} else if($server['game_code'] == 'css') {	
							$folder = "cstrike/cfg";
						} else if($server['game_code'] == 'cs') {
							$folder = "cstrike";
						}
						$ssh2Lib->execute($link, 'rm /var/nginx/fastdl_gs' . $serverid . '');
					}
				}
				if(!empty($data['restart'])) return $this->action($serverid, 'start');
				else {
					$this->deleteTask(array('task_name' => 'disable', 'server_id' => $serverid));
					$this->deleteTask(array('task_name' => 'restart', 'server_id' => $serverid));
				}
				$result = array('status' => 'success');
				break;
			}
			
			case "restart": {
				return $this->action($serverid, 'stop', array('restart' => true));
				break;
			}
			
			case "fastdl_on": {
				if($server['game_code'] == 'csgo') {
					$folder = "csgo/cfg";
					$folder_web = "csgo";
				} else if($server['game_code'] == 'css') {	
					$folder = "cstrike/cfg";
					$folder_web = "cstrike";
				} else if($server['game_code'] == 'cs') {
					$folder = "cstrike";
					$folder_web = "cstrike";
				}						
				$ssh2Lib->execute($link, 'ln -s /home/gs' . $serverid . '/' . $folder_web . ' /var/nginx/fastdl_gs' . $serverid . '');
				$result = array('status' => 'success');
				break;
			}
			
			case "block": {
				$random = mt_rand(11111111, 99999999);
				$random2 = mt_rand(11111111, 99999999);
				$ssh2Lib->execute($link, 'usermod -p ' . crypt($random, 'tlas') . ' gs' . $serverid);
				$ssh2Lib->execute($link, 'mysql -e "grant usage on *.* to gs' . $serverid . '@\'%\' identified by \'' . $random2 . '\'"');
				$ssh2Lib->execute($link, 'mysql -e "grant all privileges on gs' . $serverid . '.* to \'gs' . $serverid . '\'@\'%\' identified by \'' . $random2 . '\'"');
				$result = array('status' => 'success');
				break;
			}
				
			case "install": {
				$ssh2Lib->execute($link, 'useradd -m -g gameservers -p ' . crypt($server['server_password'], 'tlas') . ' gs' . $serverid);
				if($server['game_code'] == 'mcpe' || $server['game_code'] == 'mine') {
					$corebinary = $this->game_settings->cores[$server['game_code']]['latest_core']['corebinary'];
					$this->updateServer($serverid, array('server_binary' => $this->game_settings->cores[$server['game_code']]['latest_core']['corepath'], 'server_binary_version' => $corebinary));
					$ssh2Lib->execute($link, 'cp -Rp /home/cp/gameservers/files/' . $server['game_code'] . '/* /home/gs' . $serverid . '/; cp /home/cp/gameservers/cores/' . $server['game_code'] . '/' . $this->game_settings->cores[$server['game_code']]['latest_core']['corepath'] . '/' . $this->game_settings->cores[$server['game_code']]['latest_core']['corename'] . ' /home/gs' . $serverid . '/');
					if($corebinary == '7.0' || $corebinary == '7.2' || $corebinary == '7.3' || $corebinary == '7.4' || $corebinary == '8.0' || $corebinary == 'Java') {
						$ssh2Lib->execute($link, 'cp -Rp /home/binaries/' . $this->gameBin($serverid) . '* /home/gs' . $serverid . '/');
					}
				} else if($server['game_code'] == 'cs') {
					$this->updateServer($serverid, array('server_binary' => $this->game_settings->builds[$server['game_code']]['latest_build']['buildpath']));
					$ssh2Lib->execute($link, 'cp -Rp /home/cp/gameservers/cores/' . $server['game_code'] . '/' . $this->game_settings->builds[$server['game_code']]['latest_build']['buildpath'] . '/* /home/gs' . $serverid . '/');
				} else if($server['game_code'] == 'ragemp') {
					$this->updateServer($serverid, array('server_binary' => $this->game_settings->versions[$server['game_code']]['latest']['path']));
					$ssh2Lib->execute($link, 'cp -Rp /home/cp/gameservers/cores/' . $server['game_code'] . '/' . $this->game_settings->versions[$server['game_code']]['latest']['path'] . '/* /home/gs' . $serverid . '/');
				} else {
					$ssh2Lib->execute($link, 'cp -Rp /home/cp/gameservers/files/' . $server['game_code'] . '/* /home/gs' . $serverid . '/;');
				}
				$this->action($serverid, 'end_change');	
				$this->updateServer($serverid, array('server_status' => 1));
				$result = array('status' => 'success');
				break;
			}

			case "reinstall": {
				$ssh2Lib->execute($link, 'cd /home/gs' . $serverid . '/ && chattr -i ' . $this->gameFiles($serverid));
				$ssh2Lib->execute($link, 'rm -Rf /home/gs' . $serverid . '/*');
				if($server['game_code'] == 'mcpe' || $server['game_code'] == 'mine') {
					$this->updateServer($serverid, array('server_binary' => $this->game_settings->cores[$server['game_code']]['latest_core']['corepath'], 'server_binary_version' => $this->game_settings->cores[$server['game_code']]['latest_core']['corebinary']));
					$ssh2Lib->execute($link, 'cp -Rp /home/cp/gameservers/files/' . $server['game_code'] . '/* /home/gs' . $serverid . '/; cp /home/cp/gameservers/cores/' . $server['game_code'] . '/' . $this->game_settings->cores[$server['game_code']]['latest_core']['corepath'] . '/' . $this->game_settings->cores[$server['game_code']]['latest_core']['corename'] . ' /home/gs' . $serverid . '/');
					if($server['server_binary_version'] == '7.0' || $server['server_binary_version'] == '7.2' || $server['server_binary_version'] == '7.3' || $server['server_binary_version'] == '7.4' || $server['server_binary_version'] == '8.0' || $server['server_binary_version'] == 'Java') {
						$ssh2Lib->execute($link, 'cp -Rp /home/binaries/' . $this->gameBin($serverid) . '* /home/gs' . $serverid . '/');
					}	
				} else if($server['game_code'] == 'cs') {
					$this->updateServer($serverid, array('server_binary' => $this->game_settings->builds[$server['game_code']]['latest_build']['buildpath']));
					$ssh2Lib->execute($link, 'cp -Rp /home/cp/gameservers/cores/' . $server['game_code'] . '/' . $this->game_settings->builds[$server['game_code']]['latest_build']['buildpath'] . '/* /home/gs' . $serverid . '/');
				} else if($server['game_code'] == 'ragemp') {
					$this->updateServer($serverid, array('server_binary' => $this->game_settings->versions[$server['game_code']]['latest']['path']));
					$ssh2Lib->execute($link, 'cp -Rp /home/cp/gameservers/cores/' . $server['game_code'] . '/' . $this->game_settings->versions[$server['game_code']]['latest']['path'] . '/* /home/gs' . $serverid . '/');
				} else {
					$ssh2Lib->execute($link, 'cp -Rp /home/cp/gameservers/files/' . $server['game_code'] . '/* /home/gs' . $serverid . '/;');
				}
				$this->action($serverid, 'end_change');	
				$this->updateServer($serverid, array('server_status' => 1));
				$result = array('status' => 'success');
				break;
			}
			
			case "updatepass": {
				$ssh2Lib->execute($link, 'usermod -p ' . crypt($server['server_password'], 'tlas') . ' gs' . $serverid);
				$result = array('status' => 'success');
				break;
			}
			
			case "updatepassm": {
				$ssh2Lib->execute($link, 'mysql -e "grant usage on *.* to gs' . $serverid . '@\'%\' identified by \'' . $server['db_pass'] . '\'"');
				$ssh2Lib->execute($link, 'mysql -e "grant all privileges on gs' . $serverid . '.* to \'gs' . $serverid . '\'@\'%\' identified by \'' . $server['db_pass'] . '\'"');
				$result = array('status' => 'success');
				break;
			}
			
			case "create_mysql": {
				$chars="qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP"; 
				$max=10; 
				$size=StrLen($chars)-1; 
				$dbpass=null; 
				while($max--) 
				$dbpass.=$chars[rand(0,$size)]; 
				$ssh2Lib->execute($link, 'mysql -e "create database gs' . $serverid . '"');
				$ssh2Lib->execute($link, 'mysql -e "grant usage on *.* to gs' . $serverid . '@\'%\' identified by \'' . $dbpass . '\'"');
				$ssh2Lib->execute($link, 'mysql -e "grant all privileges on gs' . $serverid . '.* to \'gs' . $serverid . '\'@\'%\' identified by \'' . $dbpass . '\'"');
				$this->updateServer($serverid, array('db_pass' => $dbpass));
				$result = array('status' => 'success');
				break;
			}
			
			case "delete_mysql": {
				$ssh2Lib->execute($link, 'mysql -e "DROP DATABASE gs' . $serverid . ';"');
				$ssh2Lib->execute($link, 'mysql -e "DROP USER \'gs' . $serverid . '\'@\'%\';"');
				$result = array('status' => 'success');
				break;
			}
			
			case "end_change": {
				$files = explode(' ', $this->gameFiles($serverid));
				foreach($files as $item){
					$ssh2Lib->execute($link, 'chmod 700 /home/gs' . $serverid . '/' . $item);
					$ssh2Lib->execute($link, 'chown gs' . $serverid . ':gameservers /home/gs' . $serverid . '/' . $item);
				}
				$ssh2Lib->execute($link, 'chown -Rf gs' . $serverid . ':gameservers /home/gs' . $serverid . '');
				$ssh2Lib->execute($link, 'chmod -R 700 /home/gs' . $serverid);
				$ssh2Lib->execute($link, 'cd /home/gs' . $serverid . '/ && chattr +i ' . $this->gameFiles($serverid));
				$this->gameConfigs($serverid);
				$result = array('status' => 'success');
				break;
			}
		
			case "sendcommand": {
				if($data['command'] == 'unsetFiles') {
					$ssh2Lib->execute($link, 'cd /home/gs' . $serverid . '/ && chattr -i ' . $this->gameFiles($serverid));
				} else if($data['command'] == 'setFiles') {
					$ssh2Lib->execute($link, 'cd /home/gs' . $serverid . '/ && chattr +i ' . $this->gameFiles($serverid));
				} else if($data['command'] == 'installNewCore') {
					$ssh2Lib->execute($link, 'rm -Rf /home/gs' . $serverid . '/*');					
					$ssh2Lib->execute($link, "rm -Rf /home/cp/backups/gs" . $serverid . ".tar");
					if($server['server_binary_version'] == '7.0' || $server['server_binary_version'] == '7.2' || $server['server_binary_version'] == '7.3' || $server['server_binary_version'] == '7.4' || $server['server_binary_version'] == '8.0' || $server['server_binary_version'] == 'Java') {
						$ssh2Lib->execute($link, 'cp -Rp /home/binaries/' . $this->gameBin($serverid) . '* /home/gs' . $serverid . '/');
					}
					$ssh2Lib->execute($link, 'cp -Rp /home/cp/gameservers/files/' . $server['game_code'] . '/* /home/gs' . $serverid . '/; cp /home/cp/gameservers/cores/' . $server['game_code'] . '/' . $this->game_settings->cores[$server['game_code']][$server['server_binary']]['corepath'] . '/' . $this->game_settings->cores[$server['game_code']][$server['server_binary']]['corename'] . ' /home/gs' . $serverid . '/');
					$this->action($serverid, 'end_change');	
					$this->updateServer($serverid, array('server_status' => 1));	
				} else if($data['command'] == 'installNewBuild') {
					$ssh2Lib->execute($link, 'rm -Rf /home/gs' . $serverid . '/*');					
					$ssh2Lib->execute($link, "rm -Rf /home/cp/backups/gs" . $serverid . ".tar");
					$ssh2Lib->execute($link, 'cp -Rp /home/cp/gameservers/cores/' . $server['game_code'] . '/' . $this->game_settings->builds[$server['game_code']][$server['server_binary']]['buildpath'] . '/* /home/gs' . $serverid . '/');
					$this->action($serverid, 'end_change');	
					$this->updateServer($serverid, array('server_status' => 1));	
				} else if($data['command'] == 'installNewVersion') {
					$ssh2Lib->execute($link, 'rm -Rf /home/gs' . $serverid . '/*');					
					$ssh2Lib->execute($link, "rm -Rf /home/cp/backups/gs" . $serverid . ".tar");
					$ssh2Lib->execute($link, 'cp -Rp /home/cp/gameservers/cores/' . $server['game_code'] . '/' . $this->game_settings->versions[$server['game_code']][$server['server_binary']]['path'] . '/* /home/gs' . $serverid . '/');
					$this->action($serverid, 'end_change');	
					$this->updateServer($serverid, array('server_status' => 1));	
				} else if($data['command'] == 'loadModuleJS') {
					$ssh2Lib->execute($link, '
						cd /home/gs' . $serverid . ';
						mkdir node_modules;
						cd /home/gs' . $serverid . '/node_modules;
						rm -rf ' . $this->game_settings->node_modules[$server['game_code']][$data['module']]['modulepath'] . ';
						mkdir ' . $this->game_settings->node_modules[$server['game_code']][$data['module']]['modulepath'] . ';
						cd ' . $this->game_settings->node_modules[$server['game_code']][$data['module']]['modulepath'] . ';
						wget ' . $this->game_settings->node_modules[$server['game_code']][$data['module']]['moduleurl'] . $this->game_settings->node_modules[$server['game_code']][$data['module']]['modulearchive'] . ';
						unzip ' . $this->game_settings->node_modules[$server['game_code']][$data['module']]['modulearchive'] . ';
						rm ' . $this->game_settings->node_modules[$server['game_code']][$data['module']]['modulearchive'] . ';
						chown -Rf gs' . $serverid . ':gameservers /home/gs' . $serverid . '/node_modules;
						chmod -R 700 /home/gs' . $serverid . '/node_modules;
					');
				} else {
					$ssh2Lib->execute($link, 'docker exec -d -u gs' . $serverid . ' gs' . $serverid . ' screen -S gs' . $serverid . ' -X eval \'stuff "' . $data['command'] . '\015"\' > /dev/null 2>&1');
				}
				$result = array('status' => 'success');
				break;
			}
			
			case "createbackup": {
				if($server['server_mysql'] ==  1 || $server['server_mysql'] ==  2) {
					$ssh2Lib->execute($link, "cd /home/gs" . $serverid . "; mkdir backup_mysql;  cd backup_mysql; mysqldump -Q -c -e -ugs".$serverid." -h".$server['location_ip']." -p".$server['db_pass']." gs".$serverid." > database.sql; cd;");
				}
				$ssh2Lib->execute($link, "tar --totals -cvf /home/cp/backups/gs" . $serverid . ".tar /home/gs" . $serverid);
				if($server['server_mysql'] ==  1 || $server['server_mysql'] ==  2) {
					$ssh2Lib->execute($link, "rm -rf /home/gs" . $serverid . "/backup_mysql");
				}
				$this->updateServer($serverid, array('server_status' => 1));
				$result = array('status' => 'success');
				break;
			}
			
			case "installbackup": {
				$ssh2Lib->execute($link, 'cd /home/gs' . $serverid . '/ && chattr -i ' . $this->gameFiles($serverid));
				$ssh2Lib->execute($link, "rm -Rf /home/gs" . $serverid . "/*");
				$ssh2Lib->execute($link, "tar -C \"/home/gs" . $serverid . "\" -xvf /home/cp/backups/gs" . $serverid . ".tar");
				$ssh2Lib->execute($link, "cp -rp /home/gs" . $serverid . "/home/gs" . $serverid . "/* /home/gs" . $serverid . "/");
				$ssh2Lib->execute($link, "rm -Rf /home/gs" . $serverid . "/home");
				$ssh2Lib->execute($link, 'chown gs' . $serverid . ' -Rf /home/gs' . $serverid);
				$ssh2Lib->execute($link, 'cd /home/gs' . $serverid . '/ && chattr +i ' . $this->gameFiles($serverid));
				$this->gameConfigs($serverid);
				$this->updateServer($serverid, array('server_status' => 1));
				$result = array('status' => 'success');
				break;
			}
			
			case "delete_backup": {
				$ssh2Lib->execute($link, "rm -Rf /home/cp/backups/gs" . $serverid . ".tar");
				$result = array('status' => 'success');
				break;
			}
			
			case "delete": {
				$ssh2Lib->execute($link, "userdel -rf gs" . $serverid);
				$ssh2Lib->execute($link, 'cd /home/gs' . $serverid . '/ && chattr -i ' . $this->gameFiles($serverid));
				$ssh2Lib->execute($link, "rm -Rf /home/gs" . $serverid);
				$ssh2Lib->execute($link, "rm -Rf /home/cp/backups/gs" . $serverid . ".tar");
				foreach($this->getTasks(array('server_id' => $serverid)) as $task) {
					$this->deleteTask(array('task_id' => $task['task_id']));
				}
				$result = array('status' => 'success');
				break;
			}

			case "server_status": {
				$status = $ssh2Lib->execute($link, 'docker ps --all | grep gs' . $server['server_id'] . ' | awk \'{print $1}\'');
					
				if($status) {
					$status = 2;// Контейнер запущен
				} else {
					$status = 1;// Контейнер не запущен
				}
				$result = $status;
				break;
			}
		}
		$ssh2Lib->disconnect($link);
		return $result;
	}	
	
	
	/* ================== Планировщик задач ================== */
	public function createTask($data) {
		$sql = "INSERT INTO `servers_tasks` SET ";
		$sql .= "`server_id` = '" . (int)$data['server_id'] . "', ";
		$sql .= "`task_name` = '" . $this->db->escape($data['task_name']) . "', ";
		$sql .= "`task_type` = '" . $this->db->escape($data['task_type']) . "', ";
		$sql .= "`task_time` = '" . $this->db->escape($data['task_time']) . "', ";
		if(!empty($data['isSystemTask'])) $sql .= "`isSystemTask` = '" . (int)$data['isSystemTask'] . "', ";
		$sql .= "`task_lead_time` = " . $data['task_lead_time'];
		$this->db->query($sql);
		return $this->db->getLastId();
	}
		
	public function deleteTask($data) {
		if(!empty($data)) {
			$sql = "DELETE FROM `servers_tasks`";
			$count = count($data);
			$sql .= " WHERE";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
					
				$count--;
				if($count > 0) $sql .= " AND";
			}
			$this->db->query($sql);
		}
	}
		
	public function updateTask($taskid, $data = array()) {
		$sql = "UPDATE `servers_tasks`";
		if(!empty($data)) {
			$count = count($data);
			$sql .= " SET";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
					
				$count--;
				if($count > 0) $sql .= ",";
			}
		}
		$sql .= " WHERE `task_id` = '" . (int)$taskid . "'";
		$query = $this->db->query($sql);
	}
		
	public function getTasks($data = array(), $joins = array(), $sort = array(), $options = array()) {
		$sql = "SELECT * FROM `servers_tasks`";
		foreach($joins as $join) {
			$sql .= " LEFT JOIN $join";
			switch($join) {
				case "servers":
					$sql .= " ON servers.server_id=servers_tasks.server_id";
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
			if (@$options['start'] < 0) {
				@$options['start'] = 0;
			}
			if (@$options['limit'] < 1) {
				@$options['limit'] = 20;
			}
			$sql .= " LIMIT " . (int)@$options['start'] . "," . (int)@$options['limit'];
		}
		$query = $this->db->query($sql);
		return $query->rows;
	}
		
	public function getTaskById($taskid, $joins = array()) {
		$sql = "SELECT * FROM `servers_tasks`";
		foreach($joins as $join) {
			$sql .= " LEFT JOIN $join";
			switch($join) {
				case "servers":
					$sql .= " ON servers.server_id=servers_tasks.server_id";
				break;
			}
		}
		$sql .=  " WHERE `task_id` = '" . (int)$taskid . "' LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row;
	}
	/* ======================================================= */
	
	/* ===================== Совладельцы ===================== */
	public function createOwner($data) {
		$sql = "INSERT INTO `servers_owners` SET ";
		$sql .= "server_id = '" . $data['server_id'] . "', ";
		$sql .= "user_id = '" . $data['user_id'] . "', ";
		$sql .= "owner_status = '" . $data['owner_status'] . "', ";
		$sql .= "owner_add = NOW()";
		$this->db->query($sql);
		
		return $this->db->getLastId();
	}
	
	public function deleteOwner($ownerid) {
		$this->db->query("DELETE FROM `servers_owners` WHERE owner_id = '".(int)$ownerid."'");
		
		return true;
	}
	
	public function deleteserverOwners($serverid)
	{
		$sql = "DELETE FROM `servers_owners` WHERE server_id = '".(int)$serverid."'";
		$this->db->query($sql);
	}
	
	public function updateOwner($ownerid, $data = array()) {
		$sql = "UPDATE `servers_owners`";
		if(!empty($data)) {
			$count = count($data);
			$sql .= " SET";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= ",";
			}
		}
		$sql .= " WHERE `owner_id` = '" . (int)$ownerid . "'";
		$query = $this->db->query($sql);
		
		return true;
	}

	public function getOwners($data = array(), $joins = array(), $sort = array(), $options = array()) {
		$sql = "SELECT * FROM `servers_owners`";
		foreach($joins as $join) {
			$sql .= " LEFT JOIN $join";
			switch($join) {
				case "users":
					$sql .= " ON servers_owners.user_id=users.user_id";
					break;
				case "servers":
					$sql .= " ON servers_owners.server_id=servers.server_id";
					break;
				case "games":
					$sql .= " ON servers.game_id=games.game_id";
					break;
				case "locations":
					$sql .= " ON servers.location_id=locations.location_id";
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
	
	public function getOwnerById($ownerid) {
		$query = $this->db->query("SELECT * FROM `servers_owners` WHERE `owner_id` = '".(int)$ownerid."'  LIMIT 1");
		
		return $query->row;
	}
	
	public function getTotalOwners($data = array()) {
		$sql = "SELECT COUNT(*) AS count FROM `servers_owners`";
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
	/* ======================================================= */
	
	/* ====================== Firewall ====================== */
	public function createFirewall($data) {
		$sql = "INSERT INTO `servers_firewalls` SET ";
		$sql .= "server_id = '" . $data['server_id'] . "', ";
		$sql .= "server_ip = '" . $this->db->escape($data['server_ip']) . "', ";
		$sql .= "firewall_add = NOW()";
		$this->db->query($sql);
		
		return $this->db->getLastId();
	}
	
	public function deleteFirewallDB($firewallid) {
		$this->db->query("DELETE FROM `servers_firewalls` WHERE firewall_id = '".(int)$firewallid."'");
		
		return true;
	}
	
	public function addFirewall($firewallid) {		
		$this->load->library('ssh2');
		$this->load->model('servers');
		$Firewall = $this->getFirewallById($firewallid);
		$server = $this->serversModel->getServerById($Firewall['server_id'], array('locations'));
		
		$ssh2Lib = new ssh2Library();
		$link = $ssh2Lib->connect($server['location_ip'], $server['location_user'], $server['location_password']);
		
		$ssh2Lib->execute($link, "iptables -I INPUT -s ".$Firewall['server_ip']." -p udp -d ".$server['location_ip2']." --dport ".$server['server_port']." -j DROP;");
		$ssh2Lib->execute($link, "iptables -I INPUT -s ".$Firewall['server_ip']." -p tcp -d ".$server['location_ip2']." --dport ".$server['server_port']." -j DROP;");
		
		$ssh2Lib->disconnect($link);

		return true;
	}
	
	public function deleteFirewall($firewallid) {
		$this->load->library('ssh2');
		$this->load->model('servers');
		$Firewall = $this->getFirewallById($firewallid);
		$server = $this->serversModel->getServerById($Firewall['server_id'], array('locations'));
		
		$ssh2Lib = new ssh2Library();
		$link = $ssh2Lib->connect($server['location_ip'], $server['location_user'], $server['location_password']);
		
		$ssh2Lib->execute($link, "iptables -D INPUT -s ".$Firewall['server_ip']." -p udp -d ".$server['location_ip2']." --dport ".$server['server_port']." -j DROP;");
		$ssh2Lib->execute($link, "iptables -D INPUT -s ".$Firewall['server_ip']." -p tcp -d ".$server['location_ip2']." --dport ".$server['server_port']." -j DROP;");
		
		$ssh2Lib->disconnect($link);
		
		$this->deleteFirewallDB($firewallid);
		
		return true;
	}
	
	public function getFirewalls($data = array(), $sort = array(), $options = array()) {
		$sql = "SELECT * FROM `servers_firewalls`";
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
	
	public function getFirewallById($firewallid) {
		$query = $this->db->query("SELECT * FROM `servers_firewalls` WHERE `firewall_id` = '".(int)$firewallid."'  LIMIT 1");	
		return $query->row;
	}
	
	public function getFirewallsById($serverid) {
		$query = $this->db->query("SELECT * FROM `servers_firewalls` WHERE `server_id` = '".(int)$serverid."'");
		return $query->rows;
	}
	
	public function getTotalFirewalls($data = array()) {
		$sql = "SELECT COUNT(*) AS count FROM `servers_firewalls`";
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
	/* ======================================================= */
	
	/* ================= Автоустановка модов ================= */
	public function createMod($data) {
		$sql = "INSERT INTO `servers_mods` SET ";		
		$sql .= "`game_id` = '" . (int)$data['game_id'] . "', ";
		$sql .= "`mod_url` = '" . $data['mod_url'] . "', ";
		$sql .= "`mod_name` = '" . $data['mod_name'] . "', ";
		$sql .= "`mod_status` = '" . (int)$data['mod_status'] . "', ";
		$sql .= "`mod_arch` = '" . $data['mod_arch'] . "', ";
		$sql .= "`mod_textx` = '" . strip_tags(htmlspecialchars_decode($this->db->escape($data['mod_textx'])), '<img><span><ul><ol><pre><li><div><em><strong><sup><code>') . "', ";
		$sql .= "`mod_img` = '" . $data['mod_img'] . "', ";	
		$sql .= "`mod_price` = '" . $data['mod_price'] . "'";
		$this->db->query($sql);
		$return=$this->db->getLastId();		
		return $return;
	}

	public function deleteMod($modid) {
		$sql = "DELETE FROM `servers_mods` WHERE mod_id = '" . (int)$modid . "'";
		$this->db->query($sql);
	}
	
	public function updateMod($modid, $data = array()) {
		$sql = "UPDATE `servers_mods`";
		if(!empty($data)) {
			$count = count($data);
			$sql .= " SET";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= ",";
			}
		}
		$sql .= " WHERE `mod_id` = '" . (int)$modid . "'";
		$query = $this->db->query($sql);
	}
	public function getMods($data = array(), $joins = array(),$sort = array(), $options = array()) {
		$sql = "SELECT * FROM `servers_mods`";
		foreach($joins as $join) {
			$sql .= " LEFT JOIN $join";
			switch($join) {
				case "games":
					$sql .= " ON servers_mods.game_id=games.game_id";
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
	 
	public function getModById($modid, $joins = array()) {
		$sql = "SELECT * FROM `servers_mods`";
		foreach($joins as $join) {
			$sql .= " LEFT JOIN $join";
			switch($join) {
				case "games":
					$sql .= " ON servers_mods.game_id=games.game_id";
					break;
			}
		}
		$sql .=  " WHERE `mod_id` = '" . (int)$modid . "' LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getTotalMods($data = array()) {
		$sql = "SELECT COUNT(*) AS count FROM `servers_mods`";
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
	/* ======================================================= */
	
	/* ===================== Репозиторий ===================== */
	public function createRepo($data) {
		$sql = "INSERT INTO `servers_repo` SET ";		
		$sql .= "`game_id` = '" . (int)$data['game_id'] . "', ";
		$sql .= "`repo_url` = '" . $data['repo_url'] . "', ";
		$sql .= "`repo_name` = '" . $data['repo_name'] . "', ";
		$sql .= "`repo_status` = '" . (int)$data['repo_status'] . "', ";
		$sql .= "`repo_textx` = '" . strip_tags(htmlspecialchars_decode($this->db->escape($data['repo_textx'])), '<img><span><ul><ol><pre><li><div><em><strong><sup><code>') . "', ";
		$sql .= "`repo_img` = '" . $data['repo_img'] . "', ";	
		$sql .= "`repo_price` = '" . $data['repo_price'] . "'";
		$this->db->query($sql);
		$return=$this->db->getLastId();		
		return $return;
	}

	public function deleteRepo($repoid) {
		$sql = "DELETE FROM `servers_repo` WHERE repo_id = '" . (int)$repoid . "'";
		$this->db->query($sql);
	}
	
	public function updateRepo($repoid, $data = array()) {
		$sql = "UPDATE `servers_repo`";
		if(!empty($data)) {
			$count = count($data);
			$sql .= " SET";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= ",";
			}
		}
		$sql .= " WHERE `repo_id` = '" . (int)$repoid . "'";
		$query = $this->db->query($sql);
	}
	public function getRepos($data = array(), $joins = array(),$sort = array(), $options = array()) {
		$sql = "SELECT * FROM `servers_repo`";
		foreach($joins as $join) {
			$sql .= " LEFT JOIN $join";
			switch($join) {
				case "games":
					$sql .= " ON servers_repo.game_id=games.game_id";
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
	 
	public function getRepoById($repoid, $joins = array()) {
		$sql = "SELECT * FROM `servers_repo`";
		foreach($joins as $join) {
			$sql .= " LEFT JOIN $join";
			switch($join) {
				case "games":
					$sql .= " ON servers_repo.game_id=games.game_id";
					break;
			}
		}
		$sql .=  " WHERE `repo_id` = '" . (int)$repoid . "' LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getTotalRepos($data = array()) {
		$sql = "SELECT COUNT(*) AS count FROM `servers_repo`";
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
	/* ======================================================= */
	
	/* ============= Статистика игровых серверов ============= */
	public function createServerStats($data) {
		$sql = "INSERT INTO `servers_stats` SET ";
		$sql .= "server_id = '" . (int)$data['server_id'] . "', ";
		$sql .= "server_stats_date = NOW(), ";
		$sql .= "server_stats_cpu = '" . (int)$data['server_stats_cpu'] . "', ";	
		$sql .= "server_stats_ram = '" . (int)$data['server_stats_ram'] . "', ";		
		$sql .= "server_stats_hdd = '" . (int)$data['server_stats_hdd'] . "', ";				
		$sql .= "server_stats_players = '" . (int)$data['server_stats_players'] . "'";
		$this->db->query($sql);
		return $this->db->getLastId();
	}

	public function clearServersStats() {
		$sql = "DELETE FROM `servers_stats` WHERE server_stats_date < NOW() - INTERVAL 7 DAY";
		$this->db->query($sql);
	}
	
	public function deleteServerStats($serverid) {
		$sql = "DELETE FROM `servers_stats` WHERE server_id = '" . (int)$serverid . "'";
		$this->db->query($sql);
	}
	
	public function getServerStats($serverid, $start, $end) {
		$sql = "SELECT * FROM `servers_stats` WHERE server_id = '" . (int)$serverid . "' AND server_stats_date BETWEEN " . $start . " AND " . $end . " ORDER BY server_stats_date";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getTotalServerStats($data = array()) {
		$sql = "SELECT COUNT(*) AS count FROM `servers_stats`";
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
	
	public function getStatisticsPlayers() {
        $sql = "SELECT sum(`server_stats_players`) , DATE_FORMAT(`server_stats_date`, '%d.%m.%Y %H:%i') mydate FROM servers_stats GROUP BY mydate order by 'server_stats_date'";
        $query = $this->db->query($sql);
        return $query->rows;
    }
	
	public function getStatisticsLoad()
	{
		$sql = 'SELECT sum(`server_stats_cpu`), sum(`server_stats_ram`), sum(`server_stats_hdd`), DATE_FORMAT(`server_stats_date`,  \'%d.%m.%Y %H:%i\') mydate FROM servers_stats GROUP BY mydate order by \'server_stats_date\'';
		$query = $this->db->query($sql);
		return $query->rows;
	}
	/* ======================================================= */
	
	/* ================ Логи игрового сервера ================ */
	public function createLog($data) {
		$sql = "INSERT INTO `serverlog` SET ";
		$sql .= "`reason` = '" . $data['reason'] . "', ";
		$sql .= "`status` = '" . (int)$data['status'] . "', ";
		$sql .= "`server_id` = '" . (int)$data['server_id'] . "', ";
		$sql .= "`date` = NOW()";
		$this->db->query($sql);
		$return=$this->db->getLastId();		
		return $return;
	}

	public function deleteLog($logid) {
		$sql = "DELETE FROM `serverlog` WHERE log_id = '" . (int)$logid . "'";
		$this->db->query($sql);
	}
 	
	public function getLogs($data = array(), $joins = array(), $sort = array(), $options = array()) {
		$sql = "SELECT * FROM `serverlog`";
		foreach($joins as $join) {
			$sql .= " LEFT JOIN $join";
			switch($join) {
				case "servers":
					$sql .= " ON serverlog.server_id=servers.server_id";
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
	
	public function getServerLogById($logid, $joins = array()) {
		$sql = "SELECT * FROM `serverlog`";
		foreach($joins as $join) {
			$sql .= " LEFT JOIN $join";
			switch($join) {
				case "servers":
					$sql .= " ON serverlog.server_id=servers.server_id";
					break;
			}
		}
		$sql .=  " WHERE `log_id` = '" . (int)$logid . "' LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getTotalLogs($data = array()) {
		$sql = "SELECT COUNT(*) AS count FROM `serverlog`";
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
	/* ======================================================= */
}
?>