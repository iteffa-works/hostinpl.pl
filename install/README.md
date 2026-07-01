# Install package

Debian installer and CDN payloads for HOSTINPL 5.6.

## What is in this repository

| Path | In Git | On CDN |
|------|--------|--------|
| `install56_deb.sh` | Yes | Yes (mirror) |
| `p/` (panel configs) | Yes | Yes |
| `l/config_nginx.txt`, `l/php.ini` | Yes | Yes |
| `l/docker_images/` (~924 MB) | Yes (Git LFS) | Yes |
| `l/g/` game archives (~2.6 GB) | Yes (Git LFS for >100 MB) | Yes |
| `files/ragemp/node_modules/` (~1 MB) | Yes | Yes |
| `mods/` (11 game mods, ~250 MB) | Yes | Yes |
| `no.png` | Yes | Yes |

CDN base URL: **https://code.flowaxy.com/hostpanel**

Panel PHP code is **not** here — cloned from [GitHub](https://github.com/iteffa-works/hostinpl.pl) during install.

## CDN upload checklist

Upload to `https://code.flowaxy.com/hostpanel/`:

```
hostpanel/
├── install56_deb.sh
├── no.png
├── mods/
│   └── arizona.tar, capsrp.tar, … (11 RP mods)
├── p/
│   ├── config_pma.txt
│   ├── config_apache.txt
│   ├── config_apache_in_one_server.txt
│   └── php.ini
├── l/
│   ├── php.ini
│   ├── config_nginx.txt
│   ├── docker_images/debian_bullseye_hostinpl_02062024.tar
│   └── g/
│       ├── samp.zip, crmp.zip, mta.zip, cs.zip, ragemp.zip, ...
│       ├── css.zip, csgo.zip (config only; SteamCMD for game files)
│       └── mine_and_mcpe.zip
└── files/
    └── ragemp/
        └── node_modules/
            └── *.zip (16 RAGE:MP modules)
```

Requirements: direct HTTPS download, no authentication.

## Variables in installer

```bash
INSTALL_BASE_URL="https://code.flowaxy.com/hostpanel"
PANEL_REPO_URL="https://github.com/iteffa-works/hostinpl.pl"
```

## Documentation

- [Installation](../.docs/installation.md)
- [Hosting install assets](../.docs/hosting-assets.md)

Maintained by [FLOWAXY DIGITAL STUDIO](https://flowaxy.com).
