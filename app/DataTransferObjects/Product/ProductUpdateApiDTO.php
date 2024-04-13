<?php

namespace App\DataTransferObjects\Product;

use App\DataTransferObjects\FormFieldsDtoInterface;
use App\Http\Requests\Product\ProductUpdateApiRequest;

final readonly class ProductUpdateApiDTO implements FormFieldsDtoInterface
{
    public string $title;
    public float $price;
    public int $currency_id;

    /**
     * @param ProductUpdateApiRequest $productUpdateApiRequest
     * @param int $product_id
     */
    public function __construct(ProductUpdateApiRequest $productUpdateApiRequest, public int $product_id)
    {
        $this->title = trim($productUpdateApiRequest->validated('title'));
        $this->price = round($productUpdateApiRequest->validated('price'), 2);
        $this->currency_id = (int) $productUpdateApiRequest->validated('currency_id');
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
