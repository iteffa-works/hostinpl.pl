#!/bin/bash

Infon()
{
 printf "\033[1;32m$@\033[0m"
}
Info()
{
 Infon "$@\n"
}
Error()
{
 printf "\033[1;31m$@\033[0m\n"
}
Error_n()
{
 Error "$@"
}
Error_s()
{
 Error "- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - "
}
log_s()
{
 Info "- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - "
}
log_n()
{
 Info "$@"
}
log_t()
{
 log_s
 Info "- - - $@"
 log_s
}
log_tt()
{
 Info "- - - $@"
 log_s
}

RED=$(tput setaf 1)
green=$(tput setaf 2)
white=$(tput setaf 7)
reset=$(tput sgr0)
BLUE=$(tput setaf 4)
MAGENTA=$(tput setaf 5)
LOGIN=$(whoami)
VER=$(sed 's/\/.*//' /etc/debian_version | sed 's/\..*//')
VIRT=$(hostnamectl | grep -e "Virtualization" | awk '{print $2}')
RAM=$(free -m | awk '/Mem:/ { print $2 }')
IP_SERV=`ip -o -4 address show scope global | tr '/' ' ' | awk '$3~/^inet/ && $2~/^(eth|veth|venet|ens|eno|enp)[0-9]+$|^enp[0-9]+s[0-9a-z]+$/ {print $4}'|head -1`
PMA_VERSION="5.2.3"
INSTALL_BASE_URL="https://code.flowaxy.com/hostpanel"
PANEL_REPO_URL="https://github.com/iteffa-works/hostinpl.pl"

fetch_install_file()
{
	local url="$1"
	local dest="$2"
	if ! wget --inet4-only --no-check-certificate -O "$dest" "$url"; then
		Error_n "Ошибка загрузки: $url"
		exit 1
	fi
}

apply_nginx_php_sock()
{
	if [ "$PHP_VERSION" = "8.2" ]; then
		sed -i 's/php7.4/php8.2/g' /etc/nginx/nginx.conf
	elif [ "$PHP_VERSION" = "8.4" ]; then
		sed -i 's/php7.4/php8.4/g' /etc/nginx/nginx.conf
	fi
}

configure_panel_config()
{
	local config="/var/www/application/config.php"
	if [ ! -f "$config" ]; then
		if [ -f /var/www/application/config.example.php ]; then
			cp /var/www/application/config.example.php "$config"
		else
			Error_n "Не найден config.example.php в репозитории"
			exit 1
		fi
	fi
	sed -i "s/parol/${ADMIN_PASS}/g" "$config"
	sed -i "s/domen.ru/${DOMAIN}/g" "$config"
	sed -i "s|'url'[[:space:]]*=>[[:space:]]*'http://[^']*'|'url'\t\t\t=> 'http://${DOMAIN}/'|g" "$config"
	sed -i "s/'db_hostname'[[:space:]]*=>[[:space:]]*'[^']*'/'db_hostname'\t=> 'localhost'/g" "$config"
	sed -i "s/'db_username'[[:space:]]*=>[[:space:]]*'[^']*'/'db_username'\t=> 'admin'/g" "$config"
	sed -i "s/'db_password'[[:space:]]*=>[[:space:]]*'[^']*'/'db_password'       => '${ADMIN_PASS}'/g" "$config"
	sed -i "s/'db_database'[[:space:]]*=>[[:space:]]*'[^']*'/'db_database'\t=> 'hostin'/g" "$config"
	sed -i "s/edit_r_key_v2/${R_KEY_V2}/g" "$config"
	sed -i "s/edit_r_skey_v2/${R_SKEY_V2}/g" "$config"
	if [ "$VER" = "12" ]; then
		sed -i "s/Debian 11/Debian 12/g" "$config"
	fi
	if [ "$VER" = "13" ]; then
		sed -i "s/Debian 11/Debian 13/g" "$config"
	fi
}

install_panel_from_repo()
{
	log_n "${MAGENTA}Установка панели"
	rm -rf /var/www/html
	apt-get install -y git rsync
	rm -rf /tmp/hostinpl_panel
	if ! git clone --depth 1 "${PANEL_REPO_URL}.git" /tmp/hostinpl_panel; then
		Error_n "Не удалось клонировать репозиторий панели"
		exit 1
	fi
	rsync -a --exclude='.git' --exclude='install' --exclude='.osp' --exclude='.vscode' /tmp/hostinpl_panel/ /var/www/
	rm -rf /tmp/hostinpl_panel
	if [ ! -f /var/www/hostinpl5_6.sql ]; then
		Error_n "Не найден файл hostinpl5_6.sql в репозитории"
		exit 1
	fi
	configure_panel_config
	mkdir -p /var/www/plugins
	chown -R www-data:www-data /var/www
	chmod -R 770 /var/www
	chmod -R 775 /var/www/plugins
}

import_panel_database()
{
	log_n "${MAGENTA}Создание и импорт базы данных"
	mysql -e "CREATE DATABASE IF NOT EXISTS hostin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
	mkdir -p /var/lib/mysql/hostin
	chown -R mysql:mysql /var/lib/mysql/hostin
	mysql hostin < /var/www/hostinpl5_6.sql
	rm -f /var/www/hostinpl5_6.sql
}

load_docker_image()
{
	local docker_tar="/root/debian_bullseye_hostinpl_02062024.tar"
	log_n "${MAGENTA}Загрузка образа Docker для работы игровых серверов"
	rm -f "$docker_tar"
	fetch_install_file "${INSTALL_BASE_URL}/l/docker_images/debian_bullseye_hostinpl_02062024.tar" "$docker_tar"
	docker image load -i "$docker_tar"
	rm -f "$docker_tar"
	sleep 1
	docker_image_check=$(docker images -qf "reference=hostinpl:games")
	if [ -z "$docker_image_check" ]; then
		Error_n "Не найден образ Docker"
		exit 1
	fi
}

