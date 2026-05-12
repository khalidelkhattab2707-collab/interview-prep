<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Stats globales --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">Domaines</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $domains->count() }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">Concepts total</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $totalConcepts }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">Maîtrisés</p>
                    <p class="text-3xl font-bold text-green-600">{{ $conceptsByStatus['maitrise'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">À revoir</p>
                    <p class="text-3xl font-bold text-red-600">{{ $conceptsByStatus['a_revoir'] }}</p>
                </div>
            </div>

            {{-- Progression par statut --}}
            @if($totalConcepts > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Répartition par statut</h3>
                    <div class="flex h-4 rounded-full overflow-hidden">
                        <div class="bg-red-500" style="width: {{ ($conceptsByStatus['a_revoir'] / $totalConcepts) * 100 }}%"></div>
                        <div class="bg-yellow-500" style="width: {{ ($conceptsByStatus['en_cours'] / $totalConcepts) * 100 }}%"></div>
                        <div class="bg-green-500" style="width: {{ ($conceptsByStatus['maitrise'] / $totalConcepts) * 100 }}%"></div>
                    </div>
                    <div class="flex gap-4 mt-2 text-sm">
                        <span class="flex items-center"><span class="w-3 h-3 bg-red-500 rounded-full mr-1"></span> À revoir ({{ $conceptsByStatus['a_revoir'] }})</span>
                        <span class="flex items-center"><span class="w-3 h-3 bg-yellow-500 rounded-full mr-1"></span> En cours ({{ $conceptsByStatus['en_cours'] }})</span>
                        <span class="flex items-center"><span class="w-3 h-3 bg-green-500 rounded-full mr-1"></span> Maîtrisé ({{ $conceptsByStatus['maitrise'] }})</span>
                    </div>
                </div>
            @endif

            {{-- Domaines best/worst --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                @if($bestDomain && $bestDomain->concepts_count > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Domaine le mieux maîtrisé</h3>
                        <p class="text-xl font-bold text-gray-800">{{ $bestDomain->name }}</p>
                        <p class="text-sm text-green-600 mt-1">
                            {{ $bestDomain->masteredConceptsCount() }}/{{ $bestDomain->concepts_count }} concepts maîtrisés
                            ({{ round(($bestDomain->masteredConceptsCount() / $bestDomain->concepts_count) * 100) }}%)
                        </p>
                    </div>
                @endif

                @if($worstDomain && $worstDomain->concepts_count > 0 && $worstDomain->id !== $bestDomain?->id)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Domaine le plus à revoir</h3>
                        <p class="text-xl font-bold text-gray-800">{{ $worstDomain->name }}</p>
                        <p class="text-sm text-red-600 mt-1">
                            {{ $worstDomain->masteredConceptsCount() }}/{{ $worstDomain->concepts_count }} concepts maîtrisés
                            ({{ round(($worstDomain->masteredConceptsCount() / $worstDomain->concepts_count) * 100) }}%)
                        </p>
                    </div>
                @endif
            </div>

            {{-- Concepts récents --}}
            @if($recentConcepts->isNotEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Derniers concepts modifiés</h3>
                    <div class="space-y-3">
                        @foreach($recentConcepts as $concept)
                            <div class="flex justify-between items-center py-2 border-b last:border-0">
                                <div>
                                    <a href="{{ route('domains.concepts.show', [$concept->domain, $concept]) }}" 
                                       class="text-blue-600 hover:text-blue-800 font-medium">
                                        {{ $concept->title }}
                                    </a>
                                    <span class="text-xs text-gray-500 ml-2">{{ $concept->domain->name }}</span>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $concept->statusColor() }}">
                                    {{ $concept->statusLabel() }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>