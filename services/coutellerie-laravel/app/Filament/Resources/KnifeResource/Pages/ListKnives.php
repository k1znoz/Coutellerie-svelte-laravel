<?php

namespace App\Filament\Resources\KnifeResource\Pages;

use App\Filament\Resources\KnifeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKnives extends ListRecords
{
    protected static string $resource = KnifeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
