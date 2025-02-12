<?php

namespace App\Filament\Resources\AthletResource\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Resources\AthletResource\Widgets\StatsOverview;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            // ...other widgets
        ];
    }
}
