<?php

namespace App\Filament;

use Filament\Widgets\Dashboard as BaseDashboard;
use App\Filament\Widgets\AthletChart;

class Dashboard extends BaseDashboard
{
    protected static ?string $heading = 'Dashboard';

    protected function getWidgets(): array
    {
        return [
            AthletChart::class,
        ];
    }
}
