# HOSTINPL — Roadmap

Planned updates by **[FLOWAXY DIGITAL STUDIO](https://flowaxy.com)**.  
HOSTINPL remains a **free, open-source product**.

> Status key: `Planned` · `In progress` · `Done`

---

## 2026 Q2–Q3

| Item | Status | Description |
|------|--------|-------------|
| GitHub open-source release | Done | Public repo, docs, installer CDN |
| Install pipeline refactor | Done | Git clone panel, `code.flowaxy.com/hostpanel` CDN |
| Documentation (`.docs/`, README) | Done | Full English documentation |
| Installer stability | Done | Config sed, cron fixes, error handling, config.example.php |
| PHP 8.3+ compatibility audit | Planned | Test panel on PHP 8.3/8.4 beyond Debian 13 |
| Security review | Planned | Session, payment callbacks, file uploads |
| Plugin system (hooks, filters, admin UI) | Done | `/admin/plugins`, `/admin/plugin/{id}/...`, reference `site-landing` |
| GitHub community files | Done | CONTRIBUTING, SECURITY, issue templates, FUNDING |

---

## 2026 Q4

| Item | Status | Description |
|------|--------|-------------|
| Docker images for Debian 12/13 | Planned | Updated `hostinpl:games` base images |
| Game core updates | Planned | Newer MTA, RAGE:MP, Minecraft builds |
| Payment gateway refresh | Planned | Verify FreeKassa, Unitpay, YooMoney APIs |
| CDN / install mirror docs | Done | install/README.md, .docs/hosting-assets.md |

---

## 2027+

| Item | Status | Description |
|------|--------|-------------|
| UI modernization | Planned | Incremental Metronic / UX improvements |
| Optional English admin UI | Planned | i18n layer for admin and user areas |
| API for external integrations | Planned | REST hooks for billing automation |
| Automated tests (installer smoke) | Planned | CI checks for critical paths |
| Location health dashboard | Planned | Central view of all location nodes |

---

## How to influence the roadmap

- Open a [GitHub Issue](https://github.com/iteffa-works/hostinpl.pl/issues) with the `enhancement` label
- Support development: [flowaxy.com/donate](https://flowaxy.com/donate)

Completed items move to [CHANGELOG.md](CHANGELOG.md).
