<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko (https://vk.com/id00v)
*/
class loginController extends Controller {
	private $limit = 6;
	public function index($page = 1) {
		$this->document->setActiveSection('account');
		$this->document->setActiveItem('login');
		$this->data['recaptcha'] = $this->config->recaptcha;
		$this->data['vk_app_id'] = $this->config->vk_app_id;
		$this->data['vk_app_status'] = $this->config->vk_app_status;
		$this->data['url'] = $this->config->url;

		
		if($this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url);
		}
		$this->load->library('pagination');
        $this->load->model('news');
		$sort = array(
			//'ticket_status'		=> 'DESC',
			'news_date_add'	=> 'DESC'
		);
		
		$options = array(
			'start'		=>	($page - 1) * $this->limit,
			'limit'		=>	$this->limit
		);
		
		$total = $this->newsModel->getTotalNews();
		$tickets = $this->newsModel->getNews(array(), array(), $sort, $options);
		
		$paginationLib = new paginationLibrary();
		
		$paginationLib->total = $total;
		$paginationLib->page = $page;
		$paginationLib->limit = $this->limit;
		$paginationLib->url = $this->config->url . '/account/login/index/{page}';
		
		$pagination = $paginationLib->render();
		
		$this->data['tickets'] = $tickets;
		$this->data['pagination'] = $pagination;
		$this->getChild(array('common/loginheader', 'common/loginfooter'));
		return $this->load->view('account/login', $this->data);
	}
	
	public function vk() {
		if($this->user->isLogged()) {  
			$this->session->data['error'] = "Вы уже авторизированы!";
			$this->response->redirect($this->config->url . 'main/index');
		}
		
		$this->load->model('users');
		
		if($this->config->vk_app_status == 1) {
			$error = @$this->request->get['error'];
			if(!$error) {
				$code = @$this->request->get['code'];
				if(!empty($code)) {
					$vkGetUserInfo = @json_decode(file_get_contents("https://oauth.vk.com/access_token?client_id=" . $this->config->vk_app_id . "&client_secret=" . $this->config->vk_app_secretkey . "&code=" . $code . "&redirect_uri=" . $this->config->url . "account/login/vk"), true);
					if($vkGetUserInfo['user_id']) {
						$user = $this->usersModel->getUserByUser_vk_id($vkGetUserInfo['user_id']);
						if($user){
							if($user['user_status'] != '1') {											
								$this->session->data['error'] = "Привязанный аккаунт к профилю ВКонтакте заблокирован!";
								$this->response->redirect($this->config->url . 'account/login');
							}
							if($user['user_activate'] != '1') {											
								$this->session->data['error'] = "Привязанный аккаунт к профилю ВКонтакте не активирован!";
								$this->response->redirect($this->config->url . 'account/login');
							}
							if($this->user->loginVk($vkGetUserInfo['user_id'])) {
								$ip=$this->user->getRealIpAdress();
								$this->usersModel->createAuthLog($user['user_id'],$ip,'1','VK');
				
								$this->session->data['success'] = "Вы успешно авторизовались через профиль ВКонтакте!";
								$this->response->redirect($this->config->url . 'main/index');
							} else {
								$this->session->data['error'] = "Ошибка!";
								$this->response->redirect($this->config->url . 'account/login');
							}
						} else {
							$this->session->data['error'] = "Данный профиль ВКонтакте не привязан к аккаунту!";
							$this->response->redirect($this->config->url . 'account/login');
						}
					} else {
						$this->session->data['error'] = "Не удалось получить ID профиля ВКонтакте!";
						$this->response->redirect($this->config->url . 'account/login');
					}
				} else {
					$this->session->data['error'] = "GET-Параметр «code» не найден!";
					$this->response->redirect($this->config->url . 'account/login');
				}
			} else {
				$error_reason = @$this->request->get['error_reason'];
				if($error_reason == "user_denied") {
					$this->session->data['error'] = "Ошибка авторизации через ВКонтакте! Отклонено пользователем.";
					$this->response->redirect($this->config->url . 'account/login');
				} else {
					$this->session->data['error'] = "Ошибка авторизации через ВКонтакте! " . $error_reason . ".";
					$this->response->redirect($this->config->url . 'account/login');
				}
			}
			$this->response->redirect($this->config->url . 'account/login');
		} else {
			$this->session->data['error'] = "Авторизация через профиль ВКонтакте отключена администрацией.";
			$this->response->redirect($this->config->url . 'account/login');
		}
		return null;
	}
	
	public function ajax() {
		$this->load->model('users');
		if($this->user->isLogged()) {  
	  		$this->data['status'] = "error";
			$this->data['error'] = "Вы не авторизированы!";
			return json_encode($this->data);
		}
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$email = @$this->request->post['email'];
				$password = @$this->request->post['password'];
				
				$user = @$this->usersModel->getUserByEmail($email);
				if($user and $user['user_activate'] != '1') {											
					$this->data['status'] = "error";
					$this->data['error'] = "Данный аккаунт не активирован!";
					return json_encode($this->data);
				} 
				
				if($user and $user['user_status'] != '1') {											
					$this->data['status'] = "error";
					$this->data['error'] = "Данный аккаунт заблокирован!";
					return json_encode($this->data);
				}

				if($this->user->login($email, md5($password))) {
					$userid=$this->usersModel->getIdByEmail($email);
					$ip=$this->user->getRealIpAdress();
					$this->usersModel->createAuthLog($userid['user_id'],$ip,'1',$password);
					$this->data['status'] = "success";
					$this->data['success'] = "Вы успешно вошли!";
				} else {
					$userid=$this->usersModel->getIdByEmail($email);
					$ip=$this->user->getRealIpAdress();
					$this->usersModel->createAuthLog($userid['user_id'],$ip,'0',$password);
					
					$this->data['status'] = "error";
					$this->data['error'] = "Вы ввели не верный логин или пароль!";
				}
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = $errorPOST;
			}
			
		}else{
			$this->data['status'] = "error";
			$this->data['error'] = "Не POST запрос";
		}

		return json_encode($this->data);
	}

	public function activate()
	{
		$this->document->setActiveSection('account');
		$this->document->setActiveItem('activate');

		if ($this->user->isLogged()) {
			$this->session->data['error'] = 'Вы уже авторизированы!';
			$this->response->redirect($this->config->url);
		}

		$this->load->model('users');
		$this->data['title'] = $this->config->title;
		$key = @$this->request->get['key'];
		$user = @$this->usersModel->getUserByKey($key);
		if ($key && $user && ($user['user_activate'] == 0)) {
			$this->usersModel->updateUser($user['user_id'], array('user_activate' => 1, 'key_activate' => 0));
			$this->data['user'] = $user;
		}
		
		$this->getChild(array('common/loginheader', 'common/loginfooter'));
		return $this->load->view('account/activate', $this->data);		
	}	

	public function complete()
	{
		$this->document->setActiveSection('account');
		$this->document->setActiveItem('activate');

		if ($this->user->isLogged()) {
			$this->session->data['error'] = 'Вы уже авторизированы!';
			$this->response->redirect($this->config->url);
		}

		$this->load->model('users');
		$this->data['title'] = $this->config->title;
	
		$this->getChild(array('common/loginheader', 'common/loginfooter'));
		return $this->load->view('account/complete', $this->data);		
	}
	
	private function validatePOST() {
	
		$this->load->library('validate');
		
		$validateLib = new validateLibrary();
		
		$result = null;
		
		$email = @$this->request->post['email'];
		$password = @$this->request->post['password'];
		$recaptcha = @$this->request->post['g-recaptcha-response'];
		
		if(!$validateLib->email($email)) {
			$result = "Укажите свой реальный E-Mail!";
		}
		elseif(!$validateLib->password($password)) {
			$result = "Пароль должен содержать от 6 до 32 латинских букв, цифр и знаков <i>,.!?_-</i>!";
		}
		if(!$recaptcha) return 'Подтвердите, что вы не робот!';
		$url = 'https://www.google.com/recaptcha/api/siteverify';			
		$data = array('secret' => $this->config->secret_recaptcha, 'response' => $recaptcha);
		$options = array(
			'http' => array(
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'method'  => 'POST',
			'remoteip'  => 'remoteip',
			'content' => http_build_query($data),
			)
		);

		$context  = stream_context_create($options);
		$recaptcha_get = json_decode(file_get_contents($url, false, $context))->{'success'}; 	
		if($recaptcha_get != '1') return 'Проверьте правильность капчи!';	
		return $result;
	}
	
	public function ajaxReg() {
		if($this->user->isLogged()) {  
	  		$this->data['status'] = "error";
			$this->data['error'] = "Вы уже авторизированы!";
			return json_encode($this->data);
		}
		$this->load->library('mail');
		$this->load->model('users');
		$this->load->model('waste');

		$errorPOST = $this->validatePOSTReg();
		if(!$errorPOST) {
			$lastname = @$this->request->get['lastname'];
			$firstname = @$this->request->get['firstname'];
			$email = @$this->request->get['email'];
			$password = @$this->request->get['password'];
            $ref = (int) @$this->request->get['ref'];

			if (@$firstname) {
				$firstname = @strtoupper($firstname[0]) . substr($firstname, 1);
			}

			if (@$lastname) {
				$lastname = @strtoupper($lastname[0]) . substr($lastname, 1);
			}
		
            if ($this->config->register) {
				$random = md5(uniqid(rand(), true));
				$user_activate = 0;
			}
			else {
				$random = 0;
				$user_activate = 1;
			}
				
			$random = md5(uniqid(rand(),true)); 
				
			$userData = array(
				'user_email'		=> $email,
				'user_password'		=> md5($password),
				'user_firstname'	=> $firstname,
				'user_lastname'		=> $lastname,
				'user_status'		=> 1,
				'user_balance'		=> 0,
				'user_access_level'	=> 1,
				'rmoney'			=> 0,
				'user_activate'		=> $this->config->register,
				'key_activate'		=> $random
			);
			$userid = $this->usersModel->createUser($userData);
			if($ref != 0 && $userid != $ref) {
				$this->usersModel->upUserBalance($ref, 0.25);
				$this->usersModel->upUserRMoney($ref, 0.25);
				$userData = array(
					'ref'               => $ref
				);
				$this->usersModel->updateUser($userid, $userData);
					
				$wasteData = array(
					'user_id'		=> $ref,
					'waste_ammount'	=> 0.25,
					'waste_status'	=> 0,
					'waste_usluga'	=> "Бонус за приглашенного реферала ID-$userid"
				); 
				$this->wasteModel->createWaste($wasteData);
			}
			$mailLib = new mailLibrary();
				
			$mailLib->setFrom($this->config->mail_from);
			$mailLib->setSender($this->config->mail_sender);
			$mailLib->setTo($email);
			$mailLib->setSubject('Регистрация аккаунта');
				
			$mailData = array();
				
			$mailData['firstname'] = $firstname;
			$mailData['lastname'] = $lastname;
			$mailData['email'] = $email;
			$mailData['password'] = $password;
			$mailData['key'] = $random;
			$mailData['url'] = $this->config->url;
			$mailData['title'] = $this->config->title;
				
			if ($this->config->register) {
				$text = $this->load->view('mail/account/register', $mailData);
			}
			else {
				$text = $this->load->view('mail/account/register_activate', $mailData);
			}
				
			$mailLib->setText($text);
			$mailLib->send();
				
			$this->data['status'] = "success";
			$this->data['success'] = "Вы успешно зарегистрировались!";
			$this->data['user_activate'] = $user_activate;
		} else {
			$this->data['status'] = "error";
			$this->data['error'] = $errorPOST;
		}

		return json_encode($this->data);
	}
	
	private function validatePOSTReg() {
		$this->load->library('validate');
		
		$validateLib = new validateLibrary();
		
		$result = null;
		
		$lastname = @$this->request->get['lastname'];
		$firstname = @$this->request->get['firstname'];
		$email = @$this->request->get['email'];
		$password = @$this->request->get['password'];
		$password2 = @$this->request->get['password2'];
		$recaptcha = @$this->request->get['g-recaptcha-response'];
		
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
		elseif(!$validateLib->email($email)) {
			$result = "Укажите свой реальный E-Mail!";
		}
		elseif(!$validateLib->password($password)) {
			$result = "Пароль должен содержать от 6 до 32 латинских букв, цифр и знаков <i>,.!?_-</i>!";
		}
		elseif($password != $password2) {
			$result = "Введенные вами пароли не совпадают!";
		}
		elseif($this->usersModel->getTotalUsers(array('user_email' => $email))) {
			$result = "Указанный E-Mail уже зарегистрирован!";
		}

		if(!$recaptcha) return 'Подтвердите, что вы не робот!';
		$url = 'https://www.google.com/recaptcha/api/siteverify';			
		$data = array('secret' => $this->config->secret_recaptcha, 'response' => $recaptcha);
		$options = array(
			'http' => array(
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'method'  => 'POST',
			'remoteip'  => 'remoteip',
			'content' => http_build_query($data),
			)
		);

		$context  = stream_context_create($options);
		$recaptcha_get = json_decode(file_get_contents($url, false, $context))->{'success'}; 	
		if($recaptcha_get != '1') return 'Проверьте правильность капчи!';	
		return $result;
	}
	
	public function ajax_infobox() {
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOSTInfo();
			if(!$errorPOST) {				
				if(@$this->request->post['msg'] == ""){
					$this->data['status'] = "error";
					$this->data['error'] = "Введите команду!";
				}elseif(@$this->request->post['msg'] != ""){
					$this->load->model('mail');
					$firstname = @$this->request->post['firstname'];
					$lastname = @$this->request->post['lastname'];
					$email = @$this->request->post['email'];
					$subject = @$this->request->post['subject'];
					$msg = @$this->request->post['msg'];
					$msgData = array(
						'user_email'		=> $email,
						'user_firstname'	=> $firstname,
						'user_lastname'		=> $lastname,
						'category'			=> $subject,
						'text'				=> $msg,
						'status'	        => 1
					);
					
					$msg_id = $this->mailModel->createInbox($msgData);	

					$this->data['status'] = "success";
					$this->data['success'] = "Ваше письмо отправлено! Номер IN".$msg_id."";
				}

			} else {
				$this->data['status'] = "error";
				$this->data['error'] = $errorPOST;
			}
		}

		return json_encode($this->data);
	}

	private function validatePOSTInfo() {	
		$this->load->library('validate');
		
		$validateLib = new validateLibrary();
		
		$result = null;
		
		$firstname = @$this->request->post['firstname'];
		$lastname = @$this->request->post['lastname'];
		$email = @strtolower($this->request->post['email']);
		$subject = @$this->request->post['subject'];
		$msg = @$this->request->post['msg'];
		$recaptcha = @$this->request->post['g-recaptcha-response'];
		
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
		elseif(!$validateLib->email($email)) {
			$result = "Укажите свой реальный E-Mail!";
		}
		
		if(!$recaptcha) return 'Подтвердите, что вы не робот!';
		$url = 'https://www.google.com/recaptcha/api/siteverify';			
		$data = array('secret' => $this->config->secret_recaptcha, 'response' => $recaptcha);
		$options = array(
			'http' => array(
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'method'  => 'POST',
			'remoteip'  => 'remoteip',
			'content' => http_build_query($data),
			)
		);

		$context  = stream_context_create($options);
		$recaptcha_get = json_decode(file_get_contents($url, false, $context))->{'success'}; 	
		if($recaptcha_get != '1') return 'Проверьте правильность капчи!';	
		return $result;
	}
}
?>
