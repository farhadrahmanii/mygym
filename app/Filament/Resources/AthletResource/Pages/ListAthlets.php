<?php

namespace App\Filament\Resources\AthletResource\Pages;

use App\Filament\Resources\AthletResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListAthlets extends ListRecords
{
    protected static string $resource = AthletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->orderByRaw("CASE 
                WHEN admission_expiry_date <= NOW() + INTERVAL 1 DAY THEN 1 
                WHEN admission_expiry_date <= NOW() + INTERVAL 10 DAY THEN 2 
                ELSE 3 
            END");
    }

}
