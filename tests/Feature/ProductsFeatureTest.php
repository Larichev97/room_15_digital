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
        $response = $this->get('products');

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

        $response = $this->get('products');

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

        $response = $this->get('products');

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

        $response = $this->get('products?sort_by=id&sort_way=asc');

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

        $response = $this->from('products/create')->post('products', $productData);

        $response->assertStatus(302);
        $response->assertRedirect('products');

        $this->assertDatabaseHas('products', $productData);

        $lastAddedProduct = Product::query()->latest()->first();

        /** @var Product $lastAddedProduct */

        $this->assertEquals($productData['title'], $lastAddedProduct->title);
        $this->assertEquals($productData['price'], $lastAddedProduct->price);
        $this->assertEquals($productData['currency_id'], $lastAddedProduct->currency_id);
    }

    /**
     * @test
     */
    public function test_create_product_with_wrong_price_validation()
    {
        $productData = [
            'title' => 'Test Product 1',
            'price' => -99.99,
            'currency_id' => 1
        ];

        $response = $this->from('products/create')->post('products', $productData);

        $response->assertStatus(302);
        $response->assertRedirect('products/create');
        $response->assertSessionHasErrors('price');
    }

    /**
     * @test
     */
    public function test_create_product_with_empty_currency_id_validation()
    {
        $productData = [
            'title' => 'Test Product 1',
            'price' => 99.99,
            'currency_id' => null
        ];

        $response = $this->from('products/create')->post('products', $productData);

        $response->assertStatus(302);
        $response->assertRedirect('products/create');
        $response->assertSessionHasErrors('currency_id');
    }

    /**
     * @test
     */
    public function test_create_product_with_empty_title_validation()
    {
        $productData = [
            'title' => '',
            'price' => 99.99,
            'currency_id' => 1
        ];

        $response = $this->from('products/create')->post('products', $productData);

        $response->assertStatus(302);
        $response->assertRedirect('products/create');
        $response->assertSessionHasErrors('title');
    }

    /**
     * @test
     */
    public function test_create_product_with_same_title_of_existing_product_validation()
    {
        $firstProductData = [
            'title' => 'Test Product 1',
            'price' => 99.99,
            'currency_id' => 1
        ];

        Product::query()->create($firstProductData);

        $secondProductData = [
            'title' => 'Test Product 1',
            'price' => 333.03,
            'currency_id' => 3
        ];

        $response = $this->from('products/create')->post('products', $secondProductData);

        $response->assertStatus(302);
        $response->assertRedirect('products/create');
        $response->assertSessionHasErrors('title');
    }

    /**
     * @test
     */
    public function test_show_existing_product()
    {
        $productModel = Product::factory()->create();

        $response = $this->get('products/'.$productModel->id);

        $response->assertStatus(200);
        $response->assertViewIs('admin.crud.product.show');
        $response->assertViewHas('product', $productModel);
        $response->assertSee(value: 'value="'.$productModel->title.'"', escape: false);
        $response->assertSee(value: 'value="'.$productModel->price.'"', escape: false);
        $response->assertSee(value: 'value="'.$productModel->currency->iso_code.'"', escape: false);
    }

    /**
     * @test
     */
    public function test_show_non_existing_product()
    {
        $response = $this->get('products/3333');

        $response->assertStatus(500);
    }

    /**
     * @test
     */
    public function test_edit_product_contains_correct_values()
    {
        $productModel = Product::factory()->create();

        $response = $this->get('products/'.$productModel->id.'/edit');

        $response->assertStatus(200);
        $response->assertViewIs('admin.crud.product.edit');
        $response->assertViewHas('product', $productModel);
        $response->assertSee(value: 'value="'.$productModel->title.'"', escape: false);
        $response->assertSee(value: 'value="'.$productModel->price.'"', escape: false);
        $response->assertSee(value: 'value="'.$productModel->currency_id.'"', escape: false);
    }

    /**
     * @test
     */
    public function test_update_product_with_empty_title_validation()
    {
        $productModel = Product::factory()->create();

        $response = $this->from('products/'.$productModel->id.'/edit')->put('products/'.$productModel->id, [
            'title' => '',
            'price' => 333.33,
            'currency_id' => 3,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('products/'.$productModel->id.'/edit');
        $response->assertSessionHasErrors('title');
    }

    /**
     * @test
     */
    public function test_update_product_successful()
    {
        $productModel = Product::factory()->create();

        $newProductData = [
            'title' => 'New product name 123',
            'price' => 555.51,
            'currency_id' => 2,
        ];

        $response = $this->from('products/'.$productModel->id.'/edit')->put('products/'.$productModel->id, $newProductData);

        $response->assertStatus(302);
        $response->assertRedirect('products');

        $this->assertDatabaseHas('products', $newProductData);
    }

    /**
     * @test
     */
    public function test_delete_product_successful()
    {
        $productModel = Product::factory()->create();

        $response = $this->from('products/'.$productModel->id.'/edit')->delete('products/'.$productModel->id);

        $response->assertStatus(302);
        $response->assertRedirect('products');

        $productData = [
            'title' => $productModel->title,
            'price' => $productModel->price,
            'currency_id' => $productModel->currency_id,
        ];

        $this->assertDatabaseMissing('products', $productData);
        $this->assertDatabaseCount('products', 0);
    }
}
