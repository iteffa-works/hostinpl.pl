<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class Action {
	private $registry;

	private $folder;
	private $controller;
	private $method;
	private $args;
	
	public function __construct($registry)
	{
		$this->registry = $registry;
	}
	
	public function make($action) {
		$this->folder = null;
		$this->controller = null;
		$this->method = null;
		$this->args = null;
		
		$action = preg_replace('/[^\\w\\d\\s\\/]/', '', $action);
		$parts = explode('/', $action);
		$parts = array_filter($parts);
		
		foreach($parts as $item) {
			$fullpath = APPLICATION_DIR . 'controllers/' . $this->folder . '/' . $item;
			if(is_dir($fullpath)) {
				$this->folder .= '/' . $item;
				array_shift($parts);
				continue;
			}
			
			if (is_file($fullpath . '.php')) {
				$this->controller = $item;
				array_shift($parts);
				break;
			} else break;
		}
		
		if(empty($this->folder)) {
			$this->folder = 'errore';
		}

		if(empty($this->controller)) {
			$this->controller = 'index';
		}
		
		if($c = array_shift($parts)) {
			$this->method = $c;
		} else {
			$this->method = 'index';
		}
		
		if(isset($parts[0])) {
			$this->args = $parts;
		}
	}
	
	public function go($common_show = false) {
		if($common_show == false) {
			if($this->folder == '/common') {
				exit('Нет доступа.');	
			}
		}
		
		$controllerFile = APPLICATION_DIR . 'controllers/' . $this->folder . '/' . $this->controller . '.php';
		$controllerClass = $this->controller . 'Controller';
		
		if(is_readable($controllerFile)) {
			require_once($controllerFile);
				
			$controller = new $controllerClass($this->registry);
				
			if(is_callable(array($controller, $this->method))) {
				$this->method = $this->method;
			} else {
				$this->method = 'index';
			}
				
			if(empty($this->args)) {
				return call_user_func(array($controller, $this->method));
			} else {
				return call_user_func_array(array($controller, $this->method), $this->args);
			}
		}
		$error = 'Ошибка: Не удалось загрузить контроллер ' . $this->controller . '!';
		require_once("application/views/main/error.php");
		exit();	
	}
}
?>
