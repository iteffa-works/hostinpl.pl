<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class editController extends Controller {
	public function index($locationid = null) {
		$this->document->setActiveSection('admin/weblocations');
		$this->document->setActiveItem('edit');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 3) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->model('webLocations');
		
		$error = $this->validate($locationid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'admin/weblocations/index');
		}
		
		$location = $this->webLocationsModel->getLocationById($locationid);
		
		$this->data['location'] = $location;
		
		$this->getChild(array('common/admheader', 'common/footer'));
		return $this->load->view('admin/weblocations/edit', $this->data);
	}
	
	public function ajax($locationid = null) {
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
		
		$error = $this->validate($locationid);
		if($error) {
			$this->data['status'] = "error";
			$this->data['error'] = $error;
			return json_encode($this->data);
		}
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$name = @$this->request->post['name'];
				$ip = @$this->request->post['ip'];
				$url = @$this->request->post['url'];
				$user = @$this->request->post['user'];
				$ns_servers = @$this->request->post['ns_servers'];
				$password = @$this->request->post['password'];
				$password2 = @$this->request->post['password2'];
				$editpassword = @$this->request->post['editpassword'];
				$status = @$this->request->post['status'];
				$location_tarifs = @$this->request->post['location_tarifs'];
				
				$locationData = array(
					'location_name'			=> $name,
					'location_ip'			=> $ip,
					'location_url'			=> $url,
					'ns_servers'			=> $ns_servers,
					'location_user'			=> $user,
					'location_status'		=> (int)$status,
					'location_tarifs'		=> $location_tarifs
				);
				
				if($editpassword) {
					$locationData['location_password'] = $password;
				}
				
				$this->webLocationsModel->updateLocation($locationid, $locationData);
				
				$this->data['status'] = "success";
				$this->data['success'] = "Вы успешно отредактировали локацию!";
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = $errorPOST;
			}
		}

		return json_encode($this->data);
	}
	
	public function delete($locationid = null) {
		$this->document->setActiveSection('admin');
		$this->document->setActiveItem('weblocations');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 3) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->model('webLocations');
		
		$error = $this->validate($locationid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'admin/weblocations/index');
		}
		
		$this->webLocationsModel->deleteLocation($locationid);
		
		$this->session->data['success'] = "Вы успешно удалили локацию!";
		$this->response->redirect($this->config->url . 'admin/weblocations/index');
		return null;
	}
	
	private function validate($locationid) {
		$result = null;
		
		if(!$this->webLocationsModel->getTotalLocations(array('location_id' => (int)$locationid))) {
			$result = "Запрашиваемая локация не существует!";
		}
		return $result;
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
		$editpassword = @$this->request->post['editpassword'];
		
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
		elseif($editpassword) {
			if(!$validateLib->password($password)) {
				$result = "Пароль должен содержать от 6 до 32 латинских букв, цифр и знаков <i>,.!?_-</i>!";
			}
			elseif($password != $password2) {
				$result = "Введенные вами пароли не совпадают!";
			}
		}
		elseif($status < 0 || $status > 1) {
			$result = "Укажите допустимый статус!";
		}
		return $result;
	}
}
?>
