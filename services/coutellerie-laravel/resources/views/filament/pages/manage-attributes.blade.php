<x-filament-panels::page>
    <x-filament::tabs>
        <x-filament::tabs.item
            :active="$activeTab === 'categories'"
            wire:click="$set('activeTab', 'categories')"
        >
            Catégories
        </x-filament::tabs.item>

        <x-filament::tabs.item
            :active="$activeTab === 'types'"
            wire:click="$set('activeTab', 'types')"
        >
            Types
        </x-filament::tabs.item>

        <x-filament::tabs.item
            :active="$activeTab === 'materials'"
            wire:click="$set('activeTab', 'materials')"
        >
            Matériaux
        </x-filament::tabs.item>
    </x-filament::tabs>

    <div class="mt-6">
        @if ($activeTab === 'categories')
            @livewire(\App\Filament\Resources\CategoryResource\Pages\ListCategories::class)
        @elseif ($activeTab === 'types')
            @livewire(\App\Filament\Resources\TypeResource\Pages\ListTypes::class)
        @elseif ($activeTab === 'materials')
            @livewire(\App\Filament\Resources\MaterialResource\Pages\ListMaterials::class)
        @endif
    </div>
</x-filament-panels::page>
