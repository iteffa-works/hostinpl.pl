# Changelog

All notable changes to HOSTINPL are documented here.

Format based on [Keep a Changelog](https://keepachangelog.com/).

---

## [Unreleased] — FLOWAXY DIGITAL STUDIO

### Added

- Open-source release on GitHub ([iteffa-works/hostinpl.pl](https://github.com/iteffa-works/hostinpl.pl))
- Full documentation: `.docs/`, `README.md`, `AGENTS.md`, Cursor AI rules
- `ROADMAP.md`, `CONTRIBUTING.md`, `SECURITY.md` for GitHub community standards
- `.github/` issue templates, PR template, `FUNDING.yml`
- `application/config.example.php` — tracked config template (installer + dev)
- Root `.gitignore` — excludes secrets, `tmp/`, large install binaries
- `install/README.md` — CDN upload checklist
- `.docs/security.md`, `.docs/troubleshooting.md`
- Screenshots gallery (renamed: `panel-dashboard.jpg`, etc.)

### Changed

- **Ongoing development transferred to [FLOWAXY DIGITAL STUDIO](https://flowaxy.com)** — product remains **free**
- Footer/login links: `hostinpl.ru` → FLOWAXY / GitHub
- RAGE:MP node module URLs → `code.flowaxy.com/hostpanel`
- Install script uses `INSTALL_BASE_URL` and `PANEL_REPO_URL`
- Cron hourly jobs fixed (`0 * * * *` instead of invalid `*/60`)
- Nginx PHP-FPM socket mapping for PHP 8.2 and 8.4

### Fixed

- Panel `config.php` mismatch after Git clone (DB credentials, domain)
- Docker image download path in installer
- `groupadd gameservers` on re-run
- Missing `rsync` / git clone error handling

---

## [5.6] — 2026

### Added

- Debian 12 (bookworm) and 13 (trixie) installer support
- PHP 8.2 (D12) and 8.4 (D13) on locations
- Panel source via GitHub repository
- Install assets CDN structure (`p/`, `l/`, `l/g/`)

### Changed

- Latest phpMyAdmin 5.2.3
- Updated Rage:MP, MTA, Minecraft game packs

---

## [5.6] — 20.02.2022 (Debian 11)

- Debian 11 installer; Debian 9 end-of-life for updates
- Rage:MP 1.1 added
- MTA build updated to 1.5.9
- New Minecraft PE cores; removed non-working cores
- Fixed VKontakte authorization
- Freekassa API updated to new version
- Docker image: .NET for Rage:MP
- phpMyAdmin on location: Nginx + PHP-FPM
- ProFTPd replaced with Pure-FTPd on locations
- PHP 7.4, latest phpMyAdmin

---

## [5.x] — 31.12.2020

- Fixed CS 1.6 server startup and console logs
- Fixed bonus/money distribution in admin panel
- Free-Kassa standard API
- RAGE:MP version switching (0.3.6, 0.3.7)
- Unitpay electronic signature — works out of the box

---

## [5.x] — 19.12.2020

- Fixed session system; blocked users can no longer stay logged in
- Cron task error output for install/reinstall
- Location load statistics saving

---

## [5.x] — 04.12.2020

- Fixed console commands (MC, MINE, CS)
- Apache → Nginx migration for panel (cancelled)

---

## [5.x] — 21.11.2020

- Email change requires confirmation via current email
- CS 1.6 build switching
- MTA updated to 1.5.8

---

## Major features (HOSTINPL 5.x)

- Counter-Strike: GO, QIWI P2P payments
- Real-time server load statistics (no DB storage)
- CS / SAMP launch parameters (FastDL, VAC, Tickrate, RCON)
- Minecraft / MCPE core switching
- GTA V: RAGE MP + node modules tab
- Web hosting health checks
- Real-time tickets, support online status
- FastDL on Nginx, Pure-FTPd, elFinder FTP
- `serversModel` consolidation, task scheduler, repository module
- New panel UI design

---

## Credits

| Period | Team |
|--------|------|
| 2020–2022 | HOSTINPL (HOSTING-RUS) — Samir Shelenko, Alexander Zemlyanoy |
| 2026+ | [FLOWAXY DIGITAL STUDIO](https://flowaxy.com) — free product maintenance |
