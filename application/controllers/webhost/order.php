<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class orderController extends Controller {
	public function index() {
		$this->document->setActiveSection('webhost');
		$this->document->setActiveItem('order');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 0) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		$this->load->model('webhostTarifs');
		$this->load->model('webLocations');
		$tarifs = $this->webhostTarifsModel->getTarifs(array('tarif_status' => 1));
		$locations = $this->webLocationsModel->getLocations(array('location_status' => 1));
		$this->data['tarifs'] = $tarifs;
		$this->data['locations'] = $locations;
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('webhost/order', $this->data);
	}
	
	public function promo() {
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
			$code = $this->request->post['code'];
			$skidka = $this->usersModel->getSkidkaByCode($code, false);
			if(@$skidka['skidka'] == NULL){
				$this->data['status'] = "error";
				$this->data['error'] = "Данного кода не существует";
			}else{
				$kofficent=(100-$skidka['skidka'])/100;
				
				$this->data['status'] = "success";
				$this->data['success'] = "Вы активировали скидку ".$skidka['skidka']."%";
				$this->data['skidka'] = $kofficent;
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
		$this->load->model('waste');
		$this->load->model('webhost');
		$this->load->model('webhostTarifs');
		$this->load->model('webLocations');
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			if($this->config->webhost == 1) {
				$errorPOST = $this->validatePOST();
				if(!$errorPOST) {
					$tarifid = $this->request->post['tarifid'];
					$days = $this->request->post['days'];
					$locationid = $this->request->post['locationid'];
					
					$location = $this->webLocationsModel->getLocationById($locationid);
					$location_tarifs = explode(" ", $location['location_tarifs']);

					if(!in_array($tarifid, $location_tarifs)) {
						$this->data['status'] = "error";
						$this->data['error'] = "Данный тариф не доступен для указанной локации!";
						return json_encode($this->data);
					}
					
					$chars="qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
					$max=14; 
					$size=StrLen($chars)-1; 
					$password=null;
					while($max--) 
					$password.=$chars[rand(0,$size)];
				
					$userid = $this->user->getId();
					$balance = $this->user->getBalance();
					$tarif = $this->webhostTarifsModel->getTarifById($tarifid);	
					$code = $this->request->post['promo'];
					$skidka = $this->usersModel->getSkidkaByCode($code,true);
				
						$price = $tarif['tarif_price'];
					
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
								$price = 6 * $price * 0.90;
								break;
							case "360":
								$price = 12 * $price * 0.85;
								break;
						}
					
						if(@$skidka['skidka'] != NULL){
							$kofficent=(100-$skidka['skidka'])/100;	
							$price = $price * $kofficent;
						}
					
						if($balance >= $price) {
							$webData = array(
								'user_id'			=> $userid,
								'location_id'		=> $locationid,
								'web_password'		=> $password,
								'tarif_id'		    => $tarifid,
								'web_status'		=> 1,
								'web_days'			=> $days
							);
							$package = $tarif['package'];
							$webid = $this->webhostModel->createWebhost($webData);
							$result = $this->webhostModel->installWebhost($webid,$password,$package);
							if($result["status"] == "success") {
								$this->usersModel->downUserBalance($userid, $price);
								$wasteData = array(
								  'user_id'			=> $userid,
								  'waste_ammount'	=> $price,
								  'waste_status'	=> 1,
								  'waste_usluga'	=> "Заказ веб-хостинга ws$webid"
								); 
								$this->wasteModel->createWaste($wasteData);
								
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
								$this->data['success'] = "Веб-хостинг №".$webid." успешно заказан.";
								$this->data['id'] = $webid;
							} else {
								$this->data["status"] = "error";
								$this->data["error"] = $result["description"];
							}
						} else {
							$this->data['status'] = "error";
							$this->data['error'] = "На Вашем счету не хватает ".(round($price-$balance, 2))." руб";	
						}
				} else {
					$this->data['status'] = "error";
					$this->data['error'] = $errorPOST;
				}
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = "Веб.хостинг не доступен для заказа!";
			}
		}

		return json_encode($this->data);
	}
	
	private function validatePOST() {
		$this->load->library('validate');
		
		$validateLib = new validateLibrary();
		
		$result = null;
		$tarifid = @$this->request->post['tarifid'];
		$months = @$this->request->post['months'];
		$locationid = @$this->request->post['locationid'];
		$days = @$this->request->post['days'];

		if(!$this->webhostTarifsModel->getTotalTarifs(array('tarif_id' => (int)$tarifid, 'tarif_status' => 1))) {
			$result = "Вы указали несуществующий тариф!";
		}
		elseif(!$this->webLocationsModel->getTotalLocations(array('location_id' => (int)$locationid, 'location_status' => 1))) {
			$result = "Вы указали несуществующую локацию!";
		}
		elseif($days != "30" && $days != "60" && $days != "90" && $days != "180" && $days != "360" && $days != "15") {
			$result = "Вы указали недопустимый период оплаты!";
		}
		return $result;
	}
}
?>
