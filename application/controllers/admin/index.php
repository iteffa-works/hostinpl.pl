<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class indexController extends Controller {
	public function index() {
		$this->document->setActiveSection('admin');
		$this->document->setActiveItem('index');
		
		if(!$this->user->isLogged()) {
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 2) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$getlvl = $this->user->getAccessLevel();
		$this->data['getlvl'] = $getlvl;
		
		$this->load->model('servers');
		$this->load->model('tickets');
		$this->load->model('users');
		$this->load->model('invoices');
		$this->load->model('waste');

		$userid = $this->user->getId();


		$options = array(
			'start' => 0,
			'limit' => 50000
		);
		
		$tickets = $this->ticketsModel->getTickets(array(), array('users'), array(), array());	
		$this->data['tickets'] = $tickets;

		$tservers = $this->serversModel->getServers(array(), array('games', 'locations'), array(), array());
		$this->data['tservers'] = $tservers;

		$invoices = $this->invoicesModel->getInvoices(array(), array('users'), array(), $options);
		$this->data['invoices'] = $invoices;
		
		$users = $this->usersModel->getUsers(array(), array(), $options);
		$this->data['users'] = $users;
		
		$hostin = array(
			'waste_date_add'	=> 'DESC'
		);
		$pl = array(
			'start' => 0,
			'limit' => 10
		);
		$waste = $this->wasteModel->getWaste(array(), array('users'),  $hostin, $pl);
		$this->data['waste'] = $waste;

		$this->getChild(array('common/admheader', 'common/footer'));
		return $this->load->view('admin/index', $this->data);
	}
}
?>