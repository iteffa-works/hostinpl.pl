<?php
/**
 * Dovhopol Mykola Ivanovich (iTeffa)
 * Telegram: https://t.me/iteffa
 * Phone: +380966349498
 * Email: flowaxy.dev@gmail.com
 * Website: https://flowaxy.com/
 *
 * Главная страница; guest view через filter hostin.main.guest_view.
 *
 * Created: 2020
 * Modified: 2026-07-06
 *
 * © 2026 Flowaxy Digital Studio. All rights reserved.
 */
class indexController extends Controller {
	private $limit = 5;
	public function index($page = 1) {
		$this->document->setActiveSection('main');
		$this->document->setActiveItem('index');
		$this->data['public'] = $this->config->public;
		
		if(!$this->user->isLogged()) {
			$this->data['title'] = $this->config->title;
			$this->data['description'] = $this->config->description;
			$this->data['logo'] = $this->config->logo;
			$view = apply_filters('hostin.main.guest_view', 'main/landing');
			return $this->load->view($view, $this->data);
		}
		if($this->user->getAccessLevel() < 0) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}

		$this->load->library('pagination');
		$this->load->model('news');
		
		$sort = array(
			'news_date_add'	=> 'DESC'
		);
		
		$options = array(
			'start'		=>	($page - 1) * $this->limit,
			'limit'		=>	$this->limit
		);
		
		$total = $this->newsModel->getTotalNews();
		$news = $this->newsModel->getNews(array(), array(), $sort, $options);			
		
		$paginationLib = new paginationLibrary();
		
		$paginationLib->total = $total;
		$paginationLib->page = $page;
		$paginationLib->limit = $this->limit;
		$paginationLib->url = $this->config->url . 'news/index/index/{page}';
		
		$pagination = $paginationLib->render();
		$this->data['news'] = $news;
		$this->data['pagination'] = $pagination;
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('main/index', $this->data);
	}
	
	public function getstatus($action) {
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
		
		$userid = $this->user->getId();
		
		$this->load->model('users');

		switch($action) {
			case 'online': {				
				$LastTime = time() - 105;

				$online = 0;
				foreach($this->usersModel->getUsers() as $line) {
					if ($line['user_online_date'] > $LastTime) {
					  $online++;
					}
				}

				$this->usersModel->updateUser($userid, array('user_online_date'=> time()));
				$this->data['status'] = "online";
				$this->data['online'] = "USERID ".$userid." ".$this->user->getFirstname()." UPD-DATE ".date("Y-m-d H:i:s")." | ".$LastTime." LVL ".$this->user->getAccessLevel()." ST.OK";
				$this->data['online_usr'] = $online;
				break;
			}	
			case 'online_sup': {				
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
					$this->data['sup_status'] = "Online";
				} else {
					$this->data['sup_status'] = "Offline";
				}
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
}
?>