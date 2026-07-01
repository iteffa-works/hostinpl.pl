<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class editController extends Controller {
	public function index($userid = null) {
		$this->data['url'] = $this->config->url;
		$this->document->setActiveSection('admin/users');
		$this->document->setActiveItem('edit');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 2) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->model('users');
		$this->load->model('servers');
		
		$error = $this->validate($userid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'admin/users/index');
		}
		
		$user = $this->usersModel->getUserById($userid);
		
		$this->data['user'] = $user;
		$servers = $this->serversModel->getServers(array('user_id' => (int)$userid), array('games', 'locations'), array(), array());
	    $visitors = $this->usersModel->getAuthLog($userid);
		$this->data['servers'] = $servers;
		$this->data['visitors'] = $visitors;
		$this->data['user_img'] = $this->user->getUser_img();
		$this->data['user_access_level'] = $this->user->getAccessLevel();
		$this->getChild(array('common/admheader', 'common/footer'));
		return $this->load->view('admin/users/edit', $this->data);
	}
	
	public function ajax($userid = null) {
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
		
		$this->load->model('users');
		
		$error = $this->validate($userid);
		if($error) {
			$this->data['status'] = "error";
			$this->data['error'] = $error;
			return json_encode($this->data);
		}
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$lastname = @$this->request->post['lastname'];
				$firstname = @$this->request->post['firstname'];
				$email = @$this->request->post['email'];
				$status = @$this->request->post['status'];
				$balance = @$this->request->post['balance'];
				$vk_id = @$this->request->post['vk_id'];
				$test_server = @$this->request->post['test_server'];
				$user_activate = @$this->request->post['user_activate'];
				$accesslevel = @$this->request->post['accesslevel'];
				$editpassword = @$this->request->post['editpassword'];
				$password = @$this->request->post['password'];
				
				if (@$firstname) {
					$firstname = @strtoupper($firstname[0]) . substr($firstname, 1);
				}

				if (@$lastname) {
					$lastname = @strtoupper($lastname[0]) . substr($lastname, 1);
				}
				
				$userData = array(
					'user_firstname'	=> $firstname,
					'user_lastname'		=> $lastname,
					'user_email'		=> $email,
					'user_status'		=> (int)$status,
					'user_balance'		=> (float)$balance,
					'user_vk_id'		=> (float)$vk_id,
					'test_server'		=> (float)$test_server,
					'user_access_level'	=> (float)$accesslevel,
					'user_activate'		=> (float)$user_activate,
				);
				
				if($editpassword) {
					$userData['user_password'] = md5($password);
				}
				
				$this->usersModel->updateUser($userid, $userData);
				
				$this->data['status'] = "success";
				$this->data['success'] = "Изменения сохранены!";
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = $errorPOST;
			}
		}

		return json_encode($this->data);
	}

	public function delete($user_id = null) {
		$this->document->setActiveSection('admin');
		$this->document->setActiveItem('users');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 3) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->model('users');
		
		$this->usersModel->deleteUser($user_id);
		$this->data['status'] = "success";
		$this->data['success'] = "Вы успешно удалили пользователя!";
		return json_encode($this->data);
	}

	private function validate($userid) {
		$result = null;
		
		if(!$this->usersModel->getTotalUsers(array('user_id' => (int)$userid))) {
			$result = "Запрашиваемый пользователь не существует!";
		}
		return $result;
	}
	
	private function validatePOST() {
		$this->load->library('validate');
		
		$validateLib = new validateLibrary();
		
		$result = null;
		
		$lastname = @$this->request->post['lastname'];
		$firstname = @$this->request->post['firstname'];
		$status = @$this->request->post['status'];
		$balance = @$this->request->post['balance'];
		$accesslevel = @$this->request->post['accesslevel'];
		$editpassword = @$this->request->post['editpassword'];
		$password = @$this->request->post['password'];
		$password2 = @$this->request->post['password2'];
		
		if (@$firstname) {
			$firstname = @strtoupper($firstname[0]) . substr($firstname, 1);
		}

		if (@$lastname) {
			$lastname = @strtoupper($lastname[0]) . substr($lastname, 1);
		}
	
		if(!$validateLib->check_for_number($lastname)) {
			$result = "Укажите свою реальную фамилию!";
		}
		elseif(!$validateLib->check_for_number($firstname)) {
			$result = "Укажите свое реальное имя!";
		}
		elseif(!$validateLib->userstatus($status)) {
			$result = "Укажите допустимый статус!";
		}
		elseif(!$validateLib->money($balance)) {
			$result = "Укажите допустимый баланс!";
		}
		elseif(!$validateLib->accesslevel($accesslevel)) {
			$result = "Укажите допустимый уровень доступа!";
		}
		elseif($editpassword) {
			if(!$validateLib->password($password)) {
				$result = "Пароль должен содержать от 6 до 32 латинских букв, цифр и знаков <i>,.!?_-</i>!";
		}
		elseif($password != $password2) {
				$result = "Введенные вами пароли не совпадают!";
			}
		}
		return $result;
	}
}
?>
