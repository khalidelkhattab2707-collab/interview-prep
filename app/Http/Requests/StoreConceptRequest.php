<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreConceptRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
           'title' => 'required|string|max:255',
            'explanation' => 'required|string',
            'difficulty' => 'required|in:junior,mid,senior',
            'status' => 'required|in:a_revoir,en_cours,maitrise',
        ];
    }
}
