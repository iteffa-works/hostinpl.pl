<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class editController extends Controller {
	public function index($tarifid = null) {
		$this->document->setActiveSection('admin/webtarifs');
		$this->document->setActiveItem('edit');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 3) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->model('webhostTarifs');
		
		$error = $this->validate($tarifid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'admin/webtarifs/index');
		}
		
		$tarif = $this->webhostTarifsModel->getTarifById($tarifid);
		
		$this->data['tarif'] = $tarif;
		
		$this->getChild(array('common/admheader', 'common/footer'));
		return $this->load->view('admin/webtarifs/edit', $this->data);
	}
	
	public function ajax($tarifid = null) {
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
		
		$this->load->model('webhostTarifs');
		
		$error = $this->validate($tarifid);
		if($error) {
			$this->data['status'] = "error";
			$this->data['error'] = $error;
			return json_encode($this->data);
		}
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$name = @$this->request->post['name'];
				$package = @$this->request->post['package'];
				$price = @$this->request->post['price'];
				$status = @$this->request->post['status'];
				
				$tarifData = array(
					'tarif_name'		=> $name,
					'package'			=> $package,
					'tarif_price'		=> (float)$price,
					'tarif_status'		=> (int)$status
				);
				
				$this->webhostTarifsModel->updateTarif($tarifid, $tarifData);
				
				$this->data['status'] = "success";
				$this->data['success'] = "Вы успешно отредактировали тариф!";
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = $errorPOST;
			}
		}

		return json_encode($this->data);
	}
	
	public function delete($tarifid = null) {
		$this->document->setActiveSection('admin');
		$this->document->setActiveItem('tarifid');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 3) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->model('webhostTarifs');
		
		$error = $this->validate($tarifid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'admin/tarifid/index');
		}
		
		$this->webhostTarifsModel->deleteTarif($tarifid);
		
		$this->session->data['success'] = "Вы успешно удалили тариф!";
		$this->response->redirect($this->config->url . 'admin/tarifid/index');
		return null;
	}
	
	private function validate($tarifid) {
		$result = null;
		
		if(!$this->webhostTarifsModel->getTotalTarifs(array('tarif_id' => (int)$tarifid))) {
			$result = "Запрашиваеммый тариф не существует!";
		}
		return $result;
	}
	
	private function validatePOST() {
		$this->load->library('validate');
		
		$validateLib = new validateLibrary();
		
		$result = null;
		
		$name = @$this->request->post['name'];
		$package = @$this->request->post['package'];
		$price = @$this->request->post['price'];
		$status = @$this->request->post['status'];
		
		if(mb_strlen($name) < 2 || mb_strlen($name) > 32) {
			$result = "Название тарифа должно содержать от 2 до 32 символов!";
		}
		elseif(mb_strlen($package) < 1 || mb_strlen($package) > 32) {
			$result = "Название пакета должно содержать от 2 до 32 символов!";
		}
		elseif(!$validateLib->money($price)) {
			$result = "Укажите допустимую стоимость!";
		}
		elseif($status < 0 || $status > 1) {
			$result = "Укажите допустимый статус!";
		}
		return $result;
	}
}
?>
