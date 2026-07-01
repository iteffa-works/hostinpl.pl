# Security Policy

## Supported versions

| Version | Supported |
|---------|-----------|
| 5.6.x (FLOWAXY) | Yes |
| 5.x on Debian 9 | No |

## Reporting a vulnerability

**Do not** open public GitHub issues for security vulnerabilities.

1. Email or contact via [flowaxy.com](https://flowaxy.com)
2. Or open a **private** security advisory on GitHub (if enabled for the repo)

Include:

- Description of the issue
- Steps to reproduce
- Affected version / file paths
- Impact assessment (if known)

We aim to respond within 7 business days.

## Scope

In scope:

- Authentication / session handling
- Payment callback forgery
- Remote code execution via file uploads (`tmp/`)
- SQL injection in panel code
- SSH/location API abuse

Out of scope:

- Third-party dependencies (phpMyAdmin, elFinder) — report upstream
- Server misconfiguration (weak DB passwords, exposed phpMyAdmin)
- Denial of service without demonstrated exploit chain

## Best practices for deployments

- Use strong MariaDB passwords (installer generates these)
- Restrict phpMyAdmin access
- Keep Debian and PHP updated
- Do not commit `application/config.php` to version control

See [.docs/security.md](.docs/security.md) for deployment hardening.
