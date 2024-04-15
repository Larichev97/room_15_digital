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
    public function test_api_index_returns_products_list_successful()
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

    /**
     * @test
     */
    public function test_api_show_returns_product_successful()
    {
        $productData = $this->getProductData();

        /** @var Product $productModel */
        $productModel = Product::query()->create($productData);

        /** @var Currency $currencyModel */
        $currencyModel = $productModel->currency;

        $response = $this->getJson('/api/v1/products/'.$productModel->id);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'productId' => $productModel->id,
                'title' => $productModel->title,
                'price' => $productModel->price,
                'currencyId' => $productModel->currency_id,
                'currencyName' => $currencyModel->name,
                'currencyIsoCode' => $currencyModel->iso_code,
            ]
        ]);
    }

    /**
     * @test
     */
    public function test_api_create_returns_product_successful()
    {
        $productData = $this->getProductData();

        /** @var Currency $firstCurrency */
        $firstCurrency = Currency::query()->first();

        $response = $this->postJson('/api/v1/products', $productData);

        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'productId' => 1,
                'title' => $productData['title'],
                'price' => $productData['price'],
                'currencyId' => $productData['currency_id'],
                'currencyName' => $firstCurrency->name,
                'currencyIsoCode' => $firstCurrency->iso_code,
            ]
        ]);

        $this->assertDatabaseHas('products', $productData);
    }

    /**
     * @test
     */
    public function test_api_update_returns_product_successful()
    {
        $productData = $this->getProductData();

        /** @var Product $productModel */
        $productModel = Product::query()->create($productData);

        $newProductData = [
            'price' => 33.01,
        ];

        /** @var Currency $currencyModel */
        $currencyModel = $productModel->currency;

        $response = $this->putJson('/api/v1/products/'.$productModel->id, $newProductData);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'productId' => $productModel->id,
                'title' => $productModel->title,
                'price' => $newProductData['price'],
                'currencyId' => $productModel->currency_id,
                'currencyName' => $currencyModel->name,
                'currencyIsoCode' => $currencyModel->iso_code,
            ]
        ]);

        $updatedProductData = [
            'id' => $productModel->id,
            'title' => $productModel->title,
            'price' => $newProductData['price'],
            'currency_id' => $productModel->currency_id,
        ];

        $this->assertDatabaseHas('products', $updatedProductData);
    }

    /**
     * @test
     */
    public function test_api_destroy_existing_product_successful()
    {
        $productData = $this->getProductData();

        /** @var Product $productModel */
        $productModel = Product::query()->create($productData);

        $response = $this->deleteJson('/api/v1/products/'.$productModel->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('products', $productData);
    }

    /**
     * @return array
     */
    private function getProductData(): array
    {
        return [
            'title' => 'Test Product 1',
            'price' => 99.99,
            'currency_id' => 1
        ];
    }
}
