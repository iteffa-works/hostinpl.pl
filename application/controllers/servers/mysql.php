<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class mysqlController extends Controller {
	public function index($serverid = null) {
		$this->document->setActiveSection('servers');
		$this->document->setActiveItem('mysql');
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

		$server = $this->serversModel->getServerById($serverid, array('games', 'locations'));
		$this->data['server'] = $server;
		
		include_once 'application/controllers/common/main.php';
	
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('servers/mysql', $this->data);
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
		
		$error = $this->validate($serverid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'servers/index');
		}
		
		$server = $this->serversModel->getServerById($serverid);
		
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
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$editpassword = @$this->request->post['editpassword'];
				$password = @$this->request->post['password'];
				if($editpassword) {
					$this->serversModel->updateServer($serverid, array('db_pass' => $password));	
					$result = $this->serversModel->action($serverid, "updatepassm");
					if($result["status"] == "success") {
						$this->data['status'] = "success";
						$this->data['success'] = "Пароль MySql успешно изменен!!";
					} else {
						$this->data["status"] = "error";
						$this->data["error"] = $result["description"];
					}
				} else {
					$this->data['status'] = "error";
					$this->data['error'] = "Ошибка!";
				}
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = $errorPOST;
			}
		}
		return json_encode($this->data);
	}
	
	private function validatePOST() {
		$this->load->library('validate');
		
		$validateLib = new validateLibrary();
		
		$result = null;

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
}
?>
