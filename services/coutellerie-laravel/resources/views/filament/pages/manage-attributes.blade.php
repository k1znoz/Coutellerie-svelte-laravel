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
            <div>
                {{ $this->categoriesTable }}
            </div>
        @elseif ($activeTab === 'types')
            <div>
                {{ $this->typesTable }}
            </div>
        @elseif ($activeTab === 'materials')
            <div>
                {{ $this->materialsTable }}
            </div>
        @endif
    </div>
</x-filament-panels::page>
