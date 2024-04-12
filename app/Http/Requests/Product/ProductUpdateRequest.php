<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class ProductUpdateRequest extends FormRequest
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
        $idProduct = $this->request->get('product_id');

        return [
            'title' => 'required|string|max:255|unique:products,title,'.$idProduct,
            'price' => 'required|decimal:2',
            'currency_id' => 'required|min:1|integer',
        ];
    }
}
