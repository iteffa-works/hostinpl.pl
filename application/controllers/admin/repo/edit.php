<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class editController extends Controller {
	public function index($repoid = null) {
		$this->document->setActiveSection('admin/repo');
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
		
		$error = $this->validate($repoid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'admin/repo/index');
		}
		
		$repo = $this->serversModel->getRepoById($repoid);
		
		$this->data['repo'] = $repo;
		$this->load->model('games');
		$options = array(
			'start' => 0,
			'limit' => $this->limit
		);
		$games = $this->gamesModel->getGames(array(), array(), $options);
		$this->data['games'] = $games;
		$this->getChild(array('common/admheader', 'common/footer'));
		return $this->load->view('admin/repo/edit', $this->data);
	}
	
	public function ajax($repoid = null) {
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
		
		$error = $this->validate($repoid);
		if($error) {
			$this->data['status'] = "error";
			$this->data['error'] = $error;
			return json_encode($this->data);
		}
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$name = @$this->request->post['name'];
				$gameid = @$this->request->post['gameid'];
				$url = @$this->request->post['url'];
				$textx = @$this->request->post['textx'];
				$img = @$this->request->post['img'];
				$status = @$this->request->post['status'];
				$price = @$this->request->post['price'];

				$repoData = array(
					'game_id'			=> $gameid,
					'repo_name'			=> $name,
					'repo_url'			=> $url,
					'repo_status'		=> (int)$status,
					'repo_textx'		=> $textx,
					'repo_img'	     	=> $img,
					'repo_price'	    => $price
				);
				$this->serversModel->updateRepo($repoid, $repoData);				
				$this->data['status'] = "success";
				$this->data['success'] = "Вы успешно отредактировали файл в репозитории!";
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = $errorPOST;
			}
		}

		return json_encode($this->data);
	}
	
	public function delete($repoid = null) {
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 3) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->model('servers');
		
		$error = $this->validate($repoid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'admin/repo/index');
		}
		
		$this->serversModel->deleteRepo($repoid);
		
		$this->session->data['success'] = "Вы успешно удалили файл с репозитория!";
		$this->response->redirect($this->config->url . 'admin/repo/index');
		return null;
	}
	
	private function validate($repoid) {
		$result = null;
		
		if(!$this->serversModel->getTotalRepos(array('repo_id' => (int)$repoid))) {
			$result = "Запрашиваемый файл не существует!";
		}
		return $result;
	}
	
	private function validatePOST() {
		$this->load->library('validate');
		
		$validateLib = new validateLibrary();
		
		$result = null;
		
		$name = @$this->request->post['name'];
		$gameid = @$this->request->post['gameid'];
		$status = @$this->request->post['status'];
		$textx = @$this->request->post['textx'];
		$price = @$this->request->post['price'];
		
		if(mb_strlen($name) < 2 || mb_strlen($name) > 32) {
			$result = "Название файла должно содержать от 2 до 32 символов!";
		}
		elseif(mb_strlen($textx) < 2 || mb_strlen($textx) > 500) {
			$result = "Описание должно содержать от 2 до 500 символов!";
		}
		elseif($status < 0 || $status > 1) {
			$result = "Укажите допустимый статус!";
		}
		elseif(0 > $price || $price > 5000) {
			$result = "Укажите сумму от 0 до 5000 рублей!";
		}
		return $result;
	}
}
?>
