<?php

declare(strict_types=1);

namespace App\Infrastructure\Framework\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBook extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:40',
            'editor' => 'required|string|max:40',
            'publication_year' => 'required|string|max:4',
            'edition' => 'required|integer|min:1|max:9999',
            'price' => 'required|integer|min:0|max:999999999',
            'authors' => 'required|array',
            'authors.*' => 'integer|min:1',
        ];
    }
}
