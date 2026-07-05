<?php
/**
 * Dovhopol Mykola Ivanovich (iTeffa)
 * Telegram: https://t.me/iteffa
 * Phone: +380966349498
 * Email: flowaxy.dev@gmail.com
 * Website: https://flowaxy.com/
 *
 * Маршрутизация /admin/plugin/{id}/... к контроллерам плагинов.
 *
 * Created: 2026-07-02
 * Modified: 2026-07-06
 *
 * © 2026 Flowaxy Digital Studio. All rights reserved.
 */

class PluginRouter {
	private static $route = null;

	public static function parse($action) {
		self::$route = null;

		$action = trim((string)$action, '/');
		if($action === '') {
			return;
		}

		if(!preg_match('#^admin/plugin/([^/]+)(?:/(.*))?$#', $action, $matches)) {
			return;
		}

		$pluginId = Plugin::sanitize_id($matches[1]);
		if($pluginId === '' || !Plugin::is_active($pluginId)) {
			return;
		}

		$remainder = isset($matches[2]) ? trim($matches[2], '/') : '';
		$parts = $remainder === '' ? array() : explode('/', $remainder);

		$controller = 'index';
		$method = 'index';
		$args = array();

		if(!empty($parts[0])) {
			$controller = preg_replace('/[^\\w\\d]/', '', $parts[0]);
			array_shift($parts);
		}

		if(!empty($parts[0])) {
			$method = preg_replace('/[^\\w\\d]/', '', $parts[0]);
			array_shift($parts);
		}

		if(!empty($parts)) {
			$args = $parts;
		}

		if($controller === '') {
			$controller = 'index';
		}
		if($method === '') {
			$method = 'index';
		}

		self::$route = array(
			'plugin_id' => $pluginId,
			'controller' => $controller,
			'method' => $method,
			'args' => $args
		);
	}

	public static function has_route() {
		return is_array(self::$route);
	}

	public static function get_route() {
		return self::$route;
	}

	public static function dispatch($registry) {
		if(!self::has_route()) {
			return null;
		}

		$route = self::$route;
		$plugin = Plugin::get($route['plugin_id']);
		if(!$plugin) {
			self::show_error('Плагин не найден.');
		}

		$user = $registry->user;
		$requiredAccess = apply_filters('hostin.plugin.route.access', 3, $route['plugin_id'], $route);

		if(!$user->isLogged()) {
			$registry->session->data['error'] = 'Вы не авторизированы!';
			$registry->response->redirect($registry->config->url . 'account/login');
		}

		if($user->getAccessLevel() < $requiredAccess) {
			$registry->session->data['error'] = 'У вас нет доступа к данному разделу!';
			$registry->response->redirect($registry->config->url);
		}

		$controllerFile = $plugin['path'] . 'controllers/' . $route['controller'] . '.php';
		if(!is_readable($controllerFile)) {
			self::show_error('Не удалось загрузить контроллер плагина.');
		}

		require_once($controllerFile);

		$controllerClass = $route['controller'] . 'Controller';
		if(!class_exists($controllerClass)) {
			self::show_error('Не удалось загрузить контроллер плагина.');
		}

		$controller = new $controllerClass($registry);
		$method = $route['method'];

		if(!is_callable(array($controller, $method))) {
			$method = 'index';
		}

		do_action('hostin_plugin_controller_before', $route, $controller, $method);

		if(empty($route['args'])) {
			$output = call_user_func(array($controller, $method));
		} else {
			$output = call_user_func_array(array($controller, $method), $route['args']);
		}

		do_action('hostin_plugin_controller_after', $route, $controller, $method, $output);

		return $output;
	}

	private static function show_error($message) {
		$error = 'Ошибка: ' . $message;
		require_once(APPLICATION_DIR . 'views/main/error.php');
		exit();
	}
}

?>
