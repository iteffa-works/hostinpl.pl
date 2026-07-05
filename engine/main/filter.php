<?php
/**
 * Dovhopol Mykola Ivanovich (iTeffa)
 * Telegram: https://t.me/iteffa
 * Phone: +380966349498
 * Email: flowaxy.dev@gmail.com
 * Website: https://flowaxy.com/
 *
 * Система filters для плагинов.
 *
 * Created: 2026-07-02
 * Modified: 2026-07-06
 *
 * © 2026 Flowaxy Digital Studio. All rights reserved.
 */

if(!isset($GLOBALS['_hostin_filters'])) {
	$GLOBALS['_hostin_filters'] = array();
}

function add_filter($hook, $callback, $priority = 10, $accepted_args = 1) {
	if(!isset($GLOBALS['_hostin_filters'][$hook])) {
		$GLOBALS['_hostin_filters'][$hook] = array();
	}

	$GLOBALS['_hostin_filters'][$hook][] = array(
		'callback' => $callback,
		'priority' => (int)$priority,
		'accepted_args' => (int)$accepted_args
	);
}

function remove_filter($hook, $callback, $priority = 10) {
	if(!isset($GLOBALS['_hostin_filters'][$hook])) {
		return false;
	}

	foreach($GLOBALS['_hostin_filters'][$hook] as $key => $item) {
		if($item['priority'] === (int)$priority && $item['callback'] === $callback) {
			unset($GLOBALS['_hostin_filters'][$hook][$key]);
			return true;
		}
	}

	return false;
}

function apply_filters($hook, $value) {
	if(!isset($GLOBALS['_hostin_filters'][$hook]) || !is_array($GLOBALS['_hostin_filters'][$hook])) {
		return $value;
	}

	$callbacks = $GLOBALS['_hostin_filters'][$hook];
	usort($callbacks, function($a, $b) {
		if($a['priority'] === $b['priority']) {
			return 0;
		}
		return ($a['priority'] < $b['priority']) ? -1 : 1;
	});

	$args = func_get_args();
	array_shift($args);
	$args[0] = $value;

	foreach($callbacks as $item) {
		$callback_args = array_slice($args, 0, $item['accepted_args']);
		$args[0] = call_user_func_array($item['callback'], $callback_args);
	}

	return $args[0];
}

?>
