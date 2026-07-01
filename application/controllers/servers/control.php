<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class controlController extends Controller {
	public function index($serverid = null) {
		$this->document->setActiveSection('servers');
		$this->document->setActiveItem('control');
		$this->data['activesection'] = $this->document->getActiveSection();
		$this->data['activeitem'] = $this->document->getActiveItem();

		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 0) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}	
		
		$this->load->library('query');
		$this->load->model('servers');
		
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
			$this->data['query'] = $query;
		}

		$datenow = date_create('now');
		$dateend = date_create($server['server_date_end']);
		$diff = date_diff($datenow, $dateend);

		if ($diff->invert) {
			$days = 1;
		} else {
			$days = $diff->days;
		}
		
		if($server['server_status'] == 2 AND $server['game_code'] == "cs" || $server['game_code'] == "css" || $server['game_code'] == "csgo") {
			$this->load->library('ssh2');
				
			$ssh = new ssh2Library();
			$connect = $ssh->connect($server['location_ip'], $server['location_user'], $pass = $server['location_password']);
			if($server['game_code'] == "csgo") $folder = 'csgo';
			else $folder = 'cstrike';
			$output = $ssh->execute($connect, "cd /home/gs".$server['server_id']."/".$folder."/maps/; ls | grep .bsp;");
			$disconnect = $ssh->disconnect($connect);
			$data = $output . $disconnect;
			$data = explode("\n", $data);
			
			$maps = '<option selected="selected" value="' . $query['mapname'] . '">' . $query['mapname'] . '</option>';
			
			foreach ($data as $map) {
				if (!preg_match('/\.(bsp.ztmp)/', $map)) {
					$map = str_replace(".bsp", "", $map);
					$maps .= '<option value="' . $map . '">' . $map . '</option>';
				}
			}
			$this->data['maps'] = $maps;			
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
		
		if($server['server_status'] == 1 AND $server['game_code'] == "ragemp") {
			$node_modules = @array_reverse(@$this->game_settings->node_modules[$server['game_code']]);
			$this->data["node_modules"] = $node_modules;	
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
		
		$this->data['server'] = $server;
		$this->data['days'] = $days;
		$this->data['portPrice'] = $portPrice = round($this->config->portPrice);

		include_once 'application/controllers/common/main.php';
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('servers/control', $this->data);
	}
	
	public function ajax($serverid = null) {
		if(!$this->user->isLogged()) {  
	  		$this->data['status'] = "error";
			$this->data['error'] = "Вы не авторизированы!";
			return json_encode($this->data);
		}
		
		if($this->user->getAccessLevel() < 1) {
	  		$this->data['status'] = "error";
			$this->data['error'] = "У вас нет доступа к данному разделу!";
			return json_encode($this->data);
		}
		
		$this->load->model('waste');
		$this->load->model('users');
		$this->load->model('servers');
		
		$server = $this->serversModel->getServerById($serverid, array('games'));
		
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
			$errorPOST = $this->validatePOST($serverid);
			if(!$errorPOST) {
				if($server["server_status"] == 1) {
					$slots = $this->request->post['slots'];
					$userid = $this->user->getId();
					$balance = $this->user->getBalance();
					$user = $this->usersModel->getUserById($userid, array(), array(), array());
					
					$datenow = date_create('now');
					$dateend = date_create($server['server_date_end']);
					$diff = date_diff($datenow, $dateend);

					if ($diff->invert) {
						$days = 1;
					}
					else {
						$days = $diff->days;
					}
					$price = $server['game_price'] * ($slots-$server['server_slots']);

					if ($days != NULL) {
						$price = ($price / 30) * $days;
					}
					
					if ($server['server_slots'] == $slots) {
						$price = '0';
					}

					if (($slots - $server['server_slots']) <= 0) {
						$price = '0';
					}
					
					if($slots < $server['server_slots'])
					{
						$this->data['status'] = "error";
						$this->data['error'] = "Невозможно установить слоты ниже заданных.";
						return json_encode($this->data);			
					}				
					if($slots == $server['server_slots'])
					{
						$this->data['status'] = "success";
						$this->data['success'] = "Слоты не были изменены.";
						return json_encode($this->data);			
					}
					if($balance >= $price) {
						$this->serversModel->slotsServer($serverid, $slots);
						$this->usersModel->downUserBalance($userid, round($price, 2));
						$wasteData = array(
							'user_id'		=> $userid,
							'waste_ammount'	=> round($price, 2),
							'waste_status'	=> 1,
							'waste_usluga'	=> "Увеличено до $slots слотов для сервера gs$serverid"
						 ); 	
						$this->wasteModel->createWaste($wasteData);
		
						if($user['ref'] != 0) {
							$ref_percent = $this->config->ref_percent;
							$getpref = ($price * (1 + $ref_percent / 100)) - $price;
							$this->usersModel->upUserBalance($user['ref'], $getpref);
							$this->usersModel->upUserRMoney($user['ref'], $getpref);
								
							$wasteData = array(
								'user_id'		=> $user['ref'],
								'waste_ammount'	=> $getpref,
								'waste_status'	=> 0,
								'waste_usluga'	=> "Бонус с реферала ID-$userid"
							); 
							$this->wasteModel->createWaste($wasteData);
						}
							
						$logData = array(
							'server_id'			=> $serverid,
							'reason'            => 'Увеличено до '.$slots.' слотов.',
							'status'            => 1
						);
						$this->serversModel->createLog($logData);

						$this->data['status'] = "success";
						$this->data['success'] = "Вы успешно установили $slots слотов! С Вашего счета снято ".(round($price, 2))." руб.";
					} else {
						$this->data['status'] = "error";
						$logData = array(
							'server_id'			=> $serverid,
							'reason'            => '[SLOTS] Не достаточно средств!',
							'status'            => 3
						);
						$this->serversModel->createLog($logData);
						$this->data['error'] = "На Вашем счету не хватает ". round($price - $balance, 2) ." руб.";
					}
				} else {
					$this->data['status'] = "error";
					$this->data['error'] = "Сервер должен быть выключен!";
				}
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = $errorPOST;
			}
		}
		return json_encode($this->data);
	}
	
	public function action($serverid = null, $action = null) {
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
		$this->load->model('users');
		$this->load->library('ssh2');
		$ssh2Lib = new ssh2Library();
					
		$server = $this->serversModel->getServerById($serverid, array('users', 'locations', 'games'));		
		
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
		} else if($action == "start" || $action == "stop" || $action == "restart" || $action == "reinstall" || $action == "backup" || $action == "unbackup" || $action == "delete_backup") {
			if($server["server_status"] == 0) {
				$this->data["status"] = "error";
				$this->data["error"] = "Сервер заблокирован!";
				return json_encode($this->data);
			}
		}
		
		switch($action) {
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
						$this->data['error'] = "У вас нет BackUP сервера!";
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
						$this->data['error'] = "У вас нет BackUP сервера!";
					}
				} else {
					$this->data['status'] = "error";
					$this->data['error'] = "Сервер должен быть выключен!";
				}
				break;
			}
			
			case 'promised': {
				$this->load->model('users');	
				$this->load->model('waste');					
				$userid = $this->user->getId();
				$user = $this->usersModel->getUserById($userid, array(), array(), array());
				if($user['user_promised_pay'] == 0){
					$result = $this->serversModel->promisedServer($serverid);
					if($result["status"] == "success") {
						$this->usersModel->updateUser($userid, array('user_promised_pay' => 1));
						$this->usersModel->downUserBalance($userid, 15);
						$wasteData = array(
							'user_id'		=> $userid,
							'waste_ammount'	=> 15,
							'waste_status'	=> 1,
							'waste_usluga'	=> "Услуга обещанный платеж для сервера gs$serverid"
						);
						$this->wasteModel->createWaste($wasteData);									
						$this->data['status'] = "success";
						$this->data['success'] = "Вы активировали обещаный платёж!";
					} else {
						$this->data["status"] = "error";
						$this->data["error"] = $result["description"];
					}
				} else {
					$this->data['status'] = "error";
					$this->data['error'] = "На данный момент Вам недоступна данная функция!";
				}
				break;
			}
			
			default: {
				$logData = array(
					'server_id'			=> $serverid,
					'reason'            => 'Вы выбрали несуществующее действие!',
					'status'            => 3
				);
                $this->serversModel->createLog($logData);
				
				$this->data['status'] = "error";
				$this->data['error'] = "Вы выбрали несуществующее действие!";
				break;
			}
		}
		return json_encode($this->data);
	}
	
	
	public function ajax_port($serverid = null) {
		if(!$this->user->isLogged()) {
			$this->data['status'] = "error";
			$this->data['error'] = "Вы не авторизированы!";
			return json_encode($this->data);
		}
		
		if($this->user->getAccessLevel() < 1) {
			$this->data['status'] = "error";
			$this->data['error'] = "У вас нет доступа к данному разделу!";
			return json_encode($this->data);
		}

		$userid = $this->user->getId();
		$balance = $this->user->getBalance();
		$portPrice = round($this->config->portPrice);

		$this->load->model('waste');
		$this->load->model('users');
		$this->load->model('servers');

		$gameServer = $this->serversModel->getServerById($serverid, array('games', 'locations'));
		
		$error = $this->validate($serverid);
		if($error) {
			$this->data['status'] = "error";
			$this->data['error'] = $error;
			return json_encode($this->data);
		}	
		
		if($gameServer['server_status'] == 3) {
			$this->data["status"] = "error";
			$this->data["error"] = "Идет установка сервера!";
			return json_encode($this->data);
		} else if($gameServer['server_status'] == 4) {
			$this->data["status"] = "error";
			$this->data["error"] = "Идет переустановка сервера!";
			return json_encode($this->data);
		} else if($gameServer['server_status'] == 5) {
			$this->data["status"] = "error";
			$this->data["error"] = "Идет создание BackUP сервера!";
			return json_encode($this->data);
		} else if($gameServer['server_status'] == 6) {
			$this->data["status"] = "error";
			$this->data["error"] = "Идет восстанновление сервера из BackUP!";
			return json_encode($this->data);
		} else if($gameServer['server_status'] == 7) {
			$this->data["status"] = "error";
			$this->data["error"] = "Идет обновление сервера!";
			return json_encode($this->data);
		} else if($gameServer["server_status"] == 0) {
			$this->data["status"] = "error";
			$this->data["error"] = "Сервер заблокирован!";
			return json_encode($this->data);
		}
			
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$port = @(int)$this->request->post['port'];			
			if($gameServer['server_status'] == 1){
				if($balance >= $portPrice){	
					if(!$this->serversModel->getTotalFirewalls(array('server_id' => $serverid))){
						if(!$this->serversModel->getTotalServers(array('location_id' => $gameServer['location_id'], 'server_port' => $port))){
							$gameServerPorts = $this->serversModel->getGameServerPortList($gameServer['location_id'], $gameServer['game_min_port'], $gameServer['game_max_port']);	
							if (in_array($port, $gameServerPorts)) {
								$this->serversModel->updateServer($serverid, array('server_port' => $port));
								$this->usersModel->downUserBalance($userid, $portPrice);

								$wasteData = array(
									'user_id'		=> $userid,
									'waste_ammount'	=> $portPrice,
									'waste_status'	=> 1,
									'waste_usluga'	=> "Установлен порт $port для сервера gs$serverid"
								 ); 	
								$this->wasteModel->createWaste($wasteData);
								
								$user = $this->usersModel->getUserById($userid, array(), array(), array());
								
								if($user['ref'] != 0) {
									$ref_percent = $this->config->ref_percent;
									$getpref = ($portPrice * (1 + $ref_percent / 100)) - $portPrice;
									$this->usersModel->upUserBalance($user['ref'], $getpref);
									$this->usersModel->upUserRMoney($user['ref'], $getpref);
									
									$wasteData = array(
										'user_id'		=> $user['ref'],
										'waste_ammount'	=> $getpref,
										'waste_status'	=> 0,
										'waste_usluga'	=> "Бонус с реферала ID-$userid"
									); 
									$this->wasteModel->createWaste($wasteData);
								}
					
								$this->data['status'] = "success";
								$this->data['success'] = "Вы успешно сменили порт сервера на ".$port;
							} else {
								$this->data['status'] = 'error';
								$this->data['error'] = 'Выбранный порт не может быть использован!';
							}		
						} else {
							$this->data['status'] = "error";
							$this->data['error'] = "Выбранный порт уже занят другим сервером!";
						}
					} else {
						$this->data['status'] = "error";
						$this->data['error'] = "Необходимо удалить все заблокированные IP адреса!";
					}
				} else {
					$this->data['status'] = "error";
					$this->data['error'] = "На Вашем счету не хватает ".(round($portPrice-$balance, 2))." руб";
				}
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = "Сервер должен быть выключен!";
			}
		}
		return json_encode($this->data);
	}
	
	public function changemap_go($serverid = null) {
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'news/index');
		}
		if($this->user->getAccessLevel() < 0) {
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

		if($server['server_status'] == 2){	
			$command = str_replace("'", "&#039;", "changelevel ".$_POST['map']."");
			$this->serversModel->action($serverid, 'sendcommand', array('command' => $command));
			$this->data['status'] = "success";
			$this->data['success'] = "Вы успешно сменили карту на ".$_POST['map']."!";
		}else{
			$this->data['status'] = "error";
			$this->data['error'] = "Сервер должен быть включен!";
		}
		return json_encode($this->data);
	}
	
	public function changecore($serverid = null) {
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'news/index');
		}
		if($this->user->getAccessLevel() < 0) {
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
				if($server["game_code"] == "mine" || $server["game_code"] == "mcpe") {
					$core = @$this->request->post['core'];
					$coreInfo = $this->game_settings->cores[$server['game_code']][$core];
					if(is_array($coreInfo)) {
						$result = $this->serversModel->action($serverid, 'sendcommand', array('command' => 'unsetFiles'));
						if($result['status'] == "success") {
							$this->serversModel->deleteTask(array('task_name' => 'restart', 'server_id' => $serverid));
							$this->serversModel->deleteTask(array('task_name' => 'enable', 'server_id' => $serverid));
							$this->serversModel->deleteTask(array('task_name' => 'disable', 'server_id' => $serverid));
							$this->serversModel->updateServer($serverid, array('server_status' => 7, 'server_work' => 1, 'server_binary' => $core, 'server_binary_version' => $this->game_settings->cores[$server['game_code']][$core]['corebinary']));
							$this->data["status"] = "success";
							$this->data["success"] = "Вы успешно начали смену ядра!";
						} else {
							$this->data["status"] = "error";
							$this->data["error"] = $result["description"];
						}
					} else {
						$this->data["status"] = "error";
						$this->data["error"] = "Вы указали неверное ядро!";
					}
				} else {
					$this->data["status"] = "error";
					$this->data["error"] = "Не для Вашей игры!";
				}
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = "Сервер должен быть выключен!";
			}
		}
		return json_encode($this->data);
	}
	
	public function changebuild($serverid = null) {
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'news/index');
		}
		if($this->user->getAccessLevel() < 0) {
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
				if($server["game_code"] == "cs") {
					$build = @$this->request->post['build'];
					$buildInfo = $this->game_settings->builds[$server['game_code']][$build];
					if(is_array($buildInfo)) {
						$result = $this->serversModel->action($serverid, 'sendcommand', array('command' => 'unsetFiles'));
						if($result['status'] == "success") {
							$this->serversModel->deleteTask(array('task_name' => 'restart', 'server_id' => $serverid));
							$this->serversModel->deleteTask(array('task_name' => 'enable', 'server_id' => $serverid));
							$this->serversModel->deleteTask(array('task_name' => 'disable', 'server_id' => $serverid));
							$this->serversModel->updateServer($serverid, array('server_status' => 7, 'server_work' => 1, 'server_binary' => $build));
							$this->data["status"] = "success";
							$this->data["success"] = "Вы успешно начали смену билда!";
						} else {
							$this->data["status"] = "error";
							$this->data["error"] = $result["description"];
						}
					} else {
						$this->data["status"] = "error";
						$this->data["error"] = "Вы указали неверный билд!";
					}
				} else {
					$this->data["status"] = "error";
					$this->data["error"] = "Не для Вашей игры!";
				}
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = "Сервер должен быть выключен!";
			}
		}
		return json_encode($this->data);
	}
	
	public function changeversion($serverid = null) {
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'news/index');
		}
		if($this->user->getAccessLevel() < 0) {
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
				if($server["game_code"] == "ragemp") {
					$version = @$this->request->post['version'];
					$versionInfo = $this->game_settings->versions[$server['game_code']][$version];
					if(is_array($versionInfo)) {
						$result = $this->serversModel->action($serverid, 'sendcommand', array('command' => 'unsetFiles'));
						if($result['status'] == "success") {
							$this->serversModel->deleteTask(array('task_name' => 'restart', 'server_id' => $serverid));
							$this->serversModel->deleteTask(array('task_name' => 'enable', 'server_id' => $serverid));
							$this->serversModel->deleteTask(array('task_name' => 'disable', 'server_id' => $serverid));
							$this->serversModel->updateServer($serverid, array('server_status' => 7, 'server_work' => 1, 'server_binary' => $version));
							$this->data["status"] = "success";
							$this->data["success"] = "Вы успешно начали смену версии!";
						} else {
							$this->data["status"] = "error";
							$this->data["error"] = $result["description"];
						}
					} else {
						$this->data["status"] = "error";
						$this->data["error"] = "Вы указали неверную версию!";
					}
				} else {
					$this->data["status"] = "error";
					$this->data["error"] = "Не для Вашей игры!";
				}
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = "Сервер должен быть выключен!";
			}
		}
		return json_encode($this->data);
	}
	
	public function loadModuleJS($serverid = null) {
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'news/index');
		}
		if($this->user->getAccessLevel() < 0) {
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
				if($server["game_code"] == "ragemp") {
					$stats = $this->serversModel->getHDD($server['server_id']);
					if((int)$stats < $server['game_ssd']) {
						$module = @$this->request->post['module'];
						$moduleInfo = $this->game_settings->node_modules[$server['game_code']][$module];
						if(is_array($moduleInfo)) {
							$result = $this->serversModel->action($serverid, 'sendcommand', array('command' => 'loadModuleJS', 'module' => $module));
							if($result['status'] == "success") {
								$this->data["status"] = "success";
								$this->data["success"] = "Вы успешно загрузили на сервер модуль ".$this->game_settings->node_modules[$server['game_code']][$module]['module_name']."!";
							} else {
								$this->data["status"] = "error";
								$this->data["error"] = $result["description"];
							}
						} else {
							$this->data["status"] = "error";
							$this->data["error"] = "Вы указали неверный модуль!";
						}
					} else {
						$this->data["status"] = "error";
						$this->data["error"] = "Вы привысили ограничение размера директории на ". round($stats - $server['game_ssd'], 2) ."МБ, загрузка модулей невозможна!";
					}
				} else {
					$this->data["status"] = "error";
					$this->data["error"] = "Не для Вашей игры!";
				}
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = "Сервер должен быть выключен!";
			}
		}
		return json_encode($this->data);
	}	
	
	public function settingStart($serverid = null) {
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'news/index');
		}
		if($this->user->getAccessLevel() < 0) {
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
					$this->data["error"] = "Не для Вашей игры!";
				}
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = "Сервер должен быть выключен!";
			}
		}
		return json_encode($this->data);
	}
	
	public function buy_months($serverid = null) {
		if(!$this->user->isLogged()) {  
	  		$this->data['status'] = "error";
			$this->data['error'] = "Вы не авторизированы!";
			return json_encode($this->data);
		}
		if($this->user->getAccessLevel() < 1) {
	  		$this->data['status'] = "error";
			$this->data['error'] = "У вас нет доступа к данному разделу!";
			return json_encode($this->data);
		}
		
		$this->load->model('waste');
		$this->load->model('users');
		$this->load->model('servers');
		
		$error = $this->validate($serverid);
		if($error) {
			$this->data['status'] = "error";
			$this->data['error'] = $error;
			return json_encode($this->data);
		}	
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$days = @$this->request->post['days'];
			if($days != "30" && $days != "60" && $days != "90" && $days != "180" && $days != "360" && $days != "15") {
				$this->data['status'] = "error";
				$this->data['error'] = "Вы указали недопустимый период оплаты!";
				return json_encode($this->data);
			}
			
			$userid = $this->user->getId();
			$balance = $this->user->getBalance();
			
			$server = $this->serversModel->getServerById($serverid, array('games'));
			
			$price = $server['server_slots'] * $server['game_price'];
			switch($days) {
				case "15":
					$price = $price / 2;
					break;
				case "30":
					break;
				case "60":
					$price = $price * 2;
					break;
				case "90":
					$price = 3 * $price * 0.95;
					break;
				case "180":
					$price =  6 * $price * 0.90;
					break;
				case "360":
					$price = 12 * $price * 0.85;
					break;
			}
			if($balance >= $price) {
				if($server['server_status'] == 0) {
					$this->serversModel->updateServer($serverid, array('server_status' => 1));
					$this->serversModel->extendServer($serverid,$days,true);
					$this->serversModel->action($serverid, "updatepassm");
					$this->serversModel->action($serverid, "updatepass");
				} else {
					$this->serversModel->extendServer($serverid,$days,false);
				}
				$this->usersModel->downUserBalance($userid, $price);
				$wasteData = array(
					'user_id'		=> $userid,
					'waste_ammount'	=> $price,
					'waste_status'	=> 1,
					'waste_usluga'	=> "Продление сервера gs$serverid на $days дней"
				);
				$this->wasteModel->createWaste($wasteData);
		
				$user = $this->usersModel->getUserById($userid, array(), array(), array());
						
				if($user['ref'] != 0) {
					$ref_percent = $this->config->ref_percent;
					$getpref = ($price * (1 + $ref_percent / 100)) - $price;
					$this->usersModel->upUserBalance($user['ref'], $getpref);
					$this->usersModel->upUserRMoney($user['ref'], $getpref);
										
					$wasteData = array(
						'user_id'		=> $user['ref'],
						'waste_ammount'	=> $getpref,
						'waste_status'	=> 0,
						'waste_usluga'	=> "Бонус с реферала ID-$userid"
					); 
					$this->wasteModel->createWaste($wasteData);
				}
				
				$logData = array(
					'server_id'			=> $serverid,
					'reason'            => 'Сервер успешно продлен!',
					'status'            => 1
				);
                $this->serversModel->createLog($logData);
				$this->data['status'] = "success";
				$this->data['success'] = "Вы успешно оплатили сервер на $days дней. С Вашего счета снято ".(round($price, 2))." руб";
		    } else {
				$logData = array(
					'server_id'			=> $serverid,
					'reason'            => '[PAY] На счету не достаточно средств для продления сервера!',
					'status'            => 3
				);
				$this->serversModel->createLog($logData);
				$this->data['status'] = "error";
				$this->data['error'] = "На Вашем счету не хватает ".(round($price-$balance, 2))." руб";	
			}
		}
		return json_encode($this->data);
	}
	
	private function validate($serverid) {
		$result = null;
		
		$userid = $this->user->getId();
		
		if(!$this->serversModel->getTotalServerOwners(array('server_id' => (int)$serverid, 'user_id' => (int)$userid, 'owner_status' => 1))) {
			if(!$this->serversModel->getTotalServers(array('server_id' => (int)$serverid, 'user_id' => (int)$userid))) {
				$result = "Запрашиваемый сервер не существует!";
			}
		}
		return $result;
	}
	
	private function validatePOST($serverid)
	{
		$this->load->library('validate');
		$this->load->model('games');
		$validateLib = new validateLibrary();
		$result = NULL;
		$server = $this->serversModel->getServerById($serverid, array('games', 'locations'));
		$gameid = $server['game_id'];
		$slots = $this->request->post['slots'];

		if (!$this->gamesModel->validateSlots($gameid, $slots)) {
			$result = 'Вы указали недопустимое количество слотов!';
		}
		elseif(!$validateLib->money($slots)) {
			$result = "Укажите количество слотов в допустимом формате!";
		}
		return $result;
	}
}
?>
