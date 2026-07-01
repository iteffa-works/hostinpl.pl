<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class createController extends Controller {
	public function index() {
		$this->document->setActiveSection('admin/games');
		$this->document->setActiveItem('create');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 3) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->data['queryDrivers'] = $this->getQueryDrivers();
		
		$this->getChild(array('common/admheader', 'common/footer'));
		return $this->load->view('admin/games/create', $this->data);
	}
	
	public function ajax() {
		if(!$this->user->isLogged()) {  
	  		$this->data['status'] = "error";
			$this->data['error'] = "Вы не авторизированы!";
			return json_encode($this->data);
		}
		if($this->user->getAccessLevel() < 3) {
			$this->data['status'] = "error";
			$this->data['error'] = "У вас нет доступа к данному разделу!";
			return json_encode($this->data);
		}
		
		$this->load->model('games');
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$name = @$this->request->post['name'];
				$code = @$this->request->post['code'];
				$query = @$this->request->post['query'];
				$minslots = @$this->request->post['minslots'];
				$maxslots = @$this->request->post['maxslots'];
				$minport = @$this->request->post['minport'];
				$maxport = @$this->request->post['maxport'];
				$price = @$this->request->post['price'];
				$status = @$this->request->post['status'];
				$cpu = @$this->request->post['cores'];
				$ram = @$this->request->post['ram'];
				$ssd = @$this->request->post['ssd'];
				
				$gameData = array(
					'game_name'			=> $name,
					'game_code'			=> $code,
					'game_query'		=> $query,
					'game_min_slots'	=> (int)$minslots,
					'game_max_slots'	=> (int)$maxslots,
					'game_min_port'		=> (int)$minport,
					'game_max_port'		=> (int)$maxport,
					'game_price'		=> (float)$price,
					'game_status'		=> (int)$status,
					'game_ram'			=> (int)$ram,
					'game_cores'		=> (float)$cpu,
					'game_ssd'			=> (int)$ssd
				);
				
				$this->gamesModel->createGame($gameData);
				
				$this->data['status'] = "success";
				$this->data['success'] = "Вы успешно создали игру!";
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
		
		$name = @$this->request->post['name'];
		$code = @$this->request->post['code'];
		$query = @$this->request->post['query'];
		$minslots = @$this->request->post['minslots'];
		$maxslots = @$this->request->post['maxslots'];
		$minport = @$this->request->post['minport'];
		$maxport = @$this->request->post['maxport'];
		$price = @$this->request->post['price'];
		$status = @$this->request->post['status'];
		$cpu = @$this->request->post['cores'];
		$ram = @$this->request->post['ram'];
		$ssd = @$this->request->post['ssd'];
		
		if(mb_strlen($name) < 2 || mb_strlen($name) > 92) {
			$result = "Название игры должно содержать от 2 до 92 символов!";
		}
		elseif(mb_strlen($code) < 2 || mb_strlen($code) > 8) {
			$result = "Код игры должен содержать от 2 до 8 символов!";
		}
		elseif(!in_array($query, $this->getQueryDrivers())) {
			$result = "Укажите допустимый Query-драйвер.";
		}
		elseif(!$validateLib->money($price)) {
			$result = "Укажите допустимую стоимость!";
		}
		elseif(!$validateLib->money($cpu)) {
			$result = "Укажите допустимое количество ядер!";
		}
		elseif(!$validateLib->money($ram)) {
			$result = "Укажите допустимый объем оперативной памяти!";
		}
		elseif(!$validateLib->money($ssd)) {
			$result = "Укажите допустимую объем жесткого диска!";
		}
		elseif($status < 0 || $status > 1) {
			$result = "Укажите допустимый статус!";
		}
		return $result;
	}
	
	function getQueryDrivers() {
		$result = array();
		$drivers = glob(ENGINE_DIR . 'libs/query/*.driver.php');
		foreach ($drivers as $filename) {
			$result[] = basename($filename, ".driver.php");
		}
		return $result;
	}
}
?>
