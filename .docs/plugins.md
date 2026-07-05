# Plugins

HOSTINPL 5.6 plugin system — optional extensions loaded from `plugins/` without Composer or PSR-4.

## Registry

Add plugin folder ID to [application/includes/plugins.php](../application/includes/plugins.php):

```php
return array(
    'my-plugin',
);
```

## Plugin layout

```
plugins/{plugin_id}/
├── config.php              # metadata + default status
├── settings.json           # runtime status (created on install; gitignored)
├── init.php                # hooks/filters (loaded when active)
├── {sanitized_id}Plugin.php
├── controllers/            # optional admin routes
└── views/
```

**Lifecycle class name:** `preg_replace('/[^A-Za-z0-9]/', '', $plugin_id) . 'Plugin'`  
Example: `site-landing` → `sitelandingPlugin` in `sitelandingPlugin.php`.

## Status values

| Value | Meaning | `init.php` loaded |
|-------|---------|-------------------|
| `1` / `active` | Running | Yes |
| `0` / `installed` | Installed, disabled | No |
| `-1` / `uninstalled` | Not installed | No |

Manage plugins in admin: **Плагины** → `/admin/plugins` (access level 3).

## Lifecycle methods

Static methods on the plugin class:

- `install()` — setup (DB rows, defaults)
- `activate()` — enable runtime behaviour
- `disable()` — pause without removing data
- `uninstall()` — cleanup

Admin controller calls the method, then `Plugin::save_status($id, $newStatus)`.

## Hooks and filters

### Actions

```php
add_action('hostin_bootstrap', function($registry) { });
do_action('hostin_bootstrap', $registry);
```

| Hook | When |
|------|------|
| `hostin_bootstrap` | After `Plugin::initialize()` |
| `hostin_action_before` | Before routing (filter on action string) |
| `hostin_controller_before` | Before core controller method |
| `hostin_controller_after` | After core controller method |
| `hostin_plugin_controller_before` | Before plugin controller method |
| `hostin_plugin_controller_after` | After plugin controller method |
| `hostin_cron_{task}` | CLI cron task start |

### Filters

```php
add_filter('hostin.main.guest_view', function($view) {
    return 'plugin/my-plugin/landing';
}, 10, 1);

add_filter('hostin.view.path', function($file, $name) {
    if(strpos($name, 'plugin/my-plugin/') === 0) {
        $view = substr($name, strlen('plugin/my-plugin/'));
        return PLUGINS_DIR . 'my-plugin/views/' . $view . '.php';
    }
    return $file;
}, 10, 2);
```

| Filter | Purpose |
|--------|---------|
| `hostin_action_before` | Rewrite route string |
| `hostin.main.guest_view` | View name for guest home page |
| `hostin.view.path` | Absolute path to template file |
| `hostin.view.data` | Template variables |
| `hostin.head.content` | Extra HTML before `</head>` |
| `hostin.footer.content` | Extra HTML in footer |
| `hostin.plugin.route.access` | Min access level for plugin routes (default `3`) |
| `hostin.admin.menu_items` | Admin menu items (future use) |

## Plugin admin routes

Only for **active** plugins.

| URL | Controller file | Class |
|-----|-----------------|-------|
| `/admin/plugin/{id}` | `controllers/index.php` | `indexController` |
| `/admin/plugin/{id}/settings` | `controllers/settings.php` | `settingsController` |
| `/admin/plugin/{id}/settings/save` | method `save` on `settingsController` | |

Optional in `config.php`:

```php
'settings_url' => '/admin/plugin/my-plugin/settings',
```

Redirect after **Activate** in `/admin/plugins`.

## Reference plugin

See [plugins/site-landing/](../plugins/site-landing/) — replaces guest landing via filters and exposes `/admin/plugin/site-landing/settings`.

## Rules

- Plugins must not depend on each other; use hooks/filters only.
- No Composer autoload in plugins.
- Writable `plugins/{id}/` required on production for `settings.json`.
- New or modified PHP files use the FLOWAXY header (author, contacts, website, description, Created/Modified dates, copyright — see `.cursor/rules/hostinpl-core.mdc`).
