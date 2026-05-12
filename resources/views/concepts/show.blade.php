<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $concept->title }}
                </h2>
                <div class="flex gap-2 mt-2">
                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $concept->difficultyColor() }}">
                        {{ $concept->difficultyLabel() }}
                    </span>
                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $concept->statusColor() }}">
                        {{ $concept->statusLabel() }}
                    </span>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('domains.concepts.edit', [$domain, $concept]) }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition">
                    Modifier
                </a>
                <form action="{{ route('domains.concepts.destroy', [$domain, $concept]) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Supprimer ce concept ?')"
                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Explication --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Explication</h3>
                <div class="prose max-w-none text-gray-700 whitespace-pre-line">
                    {{ $concept->explanation }}
                </div>
            </div>

            {{-- Questions d'entretien avec AI --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Questions d'entretien</h3>
                    <form action="{{ route('questions.generate', [$domain, $concept]) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                            🤖 Générer des questions
                        </button>
                    </form>
                </div>

                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @if($concept->questionGenerations->isEmpty())
                    <p class="text-gray-500 text-sm">Aucune question générée. Cliquez sur le bouton ci-dessus pour générer 5 questions d'entretien réalistes.</p>
                @else
                    <div class="space-y-4">
                        @foreach($concept->questionGenerations->sortByDesc('created_at') as $generation)
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-xs text-gray-500">
                                        {{ $generation->created_at->format('d/m/Y H:i') }}
                                    </span>
                                    <form action="{{ route('questions.destroy', $generation) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('Supprimer cette génération ?')"
                                                class="text-red-500 hover:text-red-700 text-xs">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                                <ol class="list-decimal list-inside space-y-2">
                                    @foreach($generation->questions as $question)
                                        <li class="text-sm text-gray-700">{{ $question }}</li>
                                    @endforeach
                                </ol>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mt-4">
                <a href="{{ route('domains.concepts.index', $domain) }}" class="text-sm text-gray-600 hover:text-gray-800">
                    ← Retour à la liste
                </a>
            </div>
        </div>
    </div>
</x-app-layout>