install_panel()
{
	clear
	read -p "${MAGENTA}Пожалуйста, введите домен или IP:${reset}" DOMAIN
	read -p "${MAGENTA}Пожалуйста, введите ключ сайта reCaptcha v2:${reset}" R_KEY_V2
	read -p "${MAGENTA}Пожалуйста, введите секретный ключ reCaptcha v2:${reset}" R_SKEY_V2
	if [ $VER = "12" ]; then
		RELEASE="bookworm"
		PHP_VERSION="8.2"
	elif [ $VER = "13" ]; then
		RELEASE="trixie"
		PHP_VERSION="8.4"
	else
		RELEASE="bullseye"
		PHP_VERSION="7.4"
	fi
	log_n "${MAGENTA}Добавление репозитория Debian $VER ($RELEASE)"
	echo "deb http://deb.debian.org/debian $RELEASE main" > /etc/apt/sources.list
	echo "deb-src http://deb.debian.org/debian $RELEASE main" >> /etc/apt/sources.list
	echo "deb http://deb.debian.org/debian-security/ $RELEASE-security main" >> /etc/apt/sources.list
	echo "deb-src http://deb.debian.org/debian-security/ $RELEASE-security main" >> /etc/apt/sources.list
	echo "deb http://deb.debian.org/debian $RELEASE-updates main" >> /etc/apt/sources.list
	echo "deb-src http://deb.debian.org/debian $RELEASE-updates main" >> /etc/apt/sources.list
	log_n "${MAGENTA}Первоначальная настройка"
	export DEBIAN_FRONTEND=noninteractive
	echo "debconf debconf/frontend select Noninteractive" | debconf-set-selections
	echo iptables-persistent iptables-persistent/autosave_v4 boolean true | debconf-set-selections
	echo iptables-persistent iptables-persistent/autosave_v6 boolean true | debconf-set-selections
	log_n "${MAGENTA}Обновление пакетов и апгрейд системы"
	apt-get -qq update
	apt-get -y upgrade
	log_n "${MAGENTA}Установка пакетов"
	apt-get install -y cron wget pwgen apache2 php$PHP_VERSION php$PHP_VERSION-mysql php$PHP_VERSION-ssh2 php$PHP_VERSION-curl php$PHP_VERSION-mbstring php$PHP_VERSION-xml php$PHP_VERSION-zip php$PHP_VERSION-gd libapache2-mod-php$PHP_VERSION mariadb-server unzip htop apt-transport-https ca-certificates iptables-persistent
	if [ $PHP_VERSION = "7.4" ]; then
		apt-get install -y php7.4-json
	fi
	systemctl enable netfilter-persistent.service
	ADMIN_PASS=$(pwgen -cns -1 28)
	PMA_PASS=$(pwgen -cns -1 24)
	blowfish_secret=$(pwgen -cns -1 32)
	mysql -e "GRANT ALL ON *.* TO 'admin'@'localhost' IDENTIFIED BY '$ADMIN_PASS' WITH GRANT OPTION"
	mysql -e "GRANT ALL PRIVILEGES ON phpmyadmin.* TO 'phpmyadmin'@'localhost' IDENTIFIED BY '$PMA_PASS'"
	mysql -e "FLUSH PRIVILEGES"
	log_n "${MAGENTA}Установка PhpMyAdmin"
	wget --no-check-certificate https://files.phpmyadmin.net/phpMyAdmin/$PMA_VERSION/phpMyAdmin-$PMA_VERSION-all-languages.tar.gz
	tar xvf phpMyAdmin-$PMA_VERSION-all-languages.tar.gz
	rm phpMyAdmin-$PMA_VERSION-all-languages.tar.gz
	mv phpMyAdmin-$PMA_VERSION-all-languages/ /usr/share/phpmyadmin
	fetch_install_file "${INSTALL_BASE_URL}/p/config_pma.txt" /usr/share/phpmyadmin/config.inc.php
	sed -i "s/blowfish_secret_input/${blowfish_secret}/g" /usr/share/phpmyadmin/config.inc.php
	sed -i "s/pmapass/${PMA_PASS}/g" /usr/share/phpmyadmin/config.inc.php
	mysql < /usr/share/phpmyadmin/sql/create_tables.sql
	mkdir -p /var/lib/phpmyadmin/tmp
	chown -R www-data:www-data /usr/share/phpmyadmin /var/lib/phpmyadmin
	chmod -R 770 /usr/share/phpmyadmin /var/lib/phpmyadmin
	chmod -R 640 /usr/share/phpmyadmin/config.inc.php
	rm -rf /usr/share/phpmyadmin/setup /usr/share/phpmyadmin/examples /usr/share/phpmyadmin/config.sample.inc.php
	log_n "${MAGENTA}Настройка Apache2, PHP и MariaDB"
	fetch_install_file "${INSTALL_BASE_URL}/p/config_apache.txt" /etc/apache2/sites-available/hostinpl.conf
	PMA_URL=$(pwgen -cns -1 16)
	PMA_URL_LOGIN=$(pwgen -cns -1 6)
	PMA_URL_PASS=$(pwgen -cns -1 16)
	sed -i "s/domain.ru/${DOMAIN}/g" /etc/apache2/sites-available/hostinpl.conf
	sed -i "s/pma_edit/${PMA_URL}/g" /etc/apache2/sites-available/hostinpl.conf
	htpasswd -b -c /var/lib/phpmyadmin/.htpasswd $PMA_URL_LOGIN $PMA_URL_PASS
	a2ensite hostinpl
	a2dissite 000-default
	a2enmod rewrite
	fetch_install_file "${INSTALL_BASE_URL}/p/php.ini" /etc/php/$PHP_VERSION/apache2/php.ini
	fetch_install_file "${INSTALL_BASE_URL}/p/php.ini" /etc/php/$PHP_VERSION/cli/php.ini
	ln -snf /usr/share/zoneinfo/Europe/Moscow /etc/localtime && echo Europe/Moscow > /etc/timezone
	sed -i 's/#max_connections        = 100/max_connections        = 1000/g' /etc/mysql/mariadb.conf.d/50-server.cnf
	systemctl restart apache2
	systemctl restart mysql
	log_n "${MAGENTA}Настройка Cronrab"
	(crontab -l ; echo "0 0 * * * bash -c 'php /var/www/cron.php index'") 2>&1 | grep -v "no crontab" | sort | uniq | crontab -
	(crontab -l ; echo "*/1 * * * * bash -c 'php /var/www/cron.php gameServers'") 2>&1 | grep -v "no crontab" | sort | uniq | crontab -
	(crontab -l ; echo "*/1 * * * * bash -c 'php /var/www/cron.php tasks'") 2>&1 | grep -v "no crontab" | sort | uniq | crontab -
	(crontab -l ; echo "*/10 * * * * bash -c 'php /var/www/cron.php serverReloader'") 2>&1 | grep -v "no crontab" | sort | uniq | crontab -
	(crontab -l ; echo "*/30 * * * * bash -c 'php /var/www/cron.php stopServers'") 2>&1 | grep -v "no crontab" | sort | uniq | crontab -
	(crontab -l ; echo "*/30 * * * * bash -c 'php /var/www/cron.php stopServersQuery'") 2>&1 | grep -v "no crontab" | sort | uniq | crontab -
	(crontab -l ; echo "0 * * * * bash -c 'php /var/www/cron.php updateStats'") 2>&1 | grep -v "no crontab" | sort | uniq | crontab -
	(crontab -l ; echo "0 * * * * bash -c 'php /var/www/cron.php updateStatsLocations'") 2>&1 | grep -v "no crontab" | sort | uniq | crontab -
	(crontab -l ; echo "0 * */7 * * bash -c 'php /var/www/cron.php clearLogs'") 2>&1 | grep -v "no crontab" | sort | uniq | crontab -
	systemctl restart cron
	install_panel_from_repo
	import_panel_database
	service netfilter-persistent save
	log_n "==================== Установка HOSTINPL 5.6 успешно завершена ===================="
	Error_n "${green} - Адрес панели: ${white}http://$DOMAIN"
	Error_n "${green} -- Адрес phpmyadmin: ${white}http://$DOMAIN/$PMA_URL"
	Error_n "${green} --- Данные для входа в phpmyadmin:"
	Error_n "${green} --- Пользователь: ${white}$PMA_URL_LOGIN"
	Error_n "${green} --- Пароль: ${white}$PMA_URL_PASS"
	Error_n "${green} ---- Данные для входа в базу панели:"
	Error_n "${green} ---- Пользователь: ${white}admin"
	Error_n "${green} ---- Пароль: ${white}$ADMIN_PASS"
	Error_n "${green}Мониторинг нагрузки сервера: ${white}htop"
	log_n "=============================== vk.com/hosting_rus ==============================="
	Info
	log_tt "${white}Добро пожаловать в установочное меню ${MAGENTA}HOSTINPL 5.6"
	Info "- ${white}1 ${green}- ${white}Подключить файл подкачки"
	Info "- ${white}0 ${green}- ${white}Выйти из установщика"
	log_s
	Info
	read -p "Пожалуйста, введите пункт меню: " case
	case $case in
		1) install_swap;;
		0) exit;;
	esac
}

