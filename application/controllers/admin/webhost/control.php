<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class controlController extends Controller {
	public function index($webid = null) {
		$this->document->setActiveSection('admin/webhost');
		$this->document->setActiveItem('control');

		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 2) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->model('webhost');		
		
		$error = $this->validate($webid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'admin/webhost/index');
		}
		
		$webhost = $this->webhostModel->getWebhostById($webid, array('users','web_tarifs','web_locations'));
		$this->data['webhost'] = $webhost;
		
		$this->getChild(array('common/admheader', 'common/footer'));
		return $this->load->view('admin/webhost/control', $this->data);
	}
	
	public function ajax($webid = null) {
		if(!$this->user->isLogged()) {  
	  		$this->data['status'] = "error";
			$this->data['error'] = "Вы не авторизированы!";
			return json_encode($this->data);
		}
		if($this->user->getAccessLevel() < 2) {
	  		$this->data['status'] = "error";
			$this->data['error'] = "У вас нет доступа к данному разделу!";
			return json_encode($this->data);
		}	
		$this->load->model('webhost');
	
		$error = $this->validate($webid);
		if($error) {
			$this->data['status'] = "error";
			$this->data['error'] = $error;
			return json_encode($this->data);
		}
		$webhost = $this->webhostModel->getWebhostById($webid, array('users', 'web_tarifs'));
		if($webhost['web_status'] == 0) {
			$this->data['status'] = "error";
			$this->data['error'] = "Веб-хостинг заблокирован!";
			return json_encode($this->data);
		}
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$editpassword = @$this->request->post['editpassword'];
				$password = @$this->request->post['password'];
				if($editpassword) {
					$result = $this->webhostModel->updatepassUser($webid, $password);
					if($result["status"] == "success") {
						$this->webhostModel->updateWebhost($webid, array('web_password' => $password));	
						$this->data['status'] = "success";
						$this->data['success'] = "Пароль доступа успешно изменен!";
					} else {
						$this->data["status"] = "error";
						$this->data["error"] = $result["description"];
					}
				} else {
					$this->data['status'] = "error";
					$this->data['error'] = "Ошибка!";
				}
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = $errorPOST;
			}
		}

		return json_encode($this->data);
	}
	
	public function action($webid = null, $action = null) {
		if(!$this->user->isLogged()) {
			$this->data['status'] = "error";
			$this->data['error'] = "Вы не авторизированы!";
			return json_encode($this->data);
		}
		if($this->user->getAccessLevel() < 2) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		$this->load->model('webhost');
		
		$error = $this->validate($webid);
		if($error) {
			$this->data['status'] = "error";
			$this->data['error'] = $error;
			return json_encode($this->data);
		}
		
		$webhost = $this->webhostModel->getWebhostById($webid, array('users', 'web_tarifs'));
			
		switch($action) {
			case 'block': {
				$result = $this->webhostModel->blockWebhost($webid);
				if($result["status"] == "success") {	
					$this->data['status'] = "success";
					$this->data['success'] = "Вы успешно заблокировали веб-хостинг!";
				} else {
					$this->data["status"] = "error";
					$this->data["error"] = $result["description"];
				}
				break;
			}
			case 'unblock': {
				$result = $this->webhostModel->unblockWebhost($webid);
				if($result["status"] == "success") {
					$this->data['status'] = "success";
					$this->data['success'] = "Вы успешно разблокировали веб-хостинг!";
				} else {
					$this->data["status"] = "error";
					$this->data["error"] = $result["description"];
				}
				break;
			}
			case 'detele_web': {
				$result = $this->webhostModel->deleteWebhost($webid);
				if($result["status"] == "success") {
					$this->data['status'] = "success";
					$this->data['success'] = "Вы успешно удалили веб-хостинг!";
				} else {
					$this->data["status"] = "error";
					$this->data["error"] = $result["description"];
				}
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

	private function validate($webid) {
		$result = null;
		
		if(!$this->webhostModel->getTotalWebhosts(array('web_id' => (int)$webid))) {
			$result = "Запрашиваемый веб-хостинг не существует!";
		}
		return $result;
	}
	
	private function validatePOST() {
		$this->load->library('validate');
		
		$validateLib = new validateLibrary();
		
		$result = null;
		
		$editpassword = @$this->request->post['editpassword'];
		$password = @$this->request->post['password'];
		$password2 = @$this->request->post['password2'];

		if($editpassword) {
			if(!$validateLib->password_web($password)) {
				$result = "Пароль должен содержать от 8 до 32 латинских букв, цифр и знаков <i>,.!?_-</i>!";
			}
			elseif($password != $password2) {
				$result = "Введенные вами пароли не совпадают!";
			}
		}
		return $result;
	}
}
?>
