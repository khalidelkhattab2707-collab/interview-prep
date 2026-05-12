<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqAIService
{
    private string $apiKey;
    private string $baseUrl = 'https://api.groq.com/openai/v1';

    public function __construct()
    {
        $this->apiKey = config('services.groq.api_key');
    }

    public function generateInterviewQuestions(string $title, string $explanation): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($this->baseUrl . '/chat/completions', [
                'model' => 'llama3-8b-8192',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Tu es un expert en entretiens techniques backend PHP/Laravel. Génère 5 questions d\'entretien réalistes et techniques en français.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Concept technique : $title\n\nExplication : $explanation\n\nGénère exactement 5 questions d'entretien techniques réalistes sous forme de JSON array de strings. Réponds UNIQUEMENT avec le JSON, sans texte avant ou après."
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 1024,
            ]);

            if (!$response->successful()) {
                Log::error('Groq API error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception('L\'API AI est temporairement indisponible. Statut : ' . $response->status());
            }

            $content = $response->json('choices.0.message.content');

            // Extraction du JSON
            preg_match('/\[[\s\S]*\]/', $content, $matches);
            $questions = json_decode($matches[0] ?? '[]', true);

            if (empty($questions) || !is_array($questions)) {
                // Fallback : extraction ligne par ligne
                $lines = array_filter(explode("\n", $content), fn($l) => str_contains($l, '?'));
                $questions = array_values($lines);
            }

            return array_slice($questions, 0, 5);

        } catch (\Exception $e) {
            Log::error('Question generation failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}