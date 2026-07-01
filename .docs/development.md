# Development

## Local setup

1. Clone the repository
2. `cp application/config.example.php application/config.php`
3. Point web root to project directory (Apache + PHP 8.x)
4. Import `hostinpl5_6.sql` into MariaDB database `hostin`
5. Edit `application/config.php` for local DB and URL
6. Enable `short_open_tag=On` in `php.ini`

OSP: copy [.osp/project.ini.example](../.osp/project.ini.example) to `.osp/project.ini` and adjust domain. Use **PHP 8.1** in OSPanel (legacy code is not PHP 8.2-clean). Copy `.user.ini` from repo root (or create it) to hide deprecations locally without changing engine code.

## Code conventions

- **PHP** with short tags allowed in views (`<?` / `<?php`)
- **Models**: `application/models/{name}.php` → class `{name}Model extends Model`
- **Controllers**: `application/controllers/{area}/{action}.php`
- **Views**: `application/views/{area}/{action}.php`
- **Registry**: shared services via `$this->registry` in controllers/models
- **No Composer** for core app — dependencies are bundled in `engine/libs/`

## Adding a controller action

1. Create `application/controllers/foo/bar.php`
2. Create view `application/views/foo/bar.php`
3. Route: `/foo/bar` maps via `Action::make()`

## Adding a model

1. Create `application/models/foo.php` with class `fooModel extends Model`
2. Load in controller: `$this->load->model('foo')`
3. Access: `$this->fooModel->method()`

## AI / Cursor

Project rules live in `.cursor/rules/`. See `AGENTS.md` for agent context.

## Security notes

- Never commit `application/config.php` — use `config.example.php` as the tracked template
- `tmp/` stores user uploads — keep outside web execution where possible
- SSH keys for locations are sensitive — handle via admin UI only

## Contributing

Maintained by **[FLOWAXY DIGITAL STUDIO](https://flowaxy.com)** as a free product.

1. Fork [github.com/iteffa-works/hostinpl.pl](https://github.com/iteffa-works/hostinpl.pl)
2. Create a feature branch
3. Keep changes focused; match existing style
4. Update `CHANGELOG.md` for user-visible changes
5. Open a pull request — see [ROADMAP.md](../ROADMAP.md) for priorities

## Support

- Donate: [flowaxy.com/donate](https://flowaxy.com/donate)
