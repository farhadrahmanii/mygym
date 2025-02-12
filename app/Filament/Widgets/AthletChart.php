<?php

namespace App\Filament\Widgets;

use App\Models\Athlet;
use Filament\Widgets\LineChartWidget;

class AthletChart extends LineChartWidget
{
    protected static ?string $heading = 'ثبت نام شاگردان';

    protected static bool $isLazy = true;
    protected static ?string $pollingInterval = '5s';
    protected int|string|array $columnSpan = 'full';
    protected static ?string $maxHeight = '250px';
    protected static ?int $sort = 2;
    protected function getData(): array
    {
        $athletCounts = Athlet::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'ثبت نام شاگردان',
                    'data' => array_values($athletCounts),
                ],
            ],
            'labels' => array_keys($athletCounts),
        ];
    }
}
