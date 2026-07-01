<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class createController extends Controller {
	public function index($ticketid = null) {
		$this->document->setActiveSection('admin/tickets');
		$this->document->setActiveItem('create');
		$this->data['url'] = $this->config->url;
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 2) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		$this->load->model('users');
		$userid = $this->user->getId();
		$this->getChild(array('common/admheader', 'common/footer'));
		return $this->load->view('admin/tickets/create', $this->data);
	}
	
	public function ajax($ticketid = null) {
		if(!$this->user->isLogged()) {  
	  		$this->data['status'] = "error";
			$this->data['error'] = "Вы не авторизированы!";
			return json_encode($this->data);
		}
		if($this->user->getAccessLevel() < 1) {
	  		$this->data['status'] = "error";
			$this->data['error'] = "У вас нет доступа к данному разделу!";
			return json_encode($this->data);
		}
		
		$this->load->model('tickets');
		$this->load->model('ticketsMessages');
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$name = @$this->request->post['name'];
				$text = @$this->request->post['text'];
				$pid = @$this->request->post['pid'];
				$userid = $this->user->getId();
				
				$ticketData = array(
					'user_id'			=> $pid,
					'ticket_name'		=> $name,
					'ticket_status'		=> 2
				);
				$ticketid = $this->ticketsModel->createTicket($ticketData);
				
				$messageData = array(
					'ticket_id'			=> $ticketid,
					'user_id'			=> $userid,
					'ticket_message'	=> $text
				);
				$this->ticketsMessagesModel->createTicketMessage($messageData);
				
				$this->data['status'] = "success";
				$this->data['success'] = "Вы успешно отправили сообщение пользователю!";
				$this->data['id'] = $ticketid;
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = $errorPOST;
			}
		}

		return json_encode($this->data);
	}
	
	private function validatePOST() {
		$result = null;
		
		$name = @$this->request->post['name'];
		$pid = @$this->request->post['pid'];
		$text = @$this->request->post['text'];
		
		if(mb_strlen($name) < 6 || mb_strlen($name) > 32) {
			$result = "Название тикета должно содержать от 6 до 32 символов!";
		}
		elseif(mb_strlen($pid) < 1 || mb_strlen($pid) > 10) {
			$result = "ID клиента должен быть от 1 до 10 символов!";
		}
		elseif(mb_strlen($text) < 1 || mb_strlen($text) > 350) {
			$result = "Текст тикета должен содержать от 1 до 350 символов!";
		}
		return $result;
	}
}