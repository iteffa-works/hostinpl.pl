# Configuration

## Main config file

Copy the template before first run:

```bash
cp application/config.example.php application/config.php
```

`application/config.php` is loaded by `engine/main/config.php` (`Config` class).  
**This file is gitignored** — never commit production secrets.

### Production template (installer placeholders)

After install, the installer sets:

```php
'url'         => 'http://YOUR_DOMAIN/',
'db_hostname' => 'localhost',
'db_username' => 'admin',
'db_password' => '<generated>',
'db_database' => 'hostin',
```

Installer replaces in `config.example.php` clone: `domen.ru`, `parol`, `edit_r_key_v2`, `edit_r_skey_v2`.

### Payments

Toggle gateways with `'1'` / `'0'` and set credentials:

- `freekassa`, `robokassa`, `unitpay`, `qiwi`, `yandexkassa`, `enotpay`, `anypay`, `litekassa`

See [Payments](payments.md) for callback URLs.

### reCAPTCHA

```php
'recaptcha' => 'site_key',
'secret_recaptcha' => 'secret_key',
```

### VK login

```php
'vk_app_status' => '1',
'vk_app_id' => '...',
'vk_app_secretkey' => '...',
```

### Game settings

`engine/games/game_settings.php` — MCPE cores, RAGE:MP node modules, binary paths.

### Local development

Use OSP / local stack. Example:

- URL: `http://hostinpl.local/`
- Copy `config.example.php` → `config.php` and set local DB

See [.osp/project.ini.example](../.osp/project.ini.example) for Open Server Panel.

## Apache vhost (panel)

Installed from CDN: `p/config_apache.txt`  
Placeholders: `domain.ru`, `pma_edit` (random phpMyAdmin path).

## Nginx (location)

Installed from CDN: `l/config_nginx.txt`  
Includes FastDL on port 8080 and phpMyAdmin via PHP-FPM socket.
