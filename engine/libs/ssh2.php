<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class ssh2Library {
	public function connect($hostname, $username, $password) {
		if($link = ssh2_connect($hostname, 22)) {
			if(ssh2_auth_password($link, $username, $password)) {
				return $link;
			}
		}
		return false;
	}
			
	public function execute($link, $cmd)
	{
		$stream = ssh2_exec($link, $cmd);
		stream_set_blocking($stream, true);
		$output = '';
		while ($get = fgets($stream)) {
			$output .= $get;
		}
		fclose($stream);
		return $output;
	}
	
	function get($link, $cmd) {		
		if($cmd) {
			$this->stream = ssh2_exec($link, $cmd);
			stream_set_blocking($this->stream, true);
		}
		$line = '';
		while($get = fgets($this->stream))
		$line.= $get;
		return $line;
	}
	
	public function disconnect($link) {
		ssh2_exec($link, "exit");
	}
}
?>
