<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class ProductUpdateApiRequest extends FormRequest
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
        $idProduct = $this->route('product');

        return [
            'title' => 'nullable|string|max:255|unique:products,title,'.$idProduct,
            'price' => 'nullable|decimal:2|min:0.00',
            'currency_id' => 'nullable|min:1|integer',
        ];
    }
}
