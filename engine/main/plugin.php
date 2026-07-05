<?php
/**
 * Dovhopol Mykola Ivanovich (iTeffa)
 * Telegram: https://t.me/iteffa
 * Phone: +380966349498
 * Email: flowaxy.dev@gmail.com
 * Website: https://flowaxy.com/
 *
 * Загрузчик плагинов и управление lifecycle.
 *
 * Created: 2026-07-02
 * Modified: 2026-07-06
 *
 * © 2026 Flowaxy Digital Studio. All rights reserved.
 */

class Plugin {
	public static $plugins = array();

	public static function initialize() {
		$registryFile = APPLICATION_DIR . 'includes/plugins.php';
		if(!is_readable($registryFile)) {
			return;
		}

		$pluginIds = require $registryFile;
		if(!is_array($pluginIds)) {
			return;
		}

		foreach($pluginIds as $pluginId) {
			self::register($pluginId);
		}
	}

	public static function register($pluginId) {
		$pluginId = self::sanitize_id($pluginId);
		if($pluginId === '') {
			return;
		}

		$pluginDirectory = PLUGINS_DIR . $pluginId . '/';
		if(!is_dir($pluginDirectory)) {
			return;
		}

		$configFile = $pluginDirectory . 'config.php';
		if(!is_readable($configFile)) {
			return;
		}

		$config = require $configFile;
		if(!is_array($config)) {
			return;
		}

		if(empty($config['plugin_id']) || empty($config['name'])) {
			return;
		}

		if(!isset($config['description'])) {
			$config['description'] = '';
		}
		if(!isset($config['version'])) {
			$config['version'] = '1.0.0';
		}
		if(!isset($config['author'])) {
			$config['author'] = '';
		}
		if(!isset($config['status'])) {
			$config['status'] = -1;
		}

		$settingsFile = $pluginDirectory . 'settings.json';
		if(is_readable($settingsFile)) {
			$settings = json_decode(file_get_contents($settingsFile), true);
			if(is_array($settings) && array_key_exists('status', $settings)) {
				$config['status'] = $settings['status'];
				$config['settings'] = $settings;
			}
		}

		$config['path'] = $pluginDirectory;
		self::$plugins[$config['plugin_id']] = $config;

		if(self::is_active($config['plugin_id'])) {
			$initFile = $pluginDirectory . 'init.php';
			if(is_readable($initFile)) {
				require_once $initFile;
			}
		}
	}

	public static function sanitize_id($pluginId) {
		return preg_replace('/[^A-Za-z0-9_-]/', '', (string)$pluginId);
	}

	public static function get_class_name($pluginId) {
		return preg_replace('/[^A-Za-z0-9]/', '', (string)$pluginId) . 'Plugin';
	}

	public static function get($pluginId) {
		$pluginId = self::sanitize_id($pluginId);
		return isset(self::$plugins[$pluginId]) ? self::$plugins[$pluginId] : null;
	}

	public static function is_active($pluginId) {
		$plugin = self::get($pluginId);
		if(!$plugin) {
			return false;
		}
		return ($plugin['status'] === 1 || $plugin['status'] === 'active');
	}

	public static function is_installed($pluginId) {
		$plugin = self::get($pluginId);
		if(!$plugin) {
			return false;
		}
		return ($plugin['status'] === 0 || $plugin['status'] === 'installed');
	}

	public static function is_uninstalled($pluginId) {
		$plugin = self::get($pluginId);
		if(!$plugin) {
			return false;
		}
		return ($plugin['status'] === -1 || $plugin['status'] === 'uninstalled');
	}

	public static function save_status($pluginId, $newStatus) {
		$plugin = self::get($pluginId);
		if(!$plugin) {
			return false;
		}

		$settings = array();
		if(!empty($plugin['settings']) && is_array($plugin['settings'])) {
			$settings = $plugin['settings'];
		}
		$settings['status'] = $newStatus;

		$settingsFile = $plugin['path'] . 'settings.json';
		$saved = file_put_contents($settingsFile, json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
		if($saved === false) {
			return false;
		}

		self::$plugins[$pluginId]['status'] = $newStatus;
		self::$plugins[$pluginId]['settings'] = $settings;

		return true;
	}

	public static function load_lifecycle_class($pluginId) {
		$plugin = self::get($pluginId);
		if(!$plugin) {
			return false;
		}

		$className = self::get_class_name($pluginId);
		if(class_exists($className)) {
			return $className;
		}

		$classFile = $plugin['path'] . $className . '.php';
		if(!is_readable($classFile)) {
			return false;
		}

		require_once $classFile;
		if(!class_exists($className)) {
			return false;
		}

		return $className;
	}
}

?>
