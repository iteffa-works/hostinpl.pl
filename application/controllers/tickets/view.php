<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko (https://vk.com/id00v)
*/
class viewController extends Controller {
	public function index($ticketid = null) {
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
		
		$this->load->model('tickets');
		$this->load->model('users');
			
		$error = $this->validate($ticketid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'tickets/index');
		}
		
		$ticket = $this->ticketsModel->getTicketById($ticketid, array('users'));
		$this->data['ticket'] = $ticket;
		$this->data['user_img'] = $this->user->getUser_img();
		
		$LastTime = time() - 105;
		$online_sup = 0;
		foreach($this->usersModel->getUsers() as $line) {
			if($line['user_access_level'] > 1) {
				if ($line['user_online_date'] > $LastTime) {
					 $online_sup++;
				}
			}
		}
		if($online_sup > 0) {
			$this->data['sup_status'] = '<span class="label label-dot label-success"></span><span class="font-weight-bold text-muted font-size-sm"> В сети</span>';
		} else {
			$this->data['sup_status'] = '<span class="label label-dot label-danger"></span><span class="font-weight-bold text-muted font-size-sm"> Не в сети</span>';
		}
				
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('tickets/view', $this->data);
	}
	
	public function action($ticketid = null, $action = null) {
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
		switch($action) {
			case 'closed': {
				$this->ticketsModel->updateTicket($ticketid, array('ticket_status' => 0));	
				$this->data['status'] = "success";
				$this->data['success'] = "Вы успешно закрыли запрос!";
				break;
			}
			default: {
				$this->data['status'] = "error";
				$this->data['error'] = "Вы выбрали несуществующее действие!";
				break;
			}
		}
		return json_encode($this->data);
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
		
		$error = $this->validate($ticketid);
		if($error) {
	  		$this->data['status'] = "error";
			$this->data['error'] = $error;
			return json_encode($this->data);
		}
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST($ticketid);
			if(!$errorPOST) {
				$userid = $this->user->getId();
				$text = @$this->request->post['text'];
				$ticket = $this->ticketsModel->getTicketById($ticketid, array('users'));
				if($ticket['ticket_status'] != 0) {
					$messageData = array(
						'ticket_id'			=> $ticketid,
						'user_id'			=> $userid,
						'ticket_message'	=> $text
					);
					$this->ticketsModel->updateTicket($ticketid, array('ticket_status' => 1));
					$this->ticketsMessagesModel->createTicketMessage($messageData);
						
					$this->data['status'] = "success";
					$this->data['success'] = "Вы успешно отправили сообщение!";
				} else {
					$this->data['status'] = "error";
					$this->data['error'] = "Данный запрос закрыт, оставить новое сообщение невозможно!";					
				}
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = $errorPOST;
			}
		}
		return json_encode($this->data);
	}
	
	public function uploadFile($ticketid = null) {
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
		$this->load->model('users');
		
		$error = $this->validate($ticketid);
		if ($error) {
	  		$this->data['status'] = "error";
			$this->data['error'] = $error;
			return json_encode($this->data);
		}
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$uploaddir = "tmp/tickets_img/";
			$apend=date('YmdHis').rand(100,1000).'.jpg'; 
			$uploadfile = "$uploaddir$apend";
			
			if(move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) { 		
				if($_FILES['userfile']['type'] == 'image/gif' || $_FILES['userfile']['type'] == 'image/jpeg' || $_FILES['userfile']['type'] == 'image/png') { 
					if($_FILES['userfile']['size'] != 0 and $_FILES['userfile']['size'] <= 5120000) {
						$size = getimagesize($uploadfile); 
						if ($size[0] < 2560 && $size[1] < 1440) {
							if(round(($_FILES['userfile']['size'] / 1024), 2) > 1500.00) {
								$message = 'Изображение: ' . $apend . '<br>Размер: ' . round(($_FILES['userfile']['size'] / 1024), 2) . 'КБ<br><a href="/' . $uploadfile . ' target="_blank">Открыть</a>';
							} else {
								$message = '<a href="/' . $uploadfile . '" target="_blank"><img height="150" src="/' . $uploadfile . '"></a>';
							}
							$userid = $this->user->getId();
							$messageData = array(
								'ticket_id'			=> $ticketid,
								'user_id'			=> $userid,
								'ticket_message'	=> $message
							);
							$this->ticketsModel->updateTicket($ticketid, array('ticket_status' => 1));
							$this->ticketsMessagesModel->createTicketMessage($messageData);
							$this->data['status'] = 'success';
							$this->data['success'] = 'Изображение загружено!';			 
						} else {
							$this->data['error'] = 'Загружаемое изображение превышает допустимые нормы!';
							$this->data['status'] = "error";
							unlink($uploadfile); 
						} 
					} else { 
						$this->data['error'] = 'Размер изображения не должно превышать 5120Кб';
						$this->data['status'] = "error";
						unlink($uploadfile); 
					} 
				} else {     
					$this->data['status'] = "error";
					$this->data['error'] = "Можно загружать только изображения в форматах jpg, jpeg и png";
					unlink($uploadfile); 
				}
			} else {
				$this->data['error'] = 'Изображение не загружено!';
				$this->data['status'] = "error";
			} 
		}
		return json_encode($this->data);
	}
						  
	public function getMessagess() {
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
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->load->model('tickets');
			$this->load->model('ticketsMessages');
			
			$id = $this->request->post['tid'];
			
			$error = $this->validate($id);
			if($error) {
				$this->data['status'] = "error";
				$this->data['error'] = $error;
				return json_encode($this->data);
			}
		
			$url = $this->config->url;
			$messages = $this->ticketsMessagesModel->getTicketsMessages(array('ticket_id' => $id), array('users'));

			foreach($messages as $item) {
				$item['ticket_message'] = preg_replace("#(https?|ftp)://\S+[^\s.,>)\];'\"!?]#",'<a href="\\0" target="_blank">\\0</a>', $item['ticket_message']);
				if($item['user_access_level'] == '1') {
					echo '<div class="d-flex flex-column mb-5 align-items-start">
							<div class="d-flex align-items-center">
								<div class="symbol symbol-circle symbol-40 mr-3">
									<img alt="Pic" src="'.$url.''.$item['user_img'].'" />
								</div>
							<div>
							<a href="javascript:;" class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">'. $item['user_firstname'] .' '. $item['user_lastname'] .'</a>
								<span class="text-muted font-size-sm">'. date("d.m.Y в H:i", strtotime($item['ticket_message_date_add'])) .'</span>
							</div>
						  </div>
						   <div class="mt-2 rounded p-5 bg-light-success text-dark-50 font-weight-bold font-size-lg text-left max-w-400px">'. nl2br($item['ticket_message']) .'
						  </div></div>';
				}
					
				if($item['user_access_level'] >= '2') {
					echo '<div class="d-flex flex-column mb-5 align-items-end">
							<div class="d-flex align-items-center">
								<div class="symbol symbol-circle symbol-40 mr-3">
									<img alt="Pic" src="'.$url.''.$item['user_img'].'" />
								</div>
							<div>
							<a href="javascript:;" class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">'. $item['user_firstname'] .' '. $item['user_lastname'] .'</a>
								<span class="text-muted font-size-sm">'. date("d.m.Y в H:i", strtotime($item['ticket_message_date_add'])) .'</span>
							</div>
						</div>
						 <div class="mt-2 rounded p-5 bg-light-primary text-dark-50 font-weight-bold font-size-lg text-left max-w-400px">'. nl2br($item['ticket_message']) .'<br>
						</div></div>';
				}
			} 
		}
	}

	
	private function validate($ticketid) {
		$result = null;
		$userid = $this->user->getId();
		if(!$this->ticketsModel->getTotalTickets(array('ticket_id' => (int)$ticketid, 'user_id' => (int)$userid))) {
			$result = "Запрашиваемый запрос не существует!";
		}
		return $result;
	}
	
	private function validatePOST($ticketid) {
		$result = null;

		$this->load->model('tickets');
		$userid = $this->user->getId();
			
		$text = @$this->request->post['text'];
		
		if (5 <= $this->ticketsModel->spam($userid)) {
			$result = 'Пожалуйста, подождите 10 секунд.';
		}	
		else if(mb_strlen($text) < 1 || mb_strlen($text) > 350) {
			$result = "Текст сообщения должен содержать от 1 до 350 символов.";
		}
		return $result;
	}
}