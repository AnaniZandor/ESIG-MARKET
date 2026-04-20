<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'location'    => 'nullable|string|max:255',

            // ✅ Ajoutés
            'condition'   => 'required|in:neuf,tres_bon,bon,acceptable',
            'images'      => 'nullable|array|max:5',
            'images.*'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'      => 'Le titre est obligatoire',
            'price.numeric'       => 'Le prix doit être un nombre',
            'price.min'           => 'Le prix ne peut pas être négatif',
            'category_id.exists'  => 'Cette catégorie n\'existe pas',
            'condition.required'  => 'L\'état de l\'article est obligatoire',
            'condition.in'        => 'L\'état choisi n\'est pas valide',
            'images.*.image'      => 'Le fichier doit être une image',
            'images.*.max'        => 'Chaque image ne doit pas dépasser 2Mo',
        ];
    }
}