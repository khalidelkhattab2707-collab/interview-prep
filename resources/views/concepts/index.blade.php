<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $domain->name }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Concepts techniques</p>
            </div>
            <a href="{{ route('domains.concepts.create', $domain) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                + Nouveau Concept
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Filtre par statut --}}
            <div class="mb-6 flex gap-2">
                <a href="{{ route('domains.concepts.index', $domain) }}" 
                   class="px-3 py-1 rounded-full text-sm {{ !request('status') ? 'bg-gray-800 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    Tous
                </a>
                <a href="{{ route('domains.concepts.index', ['domain' => $domain, 'status' => 'a_revoir']) }}" 
                   class="px-3 py-1 rounded-full text-sm {{ request('status') === 'a_revoir' ? 'bg-red-600 text-white' : 'bg-red-100 text-red-700 hover:bg-red-200' }}">
                    À revoir
                </a>
                <a href="{{ route('domains.concepts.index', ['domain' => $domain, 'status' => 'en_cours']) }}" 
                   class="px-3 py-1 rounded-full text-sm {{ request('status') === 'en_cours' ? 'bg-yellow-600 text-white' : 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' }}">
                    En cours
                </a>
                <a href="{{ route('domains.concepts.index', ['domain' => $domain, 'status' => 'maitrise']) }}" 
                   class="px-3 py-1 rounded-full text-sm {{ request('status') === 'maitrise' ? 'bg-green-600 text-white' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                    Maîtrisé
                </a>
            </div>

            @if($concepts->isEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-center text-gray-500">
                    <p>Aucun concept dans ce domaine.</p>
                </div>
            @else
                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Difficulté</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($concepts as $concept)
                                <tr>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('domains.concepts.show', [$domain, $concept]) }}" 
                                           class="text-blue-600 hover:text-blue-800 font-medium">
                                            {{ $concept->title }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $concept->difficultyColor() }}">
                                            {{ $concept->difficultyLabel() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{-- Quick status toggle (US9) --}}
                                        <div x-data="{ status: '{{ $concept->status }}', loading: false }">
                                            <select 
                                                x-model="status"
                                                @change="
                                                    loading = true;
                                                    fetch('{{ route('domains.concepts.status', [$domain, $concept]) }}', {
                                                        method: 'PATCH',
                                                        headers: {
                                                            'Content-Type': 'application/json',
                                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                                        },
                                                        body: JSON.stringify({ status: $event.target.value })
                                                    })
                                                    .then(r => r.json())
                                                    .then(data => {
                                                        loading = false;
                                                        if(data.success) {
                                                            $event.target.className = 'text-sm rounded-md border-0 py-1 px-2 font-medium cursor-pointer ' + data.status_color;
                                                        }
                                                    })
                                                "
                                                :disabled="loading"
                                                class="text-sm rounded-md border-0 py-1 px-2 font-medium cursor-pointer {{ $concept->statusColor() }}"
                                            >
                                                <option value="a_revoir" class="bg-red-100 text-red-800">À revoir</option>
                                                <option value="en_cours" class="bg-yellow-100 text-yellow-800">En cours</option>
                                                <option value="maitrise" class="bg-green-100 text-green-800">Maîtrisé</option>
                                            </select>
                                            <span x-show="loading" class="ml-2 text-xs text-gray-400">...</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <a href="{{ route('domains.concepts.edit', [$domain, $concept]) }}" 
                                           class="text-blue-600 hover:text-blue-800 text-sm">Modifier</a>
                                        <form action="{{ route('domains.concepts.destroy', [$domain, $concept]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Supprimer ce concept ?')"
                                                    class="text-red-600 hover:text-red-800 text-sm">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="mt-4">
                <a href="{{ route('domains.index') }}" class="text-sm text-gray-600 hover:text-gray-800">
                    ← Retour aux domaines
                </a>
            </div>
        </div>
    </div>
</x-app-layout>