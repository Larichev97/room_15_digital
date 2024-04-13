<?php

namespace App\DataTransferObjects\Product;

use App\DataTransferObjects\FormFieldsDtoInterface;
use App\Http\Requests\Product\ProductStoreRequest;

final readonly class ProductStoreDTO implements FormFieldsDtoInterface
{
    public string $title;
    public float $price;
    public int $currency_id;

    /**
     * @param ProductStoreRequest $productStoreRequest
     */
    public function __construct(ProductStoreRequest $productStoreRequest)
    {
        $this->title = trim($productStoreRequest->validated('title'));
        $this->price = round($productStoreRequest->validated('price'), 2);
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
        ];
    }
}
