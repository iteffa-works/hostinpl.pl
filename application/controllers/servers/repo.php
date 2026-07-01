<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class repoController extends Controller {
	public function index($serverid = null) {
		$this->document->setActiveSection('servers');
		$this->document->setActiveItem('repo');
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
		$this->load->model('users');

		$error = $this->validate($serverid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'servers/index');
		}
		
		$userid = $this->user->getId();
		
		$server = $this->serversModel->getServerById($serverid, array('games', 'locations'));
		$this->data['server'] = $server;

		$repos = $this->serversModel->getRepos(array('repo_status' => 1),array('games'),array(), array());
		$this->data['userrepos'] = $this->usersModel->getUserRepos($userid);
        $this->data['repos'] = $repos;
		
		include_once 'application/controllers/common/main.php';
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('servers/repo', $this->data);
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
		$userid = $this->user->getId();
		$this->load->model('servers');
		$this->load->model('users');
		$this->load->model('waste');
		
		$error = $this->validate($serverid);
		if($error) {
			$this->data['status'] = "error";
			$this->data['error'] = $error;
			return json_encode($this->data);
		}	
		
		$error = $this->validateRepo($action);
		if($error) {
			$this->data['status'] = "error";
			$this->data['error'] = $error;
			return json_encode($this->data);
		}	
		
		$balance = $this->user->getBalance();
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
		
		$repo = $this->serversModel->getRepoById($action);
		
		if($repo["repo_status"] != 1) {
			$this->data["status"] = "error";
			$this->data["error"] = "Данный файл не доступен!";
			return json_encode($this->data);
		}
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {	
			$price = $repo['repo_price'];
			if($balance < $price){
				$this->data['status'] = "error";
				$this->data['error'] = "Недостаточно средств";
				return json_encode($this->data);
			}
			$userid = $this->user->getId();
			$wasteData = array(
				'user_id'		=> $userid,
				'waste_ammount'	=> $price,
				'waste_status'	=> 1,
				'waste_usluga'	=> "Покупка файла ".$repo['repo_id'].""
			); 
			$this->wasteModel->createWaste($wasteData);
			$this->usersModel->downUserBalance($userid, $price);
			$freeData = array(
				'user_id'			=> $userid,
				'repo_id'			=> "".$repo['repo_id'].""
			); 
			$this->usersModel->addRepos($freeData);
						
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
				
			$this->data['status'] = "success";
			$this->data['success'] = "Файл ".$repo['repo_name']." успешно был приобретен, с вашего счёта снято ".$repo['repo_price']." р!";	
		} else {
			$this->data['status'] = "error";
			$this->data['error'] = "Не POST запрос!";
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
	
	private function validateRepo($repoid) {
		$result = null;
		
		if(!$this->serversModel->getRepoById(array('repo_id' => (int)$repoid))) {
			$result = "Запрашиваемый файл не существует!";
		}
		return $result;
	}
}
?>