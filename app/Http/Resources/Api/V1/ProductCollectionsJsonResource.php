<?php

namespace App\Http\Resources\Api\V1;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCollectionsJsonResource extends JsonResource
{
    /**
     * @var string Обёртка данных в ответе ("data": ....)
     */
    public static $wrap = 'data';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var LengthAwarePaginator $productsCollection */
        $productsCollection = $this->resource;

        $productsArray = [];

        if (!empty($productsCollection)) {
            foreach ($productsCollection as $product) {
                /** @var Product $product */

                $productsArray[] = [
                    'id' => (int) $product->id,
                    'title' => (string) $product->title,
                    'price' => (float) $product->price,
                    'currency' => [
                        'id' => (int) $product->currency->id,
                        'name' => (string) $product->currency->name,
                        'isoCode' => (string) $product->currency->iso_code,
                    ],
                ];
            }
        }

        return [
            'success' => true,
            'page' => (int) $request->get(key: 'page', default: 1),
            'sortBy' => trim($request->get(key: 'sort_by', default: 'id')),
            'sortWay' => trim($request->get(key: 'sort_way', default: 'desc')),
            'products' => $productsArray,
        ];
    }
}
