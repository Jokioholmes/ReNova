<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Déterminer si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        return true; // Routes publiques
    }

    /**
     * Règles de validation.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'user_type' => ['sometimes', 'in:particulier,boutique,revendeur,reparateur,technicien'],
            'phone' => ['nullable', 'string', 'max:20'],
            'bio' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Messages de validation personnalisés.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Le nom est obligatoire',
            'name.min' => 'Le nom doit contenir au moins 2 caractères',
            'email.required' => 'L\'email est obligatoire',
            'email.email' => 'L\'email doit être valide',
            'email.unique' => 'Cet email est déjà utilisé',
            'password.required' => 'Le mot de passe est obligatoire',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
            'password.letters' => 'Le mot de passe doit contenir au moins une lettre',
            'password.numbers' => 'Le mot de passe doit contenir au moins un chiffre',
        ];
    }

    /**
     * Préparer les données pour validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_type' => $this->user_type ?? 'particulier',
        ]);
    }
}