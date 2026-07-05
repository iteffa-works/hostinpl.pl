<?php
/**
 * Dovhopol Mykola Ivanovich (iTeffa)
 * Telegram: https://t.me/iteffa
 * Phone: +380966349498
 * Email: flowaxy.dev@gmail.com
 * Website: https://flowaxy.com/
 *
 * Админ-контроллер настроек плагина Site Landing.
 *
 * Created: 2026-07-02
 * Modified: 2026-07-06
 *
 * © 2026 Flowaxy Digital Studio. All rights reserved.
 */

class settingsController extends Controller {
	public function index() {
		$this->document->setActiveSection('admin/plugin/site-landing');
		$this->document->setActiveItem('settings');

		$this->data['url'] = $this->config->url;

		$this->getChild(array('common/admheader', 'common/footer'));
		return $this->load->view('plugin/site-landing/settings', $this->data);
	}
}

?>
