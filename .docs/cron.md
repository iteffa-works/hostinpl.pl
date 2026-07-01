# Cron jobs

Panel cron is defined in `cron.php`. The installer registers these jobs automatically.

## Crontab (panel server)

```cron
0 0 * * * bash -c 'php /var/www/cron.php index'
*/1 * * * * bash -c 'php /var/www/cron.php gameServers'
*/1 * * * * bash -c 'php /var/www/cron.php tasks'
*/10 * * * * bash -c 'php /var/www/cron.php serverReloader'
*/30 * * * * bash -c 'php /var/www/cron.php stopServers'
*/30 * * * * bash -c 'php /var/www/cron.php stopServersQuery'
0 * * * * bash -c 'php /var/www/cron.php updateStats'
0 * * * * bash -c 'php /var/www/cron.php updateStatsLocations'
0 * */7 * * bash -c 'php /var/www/cron.php clearLogs'
```

## Task overview

| Job | Interval | Purpose |
|-----|----------|---------|
| `index` | Daily | General maintenance |
| `gameServers` | Every minute | Sync game server states |
| `tasks` | Every minute | Scheduled power on/off/restart |
| `serverReloader` | Every 10 min | Reload server configs |
| `stopServers` | Every 30 min | Stop expired servers |
| `stopServersQuery` | Every 30 min | Query-based stop logic |
| `updateStats` | Hourly | Panel statistics |
| `updateStatsLocations` | Hourly | Location load stats |
| `clearLogs` | Weekly | Log cleanup |

> Note: Use `0 * * * *` for hourly jobs — `*/60` is invalid in standard cron.

## Manual test

```bash
php /var/www/cron.php tasks
```
