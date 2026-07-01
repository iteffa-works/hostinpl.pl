# Installation

HOSTINPL 5.6 installs on **Debian 11, 12, or 13** via `install/install56_deb.sh`.

## Prerequisites

- Clean or lightly configured Debian VPS/dedicated server
- Root access
- Public IPv4
- Minimum 400 MB RAM (game locations need significantly more)
- Not supported: LXC / OpenVZ virtualization

## Download installer

```bash
apt-get update -y && apt-get install wget -y
wget --inet4-only --no-check-certificate \
  "https://code.flowaxy.com/hostpanel/install56_deb.sh" -O install56_deb.sh
chmod +x install56_deb.sh
bash install56_deb.sh
```

## Installer menu

| # | Mode | Result |
|---|------|--------|
| 1 | Web panel | Apache + MariaDB + phpMyAdmin + panel in `/var/www/` |
| 2 | Game location | Nginx + Docker + Pure-FTPd + SteamCMD |
| 3 | Combined | Panel and location on one machine (not recommended) |
| 4 | Game packs | Download SAMP, MTA, CS, etc. to `/home/cp/gameservers/files/` |
| 5 | Swap | Add swap file |

During panel setup you will be prompted for:

- Domain or IP
- reCAPTCHA v2 site key and secret

## What the installer does (panel)

1. Configures Debian repositories and installs packages
2. Sets up MariaDB (`admin` user with generated password)
3. Installs phpMyAdmin 5.2.3
4. Configures Apache vhost from `code.flowaxy.com/hostpanel/p/`
5. Clones [github.com/iteffa-works/hostinpl.pl](https://github.com/iteffa-works/hostinpl.pl) into `/var/www/`
6. Writes `application/config.php` (domain, DB password, reCAPTCHA)
7. Imports `hostinpl5_6.sql` into database `hostin`
8. Registers cron jobs

## What the installer does (location)

1. Installs Docker, Nginx, PHP-FPM, Pure-FTPd, MariaDB
2. Loads Docker image `hostinpl:games` from CDN
3. Installs SteamCMD in `/root/steamcmd/`
4. Creates `/home/cp/gameservers/` tree

## Location-only + reboot

AppArmor is required for some location setups:

```bash
apt-get update -y && apt-get install wget apparmor -y && rm -f install56_deb.sh
reboot
```

After reboot, run the installer again.

## Post-install

| Item | Location |
|------|----------|
| Panel URL | `http://YOUR_DOMAIN/` |
| phpMyAdmin (panel) | `http://YOUR_DOMAIN/RANDOM_PATH` (shown after install) |
| phpMyAdmin (location) | `http://SERVER_IP:8080/phpmyadmin` |
| DB name | `hostin` |
| DB user | `admin` |
| DB password | Printed at end of install |

Connect the location in **Admin → Locations** using the location server IP and API credentials.

## Debian migration warning

- Upgrading **Debian 9 → 11/12/13 in place is not supported**.
- Mixed versions (panel D9 + location D11) **will not work**.
