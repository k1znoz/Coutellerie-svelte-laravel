<?php

namespace App\Filament\Resources\ContactMessageResource\Pages;

use App\Filament\Resources\ContactMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\ContactMessage;

class ListContactMessages extends ListRecords
{
    protected static string $resource = ContactMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Pas d'action de création - les messages viennent du frontend
            Actions\Action::make('refreshPage')
                ->label('Actualiser')
                ->icon('heroicon-o-arrow-path')
                ->action(fn () => $this->redirect($this->getUrl())),
        ];
    }

    // Widget pour afficher les statistiques
    protected function getHeaderWidgets(): array
    {
        return [
            // Peut être étendu avec des widgets personnalisés
        ];
    }

    // Titre personnalisé avec compteur
    public function getTitle(): string
    {
        $newCount = ContactMessage::where('status', 'new')->count();
        return 'Messages de contact' . ($newCount > 0 ? " ({$newCount} nouveaux)" : '');
    }
}
