<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class indexController extends Controller {
	private $limit = 10;
	public function index($page = 1) {
		$this->document->setActiveSection('webhost');
		$this->document->setActiveItem('index');
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не залогинены!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 0) {
			$this->session->data['error'] = "Нет доступа!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->library('pagination');
		$this->load->model('webhost');
		
		$userid = $this->user->getId();
		
		$options = array(
			'start' => ($page - 1) * $this->limit,
			'limit' => $this->limit
		);
		
		$total = $this->webhostModel->getTotalWebhosts(array('user_id' => (int)$userid));
		$webhosts = $this->webhostModel->getWebhosts(array('user_id' => (int)$userid), array('web_tarifs', 'web_locations'), array(), $options);
		
		$paginationLib = new paginationLibrary();
		
		$paginationLib->total = $total;
		$paginationLib->page = $page;
		$paginationLib->limit = $this->limit;
		$paginationLib->url = $this->config->url . 'webhost/index/index/{page}';
		
		$pagination = $paginationLib->render();
		
		$this->data['webhosts'] = $webhosts;
		$this->data['pagination'] = $pagination;
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('webhost/index', $this->data);
	}
}
?>
