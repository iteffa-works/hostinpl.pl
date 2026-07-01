<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko (https://vk.com/id00v)
*/
class faqController extends Controller {
	public function index() {
      $this->document->setActiveSection('tickets');
      $this->document->setActiveItem('faq');
      
		if(!$this->user->isLogged()) {
            $this->session->data['error'] = "Вы не авторизированы!";
            $this->response->redirect($this->config->url . 'account/login');
        }
        if($this->user->getAccessLevel() < 0) {
            $this->session->data['error'] = "У вас нет доступа к данному разделу!";
            $this->response->redirect($this->config->url);
        }
      

		$this->data['user_img'] = $this->user->getUser_img();
		$this->data['url'] = $this->config->url;
		
		$userid = $this->user->getId();
		
		$this->data['logged'] = true;
		$this->data['user_email'] = $this->user->getEmail();
		$this->data['user_id'] = $userid;
		$this->data['user_firstname'] = $this->user->getFirstname();
		$this->data['user_lastname'] = $this->user->getLastname();
		$this->data['user_balance'] = $this->user->getBalance();
        $this->data['user_access_level'] = $this->user->getAccessLevel();
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('tickets/faq', $this->data);
	}
}
?>