<?php
/**
 * Dovhopol Mykola Ivanovich (iTeffa)
 * Telegram: https://t.me/iteffa
 * Phone: +380966349498
 * Email: flowaxy.dev@gmail.com
 * Website: https://flowaxy.com/
 *
 * Админ-контроллер управления плагинами (install/activate/disable/uninstall).
 *
 * Created: 2026-07-02
 * Modified: 2026-07-06
 *
 * © 2026 Flowaxy Digital Studio. All rights reserved.
 */
class indexController extends Controller {
	public function index() {
		$this->document->setActiveSection('admin/plugins');
		$this->document->setActiveItem('index');

		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 3) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}

		$this->data['plugins'] = Plugin::$plugins;

		$this->getChild(array('common/admheader', 'common/footer'));
		return $this->load->view('admin/plugins/index', $this->data);
	}

	public function install($pluginId = null) {
		return $this->runLifecycle($pluginId, 'install', 0, 'Плагин успешно установлен.');
	}

	public function activate($pluginId = null) {
		return $this->runLifecycle($pluginId, 'activate', 1, 'Плагин успешно активирован.');
	}

	public function disable($pluginId = null) {
		return $this->runLifecycle($pluginId, 'disable', 0, 'Плагин успешно отключен.');
	}

	public function uninstall($pluginId = null) {
		return $this->runLifecycle($pluginId, 'uninstall', -1, 'Плагин успешно удален.');
	}

	private function runLifecycle($pluginId, $action, $newStatus, $successMessage) {
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 3) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}

		$pluginId = Plugin::sanitize_id($pluginId);
		$plugin = Plugin::get($pluginId);
		if(!$plugin) {
			$this->session->data['error'] = "Плагин не найден.";
			$this->response->redirect($this->config->url . 'admin/plugins');
		}

		if($action === 'install' && !Plugin::is_uninstalled($pluginId)) {
			$this->session->data['error'] = "Плагин уже установлен.";
			$this->response->redirect($this->config->url . 'admin/plugins');
		}
		if($action === 'activate' && !Plugin::is_installed($pluginId)) {
			$this->session->data['error'] = "Плагин должен быть установлен.";
			$this->response->redirect($this->config->url . 'admin/plugins');
		}
		if($action === 'disable' && !Plugin::is_active($pluginId)) {
			$this->session->data['error'] = "Плагин не активен.";
			$this->response->redirect($this->config->url . 'admin/plugins');
		}
		if($action === 'uninstall' && Plugin::is_uninstalled($pluginId)) {
			$this->session->data['error'] = "Плагин уже удален.";
			$this->response->redirect($this->config->url . 'admin/plugins');
		}

		if(!is_writable($plugin['path'])) {
			$this->session->data['error'] = "Каталог плагина недоступен для записи.";
			$this->response->redirect($this->config->url . 'admin/plugins');
		}

		$initFile = $plugin['path'] . 'init.php';
		if(is_readable($initFile)) {
			require_once $initFile;
		}

		$className = Plugin::load_lifecycle_class($pluginId);
		if(!$className || !is_callable(array($className, $action))) {
			$this->session->data['error'] = "Не удалось выполнить операцию с плагином.";
			$this->response->redirect($this->config->url . 'admin/plugins');
		}

		call_user_func(array($className, $action));

		if(!Plugin::save_status($pluginId, $newStatus)) {
			$this->session->data['error'] = "Не удалось сохранить статус плагина.";
			$this->response->redirect($this->config->url . 'admin/plugins');
		}

		$this->session->data['success'] = $successMessage;

		if($action === 'activate' && !empty($plugin['settings_url'])) {
			$this->response->redirect($plugin['settings_url']);
		}

		$this->response->redirect($this->config->url . 'admin/plugins');
	}
}
?>
