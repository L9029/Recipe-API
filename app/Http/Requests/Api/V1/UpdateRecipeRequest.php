<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRecipeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'ingredients' => 'required|string|max:255',
            'instructions' => 'required|string|max:255',
            'image' => 'image|mimes:png|max:2048',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'required|array|exists:tags,id',
        ];
    }
}