install_location()
{
	clear
	if [ $VER = "12" ]; then
		RELEASE="bookworm"
		PHP_VERSION="8.2"
	elif [ $VER = "13" ]; then
		RELEASE="trixie"
		PHP_VERSION="8.4"
	else
		RELEASE="bullseye"
		PHP_VERSION="7.4"
	fi
	log_n "${MAGENTA}Добавление репозитория Debian $VER ($RELEASE)"
	echo "deb http://deb.debian.org/debian $RELEASE main" > /etc/apt/sources.list
	echo "deb-src http://deb.debian.org/debian $RELEASE main" >> /etc/apt/sources.list
	echo "deb http://deb.debian.org/debian-security/ $RELEASE-security main" >> /etc/apt/sources.list
	echo "deb-src http://deb.debian.org/debian-security/ $RELEASE-security main" >> /etc/apt/sources.list
	echo "deb http://deb.debian.org/debian $RELEASE-updates main" >> /etc/apt/sources.list
	echo "deb-src http://deb.debian.org/debian $RELEASE-updates main" >> /etc/apt/sources.list
	log_n "${MAGENTA}Первоначальная настройка"
	export DEBIAN_FRONTEND=noninteractive
	echo "debconf debconf/frontend select Noninteractive" | debconf-set-selections
	echo iptables-persistent iptables-persistent/autosave_v4 boolean true | debconf-set-selections
	echo iptables-persistent iptables-persistent/autosave_v6 boolean true | debconf-set-selections
	groupadd gameservers 2>/dev/null || true
	Line_Number=$(grep -n "127.0.0.1" /etc/hosts | cut -d: -f 1)
	My_Hostname=$(hostname)
	if [[ -n $Line_Number ]]; then
		for Line_Number2 in $Line_Number ; do
			String=$(sed "${Line_Number2}q;d" /etc/hosts)
			if [[ $String != *"$My_Hostname"* ]]; then
				New_String="$String $My_Hostname"
				sed -i "${Line_Number2}s/.*/${New_String}/" /etc/hosts
			fi
		done
	else
		echo "127.0.0.1 $My_Hostname " >> /etc/hosts
	fi
	log_n "${MAGENTA}Обновление пакетов и апгрейд системы"
	apt-get -qq update
	apt-get -y upgrade
	log_n "${MAGENTA}Установка пакетов"
	apt-get install -y wget pwgen apt-transport-https ca-certificates gnupg
	wget https://download.docker.com/linux/debian/gpg -O - | apt-key add -
	echo "deb [arch=amd64] https://download.docker.com/linux/debian $RELEASE stable" >> /etc/apt/sources.list
	apt-get -qq update
	apt-get install docker-ce docker-ce-cli docker-buildx-plugin -y
	apt-get install -y nginx php$PHP_VERSION-fpm php$PHP_VERSION-mysql php$PHP_VERSION-ssh2 php$PHP_VERSION-curl php$PHP_VERSION-mbstring php$PHP_VERSION-xml php$PHP_VERSION-zip php$PHP_VERSION-gd mariadb-server unzip htop iptables-persistent pure-ftpd
	if [ $PHP_VERSION = "7.4" ]; then
		apt-get install -y php7.4-json
	fi
	systemctl enable netfilter-persistent.service
	ADMIN_PASS=$(pwgen -cns -1 28)
	PMA_PASS=$(pwgen -cns -1 24)
	blowfish_secret=$(pwgen -cns -1 32)
	mysql -e "GRANT ALL ON *.* TO 'admin'@'localhost' IDENTIFIED BY '$ADMIN_PASS' WITH GRANT OPTION"
	mysql -e "GRANT ALL PRIVILEGES ON phpmyadmin.* TO 'phpmyadmin'@'localhost' IDENTIFIED BY '$PMA_PASS'"
	mysql -e "FLUSH PRIVILEGES"
	log_n "${MAGENTA}Установка PhpMyAdmin"
	wget --no-check-certificate https://files.phpmyadmin.net/phpMyAdmin/$PMA_VERSION/phpMyAdmin-$PMA_VERSION-all-languages.tar.gz
	tar xvf phpMyAdmin-$PMA_VERSION-all-languages.tar.gz
	rm phpMyAdmin-$PMA_VERSION-all-languages.tar.gz
	mv phpMyAdmin-$PMA_VERSION-all-languages/ /usr/share/phpmyadmin
	fetch_install_file "${INSTALL_BASE_URL}/p/config_pma.txt" /usr/share/phpmyadmin/config.inc.php
	sed -i "s/blowfish_secret_input/${blowfish_secret}/g" /usr/share/phpmyadmin/config.inc.php
	sed -i "s/pmapass/${PMA_PASS}/g" /usr/share/phpmyadmin/config.inc.php
	mysql < /usr/share/phpmyadmin/sql/create_tables.sql
	mkdir -p /var/lib/phpmyadmin/tmp
	chown -R www-data:www-data /usr/share/phpmyadmin /var/lib/phpmyadmin
	chmod -R 770 /usr/share/phpmyadmin /var/lib/phpmyadmin
	chmod -R 640 /usr/share/phpmyadmin/config.inc.php
	rm -rf /usr/share/phpmyadmin/setup /usr/share/phpmyadmin/examples /usr/share/phpmyadmin/config.sample.inc.php
	log_n "${MAGENTA}Настройка Nginx, PHP и MariaDB"
	systemctl stop nginx
	fetch_install_file "${INSTALL_BASE_URL}/l/php.ini" /etc/php/$PHP_VERSION/fpm/php.ini
	fetch_install_file "${INSTALL_BASE_URL}/l/php.ini" /etc/php/$PHP_VERSION/cli/php.ini
	fetch_install_file "${INSTALL_BASE_URL}/l/config_nginx.txt" /etc/nginx/nginx.conf
	ln -snf /usr/share/zoneinfo/Europe/Moscow /etc/localtime && echo Europe/Moscow > /etc/timezone
	sed -i 's/127.0.0.1/0.0.0.0/g' /etc/mysql/mariadb.conf.d/50-server.cnf
	sed -i 's/#max_connections        = 100/max_connections        = 1000/g' /etc/mysql/mariadb.conf.d/50-server.cnf
	echo 'sql-mode=""' >> /etc/mysql/mariadb.conf.d/50-server.cnf
	apply_nginx_php_sock
	mkdir /var/nginx
	echo 'FastDL it`s working :)' > /var/nginx/index.html
	systemctl start nginx
	systemctl restart php$PHP_VERSION-fpm
	systemctl restart mysql
	log_n "${MAGENTA}Создание нужных папок для работы серверов"
	mkdir /home/cp /home/cp/backups /home/cp/gameservers /home/cp/gameservers/files
	chown -R root /home/
	chmod -R 755 /home/
	chmod -R 700 /home/cp/backups
	log_n "${MAGENTA}Настройка SSH и FTP"
	sh -c "echo '' >> /etc/ssh/sshd_config"
	sh -c "echo 'DenyGroups gameservers' >> /etc/ssh/sshd_config"
	systemctl restart ssh
	systemctl restart sshd
	echo "yes" > /etc/pure-ftpd/conf/CreateHomeDir
	echo "yes" > /etc/pure-ftpd/conf/NoAnonymous
	echo "yes" > /etc/pure-ftpd/conf/ChrootEveryone
	echo "yes" > /etc/pure-ftpd/conf/VerboseLog
	echo "yes" > /etc/pure-ftpd/conf/IPV4Only
	echo "100" > /etc/pure-ftpd/conf/MaxClientsNumber
	echo "8" > /etc/pure-ftpd/conf/MaxClientsPerIP
	echo "no" > /etc/pure-ftpd/conf/DisplayDotFiles
	echo "15" > /etc/pure-ftpd/conf/MaxIdleTime
	echo "16" > /etc/pure-ftpd/conf/MaxLoad
	echo "50000 50300" > /etc/pure-ftpd/conf/PassivePortRange
	systemctl restart pure-ftpd
	load_docker_image
	log_n "${MAGENTA}Загрузка SteamCMD"
	apt-get install -y lib32stdc++6
	cd /root
	mkdir steamcmd
	cd steamcmd
	wget http://media.steampowered.com/client/steamcmd_linux.tar.gz
	tar xvfz steamcmd_linux.tar.gz
	rm steamcmd_linux.tar.gz
	log_n "================ Настройка игровой локации прошла успешно ================"
	Error_n "${green}Подключите локацию в панели управления"
	Error_n "${green}Базы данных серверов этой локации будут хранится на ней."
	Error_n "${green}Адрес phpmyadmin: ${white}http://$IP_SERV:8080/phpmyadmin"
	Error_n "${green}Данные для входа в phpmyadmin:"
	Error_n "${green}Пользователь: ${white}admin"
	Error_n "${green}Пароль: ${white}$ADMIN_PASS"
	Error_n "${green}Мониторинг нагрузки сервера: ${white}htop"
	log_n "=========================== vk.com/hosting_rus ==========================="
	Info
	log_tt "${white}Добро пожаловать в установочное меню ${MAGENTA}HOSTINPL 5.6"
	Info "- ${white}1 ${green}- ${white}Подключить файл подкачки"
	Info "- ${white}2 ${green}- ${white}Загрузить игры на локацию"
	Info "- ${white}0 ${green}- ${white}Выйти из установщика"
	log_s
	Info
	read -p "Пожалуйста, введите пункт меню: " case
	case $case in
		1) install_swap;;
		2) dop_games;;
		0) exit;;
	esac
}

