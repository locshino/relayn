<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\OneDg\BalanceWidget;
use App\Models\ApiConnection;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('app')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
                BalanceWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                \Hasnayeen\Themes\Http\Middleware\SetTheme::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                // \Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin::make(),
                \Jeffgreco13\FilamentBreezy\BreezyCore::make()
                    ->myProfile(),
                \Hasnayeen\Themes\ThemesPlugin::make(),
            ])
            ->databaseNotifications()
            ->sidebarCollapsibleOnDesktop();
    }

    /**
     * Dynamically build the navigation groups.
     */
    private function getNavigationGroups(): array
    {
        $groups = [
            NavigationGroup::make('Admin Management')
                ->label('Quản lý Admin'),
        ];

        // Use cache to avoid DB queries on every request.
        // The 'onedg_api_exists' key is cached forever until an ApiConnection
        // is created, updated, or deleted, which clears the cache via an Observer.
        $oneDgApiExists = Cache::remember(
            key: 'onedg_api_exists',
            ttl: 3600, // Cache for 1 hour
            callback: fn () => ApiConnection::where('name', '1DG API')
                ->where('is_active', true)->exists()
        );

        if ($oneDgApiExists) {
            $groups[] = NavigationGroup::make('OneDg Api Management')
                ->label('Quản lý 1DG API');
        }

        return $groups;
    }
}
