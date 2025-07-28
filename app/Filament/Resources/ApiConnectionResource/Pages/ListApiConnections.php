<?php

namespace App\Filament\Resources\ApiConnectionResource\Pages;

use App\Filament\Resources\ApiConnectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApiConnections extends ListRecords
{
    protected static string $resource = ApiConnectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
