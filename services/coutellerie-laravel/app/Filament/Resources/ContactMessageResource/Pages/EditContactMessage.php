<?php

namespace App\Filament\Resources\ContactMessageResource\Pages;

use App\Filament\Resources\ContactMessageResource;
use App\Models\ContactMessage;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContactMessage extends EditRecord
{
    protected static string $resource = ContactMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('markAsReplied')
                ->label('Marquer comme répondu')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->action(function () {
                    $this->record->markAsReplied();
                    $this->refreshFormData(['status']);
                })
                ->visible(fn () => $this->record->status !== ContactMessage::STATUS_REPLIED),

            Actions\Action::make('sendEmail')
                ->label('Répondre par email')
                ->icon('heroicon-o-envelope')
                ->color('primary')
                ->url(fn () => 'mailto:' . $this->record->email . '?subject=Re: ' . urlencode($this->record->subject))
                ->openUrlInNewTab(),

            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Marquer comme lu quand on ouvre le message pour la première fois
        if ($this->record && $this->record->status === ContactMessage::STATUS_NEW) {
            $this->record->markAsRead();
        }

        return $data;
    }

    // Empêcher la création de nouveaux messages depuis l'interface admin
    protected function canCreate(): bool
    {
        return false;
    }
}
