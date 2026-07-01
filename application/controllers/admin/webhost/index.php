<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class indexController extends Controller {
	private $limit = 22;
	public function index($page = 1) {
		$this->document->setActiveSection('admin/webhost');
		$this->document->setActiveItem('index');
	
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 2) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->library('pagination');
		$this->load->model('webhost');
		
		$userid = $this->user->getId();
		$userid = @$this->request->get['userid'];
		$tarifid = @$this->request->get['tarifid'];
		$locationid = @$this->request->get['locationid'];
		
		$getData = array();
		
		if(!empty($userid)) {
			$getData['webhost.user_id'] = (int)$userid;
		}
		if(!empty($tarifid)) {
			$getData['webhost.tarif_id'] = (int)$tarifid;
		}
		if(!empty($locationid)) {
			$getData['webhost.location_id'] = (int)$locationid;
		}
		
		$getOptions = array(
			'start' => ($page - 1) * $this->limit,
			'limit' => $this->limit
		);
		
		$total = $this->webhostModel->getTotalWebhosts($getData);
		$webhosts = $this->webhostModel->getWebhosts($getData, array('web_tarifs', 'web_locations'), array(), $getOptions);
		
		$paginationUrl = '/admin/webhost/index/index/{page}';
		if(!empty($userid)) {
			$paginationUrl .= '&userid=' . $userid;
		}
		if(!empty($tarifid)) {
			$paginationUrl .= '&tarifid=' . $tarifid;
		}
		if(!empty($locationid)) {
			$paginationUrl .= '&locationid=' . $locationid;
		}
	
		$paginationLib = new paginationLibrary();
		$paginationLib->total = $total;
		$paginationLib->page = $page;
		$paginationLib->limit = $this->limit;
		$paginationLib->url = $paginationUrl;
		$pagination = $paginationLib->render();
		
		$this->data['webhosts'] = $webhosts;
		$this->data['pagination'] = $pagination;
		
		$this->getChild(array('common/admheader', 'common/footer'));
		return $this->load->view('admin/webhost/index', $this->data);
	}
}
?>
