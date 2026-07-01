<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class cronController extends Controller {
	public function index() {
		if(!defined('STDIN')) return "Access Denied";
		
		$this->load->library('mail');
		$this->load->library('ssh2');
		$this->load->model('webhost');
		$this->load->model('servers');
		$this->load->model('invoices');
		
		$mailLib = new mailLibrary();
		$mailLib->setFrom($this->config->mail_from);
		$mailLib->setSender($this->config->mail_sender);
		
		$servers = $this->serversModel->getServers(array(), array('users'));
		$webhosts = $this->webhostModel->getWebhosts(array(), array('users'));
		
		$datenow = date_create('now');

		foreach($servers as $item) {
			$operation = false;
			$serverid = $item['server_id'];
			$dateend = date_create($item['server_date_end']);
			$diff = date_diff($datenow, $dateend);
							
			if($diff->invert) {
				if($diff->days >= 1) {
					if($this->serversModel->action($serverid, 'delete')["status"] != "success") {
						$operation = true;
						echo "Игровой сервер: gs" . $serverid . " - удаление не удалось.". PHP_EOL;
					}
					
					if(!$operation) {
						if($item['server_mysql'] ==  1 || $item['server_mysql'] ==  2) {
							$this->serversModel->action($serverid, "delete_mysql");
						}
						$this->serversModel->deleteServer($serverid);
						$this->serversModel->deleteServerStats($serverid);
						$this->serversModel->deleteserverOwners($serverid);
						$this->serversModel->deleteLog($serverid);
						
						$firewall = $this->serversModel->getFirewallsById($serverid);

						if ($firewall) {
							foreach ($firewall as $item2) {
								$ssh2Lib = new ssh2Library();
								$link = $ssh2Lib->connect($item['location_ip'], $item['location_user'], $item['location_password']);
								
								$ssh2Lib->execute($link, "iptables -D INPUT -s ".$item2['server_ip']." -p udp -d ".$item['location_ip2']." --dport ".$item['server_port']." -j DROP;");
								$ssh2Lib->execute($link, "iptables -D INPUT -s ".$item2['server_ip']." -p tcp -d ".$item['location_ip2']." --dport ".$item['server_port']." -j DROP;");
								
								$ssh2Lib->disconnect($link);
								
								$this->serversModel->deleteFirewallDB($item2['firewall_id']);
							}
						}
									
						$mailLib->setTo($item['user_email']);
						$mailLib->setSubject("Удаление сервера #$serverid");
									
						$mailData = array();
						$mailData['firstname'] = $item['user_firstname'];
						$mailData['lastname'] = $item['user_lastname'];
						$mailData['serverid'] = $serverid;
										
						$text = $this->load->view('mail/servers/deleted', $mailData);
									
						$mailLib->setText($text);
						$mailLib->send();
					
						echo "Игровой сервер: gs" . $serverid . " - удален.". PHP_EOL;
					}
				} else if($item['server_status'] != 0) {
					if($this->serversModel->action($serverid, "stop")["status"] == "success") {
						$this->serversModel->updateServer($serverid, array('server_status' => 0));
						$this->serversModel->action($serverid, "block");
					} else {
						$operation = true;
						echo "Игровой сервер: gs" . $serverid . " - блокировка не удалась.". PHP_EOL;
					}
								
					if(!$operation) {	
						$mailLib->setTo($item['user_email']);
						$mailLib->setSubject("Блокировка сервера #$serverid");
								
						$mailData = array();
						$mailData['firstname'] = $item['user_firstname'];
						$mailData['lastname'] = $item['user_lastname'];
						$mailData['serverid'] = $serverid;
								
						$text = $this->load->view('mail/servers/lock', $mailData);
								
						$mailLib->setText($text);
						$mailLib->send();
						
						echo "Игровой сервер: gs" . $serverid . " - заблокирован.". PHP_EOL;
					}
				}
			} else {
				if($diff->days < 3) {		
					$mailLib->setTo($item['user_email']);
					$mailLib->setSubject("Завершение оплаченного периода сервера #$serverid");
										
					$mailData = array();
					$mailData['firstname'] = $item['user_firstname'];
					$mailData['lastname'] = $item['user_lastname'];
					$mailData['serverid'] = $serverid;
					$mailData['days'] = $diff->days;
							
					$text = $this->load->view('mail/servers/needPay', $mailData);
								
					$mailLib->setText($text);
					$mailLib->send();
					
					echo "Игровой сервер: gs" . $serverid . " - отправлено уведомление.". PHP_EOL;
				}
			}
		}

		foreach($webhosts as $item) {
			$operation = false;
			$webid = $item['web_id'];
			$dateend = date_create($item['web_date_end']);
			$diff = date_diff($datenow, $dateend);
							
			if($diff->invert) {
				if($diff->days >= 3) {
					$this->webhostModel->unblockWebhost($webid);
					$this->webhostModel->deleteWebhost($webid);

					$mailLib->setTo($item['user_email']);
					$mailLib->setSubject("Удаление веб-хостинга #$webid");
					
					$mailData = array();
					$mailData['firstname'] = $item['user_firstname'];
					$mailData['lastname'] = $item['user_lastname'];
					$mailData['webid'] = $webid;
					
					$text = $this->load->view('mail/webhost/deleted', $mailData);
					
					$mailLib->setText($text);
					$mailLib->send();
					
					echo "Веб-хостинг: ws" . $webid . " - удален.". PHP_EOL;
				} else if($item['web_status'] != 0) {
					$this->webhostModel->blockWebhost($webid);

					$mailLib->setTo($item['user_email']);
					$mailLib->setSubject("Блокировка веб-хостинга #$webid");
			
					$mailData = array();
					$mailData['firstname'] = $item['user_firstname'];
					$mailData['lastname'] = $item['user_lastname'];
					$mailData['webid'] = $webid;
			
					$text = $this->load->view('mail/webhost/lock', $mailData);
			
					$mailLib->setText($text);
					$mailLib->send();
					
					echo "Веб-хостинг: ws" . $webid . " - заблокирован.". PHP_EOL;
				}
			} else {
				if($diff->days < 3) {
					$mailLib->setTo($item['user_email']);
					$mailLib->setSubject("Завершение оплаченного периода веб-хостинга #$webid");
					
					$mailData = array();
					$mailData['firstname'] = $item['user_firstname'];
					$mailData['lastname'] = $item['user_lastname'];
					$mailData['webid'] = $webid;
					$mailData['days'] = $diff->days;
			
					$text = $this->load->view('mail/webhost/needPay', $mailData);
			
					$mailLib->setText($text);
					$mailLib->send();
					
					echo "Веб-хостинг: ws" . $webid . " - отправлено уведомление.". PHP_EOL;
				}
			}
		}

        $invoices = $this->invoicesModel->getInvoices(array('invoice_status' => 0), array(), array(), array());
		foreach($invoices as $item) {
			$this->invoicesModel->deleteInvoice($item['invoice_id']);
		}
		return null;
	}
	
	public function gameServers() {
		if(!defined('STDIN')) return "Access Denied";
		
		$this->load->model('servers');
		$servers = $this->serversModel->getServers(array(), array('games', 'users', 'locations'));
		
		foreach($servers as $item) {
			$serverid = $item['server_id'];
			if($item['server_status'] == 3 AND $item['server_work'] == 1) {
				$this->serversModel->updateServer($serverid, array('server_work' => 0));
				$result = $this->serversModel->action($serverid, "install");
				
				if($result['status'] == "success") {
					echo "Игровой сервер: gs" . $serverid . " - успешная установка.". PHP_EOL;
					$logData = array(
						'server_id'			=> $serverid,
						'reason'            => 'Успешная установка сервера.',
						'status'            => 1
					);
					$this->serversModel->createLog($logData);
				} else {
					$this->serversModel->updateServer($serverid, array('server_work' => 1));
					echo "Игровой сервер: gs" . $serverid . " - неудачная установка. Причина: ".$result['description']."". PHP_EOL;
				}
			}
			if($item['server_status'] == 4 AND $item['server_work'] == 1) {
				$this->serversModel->updateServer($serverid, array('server_work' => 0));
				$result = $this->serversModel->action($serverid, "reinstall");	
				if($result['status'] == "success") {
					echo "Игровой сервер: gs" . $serverid . " - успешная переустановка.". PHP_EOL;
					$logData = array(
						'server_id'			=> $serverid,
						'reason'            => 'Успешная переустановка сервера.',
						'status'            => 1
					);
					$this->serversModel->createLog($logData);
				} else {
					$this->serversModel->updateServer($serverid, array('server_work' => 1));
					echo "Игровой сервер: gs" . $serverid . " - неудачная переустановка. Причина: ".$result['description']."". PHP_EOL;
				}
			}
			if($item['server_status'] == 5 AND $item['server_work'] == 1) {
				$this->serversModel->updateServer($serverid, array('server_work' => 0));
				$result = $this->serversModel->action($serverid, 'createbackup');
				if($result['status'] == "success") {
					echo "Игровой сервер: gs" . $serverid . " - успешный бекап сервера.". PHP_EOL;
					$logData = array(
						'server_id'			=> $serverid,
						'reason'            => 'Успешное создание BackUP.',
						'status'            => 1
					);
					$this->serversModel->createLog($logData);
				} else {
					$this->serversModel->updateServer($serverid, array('server_work' => 1));
					echo "Игровой сервер: gs" . $serverid . " - неудачный бекап сервера. Причина: ".$result['description']."". PHP_EOL;
				}
			}
			if($item['server_status'] == 6 AND $item['server_work'] == 1) {
				$this->serversModel->updateServer($serverid, array('server_work' => 0));
				$result = $this->serversModel->action($serverid, "installbackup");		
				if($result['status'] == "success") {
					echo "Игровой сервер: gs" . $serverid . " - успешное восстановление из бекапа.". PHP_EOL;
					$logData = array(
						'server_id'			=> $serverid,
						'reason'            => 'Успешное восстановление из BackUP.',
						'status'            => 1
					);
					$this->serversModel->createLog($logData);
				} else {
					$this->serversModel->updateServer($serverid, array('server_work' => 1));
					echo "Игровой сервер: gs" . $serverid . " - неудачное восстановление из бекапа. Причина: ".$result['description']."". PHP_EOL;
				}
			}
			if($item['server_status'] == 7 AND $item['server_work'] == 1) {
				if($item['game_code'] == 'mine' || $item['game_code'] == 'mcpe') {
					$this->serversModel->updateServer($serverid, array('server_work' => 0));
					$result = $this->serversModel->action($serverid, 'sendcommand', array('command' => 'installNewCore'));		
					if($result['status'] == "success") {
						echo "Игровой сервер: gs" . $serverid . " - успешная смена ядра.". PHP_EOL;
						$logData = array(
							'server_id'			=> $serverid,
							'reason'            => 'Успешная смена ядра.',
							'status'            => 1
						);
						$this->serversModel->createLog($logData);
					} else {
						$this->serversModel->updateServer($serverid, array('server_work' => 1));
						echo "Игровой сервер: gs" . $serverid . " - неудачная смена ядра. Причина: ".$result['description']."". PHP_EOL;
					}
				} else if($item['game_code'] == 'cs') {
					$this->serversModel->updateServer($serverid, array('server_work' => 0));
					$result = $this->serversModel->action($serverid, 'sendcommand', array('command' => 'installNewBuild'));		
					if($result['status'] == "success") {
						echo "Игровой сервер: gs" . $serverid . " - успешная смена билда.". PHP_EOL;
						$logData = array(
							'server_id'			=> $serverid,
							'reason'            => 'Успешная смена билда.',
							'status'            => 1
						);
						$this->serversModel->createLog($logData);
					} else {
						$this->serversModel->updateServer($serverid, array('server_work' => 1));
						echo "Игровой сервер: gs" . $serverid . " - неудачная смена билда. Причина: ".$result['description']."". PHP_EOL;
					}					
				} else if($item['game_code'] == 'ragemp') {
					$this->serversModel->updateServer($serverid, array('server_work' => 0));
					$result = $this->serversModel->action($serverid, 'sendcommand', array('command' => 'installNewVersion'));		
					if($result['status'] == "success") {
						echo "Игровой сервер: gs" . $serverid . " - успешная смена версии сервера.". PHP_EOL;
						$logData = array(
							'server_id'			=> $serverid,
							'reason'            => 'Успешная смена версии сервера.',
							'status'            => 1
						);
						$this->serversModel->createLog($logData);
					} else {
						$this->serversModel->updateServer($serverid, array('server_work' => 1));
						echo "Игровой сервер: gs" . $serverid . " - неудачная смена версии сервера. Причина: ".$result['description']."". PHP_EOL;
					}					
				}
			}
		}	
		return null;
	}
	
	public function updateStatsLocations() {
		if(!defined('STDIN')) return "Access Denied";
		
		$this->load->model('locations');
		$locations = $this->locationsModel->getLocations(array());

		foreach($locations as $item) {
			$today = date("Y-m-d H:i:s");
			if($item['location_status'] == 1) {
				$this->load->library('ssh2');	
				$ssh2Lib = new ssh2Library();
				$link = $ssh2Lib->connect($item['location_ip'], $item['location_user'], $item['location_password']);
				
				$cpu = (int) $ssh2Lib->execute($link, 'echo $[100-$(vmstat 1 2|tail -1|awk \'{print $15}\')]');
				
				$hdd_used = (int) $ssh2Lib->execute($link, 'df -h -B 1M $HOME | grep dev | awk \'{print $3}\'');
				$hdd_all = (int) $ssh2Lib->execute($link, 'df -h -B 1M $HOME | grep dev | awk \'{print $2}\'');
				
				$ram_all = (int) $ssh2Lib->execute($link, 'cat /proc/meminfo | grep MemTotal | awk \'{print $2}\'');
				$ram_free = (int) $ssh2Lib->execute($link, 'cat /proc/meminfo | grep MemAvailable | awk \'{print $2}\'');
				$ram_used = (int) $ram_all - (int) $ram_free;
				
				$swap_all = (int) $ssh2Lib->execute($link, 'cat /proc/meminfo | grep SwapTotal | awk \'{print $2}\'');
				$swap_free = (int) $ssh2Lib->execute($link, 'cat /proc/meminfo | grep SwapFree | awk \'{print $2}\'');
				$swap_used = (int) $swap_all - (int) $swap_free;
				
				$ssh2Lib->disconnect($link);

				$cpu = $cpu > 100 ? 100 : round($cpu);
				$ram_used = round($ram_used * 0.000977, 0);
				$ram_all = round($ram_all * 0.000977, 0);
				$swap_used = round($swap_used * 0.000977, 0);
				$swap_all = round($swap_all * 0.000977, 0);
				
				$hdd_end = (($hdd_used * 100) / $hdd_all);
				$hdd_end = $hdd_end > 100 ? 100 : round($hdd_end);
				
				$ram_swap_save = ((($ram_used + $swap_used) * 100) / ($ram_all + $swap_all));
				$ram_swap_save = $ram_swap_save > 100 ? 100 : round($ram_swap_save);
				
				$locationData = array(
					'location_cpu'			=> $cpu,
					'location_ram'			=> $ram_swap_save,
					'location_hdd'			=> $hdd_end,
					'location_upd'		    => $today
				);				
				$this->locationsModel->updateLocation($item['location_id'], $locationData);	
				
				$this->locationsModel->createLocationStats(array(
					'location_id'			=> $item['location_id'],
					'location_stats_cpu'	=> $cpu,
					'location_stats_ram'	=> $ram_swap_save,
					'location_stats_hdd'	=> $hdd_end								
				));	

				echo "Статистика игровой локации #". $item['location_id'] ." успешно обновлена (CPU: ".$cpu."%, RAM: ".$ram_swap_save."%, SSD: ".$hdd_end."%)" . PHP_EOL;
			} else {
				$locationData = array(
					'location_cpu'			=> 0,
					'location_ram'			=> 0,
					'location_hdd'			=> 0,
					'location_upd'		    => $today
				);				
				$this->locationsModel->updateLocation($item['location_id'], $locationData);
				
				$this->locationsModel->createLocationStats(array(
					'location_id'			=> $item['location_id'],
					'location_stats_cpu'	=> 0,
					'location_stats_ram'	=> 0,
					'location_stats_hdd'	=> 0								
				));	
			}
		}
		return null;
	}
	
	public function updateStats() {
		$this->load->library('query');
		$this->load->model('servers');
		
		if(!defined('STDIN')) return "Access Denied";
		
		$servers = $this->serversModel->getServers(array(), array('games', 'locations'));
	
		$this->serversModel->clearServersStats();
		
		foreach($servers as $item) {
			$serverid = $item['server_id'];
			
			if($item['server_status'] == 2) {
				$queryLib = new queryLibrary($item['game_query']);
				$queryLib->connect($item['location_ip2'], $item['server_port']);
				$query = $queryLib->getInfo();
				$queryLib->disconnect();
				
				$sysload = $this->serversModel->getServerSystemLoad($serverid);
				$ssd = round($sysload['ssd']);
				$ssd_all = $item['game_ssd'];
				$ssd_end = (int)(($ssd * 100) / $ssd_all);
				$cpu = $sysload['cpu'] > ($item['game_cores'] * 100) ? ($item['game_cores'] * 100) : round($sysload['cpu']);
				$ram = $sysload['ram'] > 100 ? 100 : round($sysload['ram']);					
				
				$this->serversModel->createServerStats(array(
					'server_id'				=> $serverid,
					'server_stats_players'	=> $query['players'],
					'server_stats_cpu'		=> $cpu,
					'server_stats_ram'		=> $ram,
					'server_stats_hdd'		=> $ssd_end								
				));	
				echo "Статистика игрового сервера gs" . $serverid . " успешно сохранена (CPU: ".$cpu."%, RAM: ".$ram."%, SSD: ".$ssd_end."%)" . PHP_EOL;
			}
			
			if($item['server_status'] == 1) {
				$ssd = $this->serversModel->getHDD($serverid);
				$ssd = round($ssd);
				$ssd_all = $item['game_ssd'];
				$ssd_end = (int)(($ssd * 100) / $ssd_all);			
				
				$this->serversModel->createServerStats(array(
					'server_id'				=> $serverid,
					'server_stats_players'	=> 0,
					'server_stats_cpu'		=> 0,
					'server_stats_ram'		=> 0,
					'server_stats_hdd'		=> $ssd_end								
				));	
				echo "Статистика игрового сервера gs" . $serverid . " успешно сохранена (CPU: 0%, RAM: 0%, SSD: ".$ssd_end."%)" . PHP_EOL;
			}
		}
		return null;
	}
	
	public function stopServers() {
		if(!defined('STDIN')) return "Access Denied";
		
		$this->load->model('servers');
	
		$servers = $this->serversModel->getServers(array(), array('games', 'locations'));
		
		foreach($servers as $item) {
			$serverid = $item['server_id'];
			
			if($item['server_status'] == 2) {
				$hdd = $this->serversModel->getHDD($serverid);
				$hdd = round($hdd);
				if($hdd > $item['game_ssd']){
					$connect = $this->serversModel->action($serverid, 'stop');
					if($connect['status'] == "success") {
						$this->serversModel->deleteTask(array('task_name' => 'restart', 'server_id' => $serverid));
						$this->serversModel->deleteTask(array('task_name' => 'enable', 'server_id' => $serverid));
						$this->serversModel->deleteTask(array('task_name' => 'disable', 'server_id' => $serverid));								
						$this->serversModel->updateServer($serverid, array('server_status' => 1));
						$logData = array(
							'server_id'			=> $serverid,
							'reason'            => "Остановка сервера: OVERLOAD-ssd = ".$hdd."/".$item['game_ssd']."МБ",
							'status'            => 1
						);
						$this->serversModel->createLog($logData);
						echo "Остановка сервера: OVERLOAD-ssd = ".$hdd."/".$item['game_ssd']."МБ" . PHP_EOL;	
					} else {							
						echo "Ошибка остановки сервера gs$item[server_id]" . PHP_EOL;
					}
				}
				
			}
		}
		return null;
	}

	public function stopServersQuery() {
		if(!defined('STDIN')) return "Access Denied";
		
		$this->load->library('query');
		$this->load->model('servers');
		
		$servers = $this->serversModel->getServers(array(), array('games', 'locations'));
		
		foreach($servers as $item) {
			$serverid = $item['server_id'];
			
			if($item['server_status'] == 2) {
				$queryLib = new queryLibrary($item['game_query']);
				$queryLib->connect($item['location_ip2'], $item['server_port']);
				$query = $queryLib->getInfo();
				$queryLib->disconnect();	
				
				if($query['players'] > ($item['server_slots']+1)){
					$connect = $this->serversModel->action($serverid, 'stop');
					if($connect['status'] == "success") {			
						$this->serversModel->deleteTask(array('task_name' => 'restart', 'server_id' => $serverid));
						$this->serversModel->deleteTask(array('task_name' => 'enable', 'server_id' => $serverid));
						$this->serversModel->deleteTask(array('task_name' => 'disable', 'server_id' => $serverid));	
						$this->serversModel->updateServer($serverid, array('server_status' => 1));
						$logData = array(
							'server_id'			=> $serverid,
							'reason'            => "Остановка сервера: Чрезмерное кол.во игроков на сервере, игроков ".$query['players']." слотов ".$item['server_slots']."",
							'status'            => 1
						);
						$this->serversModel->createLog($logData);
						echo "Остановка сервера: Чрезмерное кол.во игроков на сервере, игроков ".$query['players']." слотов ".$item['server_slots']."" . PHP_EOL;	
					} else {							
						echo "Ошибка остановки сервера gs$item[server_id]" . PHP_EOL;
					}
				}	
			}
		}
		return null;
	}

	public function serverReloader() {
		if(!defined('STDIN')) return "Access Denied";	
		
        $this->load->model('servers');
        $this->load->model('locations');
	
	    $locations = $this->locationsModel->getLocations(array());
		
	    foreach($locations as $location) {	
			echo PHP_EOL . 'Локация ('.$location['location_id'].') '.$location['location_name'].'' . PHP_EOL;
			$servers = $this->serversModel->getServers(array('server_status' => '2', 'location_id' => $location['location_id']));
			if($servers){
				foreach($servers as $item) {
					$serverid = $item['server_id'];
					$result_status = $this->serversModel->action($serverid, 'server_status');
					
					if($result_status == 1) {	
						$result = $this->serversModel->action($serverid, 'restart');
						if($result["status"] == "success") {
							$logData = array(
								'server_id'			=> $serverid,
								'reason'            => 'Сервер был восcтановлен после падения',
								'status'            => 1
							);
							$this->serversModel->createLog($logData);				
							echo "Игровой сервер gs$item[server_id] - Поднят.". PHP_EOL;	
						}										
					}
				}					
			}
	    }
		return null;
	}
	
	private $deletedTasks = array();

	public function tasks() {	
		if(!defined('STDIN')) return "Access Denied";
		
		$this->load->model('servers');
		$tasks = $this->serversModel->getTasks(array(), array('servers'));
		$datenow = date_create('now');
			
		foreach($tasks as $item) {
			$serverid = $item['server_id'];
				
			if(!in_array($item['task_name'], $this->deletedTasks)) {
				if(strtotime($item['task_lead_time']) <= strtotime(date("Y-m-d H:i:s"))) {
					if($item['task_name'] == "enable") {
						if($item['server_status'] == 1) {
							$result = $this->serversModel->action($serverid, "start");
							if($result["status"] == "success") {
								$this->serversModel->updateServer($serverid, array("server_status" => 2));
								array_push($this->deletedTasks, 'enable');
								$logData = array(
									'server_id'			=> $serverid,
									'reason'            => '[Планировщик] Успешный запуск сервера',
									'status'            => 1
								);
								$this->serversModel->createLog($logData);
								echo 'Задача ' . $item['task_name'] . ' для сервера #' . $item['server_id'] . ' выполнена!' . PHP_EOL;
							}
						}
						$this->serversModel->deleteTask(array('task_id' => $item['task_id']));
					} else if($item['task_name'] == "disable") {
						if($item['server_status'] == 2) {
							$result = $this->serversModel->action($serverid, "stop");
							if($result["status"] == "success") {
								$this->serversModel->updateServer($serverid, array("server_status" => 1));
								array_push($this->deletedTasks, 'disable');
								array_push($this->deletedTasks, 'restart');
								array_push($this->deletedTasks, 'enable');
								$logData = array(
									'server_id'			=> $serverid,
									'reason'            => '[Планировщик] Успешное выключение сервера',
									'status'            => 1
								);
								$this->serversModel->createLog($logData);
								echo 'Задача ' . $item['task_name'] . ' для сервера #' . $item['server_id'] . ' выполнена!' . PHP_EOL;
							}
						}
						$this->serversModel->deleteTask(array('task_id' => $item['task_id']));
					} else if($item['task_name'] == "restart") {
						if($item['server_status'] == 2) {
							$result = $this->serversModel->action($serverid, "restart");
							if($result["status"] == "success") {
								array_push($this->deletedTasks, 'enable');
								$logData = array(
									'server_id'			=> $serverid,
									'reason'            => '[Планировщик] Успешный перезапуск сервера',
									'status'            => 1
								);
								$this->serversModel->createLog($logData);
								echo 'Задача ' . $item['task_name'] . ' для сервера #' . $item['server_id'] . ' выполнена!' . PHP_EOL;
							}
						}
						if($item['task_type'] == 'single') {
							$this->serversModel->deleteTask(array('task_id' => $item['task_id']));
						} else {
							if($item['task_time'] == '15min') {
								$lead_time = date('Y-m-d H:i:s', strtotime("+15 minutes"));
							} else if($item['task_time'] == '30min') {
								$lead_time = date('Y-m-d H:i:s', strtotime("+30 minutes"));
							} else if($item['task_time'] == '1hour') {
								$lead_time = date('Y-m-d H:i:s', strtotime("+1 hour"));
							} else if($item['task_time'] == '12hours') {
								$lead_time = date('Y-m-d H:i:s', strtotime("+12 hours"));
							} else if($item['task_time'] == '24hours') {
								$lead_time = date('Y-m-d H:i:s', strtotime("+1 day"));
							}
							$this->serversModel->updateTask($item['task_id'], array('task_lead_time' => $lead_time));
						}
					}
				}
			}
		}
		return null;
	}
	
	public function clearLogs() {
		if(!defined('STDIN')) return "Access Denied";
		
		$this->load->model('servers');
		
		$serverLogs = $this->serversModel->getLogs();
		
		$datenow = date_create('now');
		
		foreach($serverLogs as $item) {
			$diff = date_diff($datenow, date_create($item['date']));
			
			if($diff->invert) {
				if($diff->days >= 7) {
					$this->serversModel->deleteLog($item['log_id']);
				}
			}
		}

		return null;
	}
}
?>
