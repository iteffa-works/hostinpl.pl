<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko (https://vk.com/id00v)
*/
class indexController extends Controller {
	private $limit = 10;
	public function index($page = 1) {
		$this->document->setActiveSection('tickets');
		$this->document->setActiveItem('index');
        $this->data['url'] = $this->config->url;
		
        if($this->user->isLogged()) {
            $this->data['logged'] = true;
            $this->data['user_email'] = $this->user->getEmail();
            $this->data['user_firstname'] = $this->user->getFirstname();
            $this->data['user_lastname'] = $this->user->getLastname();
            $this->data['user_balance'] = $this->user->getBalance();
            $this->data['user_access_level'] = $this->user->getAccessLevel();
            $this->data['user_img'] = $this->user->getUser_img();
        } else {
            $this->data['logged'] = false;
            $this->data['user_access_level'] = 0;
        }
        
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 0) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->library('pagination');
		$this->load->model('tickets');
		$this->load->model('ticketsCategory');
        $this->load->model('users');

		$userid = $this->user->getId();
		$sort = array(
			'ticket_status'		=> 'DESC',
			'ticket_date_add'	=> 'DESC'
		);
		$options = array(
			'start'		=>	($page - 1) * $this->limit,
			'limit'		=>	$this->limit
		);

		$total = $this->ticketsModel->getTotalTickets(array('user_id' => (int)$userid));
		$tickets = $this->ticketsModel->getTickets(array('user_id' => (int)$userid), array('tickets_category'), $sort, $options);
	
		$paginationLib = new paginationLibrary();
		
		$paginationLib->total = $total;
		$paginationLib->page = $page;
		$paginationLib->limit = $this->limit;
		$paginationLib->url = $this->config->url . 'tickets/index/index/{page}';
		$pagination = $paginationLib->render();
		$this->data['tickets'] = $tickets;
		$this->data['pagination'] = $pagination;
        $this->data['user_firstname'] = $this->user->getFirstname();
        $this->data['user_lastname'] = $this->user->getLastname();
        $this->data['user_access_level'] = $this->user->getAccessLevel();
        $this->data['user_img'] = $this->user->getUser_img();
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('tickets/index', $this->data);
	}
}
?>