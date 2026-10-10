<?php

namespace The14thsky\SidebarCoscroll;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;

class SidebarCoscrollPlugin implements Plugin
{
    public const ASSET_PACKAGE = 'the14thsky/filament-sidebar-coscroll';

    protected ?string $topbarOffset = null;

    protected bool $hasMobileDrawer = true;

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function getId(): string
    {
        return 'filament-sidebar-coscroll';
    }

    public function topbarOffset(?string $offset): static
    {
        $this->topbarOffset = $offset;

        return $this;
    }

    public function getTopbarOffset(): ?string
    {
        return $this->topbarOffset;
    }

    public function mobileDrawer(bool $condition = true): static
    {
        $this->hasMobileDrawer = $condition;

        return $this;
    }

    public function hasMobileDrawer(): bool
    {
        return $this->hasMobileDrawer;
    }

    /**
     * Assets go on the panel instead of through `FilamentAsset::register()` in the service
     * provider, as the plugin docs suggest, so only panels that register the plugin load them.
     * The stylesheet restyles `.fi-sidebar` unconditionally, so a global registration would
     * make every panel co-scroll.
     */
    public function register(Panel $panel): void
    {
        $panel->assets([
            Css::make('filament-sidebar-coscroll', __DIR__ . '/../resources/dist/filament-sidebar-coscroll.css'),
            Js::make('filament-sidebar-coscroll', __DIR__ . '/../resources/dist/filament-sidebar-coscroll.js'),
        ], static::ASSET_PACKAGE);
    }

    public function boot(Panel $panel): void
    {
        FilamentAsset::registerScriptData([
            'sidebarCoscroll' => [
                'mobileDrawer' => $this->hasMobileDrawer(),
            ],
        ], static::ASSET_PACKAGE);

        if (filled($this->getTopbarOffset())) {
            FilamentAsset::registerCssVariables([
                'sidebar-coscroll-topbar-offset' => $this->getTopbarOffset(),
            ], static::ASSET_PACKAGE);
        }
    }
}
