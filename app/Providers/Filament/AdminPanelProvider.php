<?php

namespace App\Providers\Filament;

use Afsakar\FilamentOtpLogin\FilamentOtpLoginPlugin;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filafly\PhosphorIconReplacement;
use Filament\FontProviders\LocalFontProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\SpatieLaravelTranslatablePlugin;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Filament\Widgets\AccountWidget;
use Guava\FilamentKnowledgeBase\KnowledgeBasePlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use ShuvroRoy\FilamentSpatieLaravelBackup\FilamentSpatieLaravelBackupPlugin;
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;
use TomatoPHP\FilamentPWA\FilamentPWAPlugin;
// use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->registration()
            ->brandName('مایار جیم')
            ->login()
            ->spa()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->font(
                'Bahij Helvetica Neue', // Ensure this matches the font-family name in CSS
                url: asset('css/custom-font.css'),
                provider: LocalFontProvider::class,
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([

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
            ])
            ->authMiddleware([
                Authenticate::class,
            ])->plugins([
                    SpatieLaravelTranslatablePlugin::make()
                        ->defaultLocales(['en', 'fa']),
                    \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                    FilamentBackgroundsPlugin::make(),
                    KnowledgeBasePlugin::make(),
                    FilamentPWAPlugin::make(),
                    \Filafly\PhosphorIconReplacement::make(),
                    FilamentOtpLoginPlugin::make(),
                    FilamentSpatieLaravelBackupPlugin::make()
                    // FilamentSpatieRolesPermissionsPlugin::make()
                ]);
    }
}
