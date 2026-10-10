# Filament Sidebar Co-scroll

<img class="filament-hidden" src="https://raw.githubusercontent.com/the14thsky/filament-sidebar-coscroll/main/art/plugin-image.jpg" alt="Sidebar Co-scroll: the sidebar pins to the bottom when scrolling down and to the top when scrolling up">

[![Latest Version on Packagist](https://img.shields.io/packagist/v/the14thsky/filament-sidebar-coscroll.svg?style=flat-square)](https://packagist.org/packages/the14thsky/filament-sidebar-coscroll)
[![Tests](https://img.shields.io/github/actions/workflow/status/the14thsky/filament-sidebar-coscroll/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/the14thsky/filament-sidebar-coscroll/actions/workflows/tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/the14thsky/filament-sidebar-coscroll.svg?style=flat-square)](https://packagist.org/packages/the14thsky/filament-sidebar-coscroll)

Filament gives a long sidebar its own scroll box. Sidebar Co-scroll makes it move with the page instead: scroll down and it follows until its last item reaches the bottom of the window; scroll up and it comes straight back, stopping under the topbar. A menu that fits stays put.

It works with every sidebar layout (collapsible, fully collapsible and top navigation) and with the mobile drawer. There's no theme to rebuild or config to publish, and it only loads on the panels you register it on.

Requires Filament 4 or 5.

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

In SPA mode, Filament normally keeps the sidebar's scroll position when you move between pages. With co-scroll the sidebar scrolls with the page instead, and each SPA navigation starts the new page at the top, so the sidebar starts from the top too. After clicking an item far down a long menu, you'll need to scroll back down to see it.

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
