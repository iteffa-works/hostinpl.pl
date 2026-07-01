<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class autoinstallController extends Controller {
	public function index($serverid = null) {
		$this->document->setActiveSection('servers');
		$this->document->setActiveItem('autoinstall');
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

		$mods = $this->serversModel->getMods(array('mod_status' => 1),array('games'),array(), array());
		$this->data['usermods'] = $this->usersModel->getUserMods($userid);
        $this->data['mods'] = $mods;
		
		include_once 'application/controllers/common/main.php';
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('servers/autoinstall', $this->data);
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
		$this->load->library('ssh2');
		$this->load->model('users');
		$this->load->model('waste');
		$ssh2Lib = new ssh2Library();
		
		$error = $this->validate($serverid);
		if($error) {
			$this->data['status'] = "error";
			$this->data['error'] = $error;
			return json_encode($this->data);
		}	
		
		$error = $this->validateMode($action);
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
		
		$mod = $this->serversModel->getModById($action);
		
		if($mod["mod_status"] != 1) {
			$this->data["status"] = "error";
			$this->data["error"] = "Данный мод не доступен для установки!";
			return json_encode($this->data);
		}
	
		if($server['server_mysql'] == 0) {
			$this->data['status'] = "error";
			$this->data['error'] = "Создайте базу MySQL и включите ее!";	
			return json_encode($this->data);		
		}
		
		if($server['server_status'] != 1) {
			$this->data['status'] = "error";
			$this->data['error'] = "Сервер должен быть выключен!";
			return json_encode($this->data);	
		}
		
		$stats = $this->serversModel->getHDD($server['server_id']);
		if($stats > $server['game_ssd']) {
			$this->data["status"] = "error";
			$this->data["error"] = "Вы привысили ограничение размера директории на ". round($stats - $server['game_ssd'], 2) ."МБ, установка мода невозможна!";
			return json_encode($this->data);
		}
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->serversModel->deleteTask(array('task_name' => 'restart', 'server_id' => $serverid));
			$this->serversModel->deleteTask(array('task_name' => 'enable', 'server_id' => $serverid));
			$this->serversModel->deleteTask(array('task_name' => 'disable', 'server_id' => $serverid));			
			if($mod['mod_price'] > 0) {
				if($this->usersModel->checkFreeMode($userid, $mod['mod_id'])){
					$free_mode = 1;
				}
				$price = $mod['mod_price'];
				if(($balance < $price) and $free_mode != 1){
					$this->data['status'] = "error";
					$this->data['error'] = "Недостаточно средств";
					return json_encode($this->data);
				}
			}
			$this->serversModel->action($serverid, 'reinstall');
			$host = $server['location_ip'];
			$uname = "gs$serverid";
			$dbpass = $server['db_pass'];
			$database = "gs$serverid"; 
			$conn = new mysqli($host, $uname, $dbpass, $database);
			$op_data = '';
			if ($mresult = $conn->query("SHOW TABLES"))
			{
				while($row = $mresult->fetch_array(MYSQLI_NUM))
				{
					$conn->query('DROP TABLE IF EXISTS '.$row[0]);
				}
			}	
			$FILE = "scriptfiles/mysql_settings.ini";
		    $link = $ssh2Lib->connect($server['location_ip'], $server['location_user'], $server['location_password']);
			$ssh2Lib->execute($link, 
				"
					cd /home/gs$serverid;	
					rm server.cfg;
					rm -rf gamemodes;
					rm -rf filterscripts;
					rm -rf include;
					rm -rf npcmodes;
					rm -rf scriptfiles;
					wget ".$mod['mod_url'].";
					tar -xf ".$mod['mod_arch'].";
					rm ".$mod['mod_arch'].";							
					mysql -h".$server['location_ip']." -u gs$serverid -p".$server['db_pass']." gs$serverid < dump.sql;
					rm dump.sql;
					echo 'host = ".$server['location_ip']."'>>".$FILE.";
					echo 'username = gs$serverid'>>".$FILE.";
					echo 'password = ".$server['db_pass']."'>>".$FILE.";
					echo 'database = gs$serverid'>>".$FILE.";
					sudo chown -R gs". $server['server_id'] .":gameservers /home/gs". $server['server_id'] ."	
				"
			);
			$ssh2Lib->disconnect($link);
			if($mod['mod_price'] > 0) {
				if($free_mode != 1){
					$userid = $this->user->getId();
					$wasteData = array(
						'user_id'			=> $userid,
						'waste_ammount'	=> $price,
						'waste_status'	=> 1,
						'waste_usluga'	=> "Установка мода ".$mod['mod_name'].""
					); 
					$this->wasteModel->createWaste($wasteData);
					$this->usersModel->downUserBalance($userid, $price);
					$freeData = array(
						'user_id'			=> $userid,
						'mod_id'			=> "".$mod['mod_id'].""
					); 
					$this->usersModel->addMode($freeData);
							
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
					$this->data['success'] = "Мод ".$mod['mod_name']." успешно был установлен,с вашего счёта снято ".$mod['mod_price']." р! Запускаем игровой сервер, пожалуйста, подождите...";	
				} else{
					$this->data['status'] = "success";
					$this->data['success'] = "Мод ".$mod['mod_name']." успешно был установлен. Запускаем игровой сервер, пожалуйста, подождите...";
				}
			} else {
				$this->data['status'] = "success";
				$this->data['success'] = "Мод ".$mod['mod_name']." успешно был установлен. Запускаем игровой сервер, пожалуйста, подождите...";
			}
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
	
	private function validateMode($modid) {
		$result = null;
		
		if(!$this->serversModel->getTotalMods(array('mod_id' => (int)$modid))) {
			$result = "Запрашиваемый мод не существует!";
		}
		return $result;
	}
}
?>