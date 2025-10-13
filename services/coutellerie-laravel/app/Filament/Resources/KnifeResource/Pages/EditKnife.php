<?php

namespace App\Filament\Resources\KnifeResource\Pages;

use App\Filament\Resources\KnifeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKnife extends EditRecord
{
    protected static string $resource = KnifeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
