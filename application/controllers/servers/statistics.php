<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class statisticsController extends Controller {
	public function index($serverid = null) {
		$this->document->setActiveSection('servers');
		$this->document->setActiveItem('statistic');
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
		$logs = $this->serversModel->getLogs(array(), array('servers'), array(), array());
		$stats = $this->serversModel->getServerStats($serverid, "NOW() - INTERVAL 31 DAY", "NOW()");
		
		$this->data['server'] = $server;
		$this->data['logs'] = $logs;
		$this->data['stats'] = $stats;

		include_once 'application/controllers/common/main.php';
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('servers/statistics', $this->data);
	}
	
	public function getData($serverid = null) {
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 0) {
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
			$queryLib->connect($server['location_ip'], $server['server_port']);
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
?>
