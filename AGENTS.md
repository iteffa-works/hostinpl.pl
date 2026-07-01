# AGENTS.md — HOSTINPL 5.6

Instructions for AI coding agents (Cursor, Copilot, Claude, etc.).

## Product

**HOSTINPL** — PHP game/web hosting billing panel (custom MVC, not Laravel).

| | |
|---|---|
| Maintainer | **FLOWAXY DIGITAL STUDIO** ([flowaxy.com](https://flowaxy.com)) |
| License | Free / open product |
| Original authors | Samir Shelenko, Alexander Zemlyanoy (HOSTINPL / HOSTING-RUS, 2020) |
| Repo | [github.com/iteffa-works/hostinpl.pl](https://github.com/iteffa-works/hostinpl.pl) |

## Stack

- PHP 7.4–8.4, MariaDB
- Panel: Apache · Location: Nginx + PHP-FPM + Docker
- UI: Metronic (`assets/`)
- Installer: `install/install56_deb.sh` (Debian 11–13)

## Entry points

| File | Role |
|------|------|
| `index.php` | HTTP bootstrap |
| `cron.php` | CLI cron (`php cron.php tasks`) |
| `application/config.example.php` | **Tracked** config template |
| `application/config.php` | **Local/production** — gitignored |
| `engine/main/action.php` | URI → controller |

## Routing

`/servers/control` → `application/controllers/servers/control.php`  
`$this->load->model('servers')` → `serversModel` in `application/models/servers.php`

## Critical paths

```
application/config.example.php   # Commit changes here, not config.php
application/models/servers.php   # Game server operations
engine/games/game_settings.php   # Game cores / RAGE module URLs
engine/engine_ftp/               # elFinder FTP UI
application/controllers/result/  # Payment webhooks
hostinpl5_6.sql                  # DB schema (database: hostin)
install/install56_deb.sh         # Production installer
```

## Deploy URLs

| Resource | URL |
|----------|-----|
| Install CDN | `https://code.flowaxy.com/hostpanel` |
| Panel repo | `https://github.com/iteffa-works/hostinpl.pl` |
| RAGE node modules | `https://code.flowaxy.com/hostpanel/files/ragemp/node_modules/` |
| Donate | `https://flowaxy.com/donate` |

## Files never to commit

- `application/config.php` (secrets)
- `.osp/` (local dev)
- `.vscode/settings.json` (machine-specific)
- `install/l/g/`, `install/l/docker_images/` (large binaries)
- `tmp/*` uploads

## Coding rules

1. **Minimal diffs** — match existing PHP style
2. **Registry pattern** — `$this->registry`, no DI container
3. **No Composer** for core logic
4. **Russian UI** in views unless i18n task
5. Update `CHANGELOG.md` + `.docs/` for user-visible changes
6. Update `config.example.php` when adding config keys
7. Follow [CONTRIBUTING.md](CONTRIBUTING.md)

## Anti-patterns

- Do not use Laravel/Symfony conventions
- Do not break `INSTALL_BASE_URL` / `PANEL_REPO_URL` without doc updates
- Do not assume `db_database` is `hostinpl56` — production uses `hostin`
- Do not reference dead `hostinpl.ru` URLs — use FLOWAXY / CDN / GitHub

## Cursor rules

See `.cursor/rules/*.mdc` for file-scoped hints.

## Documentation index

`.docs/README.md` · `CONTRIBUTING.md` · `SECURITY.md` · `ROADMAP.md` · `CHANGELOG.md`
