<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Livewire\Attributes\Url;

class ManageAttributes extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static string $view = 'filament.pages.manage-attributes';

    protected static ?string $navigationLabel = 'Attributs';

    protected static ?string $title = 'Gestion des Attributs';

    // Place cette page juste après "Couteaux" dans la navigation
    protected static ?int $navigationSort = 2;

    // Propriété pour gérer l'onglet actif
    #[Url]
    public string $activeTab = 'categories';

    public function getViewData(): array
    {
        return [
            'categories' => \App\Models\Category::all(),
            'types' => \App\Models\Type::all(),
            'materials' => \App\Models\Material::all(),
        ];
    }
}
