<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Concept extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'domain_id',
        'title',
        'explanation',
        'difficulty',
        'status'
    ];

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    public function questionGenerations(): HasMany
    {
        return $this->hasMany(QuestionGeneration::class);
    }

    // Label formaté pour la difficulté
    public function difficultyLabel(): string
    {
        return match($this->difficulty) {
            'junior' => 'Junior',
            'mid' => 'Mid',
            'senior' => 'Senior',
        };
    }

    // Label formaté pour le statut
    public function statusLabel(): string
    {
        return match($this->status) {
            'a_revoir' => 'À revoir',
            'en_cours' => 'En cours',
            'maitrise' => 'Maîtrisé',
        };
    }

    // Couleur du badge statut
    public function statusColor(): string
    {
        return match($this->status) {
            'a_revoir' => 'bg-red-100 text-red-800',
            'en_cours' => 'bg-yellow-100 text-yellow-800',
            'maitrise' => 'bg-green-100 text-green-800',
        };
    }

    // Couleur du badge difficulté
    public function difficultyColor(): string
    {
        return match($this->difficulty) {
            'junior' => 'bg-blue-100 text-blue-800',
            'mid' => 'bg-purple-100 text-purple-800',
            'senior' => 'bg-gray-800 text-white',
        };
    }
}