<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDomainRequest;
use App\Models\Domain;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DomainController extends Controller
{
    /**
     * Liste des domaines (US2)
     */
    public function index(): View
    {
        $domains = auth()->user()
            ->domains()
            ->withCount('concepts')
            ->with(['concepts' => function ($query) {
                $query->where('status', 'maitrise');
            }])
            ->get()
            ->map(function ($domain) {
                $domain->mastered_count = $domain->concepts->count();
                return $domain;
            });

        return view('domains.index', compact('domains'));
    }

    /**
     * Formulaire de création (US3)
     */
    public function create(): View
    {
        return view('domains.create');
    }

    /**
     * Sauvegarder un domaine (US3)
     */
    public function store(StoreDomainRequest $request): RedirectResponse
    {
        auth()->user()->domains()->create($request->validated());

        return redirect()->route('domains.index')
            ->with('success', 'Domaine créé avec succès.');
    }

    /**
     * Formulaire d'édition (US4)
     */
    public function edit(Domain $domain): View
    {
        $this->authorize('update', $domain);

        return view('domains.edit', compact('domain'));
    }

    /**
     * Mettre à jour un domaine (US4)
     */
    public function update(StoreDomainRequest $request, Domain $domain): RedirectResponse
    {
        $this->authorize('update', $domain);

        $domain->update($request->validated());

        return redirect()->route('domains.index')
            ->with('success', 'Domaine mis à jour.');
    }

    /**
     * Supprimer un domaine (US4)
     */
    public function destroy(Domain $domain): RedirectResponse
    {
        $this->authorize('delete', $domain);

        $domain->delete();

        return redirect()->route('domains.index')
            ->with('success', 'Domaine supprimé.');
    }
}
