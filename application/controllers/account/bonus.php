<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko (https://vk.com/id00v)
*/
class bonusController extends Controller {
	public function index() {
        $this->document->setActiveSection('account');
        $this->document->setActiveItem('bonus');
        
		if(!$this->user->isLogged()) {
            $this->session->data['error'] = "Вы не авторизированы!";
            $this->response->redirect($this->config->url . 'account/login');
        }
        if($this->user->getAccessLevel() < 0) {
            $this->session->data['error'] = "У вас нет доступа к данному разделу!";
            $this->response->redirect($this->config->url);
        }
		
		$this->load->model('users');
		$this->data['bonus1'] = $this->config->bonus1;
		$this->data['bonus2'] = $this->config->bonus2;
		$this->data['bonus3'] = $this->config->bonus3;
		$this->data['bonus4'] = $this->config->bonus4;

		$userid = $this->user->getId();
		$users = $this->usersModel->getUserById($userid, array(), array());
		$this->data['users'] = $users;
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('account/bonus', $this->data);
	}
	public function ajax_action_exchange($action = null) {
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
		
		$this->data['bonus1'] = $bonus1 = round($this->config->bonus1);
		$this->data['bonus2'] = $bonus2 = round($this->config->bonus2);
		$this->data['bonus3'] = $bonus3 = round($this->config->bonus3);
		$this->data['bonus4'] = $bonus4 = round($this->config->bonus4);

		$this->load->model('users');
		$this->load->model('waste');

		$userid = $this->user->getId();
		$users = $this->usersModel->getUserById($userid, array(), array());

		switch($action) {
			case '100DEB': {	
				$rmoney = $users['bonuses'];
				$sum = "100";
				$upsum = $bonus1;
					if ($rmoney >= $sum){

						$this->usersModel->downUserBalance($userid, $price);
							$wasteData = array(
							'user_id'			=> $userid,
							'waste_ammount'	=> $upsum,
							'waste_status'	=> 0,
							'waste_usluga'	=> "Обменял $sum бонусов на $upsum рублей"
						);
						$this->wasteModel->createWaste($wasteData);
						
						$this->usersModel->upUserBalance($userid, $upsum);
						$this->usersModel->downUserBonuses($userid, $sum);
						
						$this->data['status'] = "success";
						$this->data['success'] = "Деньги отправлены. Вам начисленно ".$upsum." руб!";
					} else {
						$this->data['status'] = "error";
						$this->data['error'] = "У вас недостаточно монет!";
					}
				break;
			}
			case '300DEB': {	
				$rmoney = $users['bonuses'];
				$sum = "300";
				$upsum = $bonus2;
					if ($rmoney >= $sum){
						
						$this->usersModel->downUserBalance($userid, $price);
							$wasteData = array(
							'user_id'			=> $userid,
							'waste_ammount'	=> $upsum,
							'waste_status'	=> 0,
							'waste_usluga'	=> "Обменял $sum бонусов на $upsum рублей"
						);
						$this->wasteModel->createWaste($wasteData);
						
						$this->usersModel->upUserBalance($userid, $upsum);
						$this->usersModel->downUserBonuses($userid, $sum);
						
						$this->data['status'] = "success";
						$this->data['success'] = "Деньги отправлены. Вам начисленно ".$upsum." руб!";
					} else {
						$this->data['status'] = "error";
						$this->data['error'] = "У вас недостаточно монет!";
					}
				break;
			}
			case '600DEB': {	
				$rmoney = $users['bonuses'];
				$sum = "600";
				$upsum = $bonus3;
					if ($rmoney >= $sum){
						
						$this->usersModel->downUserBalance($userid, $price);
							$wasteData = array(
							'user_id'			=> $userid,
							'waste_ammount'	=> $upsum,
							'waste_status'	=> 0,
							'waste_usluga'	=> "Обменял $sum бонусов на $upsum рублей"
						);
						$this->wasteModel->createWaste($wasteData);
						
						$this->usersModel->upUserBalance($userid, $upsum);
						$this->usersModel->downUserBonuses($userid, $sum);
						
						$this->data['status'] = "success";
						$this->data['success'] = "Деньги отправлены. Вам начисленно ".$upsum." руб!";
					} else {
						$this->data['status'] = "error";
						$this->data['error'] = "У вас недостаточно монет!";
					}
				break;
			}
			case '1000DEB': {	
				$rmoney = $users['bonuses'];
				$sum = "1000";
				$upsum = $bonus4;
					if ($rmoney >= $sum){
						
						$this->usersModel->downUserBalance($userid, $price);
							$wasteData = array(
							'user_id'			=> $userid,
							'waste_ammount'	=> $upsum,
							'waste_status'	=> 0,
							'waste_usluga'	=> "Обменял $sum бонусов на $upsum рублей"
						);
						$this->wasteModel->createWaste($wasteData);
						
						$this->usersModel->upUserBalance($userid, $upsum);
						$this->usersModel->downUserBonuses($userid, $sum);
						
						$this->data['status'] = "success";
						$this->data['success'] = "Деньги отправлены. Вам начисленно ".$upsum." руб!";
					} else {
						$this->data['status'] = "error";
						$this->data['error'] = "У вас недостаточно монет!";
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
}
?>