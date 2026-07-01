<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class createController extends Controller {
	public function index() {
		$this->document->setActiveSection('admin/weblocations');
		$this->document->setActiveItem('create');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 3) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->getChild(array('common/admheader', 'common/footer'));
		return $this->load->view('admin/weblocations/create', $this->data);
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
		
		$this->load->model('webLocations');
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$name = @$this->request->post['name'];
				$ip = @$this->request->post['ip'];
				$url = @$this->request->post['url'];
				$ns_servers = @$this->request->post['ns_servers'];
				$user = @$this->request->post['user'];
				$password = @$this->request->post['password'];
				$password2 = @$this->request->post['password2'];
				$status = @$this->request->post['status'];
				
				$locationData = array(
					'location_name'			=> $name,
					'location_ip'			=> $ip,
					'location_url'			=> $url,
					'location_user'			=> $user,
					'location_password'		=> $password,
					'ns_servers'			=> $ns_servers,
					'location_status'		=> (int)$status
				);
				
				$this->webLocationsModel->createLocation($locationData);
				
				$this->data['status'] = "success";
				$this->data['success'] = "Вы успешно создали локацию!";
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
		$ip = @$this->request->post['ip'];
		$url = @$this->request->post['url'];
		$user = @$this->request->post['user'];
		$ns_servers = @$this->request->post['ns_servers'];
		$password = @$this->request->post['password'];
		$password2 = @$this->request->post['password2'];
		$status = @$this->request->post['status'];
		
		if(mb_strlen($name) < 2 || mb_strlen($name) > 32) {
			$result = "Название локации должно содержать от 2 до 32 символов!";
		}
		elseif(mb_strlen($ns_servers) < 2 || mb_strlen($ns_servers) > 78) {
			$result = "NS Сервера локации должны содержать от 2 до 78 символов!";
		}
		elseif(!$validateLib->ip($ip)) {
			$result = "Укажите допустимый IP!";
		}
		elseif(mb_strlen($url) < 2 || mb_strlen($url) > 32) {
			$result = "Домен должен содержать от 2 до 32 символов!";
		}
		elseif(mb_strlen($user) < 2 || mb_strlen($user) > 32) {
			$result = "Имя пользователя должно содержать от 2 до 32 символов!";
		}
		elseif(!$validateLib->password($password)) {
			$result = "Пароль должен содержать от 6 до 32 латинских букв, цифр и знаков <i>,.!?_-</i>!";
		}
		elseif($password != $password2) {
			$result = "Введенные вами пароли не совпадают!";
		}
		elseif($status < 0 || $status > 1) {
			$result = "Укажите допустимый статус!";
		}
		return $result;
	}
}
?>
