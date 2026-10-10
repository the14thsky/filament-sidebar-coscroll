<?php

namespace Workbench\App\Providers;

use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use The14thsky\SidebarCoscroll\SidebarCoscrollPlugin;
use Workbench\App\Filament\Pages\LongPage;

/**
 * One panel per sidebar layout, so each can be checked in the browser at its own path.
 */
class WorkbenchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // The package tests boot their own panels and assert on the globally registered assets.
        if ($this->app->runningUnitTests()) {
            return;
        }

        $layouts = [
            'default' => fn (Panel $panel): Panel => $panel->default(),
            'collapsible' => fn (Panel $panel): Panel => $panel->sidebarCollapsibleOnDesktop(),
            'fully-collapsible' => fn (Panel $panel): Panel => $panel->sidebarFullyCollapsibleOnDesktop(),
            'top-navigation' => fn (Panel $panel): Panel => $panel->topNavigation(),
            'short-menu' => fn (Panel $panel): Panel => $panel,
            'without-plugin' => fn (Panel $panel): Panel => $panel,
        ];

        foreach ($layouts as $id => $configure) {
            Filament::registerPanel(fn (): Panel => $configure($this->panel($id)));
        }
    }

    protected function panel(string $id): Panel
    {
        $panel = Panel::make()
            ->id($id)
            ->path($id)
            ->brandName(str($id)->headline())
            ->login()
            ->pages([LongPage::class])
            ->navigationItems($id === 'short-menu' ? [] : $this->navigationItems())
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);

        if ($id !== 'without-plugin') {
            $panel->plugin(SidebarCoscrollPlugin::make());
        }

        return $panel;
    }

    /**
     * Enough items for the sidebar to be taller than the window.
     *
     * @return array<NavigationItem>
     */
    protected function navigationItems(): array
    {
        $items = [];

        foreach (['Sales', 'Catalog', 'Customers', 'Reports', 'Settings'] as $group) {
            foreach (range(1, 8) as $number) {
                $items[] = NavigationItem::make("{$group} item {$number}")
                    ->group($group)
                    ->icon(Heroicon::OutlinedSquares2x2)
                    ->url('#');
            }
        }

        return $items;
    }
}
