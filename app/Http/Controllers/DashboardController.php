<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $domains = auth()->user()->domains()
            ->withCount('concepts')
            ->get();

        $totalConcepts = $domains->sum('concepts_count');
        
        $conceptsByStatus = [
            'a_revoir' => 0,
            'en_cours' => 0,
            'maitrise' => 0,
        ];

        foreach ($domains as $domain) {
            foreach ($domain->concepts as $concept) {
                $conceptsByStatus[$concept->status]++;
            }
        }

        $bestDomain = $domains->sortByDesc(function ($domain) {
            if ($domain->concepts_count === 0) return -1;
            return $domain->masteredConceptsCount() / $domain->concepts_count;
        })->first();

        $worstDomain = $domains->sortBy(function ($domain) {
            if ($domain->concepts_count === 0) return 999;
            return $domain->masteredConceptsCount() / $domain->concepts_count;
        })->first();

        $recentConcepts = auth()->user()->domains()
            ->with(['concepts' => function ($query) {
                $query->latest()->limit(5);
            }])
            ->get()
            ->pluck('concepts')
            ->flatten()
            ->sortByDesc('updated_at')
            ->take(5);

        return view('dashboard', compact(
            'domains',
            'totalConcepts',
            'conceptsByStatus',
            'bestDomain',
            'worstDomain',
            'recentConcepts'
        ));
    }
}