install_panel_and_location()
{
	clear
	read -p "${MAGENTA}Пожалуйста, введите домен или IP:${reset}" DOMAIN
	read -p "${MAGENTA}Пожалуйста, введите ключ сайта reCaptcha v2:${reset}" R_KEY_V2
	read -p "${MAGENTA}Пожалуйста, введите секретный ключ reCaptcha v2:${reset}" R_SKEY_V2
	if [ $VER = "12" ]; then
		RELEASE="bookworm"
		PHP_VERSION="8.2"
	elif [ $VER = "13" ]; then
		RELEASE="trixie"
		PHP_VERSION="8.4"
	else
		RELEASE="bullseye"
		PHP_VERSION="7.4"
	fi
	log_n "${MAGENTA}Добавление репозитория Debian $VER ($RELEASE)"
	echo "deb http://deb.debian.org/debian $RELEASE main" > /etc/apt/sources.list
	echo "deb-src http://deb.debian.org/debian $RELEASE main" >> /etc/apt/sources.list
	echo "deb http://deb.debian.org/debian-security/ $RELEASE-security main" >> /etc/apt/sources.list
	echo "deb-src http://deb.debian.org/debian-security/ $RELEASE-security main" >> /etc/apt/sources.list
	echo "deb http://deb.debian.org/debian $RELEASE-updates main" >> /etc/apt/sources.list
	echo "deb-src http://deb.debian.org/debian $RELEASE-updates main" >> /etc/apt/sources.list
	log_n "${MAGENTA}Первоначальная настройка"
	export DEBIAN_FRONTEND=noninteractive
	echo "debconf debconf/frontend select Noninteractive" | debconf-set-selections
	echo iptables-persistent iptables-persistent/autosave_v4 boolean true | debconf-set-selections
	echo iptables-persistent iptables-persistent/autosave_v6 boolean true | debconf-set-selections
	groupadd gameservers 2>/dev/null || true
	Line_Number=$(grep -n "127.0.0.1" /etc/hosts | cut -d: -f 1)
	My_Hostname=$(hostname)
	if [[ -n $Line_Number ]]; then
		for Line_Number2 in $Line_Number ; do
			String=$(sed "${Line_Number2}q;d" /etc/hosts)
			if [[ $String != *"$My_Hostname"* ]]; then
				New_String="$String $My_Hostname"
				sed -i "${Line_Number2}s/.*/${New_String}/" /etc/hosts
			fi
		done
	else
		echo "127.0.0.1 $My_Hostname " >> /etc/hosts
	fi
	log_n "${MAGENTA}Обновление пакетов и апгрейд системы"
	apt-get -qq update
	apt-get -y upgrade
	log_n "${MAGENTA}Установка пакетов"
	apt-get install -y wget pwgen apt-transport-https ca-certificates gnupg
	wget https://download.docker.com/linux/debian/gpg -O - | apt-key add -
	echo "deb [arch=amd64] https://download.docker.com/linux/debian $RELEASE stable" >> /etc/apt/sources.list
	apt-get -qq update
	apt-get install docker-ce docker-ce-cli docker-buildx-plugin -y
	apt-get install -y cron wget pwgen apache2 php$PHP_VERSION php$PHP_VERSION-mysql php$PHP_VERSION-ssh2 php$PHP_VERSION-curl php$PHP_VERSION-mbstring php$PHP_VERSION-xml php$PHP_VERSION-zip php$PHP_VERSION-gd libapache2-mod-php$PHP_VERSION mariadb-server unzip htop iptables-persistent
	if [ $PHP_VERSION = "7.4" ]; then
		apt-get install -y php7.4-json
	fi
	systemctl enable netfilter-persistent.service
	systemctl stop apache2
	apt-get install -y nginx php$PHP_VERSION-fpm pure-ftpd
	ADMIN_PASS=$(pwgen -cns -1 28)
	PMA_PASS=$(pwgen -cns -1 24)
	blowfish_secret=$(pwgen -cns -1 32)
	mysql -e "GRANT ALL ON *.* TO 'admin'@'localhost' IDENTIFIED BY '$ADMIN_PASS' WITH GRANT OPTION"
	mysql -e "GRANT ALL PRIVILEGES ON phpmyadmin.* TO 'phpmyadmin'@'localhost' IDENTIFIED BY '$PMA_PASS'"
	mysql -e "FLUSH PRIVILEGES"
	log_n "${MAGENTA}Установка PhpMyAdmin"
	wget --no-check-certificate https://files.phpmyadmin.net/phpMyAdmin/$PMA_VERSION/phpMyAdmin-$PMA_VERSION-all-languages.tar.gz
	tar xvf phpMyAdmin-$PMA_VERSION-all-languages.tar.gz
	rm phpMyAdmin-$PMA_VERSION-all-languages.tar.gz
	mv phpMyAdmin-$PMA_VERSION-all-languages/ /usr/share/phpmyadmin
	fetch_install_file "${INSTALL_BASE_URL}/p/config_pma.txt" /usr/share/phpmyadmin/config.inc.php
	sed -i "s/blowfish_secret_input/${blowfish_secret}/g" /usr/share/phpmyadmin/config.inc.php
	sed -i "s/pmapass/${PMA_PASS}/g" /usr/share/phpmyadmin/config.inc.php
	mysql < /usr/share/phpmyadmin/sql/create_tables.sql
	mkdir -p /var/lib/phpmyadmin/tmp
	chown -R www-data:www-data /usr/share/phpmyadmin /var/lib/phpmyadmin
	chmod -R 770 /usr/share/phpmyadmin /var/lib/phpmyadmin
	chmod -R 640 /usr/share/phpmyadmin/config.inc.php
	rm -rf /usr/share/phpmyadmin/setup /usr/share/phpmyadmin/examples /usr/share/phpmyadmin/config.sample.inc.php
	log_n "${MAGENTA}Настройка Apache2, Nginx, PHP и MariaDB"
	systemctl stop nginx
	fetch_install_file "${INSTALL_BASE_URL}/l/config_nginx.txt" /etc/nginx/nginx.conf
	fetch_install_file "${INSTALL_BASE_URL}/p/config_apache_in_one_server.txt" /etc/apache2/sites-available/hostinpl.conf
	sed -i "s/domain.ru/${DOMAIN}/g" /etc/apache2/sites-available/hostinpl.conf
	apply_nginx_php_sock
	a2ensite hostinpl
	a2dissite 000-default
	a2enmod rewrite
	fetch_install_file "${INSTALL_BASE_URL}/p/php.ini" /etc/php/$PHP_VERSION/apache2/php.ini
	fetch_install_file "${INSTALL_BASE_URL}/p/php.ini" /etc/php/$PHP_VERSION/cli/php.ini
	fetch_install_file "${INSTALL_BASE_URL}/l/php.ini" /etc/php/$PHP_VERSION/fpm/php.ini
	ln -snf /usr/share/zoneinfo/Europe/Moscow /etc/localtime && echo Europe/Moscow > /etc/timezone
	sed -i 's/127.0.0.1/0.0.0.0/g' /etc/mysql/mariadb.conf.d/50-server.cnf
	sed -i 's/#max_connections        = 100/max_connections        = 1500/g' /etc/mysql/mariadb.conf.d/50-server.cnf
	echo 'sql-mode=""' >> /etc/mysql/mariadb.conf.d/50-server.cnf
	mkdir /var/nginx
	echo 'FastDL it`s working :)' > /var/nginx/index.html
	systemctl start nginx
	systemctl start apache2
	systemctl restart php$PHP_VERSION-fpm
	systemctl restart mysql
	log_n "${MAGENTA}Настройка Cronrab"
	(crontab -l ; echo "0 0 * * * bash -c 'php /var/www/cron.php index'") 2>&1 | grep -v "no crontab" | sort | uniq | crontab -
	(crontab -l ; echo "*/1 * * * * bash -c 'php /var/www/cron.php gameServers'") 2>&1 | grep -v "no crontab" | sort | uniq | crontab -
	(crontab -l ; echo "*/1 * * * * bash -c 'php /var/www/cron.php tasks'") 2>&1 | grep -v "no crontab" | sort | uniq | crontab -
	(crontab -l ; echo "*/10 * * * * bash -c 'php /var/www/cron.php serverReloader'") 2>&1 | grep -v "no crontab" | sort | uniq | crontab -
	(crontab -l ; echo "*/30 * * * * bash -c 'php /var/www/cron.php stopServers'") 2>&1 | grep -v "no crontab" | sort | uniq | crontab -
	(crontab -l ; echo "*/30 * * * * bash -c 'php /var/www/cron.php stopServersQuery'") 2>&1 | grep -v "no crontab" | sort | uniq | crontab -
	(crontab -l ; echo "0 * * * * bash -c 'php /var/www/cron.php updateStats'") 2>&1 | grep -v "no crontab" | sort | uniq | crontab -
	(crontab -l ; echo "0 * * * * bash -c 'php /var/www/cron.php updateStatsLocations'") 2>&1 | grep -v "no crontab" | sort | uniq | crontab -
	(crontab -l ; echo "0 * */7 * * bash -c 'php /var/www/cron.php clearLogs'") 2>&1 | grep -v "no crontab" | sort | uniq | crontab -
	systemctl restart cron
	install_panel_from_repo
	import_panel_database
	log_n "${MAGENTA}Создание нужных папок для работы серверов"
	mkdir /home/cp /home/cp/backups /home/cp/gameservers /home/cp/gameservers/files
	chown -R root /home/
	chmod -R 755 /home/
	chmod -R 700 /home/cp/backups
	log_n "${MAGENTA}Настройка SSH и FTP"
	sh -c "echo '' >> /etc/ssh/sshd_config"
	sh -c "echo 'DenyGroups gameservers' >> /etc/ssh/sshd_config"
	systemctl restart ssh
	systemctl restart sshd
	echo "yes" > /etc/pure-ftpd/conf/CreateHomeDir
	echo "yes" > /etc/pure-ftpd/conf/NoAnonymous
	echo "yes" > /etc/pure-ftpd/conf/ChrootEveryone
	echo "yes" > /etc/pure-ftpd/conf/VerboseLog
	echo "yes" > /etc/pure-ftpd/conf/IPV4Only
	echo "100" > /etc/pure-ftpd/conf/MaxClientsNumber
	echo "8" > /etc/pure-ftpd/conf/MaxClientsPerIP
	echo "no" > /etc/pure-ftpd/conf/DisplayDotFiles
	echo "15" > /etc/pure-ftpd/conf/MaxIdleTime
	echo "16" > /etc/pure-ftpd/conf/MaxLoad
	echo "50000 50300" > /etc/pure-ftpd/conf/PassivePortRange
	systemctl restart pure-ftpd
	load_docker_image
	log_n "${MAGENTA}Загрузка SteamCMD"
	apt-get install -y lib32stdc++6
	cd /root
	mkdir steamcmd
	cd steamcmd
	wget http://media.steampowered.com/client/steamcmd_linux.tar.gz
	tar xvfz steamcmd_linux.tar.gz
	rm steamcmd_linux.tar.gz
	service netfilter-persistent save
	log_n "==================== Установка HOSTINPL 5.6 успешно завершена ===================="
	Error_n "${green} - Адрес панели: ${white}http://$DOMAIN"
	Error_n "${green} -- Адрес phpmyadmin: ${white}http://$IP_SERV:8080/phpmyadmin"
	Error_n "${green} ---- Данные для входа в базу панели:"
	Error_n "${green} ---- Пользователь: ${white}admin"
	Error_n "${green} ---- Пароль: ${white}$ADMIN_PASS"
	Error_n "${green}Мониторинг нагрузки сервера: ${white}htop"
	log_n "=============================== vk.com/hosting_rus ==============================="
	Info
	log_tt "${white}Добро пожаловать в установочное меню ${MAGENTA}HOSTINPL 5.6"
	Info "- ${white}1 ${green}- ${white}Подключить файл подкачки"
	Info "- ${white}0 ${green}- ${white}Выйти из установщика"
	log_s
	Info
	read -p "Пожалуйста, введите пункт меню: " case
	case $case in
		1) install_swap;;
		0) exit;;
	esac
}

