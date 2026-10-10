<?php

use Filament\Panel;
use Filament\Support\Assets\Asset;
use Filament\Support\Facades\FilamentAsset;
use The14thsky\SidebarCoscroll\SidebarCoscrollPlugin;

function bootCoscrollPanel(?SidebarCoscrollPlugin $plugin = null): void
{
    $panel = Panel::make()->id('test');

    if ($plugin) {
        $panel->plugin($plugin);
    }

    $panel->boot();
}

function coscrollAssetIds(array $assets): array
{
    return array_map(fn (Asset $asset): string => $asset->getId(), $assets);
}

it('loads its stylesheet and script on a panel that registers the plugin', function () {
    bootCoscrollPanel(SidebarCoscrollPlugin::make());

    $package = [SidebarCoscrollPlugin::ASSET_PACKAGE];

    expect(coscrollAssetIds(FilamentAsset::getStyles($package)))->toBe(['filament-sidebar-coscroll'])
        ->and(coscrollAssetIds(FilamentAsset::getScripts($package, withCore: false)))->toBe(['filament-sidebar-coscroll'])
        ->and(FilamentAsset::getScripts($package, withCore: false)[0]->isNavigateOnce())->toBeTrue();
});

it('loads nothing on a panel without the plugin', function () {
    bootCoscrollPanel();

    $package = [SidebarCoscrollPlugin::ASSET_PACKAGE];

    expect(FilamentAsset::getStyles($package))->toBeEmpty()
        ->and(FilamentAsset::getScripts($package, withCore: false))->toBeEmpty()
        ->and(FilamentAsset::getScriptData($package))->toBeEmpty();
});

it('passes the mobile drawer setting to the script', function (SidebarCoscrollPlugin $plugin, bool $hasMobileDrawer) {
    bootCoscrollPanel($plugin);

    expect(FilamentAsset::getScriptData([SidebarCoscrollPlugin::ASSET_PACKAGE]))
        ->toBe(['sidebarCoscroll' => ['mobileDrawer' => $hasMobileDrawer]]);
})->with([
    'enabled by default' => fn () => [SidebarCoscrollPlugin::make(), true],
    'disabled' => fn () => [SidebarCoscrollPlugin::make()->mobileDrawer(false), false],
]);

it('sets the topbar offset variable only when one is configured', function () {
    bootCoscrollPanel(SidebarCoscrollPlugin::make());

    expect(FilamentAsset::getCssVariables([SidebarCoscrollPlugin::ASSET_PACKAGE]))->toBeEmpty();

    bootCoscrollPanel(SidebarCoscrollPlugin::make()->topbarOffset('6.25rem'));

    expect(FilamentAsset::getCssVariables([SidebarCoscrollPlugin::ASSET_PACKAGE]))
        ->toBe(['sidebar-coscroll-topbar-offset' => '6.25rem']);
});
