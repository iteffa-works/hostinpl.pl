# Architecture

HOSTINPL is a custom PHP MVC application (not Laravel/Symfony).

## Directory layout

```
hostinpl.pl/
├── index.php              # Front controller
├── cron.php               # CLI cron entry point
├── application/
│   ├── config.php         # Main settings (DB, payments, keys)
│   ├── controllers/       # Route handlers (folder/action)
│   ├── models/            # Data access (*Model classes)
│   └── views/             # PHP templates
├── engine/
│   ├── main/              # Core: Registry, Load, Action, DB, Session
│   ├── libs/              # SSH, query drivers, mail, pagination
│   ├── games/             # Game core/binary definitions
│   └── engine_ftp/        # elFinder-based FTP UI
├── assets/                # Metronic theme (JS/CSS)
├── tmp/                   # Uploads, avatars, ticket images
└── install/               # Debian installer + CDN payloads
```

## Request flow

1. `index.php` bootstraps `Registry` and core services
2. `Action` parses `REQUEST_URI` into `controller/action` (e.g. `servers/control`)
3. Controller class in `application/controllers/{path}/index.php` or named file
4. Controller loads models via `$this->load->model('servers')` → `serversModel`
5. View rendered via `$this->load->view('path', $data)`
6. `Response` outputs final HTML

## Key models

| Model | Responsibility |
|-------|----------------|
| `servers` | Game servers (central model for server operations) |
| `locations` | Remote game locations |
| `users` | Accounts, balance, auth |
| `invoices` | Payments |
| `tickets` | Support system |
| `games` | Available games and cores |
| `webhost` | Web hosting orders |

## Game locations

- Panel (Apache) talks to locations via SSH/API
- Locations run Docker (`hostinpl:games`), Nginx FastDL, Pure-FTPd
- Game files live under `/home/cp/gameservers/files/` on each location

## Cron

`cron.php` accepts action names (`index`, `gameServers`, `tasks`, etc.) called from system crontab. See [Cron jobs](cron.md).
