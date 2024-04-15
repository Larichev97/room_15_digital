<?php

namespace App\DataTransferObjects\Product;

use App\DataTransferObjects\FormFieldsDtoInterface;
use App\Http\Requests\Product\ProductUpdateApiRequest;

final readonly class ProductUpdateApiDTO implements FormFieldsDtoInterface
{
    public string|null $title;
    public float|null $price;
    public int|null $currency_id;

    /**
     * @param ProductUpdateApiRequest $productUpdateApiRequest
     * @param int $product_id
     */
    public function __construct(ProductUpdateApiRequest $productUpdateApiRequest, public int $product_id)
    {
        $this->title = $productUpdateApiRequest->get('title') !== null ? trim($productUpdateApiRequest->get('title')) : null;
        $this->price = $productUpdateApiRequest->get('price') !== null ? round($productUpdateApiRequest->get('price'), 2) : null;
        $this->currency_id = $productUpdateApiRequest->get('currency_id') !== null ? (int) $productUpdateApiRequest->get('currency_id') : null;
    }

    /**
     * @return array
     */
    public function getFormFieldsArray(): array
    {
        $updateData = [
            'product_id' => $this->product_id,
        ];

        if (!empty($this->title)) {
            $updateData['title'] = $this->title;
        }

        if (!empty($this->price)) {
            $updateData['price'] = $this->price;
        }

        if (!empty($this->currency_id)) {
            $updateData['currency_id'] = $this->currency_id;
        }

        return $updateData;
    }
}
