<?php
/**
 * Dovhopol Mykola Ivanovich (iTeffa)
 * Telegram: https://t.me/iteffa
 * Phone: +380966349498
 * Email: flowaxy.dev@gmail.com
 * Website: https://flowaxy.com/
 *
 * Инициализация hooks/filters плагина Site Landing.
 *
 * Created: 2026-07-02
 * Modified: 2026-07-06
 *
 * © 2026 Flowaxy Digital Studio. All rights reserved.
 */

require_once __DIR__ . '/sitelandingPlugin.php';

add_filter('hostin.main.guest_view', function($view) {
	return 'plugin/site-landing/landing';
});

add_filter('hostin.view.path', function($file, $name) {
	if(strpos($name, 'plugin/site-landing/') === 0) {
		$viewName = substr($name, strlen('plugin/site-landing/'));
		return PLUGINS_DIR . 'site-landing/views/' . $viewName . '.php';
	}
	return $file;
}, 10, 2);

?>
