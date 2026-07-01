<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class ownerController extends Controller {
	public function index($serverid = null) {
		$this->document->setActiveSection('servers');
		$this->document->setActiveItem('owner');
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
		$this->data['serversOwners'] = $this->serversModel->getOwners(array('server_id' => $serverid), array('users'));
		
		include_once 'application/controllers/common/main.php';

		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('servers/owner', $this->data);
	}
	
	public function create($serverid = null) {
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

		$this->load->model('users');
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
			if(!$this->validatePOST($serverid)) {
				$ownerid = @$this->request->post['ownerid'];
					
				$ownerData = array(
					'server_id'				=> $serverid,
					'user_id'				=> $ownerid,
					'owner_status'  		=> 1
				);
				$this->serversModel->createOwner($ownerData);
					
				$this->data['status'] = "success";
				$this->data['success'] = "Вы успешно добавили друга!";			
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = $this->validatePOST($serverid);
			}
		}

		return json_encode($this->data);
	}
	
	
	public function delete($serverid = null, $ownerid = null) {
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

		$this->load->model('users');
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
			if($this->serversModel->getTotalServerOwners(array('server_id' => $serverid, 'owner_id' => $ownerid))) {
				$this->serversModel->deleteOwner($ownerid);
					
				$this->data['status'] = "success";
				$this->data['success'] = "Вы успешно удалили друга!";
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = "Ошибка!";
			}
		}

		return json_encode($this->data);
	}
	
	private function validate($serverid) {
		$result = null;
		$userid = $this->user->getId();

		if (!$this->serversModel->getTotalServers( array( 'server_id' => (int)$serverid, 'user_id' => (int)$userid ) )) {
			$result = 'Вы не являетесь владельцем сервера. У вас нет доступа к разделу!';
		}

		return $result;
	}

	private function validatePOST($serverid) {
		$result = null;
		$this->load->library('validate');
		
		$validateLib = new validateLibrary();
		$ownerid = @$this->request->post['ownerid'];
		$userid = $this->user->getId();
				
		if(!$validateLib->money($ownerid)) {
			$result = "Вы указали недоступный ID пользователя!";
		}elseif(!$this->usersModel->getTotalUsers(array('user_id' => $ownerid))) {
			$result = "Запрашиваемый пользователь не существует!";
		}elseif($this->serversModel->getTotalServerOwners(array('server_id' => $serverid, 'user_id' => $ownerid))) {
			$result = "Запрашиваемый пользователь уже добавлен!";
		}elseif($userid == $ownerid) {
			$result = "Запрашиваемый пользователь является владельцем сервера!";
		}
		return $result;
	}
}