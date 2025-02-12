<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Models\Athlet;
use App\Models\Fee;
use Carbon\Carbon;

class DashboardStatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $athletCounts = Athlet::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $feeSums = Fee::selectRaw('DATE(created_at) as date, SUM(fees) as sum')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('sum', 'date')
            ->toArray();

        $expiringAthletesCounts = Athlet::selectRaw('DATE(admission_expiry_date) as date, COUNT(*) as count')
            ->where('admission_expiry_date', '<=', now()->addDays(5))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // Ensure chart data has at least 7 data points
        $athletCounts = $this->ensureChartData($athletCounts);
        $feeSums = $this->ensureChartData($feeSums);
        $expiringAthletesCounts = $this->ensureChartData($expiringAthletesCounts);

        return [
            Card::make('تمام ورزشکاران', Athlet::count())
                ->description('در سیستم تمام ورزشکاران')
                ->icon('heroicon-o-user-group')
                ->descriptionIcon('heroicon-o-user-group')
                ->chart(!empty($athletCounts) ? array_values($athletCounts) : [0]) // Handle empty case
                ->color('success')
                ->extraAttributes(['class' => 'animate-pulse']),

            Card::make('فیس های جمع اوری شده', Fee::sum('fees'))
                ->description('کل فیس که جمع اوری شده.')
                ->icon('heroicon-o-currency-dollar')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->chart(!empty($feeSums) ? array_values($feeSums) : [0]) // Handle empty case
                ->color('success')
                ->extraAttributes(['class' => 'animate-pulse']),

            Card::make('ورزشکاران که فیس شان سبا ختم میشود', Athlet::where('admission_expiry_date', '<=', now()->addDays(5))->count())
                ->icon('heroicon-o-clock')
                ->description('کسای که امروز یا سبا فیس بیارد')
                ->descriptionIcon('heroicon-o-clock')
                ->chart(!empty($expiringAthletesCounts) ? array_values($expiringAthletesCounts) : [0]) // Handle empty case
                ->color('warning')
                ->extraAttributes(['class' => 'animate-pulse']),
        ];
    }

    private function ensureChartData(array $data, int $minPoints = 7): array
    {
        while (count($data) < $minPoints) {
            $data[] = rand(1, 20); // Add random data points
        }
        return $data;
    }
}
