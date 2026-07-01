<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class firewallController extends Controller {
	public function index($serverid = null) {
		$this->document->setActiveSection('servers');
		$this->document->setActiveItem('firewall');
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
		
		$this->load->model('servers');
		
		$error = $this->validate($serverid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'servers/index');
		}

		$this->data['server'] = $this->serversModel->getServerById($serverid, array('games', 'locations'));
		$this->data['Firewalls'] = $this->serversModel->getFirewallsById($serverid);
		
		include_once 'application/controllers/common/main.php';

		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('servers/firewall', $this->data);
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

		$this->load->model('servers');

		$server = $this->serversModel->getServerById($serverid);
		
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
			$ipmask = @$this->request->post['address'];

			if (!$ipmask) {
				$this->data['status'] = 'error';
				$this->data['error'] = 'Вы не ввели IP';
				return json_encode($this->data);
			}
			
			$ip = explode('/', $ipmask)[0];
			$net = explode('/', $ipmask)[1];

			if (($ipmask !== str_replace('/', '', $ipmask)) && !$net) {
				$this->data['status'] = 'error';
				$this->data['error'] = 'Не введены данные после "/"';
				return json_encode($this->data);
			}

			if (!filter_var($ip, FILTER_VALIDATE_IP)) {
				$this->data['status'] = 'error';
				$this->data['error'] = 'Введен неверный IP';
				return json_encode($this->data);
			}
			if ($net) {
				if (is_numeric($net)) {
					if (2 < mb_strlen($net)) {
						$this->data['status'] = 'error';
						$this->data['error'] = 'Не больше двух цифр после "/"';
						return json_encode($this->data);

						if (!filter_var($net, FILTER_VALIDATE_IP)) {
							$this->data['status'] = 'error';
							$this->data['error'] = 'Введены неверные данные после "/"';
							return json_encode($this->data);
						}
					}
				}
				else {
					$this->data['status'] = 'error';
					$this->data['error'] = 'Введены неверные данные после "/"';
					return json_encode($this->data);
				}
			}

			if ($this->serversModel->getTotalFirewalls(array('server_ip' => $ipmask, 'server_id' => $serverid))) {
				$this->data['status'] = 'error';
				$this->data['error'] = 'Данное правило уже добавлено!';
				return json_encode($this->data);
			}

			$firewallData = array(
				'server_id'		=> $serverid,
				'server_ip'  	=> $ipmask
			);
			$firewallid = $this->serversModel->createFirewall($firewallData);
					
			$this->serversModel->addFirewall($firewallid);
			$this->data['status'] = "success";
			$this->data['success'] = "Вы успешно заблокировали ".$ipmask."!";		
			return json_encode($this->data);
		}
	}
	
	public function action($action = null, $serverid = null, $firewallid = null) {
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
		
		$this->load->model('servers');

		$server = $this->serversModel->getServerById($serverid);
			
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
		
		switch($action) {
			case 'delete': {
				$this->serversModel->deleteFirewall($firewallid);

				$this->data['status'] = "success";
				$this->data['success'] = "Вы успешно разблокировали IP!";
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

}