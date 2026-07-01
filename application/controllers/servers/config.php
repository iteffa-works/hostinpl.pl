<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class configController extends Controller {
	public function index($serverid = null) {
		$this->document->setActiveSection('servers');
		$this->document->setActiveItem('config');
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
		
		if($server['server_status'] == 1 || $server['server_status'] == 2) {
			if($server['game_query'] == 'samp') {
				$file = "/server.cfg";
			} else if($server['game_query'] == 'mtasa') {
				$file = "/mods/deathmatch/mtaserver.conf";
			} else if($server['game_code'] == 'cs') {
				$file = "/cstrike/server.cfg";
			} else if($server['game_code'] == 'css') {
				$file = "/cstrike/cfg/server.cfg";
			} else if($server['game_code'] == 'csgo') {
				$file = "/csgo/cfg/server.cfg";
			} else if($server['game_query'] == 'mine') {
				$file = "/server.properties";
			} else if($server['game_code'] == 'ragemp') {
				$file = "/conf.json";
			}
			
			$this->load->library('sftp');		
			$sftpLib = new sftpLibrary();		
			$sftpLink = $sftpLib->connect($server['location_ip'], $server['location_user'], $server['location_password']);
			$config = $sftpLib->open($sftpLink, '/home/gs' . $serverid . '/' . $file);
			$this->data['config'] = $config;
		} else {
			$config = "В данном статусе не доступно редактирование конфигурации!";
			$this->data['config'] = $config;
		}

		include_once 'application/controllers/common/main.php';
	
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('servers/config', $this->data);
	}

	public function send_config($serverid = null)
	{
		if (!$this->user->isLogged()) {
			$this->data['status'] = 'error';
			$this->data['error'] = 'Вы не авторизированы!';
			return json_encode($this->data);
		}

		if ($this->user->getAccessLevel() < 0) {
			$this->data['status'] = 'error';
			$this->data['error'] = 'У вас нет доступа к данному разделу!';
			return json_encode($this->data);
		}

		$this->load->model('servers');

		$error = $this->validate($serverid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'servers/index');
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

		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			if($server['server_status'] == 1 || $server['server_status'] == 2) {
				$config = htmlspecialchars_decode($this->request->post['config']);
				if($server['game_query'] == 'samp') {
					$file = "/server.cfg";
				} else if($server['game_query'] == 'mtasa') {
					$file = "/mods/deathmatch/mtaserver.conf";
				} else if($server['game_code'] == 'cs') {
					$file = "/cstrike/server.cfg";
				} else if($server['game_code'] == 'css') {
					$file = "/cstrike/cfg/server.cfg";
				} else if($server['game_code'] == 'csgo') {
					$file = "/csgo/cfg/server.cfg";
				} else if($server['game_query'] == 'mine') {
					$file = "/server.properties";
				} else if($server['game_code'] == 'ragemp') {
					$file = "/conf.json";
				}

				$this->load->library('sftp');		
				$sftpLib = new sftpLibrary();		
				$sftpLink = $sftpLib->connect($server['location_ip'], $server['location_user'], $server['location_password']);
				$sftpLib->write($sftpLink, '/home/gs' . $serverid . '/' . $file, $config);
				$this->data['status'] = 'success';
				$this->data['success'] = 'Конфигурационный файл отредактирован!';
			}
		}
		return json_encode($this->data);
	}

	function validate($serverid) {
		$result = null;
		$this->user->getId(  );
		$userid = $this->user->getId();

		if(!$this->serversModel->getTotalServerOwners(array('server_id' => (int)$serverid, 'user_id' => (int)$userid, 'owner_status' => 1))) {
			if(!$this->serversModel->getTotalServers(array('server_id' => (int)$serverid, 'user_id' => (int)$userid))) {
				$result = "Запрашиваемый сервер не существует!";
			}
		}
		return $result;
	}
	
	private function validatePOST() {
	
		$this->load->library('validate');
		
		$validateLib = new validateLibrary();
	
		return $result;
	}
}