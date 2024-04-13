<?php

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductsFeatureTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Текущий пользователь всегда авторизирован + Email подтвержден:
        $user = User::factory()->create(['email_verified_at' => now()]);
        $this->actingAs($user);

        Currency::factory()->count(3)->create();
    }

    /**
     * @test
     */
    public function test_index_page_products_contains_empty_table()
    {
        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertViewIs('admin.crud.product.index');
        $response->assertSee(__('Товарів немає'));
    }

    /**
     * @test
     */
    public function test_index_page_products_contains_non_empty_table()
    {
        $productModel = Product::query()->create(['title' => 'Test Product 1', 'price' => 99.99, 'currency_id' => 1]);

        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertViewIs('admin.crud.product.index');
        $response->assertViewHas('products');
        $response->assertDontSee(__('Товарів немає'));
        $response->assertSee('Test Product 1');
        $response->assertViewHas('products', function ($collection) use ($productModel) {
            return $collection->contains($productModel);
        });
    }

    /**
     * @test
     */
    public function test_index_page_paginated_products_table_doesnt_contain_first_record_order_by_desc_with_10_per_page()
    {
        $productsCollection = Product::factory()->count(11)->create();

        $firstProduct = $productsCollection->first();

        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertViewIs('admin.crud.product.index');
        $response->assertViewHas('products', function ($collection) use ($firstProduct) {
            return !$collection->contains($firstProduct);
        });
    }

    /**
     * @test
     */
    public function test_index_page_paginated_products_table_doesnt_contain_last_record_order_by_asc_with_10_per_page()
    {
        $productsCollection = Product::factory()->count(11)->create();

        $lastProduct = $productsCollection->last();

        $response = $this->get('/products?sort_by=id&sort_way=asc');

        $response->assertStatus(200);
        $response->assertViewIs('admin.crud.product.index');
        $response->assertViewHas('products', function ($collection) use ($lastProduct) {
            return !$collection->contains($lastProduct);
        });
    }

    /**
     * @test
     */
    public function test_create_product_successful()
    {
        $productData = [
            'title' => 'Test Product 1',
            'price' => 99.99,
            'currency_id' => 1
        ];

        $response = $this->post('/products', $productData);

        $response->assertStatus(302);
        $response->assertRedirect('/products');

        $this->assertDatabaseHas('products', $productData);

        $lastAddedProduct = Product::query()->latest()->first();

        /** @var Product $lastAddedProduct */

        $this->assertEquals($productData['title'], $lastAddedProduct->title);
        $this->assertEquals($productData['price'], $lastAddedProduct->price);
        $this->assertEquals($productData['currency_id'], $lastAddedProduct->currency_id);
    }
}
