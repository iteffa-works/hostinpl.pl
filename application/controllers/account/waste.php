<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko (https://vk.com/id00v)
*/
class wasteController extends Controller {
	private $limit = 20;
	public function index($page = 1) {
		$this->document->setActiveSection('account');
		$this->document->setActiveItem('waste');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 1) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->library('pagination');
		$this->load->model('waste');

		$pl = array(
			'start' => ($page - 1) * $this->limit,
		);
		$userid = $this->user->getId();
		$total = $this->wasteModel->getTotalWaste(array('user_id' => (int)$userid));
		$waste = $this->wasteModel->getWaste(array('user_id' => (int)$userid), array(), array(), $pl);
		
		
		$paginationLib = new paginationLibrary();
		
		$paginationLib->total = $total;
		$paginationLib->page = $page;
		$paginationLib->limit = $this->limit;
		$paginationLib->url = $this->config->url . 'account/waste/index/{page}';
		
		$pagination = $paginationLib->render();
		
		$this->data['waste'] = $waste;
		$this->data['pagination'] = $pagination;
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('account/waste', $this->data);
	}
}
?>
