<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class indexController extends Controller {
	private $limit = 20;
	public function index($page = 1) {
		$this->document->setActiveSection('admin/tickets');
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
		
		$this->load->library('pagination');
		$this->load->model('tickets');
		$this->load->model('users');
		$userid = $this->user->getId();
		
		$userid = @$this->request->get['userid'];
		$status = @$this->request->get['status'];
		
		$getData = array();
		
		if(!empty($userid)) {
			$getData['tickets.user_id'] = (int)$userid;
		}
		if(!empty($status)) {
			$getData['tickets.ticket_status'] = (int)$status;
		}
		
		$getSort = array(
			'ticket_status'		=> 'DESC',
			'ticket_date_add'	=> 'DESC'
		);
		
		$getOptions = array(
			'start' => ($page - 1) * $this->limit,
			'limit' => $this->limit
		);
		
		$total = $this->ticketsModel->getTotalTickets($getData);
		$tickets = $this->ticketsModel->getTickets($getData, array('users','tickets_category'), $getSort, $getOptions);
		
		$paginationUrl = '/admin/tickets/index/index/{page}';
		if(!empty($userid)) {
			$paginationUrl .= '&userid=' . $userid;
		}
		if(!empty($status)) {
			$paginationUrl .= '&status=' . $status;
		}
	
		$paginationLib = new paginationLibrary();
		$paginationLib->total = $total;
		$paginationLib->page = $page;
		$paginationLib->limit = $this->limit;
		$paginationLib->url = $paginationUrl;
		$pagination = $paginationLib->render();
		
		$this->data['tickets'] = $tickets;
		$this->data['pagination'] = $pagination;
		
		$this->getChild(array('common/admheader', 'common/footer'));
		return $this->load->view('admin/tickets/index', $this->data);
	}
}
?>