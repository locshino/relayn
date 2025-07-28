<?php

namespace App\Filament\Resources\ApiConnectionResource\Pages;

use App\Filament\Resources\ApiConnectionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApiConnection extends EditRecord
{
    protected static string $resource = ApiConnectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