install_swap()
{
	clear
	read -p "${white}Введите размер файла подкачки (в GB. Например: 1):${reset}" GB
	fallocate -l ${GB}G /swapfile
    chmod 600 /swapfile
    mkswap /swapfile
    swapon /swapfile
    echo "/swapfile    none    swap    sw    0    0" >> /etc/fstab
	log_n "================ Файл подкачки размером в ${GB}GB успешно подключен! ==============="
}

dop_games()
{
	clear
	log_s
	log_tt "${white}Добро пожаловать в меню загрузки игр для ${MAGENTA}HOSTINPL 5.6 ${green}- ${white}Rino"
	Info "- ${white}1 ${green}- ${white}San Andreas: Multiplayer 0.3.7"
	Info "- ${white}2 ${green}- ${white}Criminal Russia: Multiplayer 0.3e"
	Info "- ${white}3 ${green}- ${white}Criminal Russia: Multiplayer 0.3.7"
	Info "- ${white}4 ${green}- ${white}United Multiplayer"
	Info "- ${white}5 ${green}- ${white}Multi Theft Auto: Multiplayer 1.5.9"
	Info "- ${white}6 ${green}- ${white}MineCraft: PE и MineCraft"
	Info "- ${white}7 ${green}- ${white}Counter Strike: 1.6"
	Info "- ${white}8 ${green}- ${white}Counter Strike: Source"
	Info "- ${white}9 ${green}- ${white}Counter Strike: GO (≈24GB)"
	Info "- ${white}10 ${green}- ${white}GTA V: RAGE MP (0.3.6, 0.3.7, 1.1)"
	Info "- ${white}0 ${green}- ${white}Выход в главное меню"
	log_s
	Info
	read -p "Пожалуйста, введите пункт меню: " case
	case $case in
		1)
			clear
			wget --inet4-only --no-check-certificate "${INSTALL_BASE_URL}/l/g/samp.zip"
			sleep 2
			if [ -f "/root/samp.zip" ]; then
				unzip samp.zip -d /home/cp/gameservers/files/samp
				rm samp.zip
				log_n "Игра успешно загружена на ваш сервер, включите ее для заказа в панели управления."
				Info "- ${white}1 ${green}- ${white}Вернуться в меню выбора игр"
				Info "- ${white}0 ${green}- ${white}Вернуться в главное меню"
				log_s
				Info
				read -p "Пожалуйста, введите пункт меню: " case
				case $case in
					1) dop_games;;
					0) menu;;
				esac
			else
				Info
				log_tt "${white}Отсутствует файл /root/samp.zip"
				Info "- ${white}0 ${green}- ${white}Выход"
				log_s
				Info
				read -p "Пожалуйста, введите пункт меню: " case
				case $case in
					0) exit;;
				esac
			fi
		;;
		2)
			clear
			wget --inet4-only --no-check-certificate "${INSTALL_BASE_URL}/l/g/crmp.zip"
			sleep 2
			if [ -f "/root/crmp.zip" ]; then
				unzip crmp.zip -d /home/cp/gameservers/files/crmp
				rm crmp.zip
				log_n "Игра успешно загружена на ваш сервер, включите ее для заказа в панели управления."
				Info "- ${white}1 ${green}- ${white}Вернуться в меню выбора игр"
				Info "- ${white}0 ${green}- ${white}Вернуться в главное меню"
				log_s
				Info
				read -p "Пожалуйста, введите пункт меню: " case
				case $case in
					1) dop_games;;
					0) menu;;
				esac
			else
				Info
				log_tt "${white}Отсутствует файл /root/crmp.zip"
				Info "- ${white}0 ${green}- ${white}Выход"
				log_s
				Info
				read -p "Пожалуйста, введите пункт меню: " case
				case $case in
				  0) exit;;
				esac
			fi
		;;
		3)
			clear
			wget --inet4-only --no-check-certificate "${INSTALL_BASE_URL}/l/g/crmp037.zip"
			sleep 2
			if [ -f "/root/crmp037.zip" ]; then
				unzip crmp037.zip -d /home/cp/gameservers/files/crmp037
				rm crmp037.zip
				log_n "Игра успешно загружена на ваш сервер, включите ее для заказа в панели управления."
				Info "- ${white}1 ${green}- ${white}Вернуться в меню выбора игр"
				Info "- ${white}0 ${green}- ${white}Вернуться в главное меню"
				log_s
				Info
				read -p "Пожалуйста, введите пункт меню: " case
				case $case in
					1) dop_games;;
					0) menu;;
				esac
			else
				Info
				log_tt "${white}Отсутствует файл /root/crmp037.zip"
				Info "- ${white}0 ${green}- ${white}Выход"
				log_s
				Info
				read -p "Пожалуйста, введите пункт меню: " case
				case $case in
				  0) exit;;
				esac
			fi
		;;
		4)
			clear
			wget --inet4-only --no-check-certificate "${INSTALL_BASE_URL}/l/g/unit.zip"
			sleep 2
			if [ -f "/root/unit.zip" ]; then
				unzip unit.zip -d /home/cp/gameservers/files/unit
				rm unit.zip
				log_n "Игра успешно загружена на ваш сервер, включите ее для заказа в панели управления."
				Info "- ${white}1 ${green}- ${white}Вернуться в меню выбора игр"
				Info "- ${white}0 ${green}- ${white}Вернуться в главное меню"
				log_s
				Info
				read -p "Пожалуйста, введите пункт меню: " case
				case $case in
					1) dop_games;;
					0) menu;;
				esac
			else
				Info
				log_tt "${white}Отсутствует файл /root/unit.zip"
				Info "- ${white}0 ${green}- ${white}Выход"
				log_s
				Info
				read -p "Пожалуйста, введите пункт меню: " case
				case $case in
				  0) exit;;
				esac
			fi
		;;
		5)
			clear
			wget --inet4-only --no-check-certificate "${INSTALL_BASE_URL}/l/g/mta.zip"
			sleep 2
			if [ -f "/root/mta.zip" ]; then
				unzip mta.zip -d /home/cp/gameservers/files/mta
				rm mta.zip
				log_n "Игра успешно загружена на ваш сервер, включите ее для заказа в панели управления."
				Info "- ${white}1 ${green}- ${white}Вернуться в меню выбора игр"
				Info "- ${white}0 ${green}- ${white}Вернуться в главное меню"
				log_s
				Info
				read -p "Пожалуйста, введите пункт меню: " case
				case $case in
					1) dop_games;;
					0) menu;;
				esac
			else
				Info
				log_tt "${white}Отсутствует файл /root/mta.zip"
				Info "- ${white}0 ${green}- ${white}Выход"
				log_s
				Info
				read -p "Пожалуйста, введите пункт меню: " case
				case $case in
				  0) exit;;
				esac
			fi
		;;
		6)
			clear
			wget --inet4-only --no-check-certificate "${INSTALL_BASE_URL}/l/g/mine_and_mcpe.zip"
			sleep 2
			if [ -f "/root/mine_and_mcpe.zip" ]; then
				unzip mine_and_mcpe.zip -d /
				rm mine_and_mcpe.zip
				log_n "Игры успешно загружены на ваш сервер, включите их для заказа в панели управления."
				Info "- ${white}1 ${green}- ${white}Вернуться в меню выбора игр"
				Info "- ${white}0 ${green}- ${white}Вернуться в главное меню"
				log_s
				Info
				read -p "Пожалуйста, введите пункт меню: " case
				case $case in
					1) dop_games;;
					0) menu;;
				esac
			else
				Info
				log_tt "${white}Отсутствует файл /root/mine_and_mcpe.zip"
				Info "- ${white}0 ${green}- ${white}Выход"
				log_s
				Info
				read -p "Пожалуйста, введите пункт меню: " case
				case $case in
				  0) exit;;
				esac
			fi
		;;
		7)
			clear
			wget --inet4-only --no-check-certificate "${INSTALL_BASE_URL}/l/g/cs.zip"
			sleep 2
			if [ -f "/root/cs.zip" ]; then
				unzip cs.zip -d /
				rm cs.zip
				log_n "Игра успешно загружена на ваш сервер, включите ее для заказа в панели управления."
				Info "- ${white}1 ${green}- ${white}Вернуться в меню выбора игр"
				Info "- ${white}0 ${green}- ${white}Вернуться в главное меню"
				log_s
				Info
				read -p "Пожалуйста, введите пункт меню: " case
				case $case in
					1) dop_games;;
					0) menu;;
				esac
			else
				Info
				log_tt "${white}Отсутствует файл /root/cs.zip"
				Info "- ${white}0 ${green}- ${white}Выход"
				log_s
				Info
				read -p "Пожалуйста, введите пункт меню: " case
				case $case in
				  0) exit;;
				esac
			fi
		;;
		8)
			clear
			wget --inet4-only --no-check-certificate "${INSTALL_BASE_URL}/l/g/css.zip"
			sleep 2
			if [ -f "/root/css.zip" ]; then
				mkdir /home/cp/gameservers/files/css
				log_n "${MAGENTA}Запуск SteamCMD"
				if [ -f "/root/steamcmd/steamcmd.sh" ]; then
					cd /root/steamcmd
					./steamcmd.sh +login anonymous +force_install_dir /home/cp/gameservers/files/css +app_update 232330 validate +quit
				else
					echo "${RED}Куда пропал SteamCMD?"
					tput sgr0
					exit
				fi
				log_n "${MAGENTA}Загрузка server.cfg"
				cd
				unzip css.zip -d /home/cp/gameservers/files/css
				rm css.zip
				log_n "Игра успешно загружена на ваш сервер, включите ее для заказа в панели управления."
				Info "- ${white}1 ${green}- ${white}Вернуться в меню выбора игр"
				Info "- ${white}0 ${green}- ${white}Вернуться в главное меню"
				log_s
				Info
				read -p "Пожалуйста, введите пункт меню: " case
				case $case in
					1) dop_games;;
					0) menu;;
				esac
			else
				Info
				log_tt "${white}Отсутствует файл /root/css.zip"
				Info "- ${white}0 ${green}- ${white}Выход"
				log_s
				Info
				read -p "Пожалуйста, введите пункт меню: " case
				case $case in
				  0) exit;;
				esac
			fi
		;;
		9)
			clear
			wget --inet4-only --no-check-certificate "${INSTALL_BASE_URL}/l/g/csgo.zip"
			sleep 2
			if [ -f "/root/csgo.zip" ]; then
				mkdir /home/cp/gameservers/files/csgo
				log_n "${MAGENTA}Запуск SteamCMD"
				if [ -f "/root/steamcmd/steamcmd.sh" ]; then
					cd /root/steamcmd
					./steamcmd.sh +login anonymous +force_install_dir /home/cp/gameservers/files/csgo +app_update 740 validate +quit
				else
					echo "${RED}Куда пропал SteamCMD?"
					tput sgr0
					exit
				fi
				log_n "${MAGENTA}Загрузка server.cfg"
				cd
				unzip csgo.zip -d /home/cp/gameservers/files/csgo
				rm csgo.zip
				log_n "Игра успешно загружена на ваш сервер, включите ее для заказа в панели управления."
				Info "- ${white}1 ${green}- ${white}Вернуться в меню выбора игр"
				Info "- ${white}0 ${green}- ${white}Вернуться в главное меню"
				log_s
				Info
				read -p "Пожалуйста, введите пункт меню: " case
				case $case in
					1) dop_games;;
					0) menu;;
				esac
			else
				Info
				log_tt "${white}Отсутствует файл /root/csgo.zip"
				Info "- ${white}0 ${green}- ${white}Выход"
				log_s
				Info
				read -p "Пожалуйста, введите пункт меню: " case
				case $case in
				  0) exit;;
				esac
			fi
		;;
		10)
			clear
			wget --inet4-only --no-check-certificate "${INSTALL_BASE_URL}/l/g/ragemp.zip"
			sleep 2
			if [ -f "/root/ragemp.zip" ]; then
				unzip ragemp.zip -d /
				rm ragemp.zip
				log_n "Игра успешно загружена на ваш сервер, включите ее для заказа в панели управления."
				Info "- ${white}1 ${green}- ${white}Вернуться в меню выбора игр"
				Info "- ${white}0 ${green}- ${white}Вернуться в главное меню"
				log_s
				Info
				read -p "Пожалуйста, введите пункт меню: " case
				case $case in
					1) dop_games;;
					0) menu;;
				esac
			else
				Info
				log_tt "${white}Отсутствует файл /root/ragemp.zip"
				Info "- ${white}0 ${green}- ${white}Выход"
				log_s
				Info
				read -p "Пожалуйста, введите пункт меню: " case
				case $case in
				  0) exit;;
				esac
			fi
		;;
		0) menu;;
	esac
}

