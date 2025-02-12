<?php

namespace App\Filament\Resources\AthletResource\Pages;

use App\Filament\Resources\AthletResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAthlet extends ViewRecord
{
    protected static string $resource = AthletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
