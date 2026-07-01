<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class statisticsController extends Controller {
	public function index($locationid = null) {
		$this->document->setActiveSection('admin/locations');
		$this->document->setActiveItem('statistics');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 3) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->model('locations');
		
		$error = $this->validate($locationid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'admin/locations/index');
		}
		
		$location = $this->locationsModel->getLocationById($locationid);
		
		$this->data['location'] = $location;
		
		$loc_stats = $this->locationsModel->getLocationStats($locationid, "NOW() - INTERVAL 31 DAY", "NOW()");
		$this->data['loc_stats'] = $loc_stats;
		
		$this->getChild(array('common/admheader', 'common/footer'));
		return $this->load->view('admin/locations/statistics', $this->data);
	}
	
	private function validate($locationid) {
		$result = null;
		
		if(!$this->locationsModel->getTotalLocations(array('location_id' => (int)$locationid))) {
			$result = "Запрашиваемая локация не существует!";
		}
		return $result;
	}	
}
?>
