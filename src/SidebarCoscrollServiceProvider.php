<?php

declare(strict_types=1);

namespace The14thsky\SidebarCoscroll;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SidebarCoscrollServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-sidebar-coscroll';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name);
    }
}
