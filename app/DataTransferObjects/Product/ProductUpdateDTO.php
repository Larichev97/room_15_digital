<?php

namespace App\DataTransferObjects\Product;

use App\DataTransferObjects\FormFieldsDtoInterface;
use App\Http\Requests\Product\ProductUpdateRequest;

final readonly class ProductUpdateDTO implements FormFieldsDtoInterface
{
    public string $title;
    public float $price;
    public int $currency_id;

    /**
     * @param ProductUpdateRequest $productUpdateRequest
     * @param int $product_id
     */
    public function __construct(ProductUpdateRequest $productUpdateRequest, public int $product_id)
    {
        $this->title = trim($productUpdateRequest->validated('title'));
        $this->price = round($productUpdateRequest->validated('price'), 2);
        $this->currency_id = (int) $productUpdateRequest->validated('currency_id');
    }

    /**
     * @return array
     */
    public function getFormFieldsArray(): array
    {
        return [
            'title' => $this->title,
            'price' => $this->price,
            'currency_id' => $this->currency_id,
            'product_id' => $this->product_id,
        ];
    }
}
