<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Carte de bienvenue -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Administration
                            </dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                Coutellerie Laravel
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Raccourcis rapides -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">
                    Raccourcis
                </h3>
                <div class="space-y-2">
                    <a href="{{ route('filament.admin.resources.knives.index') }}" 
                       class="block text-primary-600 hover:text-primary-500">
                        → Gérer les couteaux
                    </a>
                    <a href="{{ route('filament.admin.resources.contact-messages.index') }}" 
                       class="block text-primary-600 hover:text-primary-500">
                        → Messages de contact
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">
                    Statistiques
                </h3>
                <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                    @php
                        try {
                            $knivesCount = \App\Models\Knife::count();
                            $messagesCount = \App\Models\ContactMessage::count();
                        } catch (\Exception $e) {
                            $knivesCount = 0;
                            $messagesCount = 0;
                        }
                    @endphp
                    <p>• Couteaux: {{ $knivesCount }}</p>
                    <p>• Messages: {{ $messagesCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <div class="bg-primary-50 dark:bg-primary-900/20 border border-primary-200 dark:border-primary-800 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-primary-800 dark:text-primary-200">
                        Interface d'administration
                    </h3>
                    <div class="mt-2 text-sm text-primary-700 dark:text-primary-300">
                        <p>Utilisez le menu de navigation pour gérer les contenus de votre site de coutellerie.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>