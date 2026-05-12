<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDomainRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:domains,name,NULL,id,user_id,' . auth()->id(),
            'color' => 'required|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/',
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Vous avez déjà un domaine avec ce nom.',
            'color.regex' => 'La couleur doit être au format hexadécimal (ex: #3B82F6).',
        ];
    }
}
