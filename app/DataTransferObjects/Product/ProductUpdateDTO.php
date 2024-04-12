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
     * @param ProductUpdateRequest $productStoreRequest
     * @param int $product_id
     */
    public function __construct(ProductUpdateRequest $productStoreRequest, public int $product_id)
    {
        $this->title = trim($productStoreRequest->validated('title'));
        $this->price = (float) $productStoreRequest->validated('price');
        $this->currency_id = (int) $productStoreRequest->validated('currency_id');
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
