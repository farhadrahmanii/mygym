<?php

namespace App\Providers;

use Filament\PluginServiceProvider;
use Filament\Widgets\Widget;
use App\Filament\Dashboard;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends PluginServiceProvider
{
    public function register(): void
    {
        parent::register();

        $this->app->singleton(Dashboard::class, function () {
            return new Dashboard();
        });
    }

    public function boot(): void
    {
        parent::boot();

        Widget::register([
            Dashboard::class,
        ]);
    }
}
