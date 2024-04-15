<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductJsonResource extends JsonResource
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
        return [
            'productId' => (int) $this->id,
            'title' => (string) $this->title,
            'price' => round($this->price, 2),
            'currencyId' => (int) $this->currency_id,
            'currencyName' => (string) $this->currency->name,
            'currencyIsoCode' => (string) $this->currency->iso_code,
        ];
    }
}
