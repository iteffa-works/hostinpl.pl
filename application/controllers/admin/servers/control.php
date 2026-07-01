<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class controlController extends Controller {
	public function index($serverid = null) {	
		$this->document->setActiveSection('admin/servers');
		$this->document->setActiveItem('control');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 2) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->library('query');
		$this->load->model('servers');
		
		$error = $this->validate($serverid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'admin/servers/index');
		}
		
		$server = $this->serversModel->getServerById($serverid, array('users', 'games', 'locations'));
		$this->data['server'] = $server;
		
		if($server['server_status'] == 2) {
			$queryLib = new queryLibrary($server['game_query']);
			$queryLib->connect($server['location_ip2'], $server['server_port']);
			$query = $queryLib->getInfo();
			$queryLib->disconnect();
			
			$this->data['query'] = $query;
		}
		
		if($server['game_code'] == "mcpe" || $server['game_code'] == "mine") {
			$cores = @array_reverse(@$this->game_settings->cores[$server['game_code']]);
			$this->data["cores"] = $cores;	
			$no_core = @array_reverse(@$this->game_settings->cores[$server['game_code']]['latest_core']);
			$this->data["no_core"] = $no_core;	
		}
		
		if($server['game_code'] == "cs") {
			$builds = @array_reverse(@$this->game_settings->builds[$server['game_code']]);
			$this->data["builds"] = $builds;	
			$no_build = @array_reverse(@$this->game_settings->builds[$server['game_code']]['latest_build']);
			$this->data["no_build"] = $no_build;	
		}
		
		if($server['game_code'] == "ragemp") {
			$versions = @array_reverse(@$this->game_settings->versions[$server['game_code']]);
			$this->data["versions"] = $versions;	
			$no_version = @array_reverse(@$this->game_settings->versions[$server['game_code']]['latest']);
			$this->data["no_version"] = $no_version;	
		}
		
		if($server['server_status'] == 1 AND $server['game_code'] == "cs" || $server['game_code'] == "css" || $server['game_code'] == "csgo") {
			if($server["game_code"] == "cs") {
				$fps = array("300", "500", "1100");
				$this->data["fps"] = $fps;
			}
					
			if($server["game_code"] == "csgo") {
				$tickrate = array("64", "128");
				$this->data["tickrate"] = $tickrate;
			}
				
			if($server["game_code"] == "css") {
				$tickrate = array("66", "100");
				$this->data["tickrate"] = $tickrate;
			}	
		}
		
		$stats = $this->serversModel->getServerStats($serverid, "NOW() - INTERVAL 1 DAY", "NOW()");
		$this->data['stats'] = $stats;
		
		$this->getChild(array('common/admheader', 'common/footer'));
		return $this->load->view('admin/servers/control', $this->data);
	}
	
	public function actionserver($serverid = null, $actionserver = null) {
		if(!$this->user->isLogged()) {
			$this->data['status'] = "error";
			$this->data['error'] = "Вы не авторизированы!";
			return json_encode($this->data);
		}
		if($this->user->getAccessLevel() < 2) {
			$this->data['status'] = "error";
			$this->data['error'] = "У вас нет доступа к данному разделу!";
			return json_encode($this->data);
		}
		
		$this->load->library('mail');
		$this->load->library('ssh2');		
		$this->load->model('servers');

		$error = $this->validate($serverid);
		if($error) {
			$this->data['status'] = "error";
			$this->data['error'] = $error;
			return json_encode($this->data);
		}
		
		$server = $this->serversModel->getServerById($serverid, array('users', 'locations', 'games'));
		
		if($server['server_status'] == 3) {
			$this->data["status"] = "error";
			$this->data["error"] = "Идет установка сервера!";
			return json_encode($this->data);
		} else if($server['server_status'] == 4) {
			$this->data["status"] = "error";
			$this->data["error"] = "Идет переустановка сервера!";
			return json_encode($this->data);
		} else if($server['server_status'] == 5) {
			$this->data["status"] = "error";
			$this->data["error"] = "Идет создание BackUP сервера!";
			return json_encode($this->data);
		} else if($server['server_status'] == 6) {
			$this->data["status"] = "error";
			$this->data["error"] = "Идет восстанновление сервера из BackUP!";
			return json_encode($this->data);
		} else if($server['server_status'] == 7) {
			$this->data["status"] = "error";
			$this->data["error"] = "Идет обновление сервера!";
			return json_encode($this->data);
		} else if($actionserver == "start" || $actionserver == "stop" || $actionserver == "restart" || $actionserver == "backup" || $actionserver == "unbackup") {
			if($server["server_status"] == 0) {				
				$this->data["status"] = "error";
				$this->data["error"] = "Сервер заблокирован!";
				return json_encode($this->data);
			}
		}
		
		switch($actionserver) {
			case "start": {
				if($server["server_status"] == 1) {
					$stats = $this->serversModel->getHDD($server['server_id']);
					if((int)$stats < $server['game_ssd']) {
						$result = $this->serversModel->action($serverid, 'start');
						if($result["status"] == "success") {
							$this->serversModel->deleteTask(array('task_name' => 'enable', 'server_id' => $serverid));
							$this->serversModel->updateServer($serverid, array("server_status" => 2));
							$logData = array(
								'server_id'			=> $serverid,
								'reason'            => 'Успешный запуск сервера',
								'status'            => 1
							);
							$this->serversModel->createLog($logData);
							$this->data["status"] = "success";
							$this->data["success"] = "Вы успешно запустили сервер!";
						} else {
							$this->data["status"] = "error";
							$this->data["error"] = $result["description"];
						}
					} else {
						$this->data["status"] = "error";
						$this->data["error"] = "Вы привысили ограничение размера директории на ". round($stats - $server['game_ssd'], 2) ."МБ, запуск сервера невозможен!";
					}
				} else {
					$this->data["status"] = "error";
					$this->data["error"] = "Сервер должен быть выключен!";
				}
				break;
			}
				
			case "reinstall": {
				if($server["server_status"] == 1) {
					$logData = array(
						'server_id'			=> $serverid,
						'reason'            => 'Сервер успешно поставлен на переустановку!',
						'status'            => 2
					);
                    $this->serversModel->createLog($logData);
					$this->serversModel->updateServer($serverid, array('server_status' => 4, 'server_work' => 1));
					$this->data["status"] = "success";
					$this->data["success"] = "Сервер успешно поставлен на переустановку!";
				} else {
					$this->data["status"] = "error";
					$this->data["error"] = "Сервер должен быть выключен!";
				}
				break;
			}
				
			case "restart": {
				if($server["server_status"] == 2) {
					$stats = $this->serversModel->getHDD($server['server_id']);
					if((int)$stats < $server['game_ssd']) {
						$result = $this->serversModel->action($serverid, 'restart');
						if($result["status"] == "success") {
							$this->serversModel->deleteTask(array('task_name' => 'restart', 'server_id' => $serverid));
							$logData = array(
								'server_id'			=> $serverid,
								'reason'            => 'Успешный перезапуск сервера',
								'status'            => 1
							);
							$this->serversModel->createLog($logData);
							$this->data["status"] = "success";
							$this->data["success"] = "Вы успешно перезапустили сервер!";
						} else {
							$this->data["status"] = "error";
							$this->data["error"] = $result["description"];
						}
					} else {
						$result = $this->serversModel->action($serverid, 'stop');
						if($result['status'] == "success") {
							$this->serversModel->updateServer($serverid, array('server_status' => 1));
							$this->data["status"] = "error";
							$this->data["error"] = "Вы привысили ограничение размера директории на ". round($stats - $server['game_ssd'], 2) ."МБ, перезагрузка сервера невозможна!";
						} else {
							$this->data["status"] = "error";
							$this->data["error"] = $result["description"];
						}
					}
				} else if($server["server_status"] == 2) {
					$this->data["status"] = "error";
					$this->data["error"] = "Сервер должен быть включен!";
				}
				break;
			}
			
			case "stop": {
				if($server["server_status"] == 2) {
					$result = $this->serversModel->action($serverid, 'stop');
					if($result["status"] == "success") {
						$this->serversModel->deleteTask(array('task_name' => 'disable', 'server_id' => $serverid));
						$this->serversModel->deleteTask(array('task_name' => 'restart', 'server_id' => $serverid));
						$this->serversModel->updateServer($serverid, array("server_status" => 1));
						$logData = array(
							'server_id'			=> $serverid,
							'reason'            => 'Успешное выключение сервера',
							'status'            => 1
						);
                        $this->serversModel->createLog($logData);	
						$this->data["status"] = "success";
						$this->data["success"] = "Вы успешно выключили сервер!";
					} else {
						$this->data["status"] = "error";
						$this->data["error"] = $result["description"];
					}
				} else {
					$this->data["status"] = "error";
					$this->data["error"] = "Сервер должен быть вылючен!";
				}
				break;
			}

			case 'backup': {
				if($server['server_status'] == 1) {
					$stats = $this->serversModel->getHDD($server['server_id']);
					if((int)$stats < $server['game_ssd']) {
						$this->serversModel->deleteTask(array('task_name' => 'restart', 'server_id' => $serverid));
						$this->serversModel->deleteTask(array('task_name' => 'enable', 'server_id' => $serverid));
						$this->serversModel->deleteTask(array('task_name' => 'disable', 'server_id' => $serverid));
						$this->serversModel->updateServer($serverid, array('server_status' => 5, 'server_work' => 1));
						$logData = array(
							'server_id'			=> $serverid,
							'reason'            => 'Успешное начало создания BackUP сервера.',
							'status'            => 1
						);
						$this->serversModel->createLog($logData);
						$this->data['status'] = "success";
						$this->data['success'] = "Вы успешно начали создание BackUP сервера!";
					} else {
						$this->data["status"] = "error";
						$this->data["error"] = "Вы привысили ограничение размера директории на ". round($stats - $server['game_ssd'], 2) ."МБ, создание бекапа сервера невозможено!";
					}
				} else {
					$this->data['status'] = "error";
					$this->data['error'] = "Сервер должен быть выключен!";
				}
				break;
			}
			
			case 'unbackup': {
				if($server['server_status'] == 1) {
					if (file_exists('ssh2.sftp://'.$server['location_user'].':'.$server['location_password'].'@'.$server['location_ip'].':22/home/cp/backups/gs'.$serverid.'.tar')) {
						$this->serversModel->deleteTask(array('task_name' => 'restart', 'server_id' => $serverid));
						$this->serversModel->deleteTask(array('task_name' => 'enable', 'server_id' => $serverid));
						$this->serversModel->deleteTask(array('task_name' => 'disable', 'server_id' => $serverid));
						$this->serversModel->updateServer($serverid, array('server_status' => 6, 'server_work' => 1));
						$logData = array(
							'server_id'			=> $serverid,
							'reason'            => 'Успешное начало восстановление BackUP сервера.',
							'status'            => 1
						);
						$this->serversModel->createLog($logData);
						$this->data['status'] = "success";
						$this->data['success'] = "Вы успешно начали восстановление сервера из BackUP!";
					} else {
						$this->data['status'] = "error";
						$this->data['error'] = "У сервера нет BackUP!";
					}
				} else {
					$this->data['status'] = "error";
					$this->data['error'] = "Сервер должен быть выключен!";
				}
				break;
			}
			
			case 'delete_backup': {
				if($server['server_status'] == 1) {
					if (file_exists('ssh2.sftp://'.$server['location_user'].':'.$server['location_password'].'@'.$server['location_ip'].':22/home/cp/backups/gs'.$serverid.'.tar')) {
						$result = $this->serversModel->action($serverid, 'delete_backup');
						if($result["status"] == "success") {
							$logData = array(
								'server_id'			=> $serverid,
								'reason'            => 'Успешное удаление BackUP сервера',
								'status'            => 1
							);
							$this->serversModel->createLog($logData);	
							$this->data["status"] = "success";
							$this->data["success"] = "Вы успешно удалили BackUP сервера!";
						} else {
							$this->data["status"] = "error";
							$this->data["error"] = $result["description"];
						}
					} else {
						$this->data['status'] = "error";
						$this->data['error'] = "У сервера нет BackUP!";
					}
				} else {
					$this->data['status'] = "error";
					$this->data['error'] = "Сервер должен быть выключен!";
				}
				break;
			}
			
			case 'block': {
				if($server['server_status'] == 1) {
					$this->serversModel->updateServer($serverid, array('server_status' => 0));
					$this->serversModel->action($serverid, "block");
					$this->data['status'] = "success";
					$this->data['success'] = "Вы успешно заблокировали сервер!";
				} else {
					$this->data['status'] = "error";
					$this->data['error'] = "Сервер должен быть выключен!";
				}
				break;
			}
			case 'delete': {
				if($server['server_status'] == 1) {
					if($this->user->getAccessLevel() == 3){
						$mailLib = new mailLibrary();
						$mailLib->setFrom($this->config->mail_from);
						$mailLib->setSender($this->config->mail_sender);
						$result = $this->serversModel->action($serverid, 'delete');
						if($result['status'] == "success") {
							if($server['server_mysql'] ==  1 || $server['server_mysql'] ==  2) {
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
									$link = $ssh2Lib->connect($server['location_ip'], $server['location_user'], $server['location_password']);
									
									$ssh2Lib->execute($link, "iptables -D INPUT -s ".$item2['server_ip']." -p udp -d ".$server['location_ip2']." --dport ".$server['server_port']." -j DROP;");
									$ssh2Lib->execute($link, "iptables -D INPUT -s ".$item2['server_ip']." -p tcp -d ".$server['location_ip2']." --dport ".$server['server_port']." -j DROP;");
									
									$ssh2Lib->disconnect($link);
									
									$this->serversModel->deleteFirewallDB($item2['firewall_id']);
								}
							}
							
							$mailLib->setTo($server['user_email']);
							$mailLib->setSubject("Удаление сервера администратором #$serverid");
										
							$mailData = array();
							$mailData['firstname'] = $server['user_firstname'];
							$mailData['lastname'] = $server['user_lastname'];
							$mailData['serverid'] = $serverid;
											
							$text = $this->load->view('mail/servers/deleted', $mailData);
										
							$mailLib->setText($text);
							$mailLib->send();

							$this->data['status'] = "success";
							$this->data['success'] = "Вы успешно удалили сервер!";
						} else {
							$this->data['status'] = "error";
							$this->data['error'] = $result['description'];
						}
					} else {
						$this->session->data['error'] = "У вас нет доступа к данному разделу!";
						$this->response->redirect($this->config->url);
					}
				} else {
					$this->data['status'] = "error";
					$this->data['error'] = "Сервер должен быть выключен!";
				}
				break;
			}
			case 'unblock': {
				if($server['server_status'] == 0) {
					$this->serversModel->updateServer($serverid, array('server_status' => 1));
					$this->serversModel->action($serverid, "updatepassm");
					$this->serversModel->action($serverid, "updatepass");
					$this->data['status'] = "success";
					$this->data['success'] = "Вы успешно разблокировали сервер!";
				} else {
					$this->data['status'] = "error";
					$this->data['error'] = "Сервер должен быть заблокирован!";
				}
				break;
			}
			default: {
				$this->data['status'] = "error";
				$this->data['error'] = "Вы выбрали несуществующее действие!";
				break;
			}
		}
		
		return json_encode($this->data);
	}
	
	public function settingStart($serverid = null) {
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'news/index');
		}
		if($this->user->getAccessLevel() < 2) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}

		$this->load->model('servers');
		
		$server = $this->serversModel->getServerById($serverid, array('games', 'locations'));
		
		$error = $this->validate($serverid);
		if($error) {
			$this->data['status'] = "error";
			$this->data['error'] = $error;
			return json_encode($this->data);
		}	
		
		if($server['server_status'] == 3) {
			$this->data["status"] = "error";
			$this->data["error"] = "Идет установка сервера!";
			return json_encode($this->data);
		} else if($server['server_status'] == 4) {
			$this->data["status"] = "error";
			$this->data["error"] = "Идет переустановка сервера!";
			return json_encode($this->data);
		} else if($server['server_status'] == 5) {
			$this->data["status"] = "error";
			$this->data["error"] = "Идет создание BackUP сервера!";
			return json_encode($this->data);
		} else if($server['server_status'] == 6) {
			$this->data["status"] = "error";
			$this->data["error"] = "Идет восстанновление сервера из BackUP!";
			return json_encode($this->data);
		} else if($server['server_status'] == 7) {
			$this->data["status"] = "error";
			$this->data["error"] = "Идет обновление сервера!";
			return json_encode($this->data);
		} else if($server["server_status"] == 0) {
			$this->data["status"] = "error";
			$this->data["error"] = "Сервер заблокирован!";
			return json_encode($this->data);
		}

		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			if($server['server_status'] == 1){	
				if($server["game_code"] == "cs" || $server["game_code"] == "css" || $server["game_code"] == "csgo") {
					if($server["game_code"] == "cs" || $server["game_code"] == "css" || $server["game_code"] == "csgo") {
						$vac = @$this->request->post['vac'];
						if(!in_array($vac, array("0", "1"))) {
							$this->data["status"] = "error";
							$this->data["error"] = "Статус античита VAC указан неверно!";
							return json_encode($this->data);						
						}
						$this->serversModel->updateServer($serverid, array('server_vac' => $vac));
								
						$fastdl = @$this->request->post['fastdl'];
						if(!in_array($fastdl, array("0", "1"))) {
							$this->data["status"] = "error";
							$this->data["error"] = "Статус FastDL указан неверно!";
							return json_encode($this->data);						
						}
						$this->serversModel->updateServer($serverid, array('fastdl_status' => $fastdl));

						if($server["game_code"] == "cs") {
							$fps = @$this->request->post['fps'];
							if(!in_array($fps, array("300", "500", "1100"))) {
								$this->data["status"] = "error";
								$this->data["error"] = "Параметр FPS указан неверно!";
								return json_encode($this->data);						
							} else {
								$this->serversModel->updateServer($serverid, array('server_fps' => $fps));
							}
						}
						
						if($server["game_code"] == "csgo") {
							$tickrate = @$this->request->post['tickrate'];
							if(!in_array($tickrate, array("64", "128"))) {
								$this->data["status"] = "error";
								$this->data["error"] = "Параметр Tickrate указан неверно!";
								return json_encode($this->data);						
							} else {
								$this->serversModel->updateServer($serverid, array('server_tickrate' => $tickrate));
							}
						}
						
						if($server["game_code"] == "css") {
							$tickrate = @$this->request->post['tickrate'];
							if(!in_array($tickrate, array("66", "100"))) {
								$this->data["status"] = "error";
								$this->data["error"] = "Параметр Tickrate указан неверно!";
								return json_encode($this->data);						
							} else {
								$this->serversModel->updateServer($serverid, array('server_tickrate' => $tickrate));
							}
						}
					} 
					
					$this->data["status"] = "success";
					$this->data["success"] = "Параметры запуска успешно изменены!";
				} else {
					$this->data["status"] = "error";
					$this->data["error"] = "Не для данной игры!";
				}
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = "Сервер должен быть выключен!";
			}
		}
		return json_encode($this->data);
	}
	
	private function validate($serverid) {
		$result = null;
		
		if(!$this->serversModel->getTotalServers(array('server_id' => (int)$serverid))) {
			$result = "Запрашиваемый сервер не существует!";
		}
		return $result;
	}
	
	private function validatePOST() {
		$this->load->library('validate');
		
		$validateLib = new validateLibrary();
		
		$result = null;
		
		$slots = @$this->request->post['slots'];
		$editpassword = @$this->request->post['editpassword'];
		$password = @$this->request->post['password'];
		$password2 = @$this->request->post['password2'];
		
		if($editpassword) {
			if(!$validateLib->password($password)) {
				$result = "Пароль должен содержать от 6 до 32 латинских букв, цифр и знаков <i>,.!?_-</i>!";
			}
			elseif($password != $password2) {
				$result = "Введенные вами пароли не совпадают!";
			}
		}
		return $result;
	}

	public function mysqlhostin($serverid = null, $mysqlhostin = null) {
		if(!$this->user->isLogged()) {
			$this->data['status'] = "error";
			$this->data['error'] = "Вы не авторизированы!";
			return json_encode($this->data);
		}
		if($this->user->getAccessLevel() < 0) {
	  		$this->data['status'] = "error";
			$this->data['error'] = "У вас нет доступа к данному разделу!";
			return json_encode($this->data);
		}
		
		$this->load->model('servers');
		
		$error = $this->validate($serverid);
		if($error) {
			$this->data['status'] = "error";
			$this->data['error'] = $error;
			return json_encode($this->data);
		}
		
		$server = $this->serversModel->getServerById($serverid);
		switch($mysqlhostin) {
			case 'mysqlon': {
				if($server['server_mysql'] == 2) {
					$this->serversModel->updateServer($serverid, array('server_mysql' => 1));
					$this->data['status'] = "success";
					$this->data['success'] = "Вы успешно включили базу данных gs$serverid!";
				} else {
					$this->data['status'] = "error";
					$this->data['error'] = "База данных и так включена либо вы ее еще не создали!";
				}
				break;
			}	
			case 'mysqloff': {
				if($server['server_mysql'] == 1) {
					$this->serversModel->updateServer($serverid, array('server_mysql' => 2));
					$this->data['status'] = "success";
					$this->data['success'] = "Вы успешно выключили базу данных gs$serverid!";
				} else {
					$this->data['status'] = "error";
					$this->data['error'] = "База данных и так выключена!";
				}
				break;
			}		
			case 'mysqlcr': {
				if($server['server_mysql'] == 0) {
					$result = $this->serversModel->action($serverid, 'create_mysql');
					if($result["status"] == "success") {
						$this->serversModel->updateServer($serverid, array('server_mysql' => 2));
						$this->data['status'] = "success";
						$this->data['success'] = "Вы успешно создали базу данных gs$serverid!";
					} else {
						$this->data["status"] = "error";
						$this->data["error"] = $result["description"];
					}
				} else {
					$this->data['status'] = "error";
					$this->data['error'] = "База данных и так создана!";
				}
				break;
			}				
			default: {
				$this->data['status'] = "error";
				$this->data['error'] = "Вы выбрали несуществующее действие!";
				break;
			}
		}
		
		return json_encode($this->data);
	}
	
	public function getData($serverid = null) {
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 2) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->model('servers');
		$this->load->library('query');
		
		$error = $this->validate($serverid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'servers/index');
		}
		
		$server = $this->serversModel->getServerById($serverid, array('games', 'locations'));
		
		if($server['server_status'] == 2) {
			$queryLib = new queryLibrary($server['game_query']);
			$queryLib->connect($server['location_ip2'], $server['server_port']);
			$query = $queryLib->getInfo();
			$queryLib->disconnect();
			
			$sysload = $this->serversModel->getServerSystemLoad($serverid);
			$ssd = round($sysload['ssd']);
			$ssd_all = $server['game_ssd'];
			$this->data['ssd'] = (int)(($ssd * 100) / $ssd_all);
			$this->data['cpu'] = $sysload['cpu'] > ($server['game_cores'] * 100) ? ($server['game_cores'] * 100) : round($sysload['cpu']);
			$this->data['ram'] = $sysload['ram'] > 100 ? 100 : round($sysload['ram']);	
			$this->data['players'] = $query['players'];
		} else if($server['server_status'] == 3 || $server['server_status'] == 4) {
			$this->data['ssd'] = 'N/A';
			$this->data['cpu'] = 'N/A';
			$this->data['ram'] = 'N/A';
			$this->data['players'] = 'N/A';
		} else {
			$ssd = $this->serversModel->getHDD($serverid);
			$ssd = round($ssd);
			$ssd_all = $server['game_ssd'];
			$this->data['ssd'] = (int)(($ssd * 100) / $ssd_all);
			$this->data['cpu'] = 0;
			$this->data['ram'] = 0;
			$this->data['players'] = 0;
		}

		return json_encode($this->data);
	}
}
?>