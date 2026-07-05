<?php
/**
 * Dovhopol Mykola Ivanovich (iTeffa)
 * Telegram: https://t.me/iteffa
 * Phone: +380966349498
 * Email: flowaxy.dev@gmail.com
 * Website: https://flowaxy.com/
 *
 * Загрузка view-шаблонов с поддержкой hooks/filters плагинов.
 *
 * Created: 2020
 * Modified: 2026-07-06
 *
 * © 2026 Flowaxy Digital Studio. All rights reserved.
 */
class Load {
	private $registry;

	public function __construct($registry) {
		$this->registry = $registry;
	}
	
	public function view($name, $vars = array()){
		$file = apply_filters('hostin.view.path', APPLICATION_DIR . 'views/' . $name . '.php', $name);
		$vars = apply_filters('hostin.view.data', $vars, $name);
		if(is_readable($file)){
			extract($vars);
			
	  		ob_start();
	  		include($file);
	  		$content = ob_get_contents();
	  		ob_end_clean();
			
	  		return $content;
		}
		$error = 'Ошибка: Не удалось загрузить шаблон ' . $name . '!';
		require_once("application/views/main/error.php");
		exit();
	}
	
	public function model($name){
		$modelClass = $name . 'Model';
		$modelPath = APPLICATION_DIR . 'models/' . $name . '.php';
		
		if(is_readable($modelPath)){
			require_once($modelPath);
			if(class_exists($modelClass)){
				$this->registry->$modelClass = new $modelClass($this->registry);
				return true;
			}
		}
		$error = 'Ошибка: Не удалось загрузить модель ' . $name . '!';
		require_once("application/views/main/error.php");
		exit();
	}
	
	public function library($name){
		$libClass = $name . 'Library';
		$libPath = ENGINE_DIR . 'libs/' . $name . '.php';
		
		if(is_readable($libPath)){
			require_once($libPath);
			return true;
		}
		$error = 'Ошибка: Не удалось загрузить библиотеку ' . $name . '!';
		require_once("application/views/main/error.php");
		exit();
	}
	
	public function genpass($length) {
		return substr(md5(microtime() . rand(0, 9999)), 0, $length);
	}

}
?>
