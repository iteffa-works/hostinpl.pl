<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class indexController extends Controller {
	private $limit = 22;
	public function index($page = 1) {
		$this->document->setActiveSection('admin/servers');
		$this->document->setActiveItem('index');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 2) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->library('pagination');
		$this->load->model('servers');
		
		$userid = @$this->request->get['userid'];
		$gameid = @$this->request->get['gameid'];
		$locationid = @$this->request->get['locationid'];
			
		$getData = array();
		
		if(!empty($userid)) {
			$getData['servers.user_id'] = (int)$userid;
		}
		if(!empty($gameid)) {
			$getData['servers.game_id'] = (int)$gameid;
		}
		if(!empty($locationid)) {
			$getData['servers.location_id'] = (int)$locationid;
		}
			
		$getOptions = array(
			'start' => ($page - 1) * $this->limit,
			'limit' => $this->limit
		);
		
		
		$total = $this->serversModel->getTotalServers($getData);
		$servers = $this->serversModel->getServers($getData, array('games', 'locations'), array(), $getOptions);
		
		$paginationUrl = '/admin/servers/index/index/{page}';
		if(!empty($userid)) {
			$paginationUrl .= '&userid=' . $userid;
		}
		if(!empty($gameid)) {
			$paginationUrl .= '&gameid=' . $gameid;
		}
		if(!empty($locationid)) {
			$paginationUrl .= '&locationid=' . $locationid;
		}
	
		$paginationLib = new paginationLibrary();
		$paginationLib->total = $total;
		$paginationLib->page = $page;
		$paginationLib->limit = $this->limit;
		$paginationLib->url = $paginationUrl;
		$pagination = $paginationLib->render();
			
		$this->data['servers'] = $servers;
		$this->data['pagination'] = $pagination;
		
		$this->getChild(array('common/admheader', 'common/footer'));
		return $this->load->view('admin/servers/index', $this->data);
	}
	
	public function action($serverid = null, $action = null) {
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

		$this->load->model('servers');
		$this->load->model('serverLog');

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
		} else if($server["server_status"] == 0) {
			$this->data["status"] = "error";
			$this->data["error"] = "Сервер заблокирован!";
			return json_encode($this->data);
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
		}

		return json_encode($this->data);
	}
	
	private function validate($serverid) {
		$result = null;

		$userid = $this->user->getId();

		if(!$this->serversModel->getTotalServers(array('server_id' => (int)$serverid, 'user_id' => (int)$userid))) {
			$result = "Запрашиваемый сервер не существует или у ват нету доступа!";
		}
		return $result;
	}
}
?>
