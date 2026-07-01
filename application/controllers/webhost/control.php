<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class controlController extends Controller {
	public function index($webid = null) {
		$this->document->setActiveSection('webhost');
		$this->document->setActiveItem('control');
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
		
		$this->load->model('webhost');		
		
		$error = $this->validate($webid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'webhost/index');
		}
		
		$webhost = $this->webhostModel->getWebhostById($webid, array('users','web_tarifs','web_locations'));
		$this->data['webhost'] = $webhost;
		
		include_once 'application/controllers/common/main.php';

		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('webhost/control', $this->data);
	}
	
	public function ajax($webid = null) {
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
		$this->load->model('webhost');
	
		$error = $this->validate($webid);
		if($error) {
			$this->data['status'] = "error";
			$this->data['error'] = $error;
			return json_encode($this->data);
		}
		$webhost = $this->webhostModel->getWebhostById($webid, array('web_tarifs'));
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
	
	public function buy_months($webid = null) {
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
		$this->load->model('webhost');
		$this->load->model('waste');
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$days = @$this->request->post['days'];
			if($days != "30" && $days != "60" && $days != "90" && $days != "180" && $days != "360" && $days != "15") {
				$this->data['status'] = "error";
				$this->data['error'] = "Вы указали недопустимый период оплаты!";
				return json_encode($this->data);
			}
			
			$userid = $this->user->getId();
			$balance = $this->user->getBalance();
			$webhost = $this->webhostModel->getWebhostById($webid, array('web_tarifs'));
			
			$price = $webhost['tarif_price'];
			switch($days) {
				case "15":
					$price = $price / 2;
					break;
				case "30":
					break;
				case "60":
					$price = $price * 2;
					break;
				case "90":
					$price = 3 * $price * 0.95;
					break;
				case "180":
					$price =  6 * $price * 0.90;
					break;
				case "360":
					$price = 12 * $price * 0.85;
					break;
			}
			
			if($balance >= $price) {
				if($webhost['web_status'] == 0) {
					$this->webhostModel->extendWebhost($webid,$days,false);
					$result = $this->webhostModel->unblockWebhost($webid);
					if($result["status"] != "success") {
						$this->data["status"] = "error";
						$this->data["error"] = $result["description"];
					}
				} else {
					$this->webhostModel->extendWebhost($webid,$days,false);
				}
				
				$wasteData = array(
					'user_id'		=> $userid,
					'waste_ammount'	=> $price,
					'waste_status'	=> 1,
					'waste_usluga'	=> "Продление веб-хостинга ws$webid на $days дней"
				);
				$this->wasteModel->createWaste($wasteData);
				
				$this->usersModel->downUserBalance($userid, $price);
				
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
				$this->data['success'] = "Вы успешно оплатили веб-хостинг на $days дней. С Вашего счета снято ".(round($price, 2))." руб";
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = "На Вашем счету не хватает ".(round($price-$balance, 2))." руб";	
			}
		}
		return json_encode($this->data);
	}

	private function validate($webid) {
		$result = null;
		
		$userid = $this->user->getId();
		
		if(!$this->webhostModel->getTotalWebhosts(array('web_id' => (int)$webid, 'user_id' => (int)$userid))) {
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
