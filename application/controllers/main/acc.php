<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class accController extends Controller {
	public function index() {
		$this->data['vk_app_id'] = $this->config->vk_app_id;
		$this->data['vk_app_status'] = $this->config->vk_app_status;
		$this->data['url'] = $this->config->url;

		
		if(!$this->user->isLogged()) {
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 0) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->data['logged'] = true;
		$this->data['user_email'] = $this->user->getEmail();
		$this->data['user_id'] = $this->user->getId();
		$this->data['user_firstname'] = $this->user->getFirstname();
		$this->data['user_lastname'] = $this->user->getLastname();
		$this->data['user_balance'] = $this->user->getBalance();
		$this->data['user_img'] = $this->user->getUser_img();
		$this->data['user_balance'] = $this->user->getBalance();
		
		$this->load->model('waste');
		$this->load->model('tickets');
		$this->load->model('users');
		$this->load->model('servers');

		$userid = $this->user->getId();
		
		$waste = $this->wasteModel->getWaste(array('user_id' => (int)$userid), array(), array('waste_date_add'	=> 'DESC'), array('start' => 0,'limit' => 10));
		$this->data['waste'] = $waste;
		
		$this->data['user'] = array('firstname' => $this->user->getFirstname(), 'lastname' => $this->user->getLastname(), 'user_email' => $this->user->getEmail());
		$users = $this->usersModel->getUserById($userid, array(), array(), array());
		$this->data['users'] = $users;

		$userg = $this->usersModel->getUsers(array('servers'), array(), array());
		$this->data['userg'] = $userg;
		
		$visitors = $this->usersModel->getAuthLog($userid);
		$this->data['visitors'] = $visitors;
		
		$auths = $this->usersModel->getAuths(array('user_id' => $userid), array(), array('auth_id' => 'DESC'));
		$this->data['auths'] = $auths;
		
		$servers = $this->serversModel->getServers(array('user_id' => (int)$userid), array('games', 'locations'), array(), array());
		$this->data['servers'] = $servers;
		
		$tickets = $this->ticketsModel->getTickets(array('user_id' => (int)$userid), array(), array(), array());
		$this->data['tickets'] = $tickets;
		
		$row['date'] = strtotime($users['user_date_reg']); 
        $now = time(); 
        $seconds = $now - $row['date']; 
        $days = floor($seconds / (24*60*60)); 
        $this->data['user_date_reg'] = $days; 
        $res = $this->pluralForm($days, 'день', 'дней', 'дня'); 
        $this->data['user_date'] = $res;		
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('main/acc', $this->data);
	}
	
	public function vk() {
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url);
		}
		if($this->user->getAccessLevel() < 1) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$userid = $this->user->getId();
		
		$this->load->model('users');
		
		if($this->config->vk_app_status == 1) {
			$error = @$this->request->get['error'];
			if(!$error) {
				$code = @$this->request->get['code'];
				if(!empty($code)) {
					$vkGetUserInfo = @json_decode(file_get_contents("https://oauth.vk.com/access_token?client_id=" . $this->config->vk_app_id . "&client_secret=" . $this->config->vk_app_secretkey . "&code=" . $code . "&redirect_uri=" . $this->config->url . "main/acc/vk"), true);
					if($vkGetUserInfo['user_id']) {
						if($this->usersModel->getUserByUser_vk_id($vkGetUserInfo['user_id']) == null) {
							$this->usersModel->updateUser($userid, array('user_vk_id' => $vkGetUserInfo['user_id']));
							$this->session->data['success'] = "Профиль ВКонтакте успешно был привязан к Вашему аккаунту!";
							$this->response->redirect($this->config->url . 'main/acc');
						} else {
							$this->session->data['error'] = "Данный профиль уже привазан к другому аккаунту!";
							$this->response->redirect($this->config->url . 'main/acc');
						}
					} else {
						$this->session->data['error'] = "Не удалось получить ID профиля ВКонтакте. Неизвестная ошибка.";
						$this->response->redirect($this->config->url . 'main/acc');
					}
				} else {
					$this->session->data['error'] = "GET-Параметр «code» не найден.";
					$this->response->redirect($this->config->url . 'main/acc');
				}
			} else {
				$error_reason = @$this->request->get['error_reason'];
				if($error_reason == "user_denied") {
					$this->session->data['error'] = "Ошибка авторизации через ВКонтакте. Отклонено пользователем.";
					$this->response->redirect($this->config->url . 'main/acc');
				} else {
					$this->session->data['error'] = "Ошибка авторизации через ВКонтакте. " . $error_reason . ".";
					$this->response->redirect($this->config->url . 'main/acc');
				}
			}
		} else {
			$this->session->data['error'] = "Привязка профиля ВКонтакте отключена администрацией.";
			$this->response->redirect($this->config->url . 'main/acc');
		}
	}
	
	public function action($action = null) {
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
		
		$userid = $this->user->getId();

		$this->load->model('users');
		$this->load->model('waste');
		
		switch($action) {
			case 'unsetVk': {
				if ($this->user->getUser_vk_id()) {
					$this->usersModel->updateUser($userid, array('user_vk_id' => 0));						
					$this->data['status'] = "success";
					$this->data['success'] = "Вы успешно отвязали VK!";
				} else {
					$this->data['status'] = "error";
					$this->data['error'] = "На данный момент у Вас не привязан VK!";
				}
				break;
			}
			case 'closeSession': {
				if($this->request->server['REQUEST_METHOD'] == 'POST') {
					$auth_id = $this->request->post['auth_id'];
					$auths = $this->usersModel->getAuths(array('auth_id' => $auth_id, 'user_id' => $userid));
					if(empty($auths)) {
						$this->data['status'] = "error";
						$this->data['error'] = "Сеанс не найден!";
					} else {
						$auth = $auths[0];
							
						if($auth['auth_key'] != @$_COOKIE['uid']) {
							$this->usersModel->deleteAuth($auth['auth_id']);
								
							$this->data['status'] = "success";
							$this->data['success'] = "Сеанс успешно завершён!";
						} else {
							$this->data['status'] = "error";
							$this->data['error'] = "Вы не можете завершить текущий сеанс!";
						}
					}
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
	
	public function ajax() {
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
		
		$this->load->model('users');
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$lastname = @$this->request->post['lastname'];
				$firstname = @$this->request->post['firstname'];
				$editpassword = @$this->request->post['editpassword'];
				$password = @$this->request->post['password'];
				
				$userid = $this->user->getId();
				
				if (@$firstname) {
					$firstname = @strtoupper($firstname[0]) . substr($firstname, 1);
				}

				if (@$lastname) {
					$lastname = @strtoupper($lastname[0]) . substr($lastname, 1);
				}
				
				$userData = array(
					'user_firstname'	=> $firstname,
					'user_lastname'		=> $lastname
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
	
	public function change_email() {
		if (!$this->user->isLogged()) {
			$this->data['status'] = 'error';
			$this->data['error'] = 'Вы не авторизированы!';
			return json_encode($this->data);
		}

		if ($this->user->getAccessLevel() < 1) {
			$this->data['status'] = 'error';
			$this->data['error'] = 'У вас нет доступа к данному разделу!';
			return json_encode($this->data);
		}

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST_email = $this->validatePOST_email();

			if (!$errorPOST_email) {
				$email = @$this->request->post['email'];
				$userid = $this->user->getId();
				$this->load->model('users');

				if ($this->usersModel->getTotalUsers(array('user_email' => $email))) {
					$this->data['status'] = 'error';
					$this->data['error'] = 'Указанный E-Mail уже используется!';
					return json_encode($this->data);
				}

				$random = md5(uniqid(rand(), true));
				$this->usersModel->updateUser($userid, array('user_new_email' => $email . '|' . $random));
				$user = $this->usersModel->getUserById($userid);
				
				$this->load->library('mail');

				$mailLib = new mailLibrary();
				$mailLib->setFrom($this->config->mail_from);
				$mailLib->setSender($this->config->mail_sender);
				$mailLib->setTo($user['user_email']);
				$mailLib->setSubject("Смена E-Mail");		
				$mailData = array();
				$mailData['firstname'] = $user['user_firstname'];
				$mailData['lastname'] = $user['user_lastname'];
				$mailData['email'] = $user['user_email'];
				$mailData['new_email'] = $email;
				$mailData['userid'] = $userid;
				$mailData['key'] = $random;
				$mailData['url'] = $this->config->url;
				$mailData['title'] = $this->config->title;
				$text = $this->load->view('mail/account/change_email', $mailData);
									
				$mailLib->setText($text);
				$mailLib->send();

				$this->data['status'] = 'success';
				$this->data['success'] = 'На текущий E-Mail \'' . $user['user_email'] . '\' Отправлена инструкция с дальнейшими действиями!';
			}
			else {
				$this->data['status'] = 'error';
				$this->data['error'] = $errorPOST_email;
			}
		}

		return json_encode($this->data);
	}

	public function change_email_verifity($userid = null, $key = null)
	{
		if (!$userid || !$key) {
			$this->session->data['error'] = 'Ошибка! Повторите действие.';
			$this->response->redirect('/main/index');
			return null;
		}

		$this->load->model('users');
		$user = @$this->usersModel->getUserById($userid);
		if ($user && (@explode('|', $user['user_new_email'])[1] == $key)) {
			if ($this->usersModel->getTotalUsers(array('user_email' => @explode('|', $user['user_new_email'])[0]))) {
				exit('Указанный E-Mail уже используется!');
			}

			$this->usersModel->updateUser($userid, array('user_new_email' => null, 'user_email' => @explode('|', $user['user_new_email'])[0]));
			$this->session->data['success'] = 'E-Mail Сменен!';
		} else {
			$this->session->data['error'] = 'Ошибка! Повторите действие.';
		}

		$this->response->redirect('/account/login');
	}
	
	public function img() {
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
		
		$this->load->model('users');
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$uploaddir = 'tmp/avatar/';
			$apend=date('YmdHis').rand(100,1000).'.jpg'; 
			$uploadfile = "$uploaddir$apend"; 

			if(move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) { 
				if($_FILES['userfile']['type'] == 'image/gif' || $_FILES['userfile']['type'] == 'image/jpeg' || $_FILES['userfile']['type'] == 'image/png') {  
					if($_FILES['userfile']['size'] != 0 and $_FILES['userfile']['size'] <= 512000) {
						$size = getimagesize($uploadfile); 
						if ($size[0] <= 512 && $size[1] <= 512)  { 
							$userid = $this->user->getId();	
							$this->usersModel->updateUser($userid, array('user_img' => $uploadfile));
							$this->data['status'] = "success";
							$this->data['success'] = "Аватар успешно был загружен!";			 
						} else {
							$this->data['error'] = 'Загружаемое изображение превышает допустимые нормы!';
							$this->data['status'] = "error";
							unlink($uploadfile); 
						} 
					} else { 
						$this->data['error'] = 'Размер изображения не должно превышать 512Кб';
						$this->data['status'] = "error";
						unlink($uploadfile); 
					} 
				} else {     
					$this->data['status'] = "error";
					$this->data['error'] = "Можно загружать только изображения в форматах jpg, jpeg и png";
					unlink($uploadfile); 
				}
			} else {
				$this->data['error'] = 'Изображение не загружено!';
				$this->data['status'] = "error";
			} 	
		}
		return json_encode($this->data);
    }
	
	private function validatePOST() {
	
		$this->load->library('validate');
		
		$validateLib = new validateLibrary();
		
		$result = null;
		
		$lastname = @$this->request->post['lastname'];
		$firstname = @$this->request->post['firstname'];
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
	
	private function validatePOST_email()
	{
		$this->load->library('validate');
		$validateLib = new validateLibrary();
		$result = NULL;
		$email = @$this->request->post['email'];

		if (!$validateLib->email($email)) {
			$result = 'Укажите свой реальный E-Mail!';
		}

		return $result;
	}
	
	private function validatePOST_VK() {
		$this->load->library('validate');
		
		$validateLib = new validateLibrary();
		
		$result = null;
		
		$id = @strtolower($this->request->post['id']);

		if($this->usersModel->getTotalUsers(array('user_vk_id' => $id))) {
			$result = "Указанный ID-".$id." уже привязан!";
		}

		return $result;
	}

	public function pluralForm($n, $form1, $form2,$form5) { 
        $n = abs($n) % 100; 
        $n1 = $n % 10; 
        if ($n1 >= 5 && $n1 <= 4) return $form2; 
        if ($n >= 10 && $n <= 20) return $form2; 
        if ($n1 >= 2 && $n1 <= 4) return $form5; 
        if ($n1 == 1) return $form1; 
        if ($n == 0) return $form2; 
    }
}
?>