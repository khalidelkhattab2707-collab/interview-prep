<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier le Concept') }} — {{ $concept->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('domains.concepts.update', [$domain, $concept]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $concept->title) }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="explanation" class="block text-sm font-medium text-gray-700 mb-1">Explication</label>
                        <textarea name="explanation" id="explanation" rows="6"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>{{ old('explanation', $concept->explanation) }}</textarea>
                        @error('explanation')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-1">Niveau de difficulté</label>
                        <select name="difficulty" id="difficulty" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="junior" {{ old('difficulty', $concept->difficulty) === 'junior' ? 'selected' : '' }}>Junior</option>
                            <option value="mid" {{ old('difficulty', $concept->difficulty) === 'mid' ? 'selected' : '' }}>Mid</option>
                            <option value="senior" {{ old('difficulty', $concept->difficulty) === 'senior' ? 'selected' : '' }}>Senior</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                        <select name="status" id="status" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="a_revoir" {{ old('status', $concept->status) === 'a_revoir' ? 'selected' : '' }}>À revoir</option>
                            <option value="en_cours" {{ old('status', $concept->status) === 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="maitrise" {{ old('status', $concept->status) === 'maitrise' ? 'selected' : '' }}>Maîtrisé</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('domains.concepts.show', [$domain, $concept]) }}" 
                           class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Annuler
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                            Mettre à jour
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>