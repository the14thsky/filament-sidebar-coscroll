# Filament Sidebar Co-scroll

<img class="filament-hidden" src="https://raw.githubusercontent.com/the14thsky/filament-sidebar-coscroll/main/art/plugin-image.jpg" alt="Sidebar Co-scroll: the sidebar pins to the bottom when scrolling down and to the top when scrolling up">

[![Latest Version on Packagist](https://img.shields.io/packagist/v/the14thsky/filament-sidebar-coscroll.svg?style=flat-square)](https://packagist.org/packages/the14thsky/filament-sidebar-coscroll)
[![Tests](https://img.shields.io/github/actions/workflow/status/the14thsky/filament-sidebar-coscroll/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/the14thsky/filament-sidebar-coscroll/actions/workflows/tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/the14thsky/filament-sidebar-coscroll.svg?style=flat-square)](https://packagist.org/packages/the14thsky/filament-sidebar-coscroll)

Makes the Filament panel sidebar scroll together with the page instead of in its own scroll box. A menu taller than the window travels with the page in both directions: it pins to the bottom as you scroll down and to the top as you scroll up. A menu that fits stays pinned at the top. The open mobile drawer can co-scroll too.

Requires Filament 5.

## Installation

```bash
composer require the14thsky/filament-sidebar-coscroll
```

The plugin ships a small compiled stylesheet and script, so no theme or Tailwind changes are needed. Filament publishes them when Composer runs `php artisan filament:upgrade`. Otherwise, run:

```bash
php artisan filament:assets
```

## Usage

Register the plugin on each panel that should co-scroll:

```php
use The14thsky\SidebarCoscroll\SidebarCoscrollPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugin(SidebarCoscrollPlugin::make());
}
```

Panels without the plugin keep Filament's own sidebar scrolling.

### Options

| Method | Default | Description |
| --- | --- | --- |
| `topbarOffset(?string $offset)` | `4rem` | Height of everything above the sidebar, such as the topbar and any banner. The sidebar's minimum height is the window height minus this. |
| `mobileDrawer(bool $condition = true)` | `true` | Whether the open mobile drawer co-scrolls with the page. |

```php
SidebarCoscrollPlugin::make()
    ->topbarOffset('6.25rem')
    ->mobileDrawer(false)
```

## Known limitations

With SPA mode, every navigation scrolls the page back to the top, so the sidebar returns to the top as well.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [the14thsky](https://github.com/the14thsky)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
