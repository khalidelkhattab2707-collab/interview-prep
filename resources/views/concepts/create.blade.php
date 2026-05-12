<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nouveau Concept') }} — {{ $domain->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('domains.concepts.store', $domain) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               placeholder="Ex: Eloquent N+1 Problem" required>
                        @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="explanation" class="block text-sm font-medium text-gray-700 mb-1">Explication</label>
                        <textarea name="explanation" id="explanation" rows="6"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="Décrivez ce concept en vos propres mots..." required>{{ old('explanation') }}</textarea>
                        @error('explanation')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-1">Niveau de difficulté</label>
                        <select name="difficulty" id="difficulty" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="junior" {{ old('difficulty') === 'junior' ? 'selected' : '' }}>Junior</option>
                            <option value="mid" {{ old('difficulty') === 'mid' ? 'selected' : '' }}>Mid</option>
                            <option value="senior" {{ old('difficulty') === 'senior' ? 'selected' : '' }}>Senior</option>
                        </select>
                        @error('difficulty')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <input type="hidden" name="status" value="a_revoir">

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('domains.concepts.index', $domain) }}" 
                           class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Annuler
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                            Créer le concept
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>