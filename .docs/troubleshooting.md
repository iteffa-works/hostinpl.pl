# Troubleshooting

Common HOSTINPL installation and runtime issues.

## Installer

### `wget` fails on CDN URL

- Verify `https://code.flowaxy.com/hostpanel/p/config_pma.txt` is reachable
- Check firewall allows outbound HTTPS
- Installer uses `--inet4-only` — ensure IPv4 connectivity

### `git clone` fails

- Install `git` manually: `apt-get install git`
- Verify GitHub is reachable from server
- Check `PANEL_REPO_URL` in `install56_deb.sh`

### Docker image not found after load

- Confirm tar downloaded completely (~924 MB)
- Run: `docker images | grep hostinpl`
- Re-download from `${INSTALL_BASE_URL}/l/docker_images/debian_bullseye_hostinpl_02062024.tar`

## Panel

### White screen / 500 error

- Check Apache error log: `/var/log/apache2/hostinpl_error.log`
- Verify `short_open_tag=On` in PHP ini
- Confirm `application/config.php` exists with correct DB credentials

### Database connection failed

- DB name must be `hostin` (installer default)
- User: `admin`, password: shown at end of install
- Test: `mysql -u admin -p hostin`

### Cron not running tasks

```bash
crontab -l
php /var/www/cron.php tasks
```

See [cron.md](cron.md) for full schedule.

## Location

### Games not available for order

- Download game packs via installer menu option **4**
- Enable game in **Admin → Games**
- Verify files exist in `/home/cp/gameservers/files/`

### FastDL not working

- Nginx listens on port 8080
- Check `/var/nginx/index.html` exists
- Firewall: allow port 8080

## Mixed Debian versions

Panel on Debian 11 + location on Debian 12 (or vice versa) is **unsupported**. Reinstall both on the same Debian major version.

## Still stuck?

1. [.docs/installation.md](installation.md)
2. [GitHub Issues](https://github.com/iteffa-works/hostinpl.pl/issues)
