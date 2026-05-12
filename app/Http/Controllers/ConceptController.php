<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConceptRequest;
use App\Models\Concept;
use App\Models\Domain;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConceptController extends Controller
{
    /**
     * Liste des concepts d'un domaine (US5)
     */
    public function index(Request $request, Domain $domain): View
    {
        $this->authorize('view', $domain);

        $query = $domain->concepts();

        // Filtre par statut
        if ($request->has('status') && in_array($request->status, ['a_revoir', 'en_cours', 'maitrise'])) {
            $query->where('status', $request->status);
        }

        $concepts = $query->orderBy('created_at', 'desc')->get();

        return view('concepts.index', compact('domain', 'concepts'));
    }

    /**
     * Formulaire de création (US6)
     */
    public function create(Domain $domain): View
    {
        $this->authorize('view', $domain);

        return view('concepts.create', compact('domain'));
    }

    /**
     * Sauvegarder un concept (US6)
     */
    public function store(StoreConceptRequest $request, Domain $domain): RedirectResponse
    {
        $this->authorize('view', $domain);

        $domain->concepts()->create($request->validated());

        return redirect()->route('domains.concepts.index', $domain)
            ->with('success', 'Concept créé avec succès.');
    }

    /**
     * Détail d'un concept (US7)
     */
    public function show(Domain $domain, Concept $concept): View
    {
        $this->authorize('view', $concept);

        $concept->load('questionGenerations');

        return view('concepts.show', compact('domain', 'concept'));
    }

    /**
     * Formulaire d'édition (US8)
     */
    public function edit(Domain $domain, Concept $concept): View
    {
        $this->authorize('update', $concept);

        return view('concepts.edit', compact('domain', 'concept'));
    }

    /**
     * Mettre à jour un concept (US8)
     */
    public function update(StoreConceptRequest $request, Domain $domain, Concept $concept): RedirectResponse
    {
        $this->authorize('update', $concept);

        $concept->update($request->validated());

        return redirect()->route('domains.concepts.show', [$domain, $concept])
            ->with('success', 'Concept mis à jour.');
    }

    /**
     * Supprimer un concept (US10)
     */
    public function destroy(Domain $domain, Concept $concept): RedirectResponse
    {
        $this->authorize('delete', $concept);

        $concept->delete();

        return redirect()->route('domains.concepts.index', $domain)
            ->with('success', 'Concept supprimé.');
    }

    /**
     * Changer le statut rapidement (US9) - AJAX
     */
    public function updateStatus(Request $request, Domain $domain, Concept $concept): JsonResponse
    {
        $this->authorize('update', $concept);

        $request->validate([
            'status' => 'required|in:a_revoir,en_cours,maitrise'
        ]);

        $concept->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'status' => $concept->status,
            'status_label' => $concept->statusLabel(),
            'status_color' => $concept->statusColor(),
        ]);
    }
}