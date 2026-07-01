# Hosting install assets

The installer downloads configs and game archives from a public HTTP mirror.  
Default URL is set in `install/install56_deb.sh`:

```bash
INSTALL_BASE_URL="https://code.flowaxy.com/hostpanel"
```

The panel itself is cloned from GitHub — it is **not** part of the CDN package.

## CDN directory layout

Upload the contents of the `install/` folder (except large binaries if using Git LFS) to:

```
https://code.flowaxy.com/hostpanel/
├── install56_deb.sh
├── no.png
├── mods/
│   ├── arizona.tar, diamond-rp.tar, … (SAMP/CRMP RP mods)
│   └── …
├── p/
│   ├── config_pma.txt
│   ├── config_apache.txt
│   ├── config_apache_in_one_server.txt
│   └── php.ini
└── l/
    ├── php.ini
    ├── config_nginx.txt
    ├── docker_images/
    │   └── debian_bullseye_hostinpl_02062024.tar   (~924 MB)
    └── g/
        ├── samp.zip
        ├── crmp.zip
        ├── crmp037.zip
        ├── unit.zip
        ├── mta.zip
        ├── mine_and_mcpe.zip    (~361 MB)
        ├── cs.zip               (~1.2 GB)
        ├── ragemp.zip             (~86 MB)
        ├── css.zip              (server.cfg only; CS:S via SteamCMD)
        └── csgo.zip             (server.cfg only; CS:GO via SteamCMD)
└── files/
    └── ragemp/
        └── node_modules/        (16 × .zip — RAGE:MP Node modules)
```

## Requirements for the mirror

- Direct file download without authentication
- HTTPS recommended
- Stable paths matching `${INSTALL_BASE_URL}/p/...`, `${INSTALL_BASE_URL}/l/...`, and `${INSTALL_BASE_URL}/files/...`

## Panel source (GitHub)

```
https://github.com/iteffa-works/hostinpl.pl
```

Cloned at install time into `/var/www/`. Excluded from sync: `.git`, `install/`, `.osp`, `.vscode`.

## External dependencies (fetched by installer)

| Resource | Source |
|----------|--------|
| phpMyAdmin 5.2.3 | https://files.phpmyadmin.net/ |
| SteamCMD | http://media.steampowered.com/client/steamcmd_linux.tar.gz |
| Docker CE | https://download.docker.com/linux/debian |

## Large files and Git

`install/.gitignore` excludes `l/docker_images/`, `l/g/` from git.  
Use Git LFS, a release asset, or upload binaries directly to the CDN.
