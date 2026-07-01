<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class createController extends Controller {

	public function index($ticketid = null) {
		$this->document->setActiveSection('admin/news');
		$this->document->setActiveItem('create');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 3) {
            $this->session->data['error'] = "У вас нет доступа к данному разделу!";
            $this->response->redirect($this->config->url);
        }
		$this->getChild(array('common/admheader', 'common/footer'));
		return $this->load->view('admin/news/create', $this->data);
	}
	
	public function ajax($ticketid = null) {
		if(!$this->user->isLogged()) {  
	  		$this->data['status'] = "error";
			$this->data['error'] = "Вы не авторизированы!";
			return json_encode($this->data);
		}
		if($this->user->getAccessLevel() < 3) {
	  		$this->data['status'] = "error";
			$this->data['error'] = "У вас нет доступа к данному разделу!";
			return json_encode($this->data);
		}
		
		$this->load->model('news');
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$name = @$this->request->post['name'];
				$text = @$this->request->post['text'];
				$userid = $this->user->getId();
				
				$newsData = array(
					'user_id'			=> $userid,
					'news_title'		=> $name,
					'news_text'			=> $text
				);
				$newsid = $this->newsModel->createNews($newsData);

				
				$this->data['status'] = "success";
				$this->data['success'] = "Вы успешно создали новость!";
				$this->data['id'] = $newsid;
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
		$text = @$this->request->post['text'];
		
		if(mb_strlen($name) < 6 || mb_strlen($name) > 100) {
			$result = "Название новости должно содержать от 2 до 100 символов!";
		}
		elseif(mb_strlen($text) < 10 || mb_strlen($text) > 1000) {
			$result = "Текст новости должен содержать от 2 до 1000 символов!";
		}
		return $result;
	}
}
