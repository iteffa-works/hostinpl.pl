<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class editController extends Controller {
	public function index($modid = null) {
		$this->document->setActiveSection('admin/mods');
		$this->document->setActiveItem('edit');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 3) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->model('servers');
		
		$error = $this->validate($modid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'admin/mods/index');
		}
		
		$mod = $this->serversModel->getModById($modid);
		
		$this->data['mod'] = $mod;
		$this->load->model('games');
		$options = array(
			'start' => 0,
			'limit' => $this->limit
		);
		$games = $this->gamesModel->getGames(array(), array(), $options);
		$this->data['games'] = $games;
		$this->getChild(array('common/admheader', 'common/footer'));
		return $this->load->view('admin/mods/edit', $this->data);
	}
	
	public function ajax($modid = null) {
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
		
		$this->load->model('servers');
		
		$error = $this->validate($modid);
		if($error) {
			$this->data['status'] = "error";
			$this->data['error'] = $error;
			return json_encode($this->data);
		}
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$gameid = @$this->request->post['gameid'];
				$name = @$this->request->post['name'];
				$url = @$this->request->post['url'];
				$status = @$this->request->post['status'];
				$textx = @$this->request->post['textx'];
				$img = @$this->request->post['img'];
				$arch = @$this->request->post['arch'];
				$price = @$this->request->post['price'];
			
				$modsData = array(
					'game_id'			=> $gameid,
					'mod_name'			=> $name,
					'mod_url'			=> $url,
					'mod_status'		=> (int)$status,
					'mod_textx'			=> $textx,
					'mod_img'	     	=> $img,
					'mod_arch'		    => $arch,
					'mod_price'	    	=> $price
				);
				$this->serversModel->updateMod($modid, $modsData);				
				$this->data['status'] = "success";
				$this->data['success'] = "Вы успешно отредактировали мод!";
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = $errorPOST;
			}
		}

		return json_encode($this->data);
	}
	
	public function delete($modid = null) {
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 3) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->model('servers');
		
		$error = $this->validate($modid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'admin/mods/index');
		}
		
		$this->serversModel->deleteMod($modid);
		
		$this->session->data['success'] = "Вы успешно удалили мод!";
		$this->response->redirect($this->config->url . 'admin/mods/index');
		return null;
	}
	
	private function validate($modid) {
		$result = null;
		
		if(!$this->serversModel->getTotalMods(array('mod_id' => (int)$modid))) {
			$result = "Запрашиваемый мод не существует!";
		}
		return $result;
	}
	
	private function validatePOST() {
		$this->load->library('validate');
		
		$validateLib = new validateLibrary();
		
		$result = null;
		
		$gameid = @$this->request->post['gameid'];
		$url = @$this->request->post['url'];
		$arch = @$this->request->post['arch'];
		$name = @$this->request->post['name'];
		$status = @$this->request->post['status'];
		$textx = @$this->request->post['textx'];
		$img = @$this->request->post['img'];
		$price = @$this->request->post['price'];
		
		if(mb_strlen($name) < 2 || mb_strlen($name) > 32) {
			$result = "Название мода должно содержать от 2 до 32 символов!";
		}
		elseif(0 > $price || $price > 5000) {
			$result = "Укажите сумму от 0 до 5000 рублей!";
		}
		elseif(mb_strlen($textx) < 2 || mb_strlen($textx) > 500) {
			$result = "Описание должно содержать от 2 до 500 символов!";
		}
		elseif($status < 0 || $status > 1) {
			$result = "Укажите допустимый статус!";
		}
		return $result;
	}
}
?>
