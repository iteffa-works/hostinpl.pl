<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class Game_settings {
	private $data = array();
	
	public function __construct() {
		if(is_readable(ENGINE_DIR . 'games/game_settings.php')) {
			require_once(ENGINE_DIR . 'games/game_settings.php');
			$this->data = array_merge($this->data, $game_settings);
			return true;
		}
		exit('Ошибка: Не удалось загрузить файл конфигурации игр!');
	}
	
	public function __get($key){
		if(isset($this->data[$key])){
			return $this->data[$key];
		}
		return false;
	}
}
?>
