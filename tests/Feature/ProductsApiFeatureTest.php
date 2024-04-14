<?php

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductsApiFeatureTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Currency::factory()->count(3)->create();
    }

    /**
     * @test
     */
    public function test_api_returns_products_list()
    {
        $firstProductData = [
            'title' => 'Test Product 1',
            'price' => 99.99,
            'currency_id' => 1
        ];

        /** @var Product $firstProductModel */
        $firstProductModel = Product::query()->create($firstProductData);

        $secondProductData = [
            'title' => 'Test Product 2',
            'price' => 333.31,
            'currency_id' => 2
        ];

        /** @var Product $secondProductModel */
        $secondProductModel = Product::query()->create($secondProductData);

        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'success' => true,
                'page' => 1,
                'sortBy' => 'id',
                'sortWay' => 'desc',
                'products' => [
                    [
                        'id' => $secondProductModel->id,
                        'title' => $secondProductModel->title,
                        'price' => $secondProductModel->price,
                        'currency' => [
                            'id' => $secondProductModel->currency->id,
                            'name' => $secondProductModel->currency->name,
                            'isoCode' => $secondProductModel->currency->iso_code
                        ]
                    ],
                    [
                        'id' => $firstProductModel->id,
                        'title' => $firstProductModel->title,
                        'price' => $firstProductModel->price,
                        'currency' => [
                            'id' => $firstProductModel->currency->id,
                            'name' => $firstProductModel->currency->name,
                            'isoCode' => $firstProductModel->currency->iso_code
                        ]
                    ],
                ]
            ]
        ]);
    }
}
