<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class ftpController extends Controller {
	public function index($serverid = null) {
		$this->document->setActiveSection('servers');
		$this->document->setActiveItem('ftp');
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
		
		if(isset($_COOKIE["data-theme-ftp"])){
			$this->data['theme'] = $_COOKIE['data-theme-ftp'];
		} else{
			$this->data['theme'] = "/application/public/css/elfinderT/default/css/theme.css";
		}
		
		include_once 'application/controllers/common/main.php';
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('servers/ftp', $this->data);
	}

	public function ajax($serverid = null) {
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
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$editpassword = @$this->request->post['editpassword'];
				$password = @$this->request->post['password'];
				if($editpassword) {
					$this->serversModel->updateServer($serverid, array('server_password' => $password));	
					$result = $this->serversModel->action($serverid, "updatepass");
					if($result["status"] == "success") {
						$this->data['status'] = "success";
						$this->data['success'] = "Пароль FTP успешно изменен!!";
					} else {
						$this->data["status"] = "error";
						$this->data["error"] = $result["description"];
					}
				} else {
					$this->data['status'] = "error";
					$this->data['error'] = "Ошибка!";
				}
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = $errorPOST;
			}
		}
		return json_encode($this->data);
	}
	
	public function action_theme_ftp($action = null) {
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
		
		$this->load->model('servers');

		switch($action) {
			case 'default': {	
					$name = '/application/public/css/elfinderT/default/css/theme.css';
					setcookie('data-theme-ftp',$name,time() + (86400 * 5), '/' );
					$this->data['status'] = "success";
					$this->data['success'] = "Тема Default успешно установлена!";
				break;
			}
			case 'material': {	
					$name = '/application/public/css/elfinderT/Material/css/theme.css';
					setcookie('data-theme-ftp',$name,time() + (86400 * 5), '/' );
					$this->data['status'] = "success";
					$this->data['success'] = "Тема Material успешно установлена!";
				break;
			}
			case 'materialgray': {	
					$name = '/application/public/css/elfinderT/Material/css/theme-gray.css';
					setcookie('data-theme-ftp',$name,time() + (86400 * 5), '/' );
					$this->data['status'] = "success";
					$this->data['success'] = "Тема Material gray успешно установлена!";
				break;
			}
			case 'materiallight': {	
					$name = '/application/public/css/elfinderT/Material/css/theme-light.css';
					setcookie('data-theme-ftp',$name,time() + (86400 * 5), '/' );
					$this->data['status'] = "success";
					$this->data['success'] = "Тема Material light успешно установлена!";
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
	
	public function getftp($serverid = null) {
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
  
		include_once ENGINE_DIR . 'engine_ftp/elFinderConnector.class.php';
		include_once ENGINE_DIR . 'engine_ftp/elFinder.class.php';
		include_once ENGINE_DIR . 'engine_ftp/elFinderVolumeDriver.class.php';
		include_once ENGINE_DIR . 'engine_ftp/elFinderVolumeLocalFileSystem.class.php';
		include_once ENGINE_DIR . 'engine_ftp/elFinderVolumeFTP.class.php';
 
		function access($attr, $path, $data, $volume) {
			return strpos(basename($path), '.') === 0  
				? !($attr == 'read' || $attr == 'write')    
				:  null;  
		}
		
		function logger($cmd, $result, $args, $elfinder) {
			$log = sprintf('[%s] %s:', date('r'), strtoupper($cmd));
			foreach ($result as $key => $value) {
				if (empty($value)) {
					continue;
				}
				$data = array();
				if (in_array($key, array('error', 'warning'))) {
					array_push($data, implode(' ', $value));
				} else {
					if (is_array($value)) { 
						foreach ($value as $file) {
							$filepath = (isset($file['realpath']) ? $file['realpath'] : $elfinder->realpath($file['hash']));
							array_push($data, $filepath);
						}
					} else { 
						array_push($data, $value);
					}
				}
				$log .= sprintf(' %s(%s)', $key, implode(', ', $data));
			}
			$log .= "\n";

			$logfile = 'files/log.txt';
			$dir = dirname($logfile);
			if (!is_dir($dir) && !mkdir($dir)) {
				return;
			}
			if (($fp = fopen($logfile, 'a'))) {
				fwrite($fp, $log);
				fclose($fp);
			}
		}
		
		$opts = array(
			'roots' => array(
				array(
					'driver' => 'FTP',
					'URL' => 'ftp://gs'.$server['server_id'].':'.$server['server_password'].'@'.$server['location_ip2'].':21/',
					'host'   => $server['location_ip'],
					'user'   => 'gs'.$server['server_id'],
					'pass'   => $server['server_password'],
					'port'   => 21,  
					'mode'   => 'passive',
					'path'   => '/',
					'read' => true,
					'write' => true,
					'utf8fix' => true,
					'bind' => array(
						'mkdir mkfile rename duplicate upload rm paste' => 'logger'
					),
					'rootAlias'=>'Server Root',
					'fileURL'=>True,
					'dotFiles'=>True,
					'dirSize'=>True,
					'fileMode'=>0644,
					'dirMode'=>0775,
					'imgLib'=>False,
					'tmbDir'=>'.tmb',
					'tmbAtOnce'=>5,
					'uploadMaxSize' => '100M',
					'debug'=>True
				),
				'attributes' => array(
					array(
						'pattern' => '~/\.~',
						'read' => true,
						'write' => true,
						'hidden' => true	
					),
		 		
				)
			)
		);
		$connector = new elFinderConnector(new elFinder($opts));
		$connector->run();
	}

	private function validatePOST() {
		$this->load->library('validate');
		
		$validateLib = new validateLibrary();
		
		$result = null;
		
		$editpassword = @$this->request->post['editpassword'];
		$password = @$this->request->post['password'];
		$password2 = @$this->request->post['password2'];
		if($editpassword) {
			if(!$validateLib->password($password)) {
				$result = "Пароль должен содержать от 6 до 32 латинских букв, цифр и знаков <i>,.!?_-</i>!";
			}
			elseif($password != $password2) {
				$result = "Введенные вами пароли не совпадают!";
			}
		}
		return $result;
	}

}
?>