menu()
{
	if [ ! $LOGIN = "root" ]; then
		log_n "${RED}Запустите установщик от имени root!"
		exit 1
	fi

	if [ $VIRT = "lxc" ] || [ $VIRT = "openvz" ]; then
		log_n "${RED}Виртуализация ${VIRT} не поддерживается!"
		exit 1
	fi

	if [ ! $VER = "11" ] && [ ! $VER = "12" ] && [ ! $VER = "13" ]; then
		clear
		log_s
		log_tt "${white}Добро пожаловать в установочное меню ${MAGENTA}HOSTINPL 5.6 (Debian 11 и 12) ${green}- ${white}lifesheets"
		Info "- ${white}1 ${green}- ${white}Загрузить игры на настроенную игровую локацию"
		Info "- ${white}2 ${green}- ${white}Подключить файл подкачки"
		Info "- ${white}0 ${green}- ${white}Выход из установщика"
		log_s
		Info
		read -p "Пожалуйста, введите пункт меню: " case
		case $case in
			1) dop_games;;
			2) install_swap;;
			0) exit;;
		esac

		exit 1
	fi

	if [ $RAM -lt "400" ]; then
		log_n "${RED}У Вас недостаточно RAM! Минимум 400 мб."
		exit 1
	fi

	clear
	log_s
	log_tt "${white}Добро пожаловать в установочное меню ${MAGENTA}HOSTINPL 5.6 (Debian 11, 12, 13) ${green}- ${white}lifesheets"
	Info "- ${white}1 ${green}- ${white}Настроить веб-часть"
	Info "- ${white}2 ${green}- ${white}Настроить игровую локацию"
	Info "- ${white}3 ${green}- ${white}Настроить веб-часть и игровую локацию (не рекомендуется)"
	Info "- ${white}4 ${green}- ${white}Загрузить игры на настроенную игровую локацию"
	Info "- ${white}5 ${green}- ${white}Подключить файл подкачки"
	Info "- ${white}0 ${green}- ${white}Выход из установщика"
	log_s
	Info
	read -p "Пожалуйста, введите пункт меню: " case
	case $case in
		1) install_panel;;
		2) install_location;;
		3) install_panel_and_location;;
		4) dop_games;;
		5) install_swap;;
		0) exit;;
	esac
}
menu
