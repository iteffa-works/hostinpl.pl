# HOSTINPL 5.6

A unique game hosting control panel featuring a lightweight modern design, up-to-date functionality, and enhanced security.

**Supported OS:** Debian 11, 12, and 13 (latest updates)

## Panel Installation

```bash
apt-get update -y && apt-get install wget -y && rm install56_deb.sh

wget --inet4-only --no-check-certificate "https://sources.flowaxy.com/download.php?hash=3fd3e8173d16923ba47c8d4f6f4cf441&type=install_bash&user=bGlmZXNoZWV0cw==" -O install56_deb.sh

chmod 777 install56_deb.sh && bash install56_deb.sh
```

## Location Installation (or Panel + Location on One Server)

> **Note:** The second step (`reboot`) is required.

```bash
apt-get update -y && apt-get install wget apparmor -y && rm install56_deb.sh

reboot
```

After reboot:

```bash
wget --inet4-only --no-check-certificate "https://sources.flowaxy.com/download.php?hash=3fd3e8173d16923ba47c8d4f6f4cf441&type=install_bash&user=bGlmZXNoZWV0cw==" -O install56_deb.sh

chmod 777 install56_deb.sh && bash install56_deb.sh
```

## Dependencies

- `php-mysql`
- `php-ssh2`
- `php-mbstring`
- `short_open_tag` enabled in `php.ini`
- PHP **7.4–8.2** recommended (not tested on PHP 8.3+)

## Docker

Dockerfile download:

https://sources.flowaxy.com/download.php?hash=3d416709485a3070bab617533ca95124&type=dockerfile&user=bGlmZXNoZWV0cw==

## What's New in This Version

- Latest phpMyAdmin
- PHP 7.4–8.2 support
- Updated packages in the distribution
- Support for newer Rage:MP, MTA, Minecraft, and other game versions
- Fixed VK (VKontakte) authorization
- New FreeKassa API

## Important: Debian Migration

Migrating from **Debian 9** to **Debian 11/12** without issues is **not possible** — changes were made on both the location and panel sides.

Running the panel on Debian 9 with a location on Debian 11/12 (or vice versa) is also **not supported**.

## Payment Gateway Configuration

Replace `domain.ru` with your domain.

### General URLs

| Purpose   | URL                              |
|-----------|----------------------------------|
| Success   | `http://domain.ru/account/success` |
| Error     | `http://domain.ru/account/error`   |

### Gateway Notification URLs

| Gateway        | Notification URL                        |
|----------------|-----------------------------------------|
| FreeKassa.RU   | `http://domain.ru/result/freekassa`     |
| Robokassa.RU   | `http://domain.ru/result/robokassa`     |
| Enot.IO        | `http://domain.ru/result/enot`          |
| Anipay.IO      | `http://domain.ru/result/anypay`        |
| Unitpay        | `http://domain.ru/result/unitpay`       |
| Qiwi P2P       | `http://domain.ru/result/qiwi`          |
| YooMoney       | `http://domain.ru/result/yandexkassa`   |

For YooMoney, also set the HTTP notification URL in your account:
https://yoomoney.ru/transfer/myservices/http-notification

## Manual Cron Setup (Debian 11/12 Panel)

Add to crontab (`crontab -e`):

```cron
0 0 * * * bash -c 'php /var/www/cron.php index'
*/1 * * * * bash -c 'php /var/www/cron.php gameServers'
*/1 * * * * bash -c 'php /var/www/cron.php tasks'
*/10 * * * * bash -c 'php /var/www/cron.php serverReloader'
*/30 * * * * bash -c 'php /var/www/cron.php stopServers'
*/30 * * * * bash -c 'php /var/www/cron.php stopServersQuery'
*/60 * * * * bash -c 'php /var/www/cron.php updateStats'
*/60 * * * * bash -c 'php /var/www/cron.php updateStatsLocations'
0 * */7 * * bash -c 'php /var/www/cron.php clearLogs'
```

## License

Copyright (c) 2020 HOSTINPL (HOSTING-RUS).  
Developed by Samir Shelenko and Alexander Zemlyanoy.
