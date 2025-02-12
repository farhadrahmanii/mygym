<?php

namespace App\Filament\Resources\AthletResource\Pages;

use App\Filament\Resources\AthletResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAthlet extends EditRecord
{
    protected static string $resource = AthletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
