<?php
mb_internal_encoding("UTF-8");
date_default_timezone_set("Europe/Moscow");
set_time_limit(0);

ini_set('memory_limit', '512M');

if(!defined('STDIN')) die("Access Denied" . PHP_EOL);

if(empty($argv[1])) {
	die("Не задана задача." . PHP_EOL);
}

define('TMP_DIR', dirname(__FILE__) . '/tmp/');
define('ENGINE_DIR', dirname(__FILE__) . '/engine/');
define('APPLICATION_DIR', dirname(__FILE__) . '/application/');

require_once(ENGINE_DIR . 'main/controller.php');
require_once(ENGINE_DIR . 'main/model.php');
require_once(ENGINE_DIR . 'main/registry.php');
require_once(ENGINE_DIR . 'main/config.php');
require_once(ENGINE_DIR . 'main/game_settings.php');
require_once(ENGINE_DIR . 'main/response.php');
require_once(ENGINE_DIR . 'main/db.php');
require_once(ENGINE_DIR . 'main/load.php');
require_once(ENGINE_DIR . 'main/action.php');

$registry = new Registry();

$config = new Config();
$registry->config = $config;

$game_settings = new Game_settings();
$registry->game_settings = $game_settings;

$response = new Response();
$registry->response = $response;

$db = new DB($config->db_type, $config->db_hostname, $config->db_username, $config->db_password, $config->db_database);
$registry->db = $db;

$load = new Load($registry);
$registry->load = $load;

$action = new Action($registry);
$registry->action = $action;

$action->make('main/cron/' . $argv[1]);
$response->output($action->go());
?>