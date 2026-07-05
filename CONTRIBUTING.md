# Contributing to HOSTINPL

Thank you for contributing to **HOSTINPL 5.6** — a free product maintained by [FLOWAXY DIGITAL STUDIO](https://flowaxy.com).

## Before you start

1. Read [ROADMAP.md](ROADMAP.md) for planned work
2. Check [existing issues](https://github.com/iteffa-works/hostinpl.pl/issues)
3. For large changes, open an issue first

## Development setup

```bash
git clone https://github.com/iteffa-works/hostinpl.pl.git
cp application/config.example.php application/config.php
# Import hostinpl5_6.sql, configure local web server — see .docs/development.md
```

**Never commit** `application/config.php` with real passwords or API keys.

## Pull request process

1. Fork the repository
2. Create a branch: `feature/short-description` or `fix/short-description`
3. Keep changes focused and match existing PHP style
4. Use the FLOWAXY file header in **new or modified** PHP files (see `.cursor/rules/hostinpl-core.mdc`)
5. Update [CHANGELOG.md](CHANGELOG.md) for user-visible changes
6. Update `.docs/` if install flow, config, or architecture changes
7. Open a PR with a clear description

## PR checklist

- [ ] No secrets in committed files
- [ ] `config.example.php` updated if config keys change
- [ ] Installer/docs updated if deploy flow changes
- [ ] CHANGELOG.md updated (Unreleased section)

## Code style

- Custom MVC — not Laravel/Symfony
- Models: `{name}Model` in `application/models/`
- Minimal diffs; Registry pattern (`$this->registry`)
- Russian UI strings unless working on i18n

## Questions

Open a [GitHub Discussion](https://github.com/iteffa-works/hostinpl.pl/issues) or issue.

Support development: [flowaxy.com/donate](https://flowaxy.com/donate)
