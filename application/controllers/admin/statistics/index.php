<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class indexController extends Controller {
	public function index() {
		$this->document->setActiveSection('admin/statistics');
		$this->document->setActiveItem('index');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 2) {
			$this->data['status'] = "error";
			$this->data['error'] = "У вас нет доступа к данному разделу!";
			return json_encode($this->data);
		}

		$userid = $this->user->getId();
		$this->load->model('users');	
	
		$this->getChild(array('common/admheader', 'common/footer'));
			
			
		$this->load->model('invoices');
		$this->load->model('servers');
		$get_invoices = $this->invoicesModel->getStatisticsInvStatsById();	
		$get_registers = $this->usersModel->getStatisticsRegistersById();
		$get_players = $this->serversModel->getStatisticsPlayers();
		$get_loads = $this->serversModel->getStatisticsLoad();

		$charts = 'var invoices = [ ';
		foreach($get_invoices as $item){ 		
			$charts .= '["'. date('Y-m-d\TH:i:s.000\Z', strtotime($item['invoice_date_add'])) .'", '.$item['SUM(`invoice_ammount`)'].'], ';				
		}
		$charts .= '];';

		$charts .= 'var registers = [ ';
		foreach($get_registers as $item){ 		
			$charts .= '["'. date('Y-m-d\TH:i:s.000\Z', strtotime($item['mydate'])) .'", '.(int)$item['count(user_id)'].'], ';			
		}
		$charts .= '];';
			
		$charts .= 'var players = [ ';
		foreach($get_players as $item){ 	
			$charts .= '["'. date('Y-m-d\TH:i:s.000\Z', strtotime($item['mydate'])) .'", '.(int)$item['sum(`server_stats_players`)'].'], ';			
		}
		$charts .= '];';			

		$charts .= 'var loads1 = [ ';
		foreach($get_loads as $item){ 		
			$charts .= '["'. date('Y-m-d\TH:i:s.000\Z', strtotime($item['mydate'])) .'", '.(int)$item['sum(`server_stats_cpu`)'].'], ';			
		}
		$charts .= '];';	

		$charts .= 'var loads2 = [ ';
		foreach($get_loads as $item){ 		
			$charts .= '["'. date('Y-m-d\TH:i:s.000\Z', strtotime($item['mydate'])) .'", '.(int)$item['sum(`server_stats_hdd`)'].'], ';			
		}
		$charts .= '];';	
			
		$charts .= 'var loads3 = [ ';
		foreach($get_loads as $item){ 		
			$charts .= '["'. date('Y-m-d\TH:i:s.000\Z', strtotime($item['mydate'])) .'", '.(int)$item['sum(`server_stats_ram`)'].'], ';			
		}
		$charts .= '];';				
			
		$this->data['charts'] = $charts;

			
		return $this->load->view('admin/statistics/index', $this->data);
	}	
}
?>
