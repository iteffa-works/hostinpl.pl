<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko (https://vk.com/id00v)
*/
class indexController extends Controller {
	public function index() {
		$this->document->setActiveSection('status');
        $this->document->setActiveItem('index');

		if(!$this->user->isLogged()) {
            $this->session->data['error'] = "Вы не авторизированы!";
            $this->response->redirect($this->config->url . 'account/login');
        }
        if($this->user->getAccessLevel() < 0) {
            $this->session->data['error'] = "У вас нет доступа к данному разделу!";
            $this->response->redirect($this->config->url);
        }
		
		$this->load->model('servers');
		$this->load->model('tickets');
		$this->load->model('users');
		$this->load->model('invoices');
		$this->load->model('games');
		$this->load->model('locations');
		$this->load->model('promos');
		$this->load->model('news');
		$userid = $this->user->getId();
		$ticketsSort = array(
			'ticket_status'		=> 'DESC',
			'ticket_date_add'	=> 'DESC'
		);
        $getData = array();
		if(isset($this->request->get['gameid'])) {
			$getData['servers.game_id'] = (int)$this->request->get['gameid'];
		}
		
		if(isset($this->request->get['locationid'])) {
			$getData['servers.location_id'] = (int)$this->request->get['locationid'];
		}

		$options = array(
			'start' => 0,
			'limit' => 100000
		);
		$getOptions = array(
			'start' => 0,
			'limit' => 100000
		);
		
		$total = $this->ticketsModel->getTotalTickets($getData);
		$tickets = $this->ticketsModel->getTickets($getData, array('users'), array(), $getOptions);
		$servers = $this->serversModel->getServers(array('user_id' => (int)$userid), array('games', 'locations'), array(), $options);		
		$total = $this->serversModel->getTotalServers($getData);
		$tservers = $this->serversModel->getServers($getData, array('games', 'locations'), array(), $getOptions);
		$total = $this->invoicesModel->getTotalInvoices($getData);
		$invoices = $this->invoicesModel->getInvoices($getData, array('users'), array(), $options);
		$users = $this->usersModel->getUsers(array(), array(), $options);
		$games = $this->gamesModel->getGames(array(), array(), $options);
		$locations = $this->locationsModel->getLocations(array('location_status' => 1), array(), $options);
		$promos = $this->promosModel->getpromo(array(), array(), $options);
		$news = $this->newsModel->getNews(array(), array(), array(),$options);
		$this->data['games'] = $games;
		$this->data['users'] = $users;
		$this->data['tservers'] = $tservers;
		$this->data['tickets'] = $tickets;
		$this->data['servers'] = $servers;		
		$this->data['invoices'] = $invoices;
		$this->data['locations'] = $locations;
		$this->data['promos'] = $promos;
		$this->data['news'] = $news;
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('status/index', $this->data);
	}
}
?>