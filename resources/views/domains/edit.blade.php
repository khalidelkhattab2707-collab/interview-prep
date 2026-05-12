<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier le Domaine') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('domains.update', $domain) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Nom du domaine
                        </label>
                        <input type="text" name="name" id="name" 
                               value="{{ old('name', $domain->name) }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="color" class="block text-sm font-medium text-gray-700 mb-1">
                            Couleur du badge
                        </label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="color" id="color" 
                                   value="{{ old('color', $domain->color) }}"
                                   class="h-10 w-20 rounded cursor-pointer">
                            <input type="text" name="color_text" id="color_text"
                                   value="{{ old('color', $domain->color) }}"
                                   class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   maxlength="7" size="8">
                        </div>
                        @error('color')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('domains.index') }}" 
                           class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Annuler
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                            Mettre à jour
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.getElementById('color').addEventListener('input', function() {
            document.getElementById('color_text').value = this.value;
        });
        document.getElementById('color_text').addEventListener('input', function() {
            document.getElementById('color').value = this.value;
        });
    </script>
</x-app-layout>