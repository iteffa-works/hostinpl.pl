<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class indexController extends Controller {
	private $limit = 20;
	public function index($page = 1) {
		$this->document->setActiveSection('admin/waste');
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
		$this->load->model('waste');
		
		$userid = @$this->request->get['userid'];
		
		$getData = array();
		
		if(!empty($userid)) {
			$getData['waste.user_id'] = (int)$userid;
		}
		
		$getOptions = array(
			'start' => ($page - 1) * $this->limit,
			'limit' => $this->limit
		);
		
		$total = $this->wasteModel->getTotalWaste($getData);
		$waste = $this->wasteModel->getWaste($getData, array('users'), array(), $getOptions);
		$wastes = $this->wasteModel->getWaste(array(), array('users'), array(), array());
		
		$paginationUrl = '/admin/waste/index/index/{page}';
		if(!empty($userid)) {
			$paginationUrl .= '&userid=' . $userid;
		}
	
		$paginationLib = new paginationLibrary();
		$paginationLib->total = $total;
		$paginationLib->page = $page;
		$paginationLib->limit = $this->limit;
		$paginationLib->url = $paginationUrl;
		$pagination = $paginationLib->render();
		
		$this->data['waste'] = $waste;
		$this->data['wastes'] = $wastes;
		$this->data['pagination'] = $pagination;
		
		$this->getChild(array('common/admheader', 'common/footer'));
		return $this->load->view('admin/waste/index', $this->data);
	}
}
?>