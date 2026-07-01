<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class Response {
	private $headers = array();
	
	public function addHeader($header) {
		$this->headersarray[] = $header;
	}

	public function redirect($url) {
		header('Location: ' . $url);
		exit;
	}
	
	public function output($content) {
		if ($content) {
			if (!headers_sent()) {
				foreach($this->headers as $header) {
					header($header, true);
				}
			}
			echo $content;
		}
	}
}
?>
