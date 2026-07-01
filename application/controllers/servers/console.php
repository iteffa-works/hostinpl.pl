<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class consoleController extends Controller {
	public function index($serverid = null) {
		$this->document->setActiveSection('servers');
		$this->document->setActiveItem('console');
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
		
		if(isset($this->request->get['open'])) {
			$this->data['fileid'] = $this->request->get['open'];
		}
		
		$this->load->model('servers');

		$error = $this->validate($serverid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'servers/index');
		}
		
		$server = $this->serversModel->getServerById($serverid, array('games', 'locations'));
		$this->data['server'] = $server;
		
		if(isset($_COOKIE["data-theme-console"])){
			$this->data['theme'] = $_COOKIE['data-theme-console'];
		} else{
			$this->data['theme'] = "color: white; background-color: black; font-family: Inconsolata; resize: none; min-height: 300px;";
		}
		
		include_once 'application/controllers/common/main.php';

		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('servers/console', $this->data);
	}

	public function getconsole($serverid = null, $file = null) {
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
		$this->load->library('ssh2');

		$server = $this->serversModel->getServerById($serverid, array('games', 'locations'));

		$error = $this->validate($serverid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'servers/index');
		}
		
		switch($file) {
			default: {	
				if($server["game_query"] == "samp") {
					$fileLog = "server_log.txt";
				} elseif($server["game_query"] == "mtasa") {
					$fileLog = "mods/deathmatch/logs/server.log";
				} elseif($server["game_code"] == "mcpe") {
					$fileLog = "server.log";
				} elseif($server["game_code"] == "cs") {
					$fileLog = "cstrike/qconsole.log";
				} elseif($server["game_code"] == "css") {
					$fileLog = "cstrike/console.log";
				} elseif($server["game_code"] == "csgo") {
					$fileLog = "csgo/console.log";
				} else {
					$fileLog = "screenlog.0";
				}
				break;
			}
			case 'screenlog': {					
				$fileLog = "screenlog.0";
				break;
			}
		}
		
		if($server['server_status'] == 3) {
			$result = "Идет установка сервера!";
		} else if($server['server_status'] == 4) {
			$result = "Идет переустановка сервера!";
		} else if($server['server_status'] == 5) {
			$result = "Идет создание BackUP сервера!";
		} else if($server['server_status'] == 6) {
			$result = "Идет восстанновление сервера из BackUP!";
		} else if($server['server_status'] == 7) {
			$result = "Идет обновление сервера!";
		} else if($server["server_status"] == 0) {
			$result = "Сервер заблокирован!";
		} else if($server["server_status"] == 1) {
			$result = "Сервер должен быть включен!";
		} else if($server["server_status"] == 2) {
			$ssh = new ssh2Library();
			$connect = $ssh->connect($server['location_ip'], $server['location_user'], $server['location_password']);
			if($server["game_query"] == "ragemp" || $server["game_query"] == "valve" || $server["game_query"] == "mine") {
				$logs = $ssh->execute($connect, "tail -n 500 /home/gs" . $serverid . "/" . $fileLog. " | iconv -t utf8");
			} else {
				$logs = $ssh->execute($connect, "tail -n 500 /home/gs" . $serverid . "/" . $fileLog. " | iconv -f cp1251 -t utf8");
			}
			$logs = str_replace("", "", $logs);
			$logs = str_replace(">[2K", "", $logs);
			$logs = str_replace(">", "", $logs);
			$logs = str_replace("[1mnull[22m", "", $logs);
			if(!empty($logs)) {
				$result = $logs;
			} else {
				$result = 'Список логов пуст :(';
			}
			$ssh->disconnect($connect);
		}
		return $result;
	}		

	public function sendconsole($serverid = null) {
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
		$this->load->library('ssh2');

		$server = $this->serversModel->getServerById($serverid, array('games', 'locations'));

		$error = $this->validate($serverid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'servers/index');
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
		
		$command = @$this->request->post['cmd'];

		if($server['server_status'] == 2){
			if($command == ""){
				$this->data['status'] = "error";
				$this->data['error'] = "Введите команду!";
			}elseif($command != ""){
				if($server['game_query'] == "samp") {
					$this->load->library('SampRconAPI');
					$SampRcon = new SampRconAPI($server['location_ip2'], $server['server_port'], $server['rcon_password']);
					if ($SampRcon->connect()) {
						$SampRcon->call($command, false);
						$SampRcon->close();
						$this->data['status'] = "success";
						$this->data['success'] = "Команда успешно отправлена!";
					} else { 
						$this->data['status'] = "error";
						$this->data['error'] = "Ошибка подключения к rcon!";
					}
				} else {					
					$exec = str_replace("'", "&#039;", $command);
					$this->serversModel->action($serverid, 'sendcommand', array('command' => $exec));
						
					$this->data['status'] = "success";
					$this->data['success'] = "Команда успешно отправлена!";
				}
			}
		}else{
			$this->data['status'] = "error";
			$this->data['error'] = "Сервер должен быть включён!";
		}
		return json_encode($this->data);
	}
	
	public function clearcon($serverid = null) {
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
		$this->load->library('ssh2');
		
		$server = $this->serversModel->getServerById($serverid, array('games','locations'));	
		
		$error = $this->validate($serverid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'servers/index');
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
			if($server['server_status'] == 2) {
				$ssh2Lib = new ssh2Library();
				$link = $ssh2Lib->connect($server['location_ip'], $server['location_user'], $server['location_password']);
				if($server['game_query'] == 'samp') {				
					$ssh2Lib->execute($link, "cat /dev/null > /home/gs$serverid/server_log.txt;");
					$ssh2Lib->execute($link, "cat /dev/null > /home/gs$serverid/screenlog.0;");
				} elseif($server['game_code'] == 'mcpe') {				
					$ssh2Lib->execute($link, "cat /dev/null > /home/gs$serverid/server.log;");
				} elseif($server['game_query'] == 'mtasa') {				
					$ssh2Lib->execute($link, "cat /dev/null > /home/gs$serverid/mods/deathmatch/logs/server.log;");
				} elseif($server["game_code"] == "cs") {
					$ssh2Lib->execute($link, "cat /dev/null > /home/gs$serverid/cstrike/qconsole.log;");
				} elseif($server["game_code"] == "css") {
					$ssh2Lib->execute($link, "cat /dev/null > /home/gs$serverid/cstrike/console.log;");
				} elseif($server["game_code"] == "csgo") {
					$ssh2Lib->execute($link, "cat /dev/null > /home/gs$serverid/csgo/console.log;");
				} else {
					$ssh2Lib->execute($link, "cat /dev/null > /home/gs$serverid/screenlog.0;");
				}
				$ssh2Lib->disconnect($link);
				$this->data['status'] = "success";
				$this->data['success'] = "Консоль успешно очищена!";
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = "Сервер должен быть включен!";
			}
		}
		return json_encode($this->data);
	}
	
	public function action_theme_console($action = null) {
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

		switch($action) {
			case 'default': {	
					$name = 'color: white; background-color: black; font-family: Inconsolata; resize: none; min-height: 300px;';
					setcookie('data-theme-console',$name,time() + (86400 * 5), '/' );
					$this->data['status'] = "success";
					$this->data['success'] = "Тема установлена!";
				break;
			}
			case 'Amethyst': {	
					$name = 'color: #323e42; background-color: #a48ad4; font-family: Inconsolata; resize: none; min-height: 300px;';
					setcookie('data-theme-console',$name,time() + (86400 * 5), '/' );
					$this->data['status'] = "success";
					$this->data['success'] = "Тема установлена!";
				break;
			}
			case 'City': {	
					$name = 'color: #323e42; background-color: #ff6b6b; font-family: Inconsolata; resize: none; min-height: 300px;';
					setcookie('data-theme-console',$name,time() + (86400 * 5), '/' );
					$this->data['status'] = "success";
					$this->data['success'] = "Тема установлена!";
				break;
			}
			case 'Flat': {	
					$name = 'color: #323e42; background-color: #44b4a6; font-family: Inconsolata; resize: none; min-height: 300px;';
					setcookie('data-theme-console',$name,time() + (86400 * 5), '/' );
					$this->data['status'] = "success";
					$this->data['success'] = "Тема установлена!";
				break;
			}
			case 'Modern': {	
					$name = 'color: #323e42; background-color: #14adc4; font-family: Inconsolata; resize: none; min-height: 300px;';
					setcookie('data-theme-console',$name,time() + (86400 * 5), '/' );
					$this->data['status'] = "success";
					$this->data['success'] = "Тема установлена!";
				break;
			}
			case 'Smooth': {	
					$name = 'color: #323e42; background-color: #ff6c9d; font-family: Inconsolata; resize: none; min-height: 300px;';
					setcookie('data-theme-console',$name,time() + (86400 * 5), '/' );
					$this->data['status'] = "success";
					$this->data['success'] = "Тема установлена!";
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
		
		$result = null;
		return $result;
	}
}