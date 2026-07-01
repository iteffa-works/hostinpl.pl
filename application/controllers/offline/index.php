<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class indexController extends Controller {
	public function index() {
		$this->data['result'] = $this->request->get['result'];
        $this->data['public'] = $this->config->public;
		
		return $this->load->view('offline/index', $this->data);
	}
}
?>