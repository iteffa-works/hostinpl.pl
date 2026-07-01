# Security

Deployment and development security notes for HOSTINPL 5.6.

## Secrets management

| File | Rule |
|------|------|
| `application/config.php` | **Never commit** — use `config.example.php` as template |
| Payment API keys | Store only in `config.php` on server |
| SSH keys for locations | Managed via admin panel, not in Git |

```bash
cp application/config.example.php application/config.php
# Edit with production values
```

## File uploads

User content is stored under `tmp/`:

- `tmp/avatar/` — profile images
- `tmp/tickets_img/` — ticket attachments

Ensure `.htaccess` blocks script execution in upload directories (included in repo).

## Payment callbacks

Webhook URLs must use HTTPS in production. Verify signatures in:

`application/controllers/result/*.php`

See [payments.md](payments.md) for gateway URL configuration.

## Database

- Installer creates `admin` user with random password
- Production database name: `hostin`
- Do not expose MariaDB port publicly on panel servers

## phpMyAdmin

- Panel install: random URL path + htpasswd (installer sets this)
- Location: port 8080 — restrict via firewall

## Location servers

- Docker runs game processes isolated per server
- `gameservers` group denied SSH (`DenyGroups` in installer)
- Pure-FTPd chroot enabled

## Reporting issues

See [SECURITY.md](../SECURITY.md) for vulnerability disclosure.
