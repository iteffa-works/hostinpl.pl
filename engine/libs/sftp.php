<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class sftpLibrary {
	public function connect($hostname, $username, $password) {
		if(is_readable(ENGINE_DIR . 'libs/seclib/Net/SFTP.php')) {
			set_include_path(ENGINE_DIR . 'libs/seclib');
			require_once(ENGINE_DIR . 'libs/seclib/Net/SFTP.php'); 
		} else {
			exit("Ошибка: Не удалось загрузить библиотеку для работы с sftp!");
		}
			
		$sftp = new Net_SFTP($hostname);
		if ($sftp->login($username, $password)) {
			return $sftp;
		}
		return 'Ошибка: Не удалось соединиться с сервером!';
	}
		
	public function open($sftp, $command) {
		return $sftp->get($command);
	}

	public function write($sftp, $command, $file) {
		return $sftp->put($command, $file);
	}

	public function files($sftp, $path) {
		return @$sftp->nlist($path);
	}
		
	public function get($sftp, $path, $fun) {
		return @$sftp->$fun($path);
	}
}
?>
