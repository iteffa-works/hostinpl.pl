-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Фев 19 2022 г., 19:19
-- Версия сервера: 10.5.13-MariaDB-1:10.5.13+maria~focal
-- Версия PHP: 8.1.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `hpl56`
--

-- --------------------------------------------------------

--
-- Структура таблицы `authlog`
--

CREATE TABLE `authlog` (
  `id` int(11) NOT NULL,
  `user` int(11) DEFAULT NULL,
  `ip` varchar(64) DEFAULT NULL,
  `city` text DEFAULT NULL,
  `country` text DEFAULT NULL,
  `code` text DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `system` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `games`
--

CREATE TABLE `games` (
  `game_id` int(10) NOT NULL,
  `game_name` varchar(92) DEFAULT NULL,
  `game_code` varchar(8) DEFAULT NULL,
  `game_query` varchar(32) DEFAULT NULL,
  `game_min_slots` int(8) DEFAULT NULL,
  `game_max_slots` int(8) DEFAULT NULL,
  `game_min_port` int(8) DEFAULT NULL,
  `game_max_port` int(8) DEFAULT NULL,
  `game_cores` decimal(10,2) NOT NULL DEFAULT 1.00,
  `game_ram` int(64) NOT NULL DEFAULT 1024,
  `game_ssd` varchar(128) NOT NULL DEFAULT '512',
  `game_price` decimal(10,2) DEFAULT NULL,
  `game_status` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `games`
--

INSERT INTO `games` (`game_id`, `game_name`, `game_code`, `game_query`, `game_min_slots`, `game_max_slots`, `game_min_port`, `game_max_port`, `game_cores`, `game_ram`, `game_ssd`, `game_price`, `game_status`) VALUES
(1, 'San Andreas: Multiplayer 0.3.7', 'samp', 'samp', 50, 1000, 7777, 9999, '0.45', 512, '250', '1.00', 0),
(2, 'Criminal Russia: Multiplayer 0.3e', 'crmp', 'samp', 50, 500, 3335, 7000, '0.45', 512, '250', '1.00', 0),
(3, 'Criminal Russia: Multiplayer 0.3.7', 'crmp037', 'samp', 50, 500, 3335, 7000, '0.45', 512, '250', '1.00', 0),
(4, 'United Multiplayer', 'unit', 'samp', 50, 500, 7777, 9999, '0.45', 512, '250', '1.00', 0),
(5, 'Multi Theft Auto: Multiplayer', 'mta', 'mtasa', 50, 1000, 25020, 80520, '0.60', 512, '500', '1.00', 0),
(6, 'MineCraft: PE', 'mcpe', 'mine', 10, 100, 12410, 55641, '1.00', 1024, '750', '5.00', 0),
(7, 'MineCraft', 'mine', 'mine', 10, 100, 12410, 55641, '1.00', 1024, '750', '5.00', 0),
(8, 'Counter-Strike: 1.6', 'cs', 'valve', 6, 32, 27016, 30550, '1.00', 1024, '2500', '4.00', 0),
(9, 'Counter-Strike: Source', 'css', 'valve', 6, 32, 27016, 30550, '1.00', 1024, '6500', '8.00', 0),
(10, 'Counter-Strike: GO', 'csgo', 'valve', 6, 32, 27016, 30550, '2.00', 1512, '35000', '25.00', 0),
(11, 'GTA V: RAGE MP (NodeJS + MySQL)', 'ragemp', 'ragemp', 50, 500, 22000, 25000, '0.95', 1024, '2500', '4.00', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `inbox`
--

CREATE TABLE `inbox` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_firstname` varchar(15) DEFAULT NULL,
  `user_lastname` varchar(50) DEFAULT NULL,
  `user_email` varchar(50) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `text` text DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `inbox_date_add` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `invoices`
--

CREATE TABLE `invoices` (
  `invoice_id` int(10) NOT NULL,
  `user_id` int(10) DEFAULT NULL,
  `invoice_ammount` decimal(10,2) DEFAULT NULL,
  `invoice_status` int(1) DEFAULT NULL,
  `invoice_date_add` datetime DEFAULT NULL,
  `system` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `locations`
--

CREATE TABLE `locations` (
  `location_id` int(10) NOT NULL,
  `location_name` varchar(32) DEFAULT NULL,
  `location_ip` varchar(15) DEFAULT NULL,
  `location_ip2` varchar(15) DEFAULT NULL,
  `location_user` varchar(32) DEFAULT NULL,
  `location_password` varchar(32) DEFAULT NULL,
  `location_status` int(1) DEFAULT NULL,
  `location_cpu` varchar(128) NOT NULL DEFAULT '0',
  `location_ram` varchar(128) NOT NULL DEFAULT '0',
  `location_hdd` varchar(128) NOT NULL DEFAULT '0',
  `location_upd` datetime DEFAULT NULL,
  `location_games` varchar(98) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `locations_stats`
--

CREATE TABLE `locations_stats` (
  `location_id` int(11) DEFAULT NULL,
  `location_stats_date` datetime DEFAULT NULL,
  `location_stats_cpu` int(11) DEFAULT NULL,
  `location_stats_ram` int(11) DEFAULT NULL,
  `location_stats_hdd` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE `news` (
  `news_id` int(10) NOT NULL,
  `user_id` int(10) DEFAULT 0,
  `news_title` varchar(100) DEFAULT NULL,
  `news_text` text NOT NULL,
  `news_date_add` datetime DEFAULT NULL,
  `look` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `news_messages`
--

CREATE TABLE `news_messages` (
  `news_message_id` int(10) NOT NULL,
  `news_id` int(10) DEFAULT NULL,
  `user_id` int(10) DEFAULT NULL,
  `news_message` text DEFAULT NULL,
  `news_message_date_add` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `promo`
--

CREATE TABLE `promo` (
  `id` int(11) NOT NULL,
  `cod` text DEFAULT NULL,
  `uses` int(11) DEFAULT NULL,
  `used` int(11) DEFAULT NULL,
  `skidka` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `serverlog`
--

CREATE TABLE `serverlog` (
  `log_id` int(11) NOT NULL,
  `reason` text DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `server_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `servers`
--

CREATE TABLE `servers` (
  `server_id` int(10) NOT NULL,
  `user_id` int(10) DEFAULT NULL,
  `game_id` int(10) DEFAULT NULL,
  `location_id` int(10) DEFAULT NULL,
  `server_slots` int(8) DEFAULT NULL,
  `server_port` int(8) DEFAULT NULL,
  `server_password` varchar(32) DEFAULT NULL,
  `server_status` int(1) DEFAULT NULL,
  `server_work` int(11) DEFAULT NULL,
  `server_date_reg` datetime DEFAULT NULL,
  `server_date_end` datetime DEFAULT NULL,
  `db_pass` varchar(32) DEFAULT NULL,
  `server_mysql` int(11) DEFAULT NULL,
  `fastdl_status` int(11) NOT NULL DEFAULT 0,
  `server_fps` int(10) DEFAULT 0,
  `server_tickrate` int(10) NOT NULL DEFAULT 0,
  `server_vac` int(10) NOT NULL DEFAULT 0,
  `server_binary` varchar(96) DEFAULT NULL,
  `server_binary_version` varchar(38) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `servers_firewalls`
--

CREATE TABLE `servers_firewalls` (
  `firewall_id` int(10) NOT NULL,
  `server_id` int(8) DEFAULT NULL,
  `server_ip` text DEFAULT NULL,
  `firewall_add` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `servers_mods`
--

CREATE TABLE `servers_mods` (
  `game_id` int(11) DEFAULT NULL,
  `mod_url` text DEFAULT NULL,
  `mod_name` text DEFAULT NULL,
  `mod_status` int(11) DEFAULT NULL,
  `mod_arch` text DEFAULT NULL,
  `mod_textx` text DEFAULT NULL,
  `mod_img` text DEFAULT NULL,
  `mod_price` text DEFAULT NULL,
  `mod_set` int(1) DEFAULT NULL,
  `mod_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `servers_mods`
--

INSERT INTO `servers_mods` (`game_id`, `mod_url`, `mod_name`, `mod_status`, `mod_arch`, `mod_textx`, `mod_img`, `mod_price`, `mod_set`, `mod_id`) VALUES
(1, 'https://code.flowaxy.com/hostpanel/mods/arizona.tar', 'Arizona RP', 1, 'arizona.tar', 'Игровой мод Arizona RP, для SAMP 0.3.7', 'https://code.flowaxy.com/hostpanel/no.png', '0', 0, 1),
(1, 'https://code.flowaxy.com/hostpanel/mods/diamond-rp.tar', 'Diamond RP', 1, 'diamond-rp.tar', 'Игровой мод Diamond RP, для SAMP 0.3.7', 'https://code.flowaxy.com/hostpanel/no.png', '0', 0, 2),
(1, 'https://code.flowaxy.com/hostpanel/mods/evolverp.tar', 'Evolve RP', 1, 'evolverp.tar', 'Игровой мод Evolve RP, для SAMP 0.3.7', 'https://code.flowaxy.com/hostpanel/no.png', '0', 0, 3),
(1, 'https://code.flowaxy.com/hostpanel/mods/heavilyrp.tar', 'Heavily RP', 1, 'heavilyrp.tar', 'Игровой мод Heavily RP, для SAMP 0.3.7', 'https://code.flowaxy.com/hostpanel/no.png', '0', 0, 4),
(1, 'https://code.flowaxy.com/hostpanel/mods/samp-mobile.tar', 'SAMP MOBILE', 1, 'samp-mobile.tar', 'Игровой мод SAMP MOBILE, для SAMP 0.3.7', 'https://code.flowaxy.com/hostpanel/no.png', '0', 0, 5),
(1, 'https://code.flowaxy.com/hostpanel/mods/samprp.tar', 'Samp RP', 1, 'samprp.tar', 'Игровой мод Samp RP, для SAMP 0.3.7', 'https://code.flowaxy.com/hostpanel/no.png', '0', 0, 6),
(2, 'https://code.flowaxy.com/hostpanel/mods/capsrp.tar', 'Caps RP', 1, 'capsrp.tar', 'Игровой мод Caps RP, для CRMP 0.3e и CRMP 0.3.7', 'https://code.flowaxy.com/hostpanel/no.png', '0', 0, 7),
(2, 'https://code.flowaxy.com/hostpanel/mods/countyrp.tar', 'County RP', 1, 'countyrp.tar', 'Игровой мод County RP, для CRMP 0.3e', 'https://code.flowaxy.com/hostpanel/no.png', '0', 0, 9),
(2, 'https://code.flowaxy.com/hostpanel/mods/justrp.tar', 'Just RP', 1, 'justrp.tar', 'Игровой мод Just RP, для CRMP 0.3e', 'https://code.flowaxy.com/hostpanel/no.png', '0', 0, 10),
(2, 'https://code.flowaxy.com/hostpanel/mods/rayonrp.tar', 'Rayon RP', 1, 'rayonrp.tar', 'Игровой мод Rayon RP, для CRMP 0.3e и CRMP 0.3.7', 'https://code.flowaxy.com/hostpanel/no.png', '10', 1, 11),
(2, 'https://code.flowaxy.com/hostpanel/mods/severerp.tar', 'Severe RP', 1, 'severerp.tar', 'Игровой мод Severe RP, для CRMP 0.3e и CRMP 0.3.7', 'https://code.flowaxy.com/hostpanel/no.png', '0', 0, 12);

-- --------------------------------------------------------

--
-- Структура таблицы `servers_owners`
--

CREATE TABLE `servers_owners` (
  `owner_id` int(10) NOT NULL,
  `server_id` int(10) DEFAULT NULL,
  `user_id` int(10) DEFAULT NULL,
  `owner_status` int(11) DEFAULT NULL,
  `owner_add` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `servers_repo`
--

CREATE TABLE `servers_repo` (
  `game_id` int(11) DEFAULT NULL,
  `repo_url` text DEFAULT NULL,
  `repo_name` text DEFAULT NULL,
  `repo_status` int(11) DEFAULT NULL,
  `repo_textx` text DEFAULT NULL,
  `repo_img` text DEFAULT NULL,
  `repo_price` text DEFAULT NULL,
  `repo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `servers_stats`
--

CREATE TABLE `servers_stats` (
  `server_id` int(11) DEFAULT NULL,
  `server_stats_date` datetime DEFAULT NULL,
  `server_stats_players` int(11) DEFAULT NULL,
  `server_stats_cpu` int(11) DEFAULT NULL,
  `server_stats_ram` int(11) DEFAULT NULL,
  `server_stats_hdd` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `servers_tasks`
--

CREATE TABLE `servers_tasks` (
  `task_id` bigint(255) NOT NULL,
  `server_id` bigint(255) DEFAULT NULL,
  `task_name` text DEFAULT NULL,
  `task_type` text DEFAULT NULL,
  `task_time` text DEFAULT NULL,
  `task_lead_time` datetime DEFAULT NULL,
  `task_status` int(11) DEFAULT NULL,
  `isSystemTask` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tickets`
--

CREATE TABLE `tickets` (
  `ticket_id` int(10) NOT NULL,
  `user_id` int(10) DEFAULT NULL,
  `ticket_name` varchar(32) DEFAULT NULL,
  `ticket_status` int(1) DEFAULT NULL,
  `ticket_date_add` datetime DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tickets_category`
--

CREATE TABLE `tickets_category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(32) DEFAULT NULL,
  `category_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tickets_messages`
--

CREATE TABLE `tickets_messages` (
  `ticket_message_id` int(10) NOT NULL,
  `ticket_id` int(10) DEFAULT NULL,
  `user_id` int(10) DEFAULT NULL,
  `ticket_message` text DEFAULT NULL,
  `ticket_message_date_add` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `user_id` int(10) NOT NULL,
  `user_email` varchar(96) DEFAULT NULL,
  `user_new_email` varchar(256) DEFAULT NULL,
  `user_password` varchar(32) DEFAULT NULL,
  `user_firstname` varchar(32) DEFAULT NULL,
  `user_lastname` varchar(32) DEFAULT NULL,
  `user_status` int(1) DEFAULT NULL,
  `user_balance` decimal(10,2) DEFAULT NULL,
  `user_restore_key` varchar(32) DEFAULT NULL,
  `user_access_level` int(1) DEFAULT NULL,
  `user_date_reg` datetime DEFAULT NULL,
  `user_img` varchar(250) NOT NULL DEFAULT 'application/public/img/user.png',
  `user_online_date` text DEFAULT NULL,
  `user_promo_date` date DEFAULT NULL,
  `user_activate` int(1) DEFAULT NULL,
  `key_activate` text DEFAULT NULL,
  `ref` int(11) DEFAULT NULL,
  `rmoney` decimal(10,2) DEFAULT NULL,
  `bonuses` decimal(10,2) DEFAULT 0.00,
  `user_vk_id` varchar(96) DEFAULT NULL,
  `test_server` varchar(2) DEFAULT '2',
  `user_promised_pay` int(11) DEFAULT 0,
  `user_last_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `users_auth`
--

CREATE TABLE `users_auth` (
  `auth_id` bigint(255) NOT NULL,
  `user_id` bigint(255) DEFAULT NULL,
  `user_ip` text DEFAULT NULL,
  `user_last_activity` datetime DEFAULT NULL,
  `auth_user_email` longtext DEFAULT NULL,
  `auth_user_password` longtext DEFAULT NULL,
  `auth_type` text DEFAULT NULL,
  `auth_key` longtext DEFAULT NULL,
  `auth_date_add` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `users_mods`
--

CREATE TABLE `users_mods` (
  `id` int(10) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `mod_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `users_repo`
--

CREATE TABLE `users_repo` (
  `id` int(10) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `repo_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `waste`
--

CREATE TABLE `waste` (
  `waste_id` int(10) NOT NULL,
  `user_id` int(10) DEFAULT NULL,
  `waste_ammount` decimal(10,2) DEFAULT NULL,
  `waste_status` int(1) DEFAULT NULL,
  `waste_usluga` varchar(120) DEFAULT NULL,
  `waste_date_add` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `webhost`
--

CREATE TABLE `webhost` (
  `web_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tarif_id` int(11) DEFAULT NULL,
  `web_password` varchar(32) DEFAULT NULL,
  `web_domain` varchar(32) DEFAULT NULL,
  `web_status` int(11) DEFAULT NULL,
  `web_date_reg` datetime DEFAULT NULL,
  `web_date_end` datetime DEFAULT NULL,
  `location_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `web_locations`
--

CREATE TABLE `web_locations` (
  `location_id` int(10) NOT NULL,
  `location_name` varchar(32) DEFAULT NULL,
  `location_ip` varchar(15) DEFAULT NULL,
  `location_url` varchar(45) DEFAULT NULL,
  `location_user` varchar(32) DEFAULT NULL,
  `location_password` varchar(32) DEFAULT NULL,
  `ns_servers` text DEFAULT NULL,
  `location_status` int(1) DEFAULT NULL,
  `location_tarifs` varchar(98) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `web_tarifs`
--

CREATE TABLE `web_tarifs` (
  `tarif_id` int(150) NOT NULL,
  `tarif_name` varchar(32) DEFAULT NULL,
  `package` varchar(32) DEFAULT NULL,
  `tarif_price` decimal(10,2) DEFAULT NULL,
  `tarif_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `authlog`
--
ALTER TABLE `authlog`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`game_id`);

--
-- Индексы таблицы `inbox`
--
ALTER TABLE `inbox`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`invoice_id`);

--
-- Индексы таблицы `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`location_id`);

--
-- Индексы таблицы `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`news_id`);

--
-- Индексы таблицы `news_messages`
--
ALTER TABLE `news_messages`
  ADD PRIMARY KEY (`news_message_id`);

--
-- Индексы таблицы `promo`
--
ALTER TABLE `promo`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `serverlog`
--
ALTER TABLE `serverlog`
  ADD PRIMARY KEY (`log_id`);

--
-- Индексы таблицы `servers`
--
ALTER TABLE `servers`
  ADD PRIMARY KEY (`server_id`);

--
-- Индексы таблицы `servers_firewalls`
--
ALTER TABLE `servers_firewalls`
  ADD PRIMARY KEY (`firewall_id`);

--
-- Индексы таблицы `servers_mods`
--
ALTER TABLE `servers_mods`
  ADD PRIMARY KEY (`mod_id`);

--
-- Индексы таблицы `servers_owners`
--
ALTER TABLE `servers_owners`
  ADD PRIMARY KEY (`owner_id`);

--
-- Индексы таблицы `servers_repo`
--
ALTER TABLE `servers_repo`
  ADD PRIMARY KEY (`repo_id`);

--
-- Индексы таблицы `servers_tasks`
--
ALTER TABLE `servers_tasks`
  ADD PRIMARY KEY (`task_id`);

--
-- Индексы таблицы `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ticket_id`);

--
-- Индексы таблицы `tickets_category`
--
ALTER TABLE `tickets_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Индексы таблицы `tickets_messages`
--
ALTER TABLE `tickets_messages`
  ADD PRIMARY KEY (`ticket_message_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Индексы таблицы `users_auth`
--
ALTER TABLE `users_auth`
  ADD PRIMARY KEY (`auth_id`);

--
-- Индексы таблицы `users_mods`
--
ALTER TABLE `users_mods`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users_repo`
--
ALTER TABLE `users_repo`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `waste`
--
ALTER TABLE `waste`
  ADD PRIMARY KEY (`waste_id`);

--
-- Индексы таблицы `webhost`
--
ALTER TABLE `webhost`
  ADD PRIMARY KEY (`web_id`);

--
-- Индексы таблицы `web_locations`
--
ALTER TABLE `web_locations`
  ADD PRIMARY KEY (`location_id`);

--
-- Индексы таблицы `web_tarifs`
--
ALTER TABLE `web_tarifs`
  ADD PRIMARY KEY (`tarif_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `authlog`
--
ALTER TABLE `authlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `games`
--
ALTER TABLE `games`
  MODIFY `game_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `inbox`
--
ALTER TABLE `inbox`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `invoices`
--
ALTER TABLE `invoices`
  MODIFY `invoice_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `locations`
--
ALTER TABLE `locations`
  MODIFY `location_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `news`
--
ALTER TABLE `news`
  MODIFY `news_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `news_messages`
--
ALTER TABLE `news_messages`
  MODIFY `news_message_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `promo`
--
ALTER TABLE `promo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `serverlog`
--
ALTER TABLE `serverlog`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `servers`
--
ALTER TABLE `servers`
  MODIFY `server_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `servers_firewalls`
--
ALTER TABLE `servers_firewalls`
  MODIFY `firewall_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `servers_mods`
--
ALTER TABLE `servers_mods`
  MODIFY `mod_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `servers_owners`
--
ALTER TABLE `servers_owners`
  MODIFY `owner_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `servers_repo`
--
ALTER TABLE `servers_repo`
  MODIFY `repo_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `servers_tasks`
--
ALTER TABLE `servers_tasks`
  MODIFY `task_id` bigint(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ticket_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `tickets_category`
--
ALTER TABLE `tickets_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `tickets_messages`
--
ALTER TABLE `tickets_messages`
  MODIFY `ticket_message_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users_auth`
--
ALTER TABLE `users_auth`
  MODIFY `auth_id` bigint(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users_mods`
--
ALTER TABLE `users_mods`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users_repo`
--
ALTER TABLE `users_repo`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `waste`
--
ALTER TABLE `waste`
  MODIFY `waste_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `webhost`
--
ALTER TABLE `webhost`
  MODIFY `web_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `web_locations`
--
ALTER TABLE `web_locations`
  MODIFY `location_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `web_tarifs`
--
ALTER TABLE `web_tarifs`
  MODIFY `tarif_id` int(150) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
