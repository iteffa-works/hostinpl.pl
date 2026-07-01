<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class tasksController extends Controller {
	public function index($serverid = null) {
		$this->document->setActiveSection('servers');
		$this->document->setActiveItem('tasks');
		$this->data['activesection'] = $this->document->getActiveSection();
		$this->data['activeitem'] = $this->document->getActiveItem();

		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 0) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}	
		
		$this->load->model('servers');
		
		$error = $this->validate($serverid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'servers/index');
		}
		
		$server = $this->serversModel->getServerById($serverid, array('games', 'locations'));
		$this->data['server'] = $server;
		
		$tasks = $this->serversModel->getTasks(array("server_id" => $serverid), array(), array("task_id" => "DESC"));
		$this->data["tasks"] = $tasks;

		include_once 'application/controllers/common/main.php';
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('servers/tasks', $this->data);
	}

	public function createTask($serverid = null) {
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'news/index');
		}
		if($this->user->getAccessLevel() < 0) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}

		$this->load->model('servers');
		
		$server = $this->serversModel->getServerById($serverid, array('games', 'locations'));
		
		$error = $this->validate($serverid);
		if($error) {
			$this->data['status'] = "error";
			$this->data['error'] = $error;
			return json_encode($this->data);
		}	
		
		if($server['server_status'] == 3) {
			$this->data["status"] = "error";
			$this->data["error"] = "Идет установка сервера!";
			return json_encode($this->data);
		} else if($server['server_status'] == 4) {
			$this->data["status"] = "error";
			$this->data["error"] = "Идет переустановка сервера!";
			return json_encode($this->data);
		} else if($server['server_status'] == 5) {
			$this->data["status"] = "error";
			$this->data["error"] = "Идет создание BackUP сервера!";
			return json_encode($this->data);
		} else if($server['server_status'] == 6) {
			$this->data["status"] = "error";
			$this->data["error"] = "Идет восстанновление сервера из BackUP!";
			return json_encode($this->data);
		} else if($server['server_status'] == 7) {
			$this->data["status"] = "error";
			$this->data["error"] = "Идет обновление сервера!";
			return json_encode($this->data);
		} else if($server["server_status"] == 0) {
			$this->data["status"] = "error";
			$this->data["error"] = "Сервер заблокирован!";
			return json_encode($this->data);
		}

		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$tasks = $this->serversModel->getTasks(array("server_id" => $serverid));
			if(count($tasks) < 4) {
				$task = @$this->request->post['task'];
				$type = @$this->request->post['type'];
				$time = @$this->request->post['time'];
				if(!in_array($task, array("enable", "disable", "restart"))) {
					$this->data["status"] = "error";
					$this->data["error"] = "Вы указали несуществующую задачу!";
				} else if(($task == 'enable' && $server['server_status'] != 1) || ($task == 'diable' && $server['server_status'] != 2) || ($task == 'restart' && $server['server_status'] != 2)) {
					$this->data["status"] = "error";
					$this->data["error"] = "Вы не можете создать данную задачу!";
				} else if(!empty($this->serversModel->getTasks(array("server_id" => $serverid, "task_name" => $task)))) {
					$this->data["status"] = "error";
					$this->data["error"] = "Данная задача уже создана!";
				} else if(!in_array($type, array('repeating', 'single'))) {
					$this->data["status"] = "error";
					$this->data["error"] = "Вы указали несуществующий тип задачи!";
				} else if($task == 'repeating' && ($task == 'enable' || $task == 'diable')) {
					$this->data["status"] = "error";
					$this->data["error"] = "Данный тип задачи недоступен для задачи!";
				} else if(!in_array($time, array("15min", "30min", "1hour", "12hours", "24hours"))) {
					$this->data["status"] = "error";
					$this->data["error"] = "Вы указали недопустимое время выполнения!";
				} else {
					if($time == '15min') {
						$lead_time = "NOW() + INTERVAL 15 MINUTE";
					} else if($time == '30min') {
						$lead_time = "NOW() + INTERVAL 30 MINUTE";
					} else if($time == '1hour') {
						$lead_time = "NOW() + INTERVAL 1 HOUR";
					} else if($time == '12hours') {
						$lead_time = "NOW() + INTERVAL 12 HOUR";
					} else if($time == '24hours') {
						$lead_time = "NOW() + INTERVAL 24 HOUR";
					}
					$task = $this->serversModel->getTaskById($this->serversModel->createTask(array("server_id" => $serverid, "task_name" => $task, "task_type" => $type, "task_time" => $time, "task_lead_time" => $lead_time)));
								
					$this->data['task'] = array("task_id" => $task['task_id'], "task_name" => $task['task_name'], "task_type" => $task['task_type'], "task_time" => $task['task_time'], "task_lead_time" => date("d.m.Y в H:i", strtotime($task['task_lead_time'])));
							
					$this->data["status"] = "success";
					$this->data["success"] = "Задача успешно создана!";
				}
			} else {
				$this->data["status"] = "error";
				$this->data["error"] = "Вы не можете создавать больше 4-х задач!";
			}			
		}
		return json_encode($this->data);
	}	
	
	public function deleteTask($serverid = null) {
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'news/index');
		}
		if($this->user->getAccessLevel() < 0) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}

		$this->load->model('servers');
		
		$server = $this->serversModel->getServerById($serverid, array('games', 'locations'));
		
		$error = $this->validate($serverid);
		if($error) {
			$this->data['status'] = "error";
			$this->data['error'] = $error;
			return json_encode($this->data);
		}	
		
		if($server['server_status'] == 3) {
			$this->data["status"] = "error";
			$this->data["error"] = "Идет установка сервера!";
			return json_encode($this->data);
		} else if($server['server_status'] == 4) {
			$this->data["status"] = "error";
			$this->data["error"] = "Идет переустановка сервера!";
			return json_encode($this->data);
		} else if($server['server_status'] == 5) {
			$this->data["status"] = "error";
			$this->data["error"] = "Идет создание BackUP сервера!";
			return json_encode($this->data);
		} else if($server['server_status'] == 6) {
			$this->data["status"] = "error";
			$this->data["error"] = "Идет восстанновление сервера из BackUP!";
			return json_encode($this->data);
		} else if($server['server_status'] == 7) {
			$this->data["status"] = "error";
			$this->data["error"] = "Идет обновление сервера!";
			return json_encode($this->data);
		} else if($server["server_status"] == 0) {
			$this->data["status"] = "error";
			$this->data["error"] = "Сервер заблокирован!";
			return json_encode($this->data);
		}

		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$task_id = @$this->request->post['task_id'];
			$task = $this->serversModel->getTaskById($serverid);	
			if($task['server_id'] == $server_id) {
				$this->serversModel->deleteTask(array('task_id' => $task_id));
				$this->data["status"] = "success";
				$this->data["success"] = "Задача успешно удалена!";
			} else {
				$this->data["status"] = "error";
				$this->data["error"] = "Задача не найдена!";
			}			
		}
		return json_encode($this->data);
	}	
	
	
	private function validate($serverid) {
		$result = null;
		
		$userid = $this->user->getId();
		
		if(!$this->serversModel->getTotalServerOwners(array('server_id' => (int)$serverid, 'user_id' => (int)$userid, 'owner_status' => 1))) {
			if(!$this->serversModel->getTotalServers(array('server_id' => (int)$serverid, 'user_id' => (int)$userid))) {
				$result = "Запрашиваемый сервер не существует!";
			}
		}
		return $result;
	}
}
?>
