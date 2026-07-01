<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class indexController extends Controller {
	public function index($page = 1) {	
		$this->document->setActiveSection('admin/checksys');
		$this->document->setActiveItem('index');
		
		$this->data['url'] = $this->config->url;
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 2) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->data['user_access_level'] = $this->user->getAccessLevel();
		
		$this->getChild(array('common/admheader', 'common/footer'));
		return $this->load->view('admin/checksys/index', $this->data);
	}
}
?>
