<?php

namespace App\Http\Controllers;

use App\Models\Concept;
use App\Models\Domain;
use App\Models\QuestionGeneration;
use App\Services\GroqAIService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class QuestionGenerationController extends Controller
{
    public function __construct(
        private GroqAIService $groqService
    ) {}

    public function generate(Request $request, Domain $domain, Concept $concept): RedirectResponse
    {
        $this->authorize('update', $concept);

        try {
            $questions = $this->groqService->generateInterviewQuestions(
                $concept->title,
                $concept->explanation
            );

            $concept->questionGenerations()->create([
                'questions' => $questions,
            ]);

            return redirect()->route('domains.concepts.show', [$domain, $concept])
                ->with('success', '5 questions générées avec succès !');

        } catch (\Exception $e) {
            return redirect()->route('domains.concepts.show', [$domain, $concept])
                ->with('error', 'Erreur lors de la génération : ' . $e->getMessage());
        }
    }

    public function destroy(QuestionGeneration $generation): RedirectResponse
    {
        $this->authorize('update', $generation->concept);

        $domain = $generation->concept->domain;
        $concept = $generation->concept;

        $generation->delete();

        return redirect()->route('domains.concepts.show', [$domain, $concept])
            ->with('success', 'Génération supprimée.');
    }
}
