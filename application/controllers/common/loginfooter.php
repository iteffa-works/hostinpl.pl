<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class loginfooterController extends Controller {
	public function index() {
		return $this->load->view('common/loginfooter', $this->data);
	}
}
?>
