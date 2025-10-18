<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashboard';

    protected static ?string $title = 'Tableau de bord';

    protected static ?string $navigationLabel = 'Accueil';

    protected static ?int $navigationSort = -2;

    public function getTitle(): string
    {
        return 'Tableau de bord - Coutellerie';
    }

    public function getHeading(): string
    {
        return 'Bienvenue dans l\'administration';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // Ici vous pouvez ajouter des widgets
        ];
    }
}