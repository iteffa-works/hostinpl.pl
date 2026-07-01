<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class unitpayController extends Controller {
	public function index() {
		$this->load->model('users');
		$this->load->model('invoices');
		$this->load->model('waste');

		error_reporting(0);
		function sha256sign($method, $params, $secretKey) {
			ksort($params);
			unset($params['signature']);

			return hash('sha256', $method . '{up}' . join('{up}', $params) . '{up}' . $secretKey);
		}

		function responseError($message) {
			$error = array(
				"error" => array(
					"message" => $message
				)
			);
			echo json_encode($error, JSON_UNESCAPED_UNICODE); exit();
		}

		function responseSuccess($message) {
			$success = array(
				"result" => array(
					"message" => $message
				)
			);
			echo json_encode($success, JSON_UNESCAPED_UNICODE); exit();
		}
		 
		$secretKey = $this->config->unitpay_secret;
		$method = $_GET['method'];
		$params = $_GET['params'];
			
		if ($params['signature'] != sha256sign($method, $params, $secretKey)) {
			responseError("Не соответсвие SHA256 хешей");
		}	
		
		switch($method) {
			case 'check':
					if (!$this->link = @mysqli_connect($this->config->db_hostname, $this->config->db_username, $this->config->db_password, $this->config->database)) {
						die('Ошибка: Не удалось соединиться с сервером базы данных!');
					}
				break;
			case 'pay':
					if (!$this->link = @mysqli_connect($this->config->db_hostname, $this->config->db_username, $this->config->db_password, $this->config->database)) {
						die('Ошибка: Не удалось соединиться с сервером базы данных!');
					}
					
					$invid = $_GET['params']['account'];
						
					if(!$this->invoicesModel->getTotalInvoices(array('invoice_id' => (int)$invid))) {
						responseError("Invalid invoice!");
					}
					
					$ammount = $_GET['params']['orderSum'];
							
					$invoice = $this->invoicesModel->getInvoiceById($invid);
					$userid = $invoice['user_id'];
					$user = $this->usersModel->getUserById($userid);
				
					if($invoice['invoice_ammount'] == $ammount){
						if($ammount > 50){
							$this->usersModel->updateUser($userid, $userData = array('user_promised_pay' => 0));
						}

						$wasteData = array(
							'user_id'		=> $userid,
							'waste_ammount'	=> $ammount,
							'waste_status'	=> 0,
							'waste_usluga'	=> "Пополнение баланса пользователя",
						); 						
						$this->wasteModel->createWaste($wasteData);
			
						$this->usersModel->upUserBalance($userid, $ammount);	
						
						$bonus_percent = $this->config->bonus_percent;
						$getbonus = ($ammount * (1 + $bonus_percent / 100)) - $ammount;
						$this->usersModel->upUserBonuses($userid, $getbonus);
						
						$this->invoicesModel->updateInvoice($invid, array('invoice_status' => 1));
					}
				break;
			case 'error':
					responseError("Ошибка,{$params['errorMessage']}"); exit;
				break;
			default:
				responseError("Только: check, pay"); exit;
		}
		responseSuccess("Успех");
	}
}
?>
