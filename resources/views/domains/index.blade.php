<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Mes Domaines Techniques') }}
            </h2>
            <a href="{{ route('domains.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                + Nouveau Domaine
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

            @if($domains->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-gray-500">
                    <p class="text-lg">Aucun domaine créé.</p>
                    <p class="mt-2">Commencez par créer votre premier domaine technique !</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($domains as $domain)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                          style="background-color: {{ $domain->color }}20; color: {{ $domain->color }};">
                                        <span class="w-2 h-2 rounded-full mr-2" style="background-color: {{ $domain->color }};"></span>
                                        {{ $domain->name }}
                                    </span>
                                </div>
                                
                                <div class="space-y-3">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Concepts total :</span>
                                        <span class="font-semibold">{{ $domain->concepts_count }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Maîtrisés :</span>
                                        <span class="font-semibold text-green-600">{{ $domain->mastered_count }}</span>
                                    </div>
                                    
                                    @if($domain->concepts_count > 0)
                                        <div class="mt-3">
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-green-500 h-2 rounded-full transition-all" 
                                                     style="width: {{ ($domain->mastered_count / $domain->concepts_count) * 100 }}%">
                                                </div>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1 text-right">
                                                {{ round(($domain->mastered_count / $domain->concepts_count) * 100) }}% maîtrisé
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                {{-- ACTIONS : Voir concepts + Modifier + Supprimer --}}
                                <div class="mt-4 pt-4 border-t flex justify-between items-center">
                                    <a href="{{ route('domains.concepts.index', $domain) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Voir les concepts ({{ $domain->concepts_count }})
                                    </a>
                                    <div class="flex gap-3">
                                        <a href="{{ route('domains.edit', $domain) }}" 
                                           class="text-gray-600 hover:text-gray-800 text-sm">Modifier</a>
                                        <form action="{{ route('domains.destroy', $domain) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Supprimer ce domaine ?')"
                                                    class="text-red-600 hover:text-red-800 text-sm">Supprimer</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>