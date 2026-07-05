<?php
/**
 * Dovhopol Mykola Ivanovich (iTeffa)
 * Telegram: https://t.me/iteffa
 * Phone: +380966349498
 * Email: flowaxy.dev@gmail.com
 * Website: https://flowaxy.com/
 *
 * Маршрутизация HTTP-запросов к контроллерам и плагинам.
 *
 * Created: 2020
 * Modified: 2026-07-06
 *
 * © 2026 Flowaxy Digital Studio. All rights reserved.
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

		$action = apply_filters('hostin_action_before', (string)$action);
		$action = preg_replace('/[^\\w\\d\\s\\/]/', '', $action);
		$action = trim($action, '/');

		PluginRouter::parse($action);
		if(PluginRouter::has_route()) {
			return;
		}

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
		if(PluginRouter::has_route()) {
			return PluginRouter::dispatch($this->registry);
		}

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

			do_action('hostin_controller_before', $this->folder, $this->controller, $this->method, $controller);
				
			if(is_callable(array($controller, $this->method))) {
				$this->method = $this->method;
			} else {
				$this->method = 'index';
			}
				
			if(empty($this->args)) {
				$output = call_user_func(array($controller, $this->method));
			} else {
				$output = call_user_func_array(array($controller, $this->method), $this->args);
			}

			do_action('hostin_controller_after', $this->folder, $this->controller, $this->method, $controller, $output);

			return $output;
		}
		$error = 'Ошибка: Не удалось загрузить контроллер ' . $this->controller . '!';
		require_once("application/views/main/error.php");
		exit();	
	}
}
?>
