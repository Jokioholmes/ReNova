<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateListingRequest extends FormRequest
{
    /**
     * Déterminer si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        $listing = $this->route('listing');
        return $listing && $listing->user_id === auth()->id();
    }

    /**
     * Règles de validation pour la mise à jour.
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'min:5', 'max:255'],
            'description' => ['sometimes', 'required', 'string', 'min:20', 'max:5000'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0', 'max:999999.99'],
            'condition' => ['sometimes', 'required', 'in:new,excellent,good,fair,poor'],
            'device_type' => ['sometimes', 'required', 'string', 'max:100'],
            'brand' => ['nullable', 'string', 'max:100'],
            'model' => ['nullable', 'string', 'max:100'],
            'status' => ['sometimes', 'required', 'in:draft,active,sold,archived'],
            'location' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['string', 'url'],
        ];
    }

    /**
     * Messages de validation personnalisés.
     */
    public function messages(): array
    {
        return [
            'title.min' => 'Le titre doit contenir au moins 5 caractères',
            'description.min' => 'La description doit contenir au moins 20 caractères',
            'price.numeric' => 'Le prix doit être un nombre',
        ];
    }
}