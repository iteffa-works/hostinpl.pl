<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class loginheaderController extends Controller {
	public function index() {
		$this->data['title'] = $this->config->title;
		$this->data['description'] = $this->config->description;
		$this->data['keywords'] = $this->config->keywords;
		$this->data['logo'] = $this->config->logo;
		$this->data['public'] = $this->config->public;
		$this->data['count'] = $this->config->count;
		$this->data['recaptcha'] = $this->config->recaptcha;
		$this->data['vk_app_id'] = $this->config->vk_app_id;
		$this->data['vk_app_status'] = $this->config->vk_app_status;
		$this->data['url'] = $this->config->url;
		
		
		if(isset($this->session->data['error'])) {
			$this->data['error'] = $this->session->data['error'];
			unset($this->session->data['error']);
		}
		
		if(isset($this->session->data['warning'])) {
			$this->data['warning'] = $this->session->data['warning'];
			unset($this->session->data['warning']);
		}
		
		if(isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}
		$this->load->model('users');
		$userid = $this->user->getId();
		
		$user = $this->usersModel->getUserById(@$_GET['ref'], array(), array(), array());
		$this->data['user'] = $user;

		return $this->load->view('common/loginheader', $this->data);
	}
}
?>
