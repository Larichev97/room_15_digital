<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthFeatureTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function test_login_user_with_wrong_email()
    {
        User::factory()->create([
            'name' => 'Nickname',
            'email' => 'test_user@test.com',
            'email_verified_at' => now(),
            'password' => bcrypt('qwerty123'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'wrong_email@test.com',
            'password' => 'qwerty123',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
    }

    /**
     * @test
     */
    public function test_login_user_with_wrong_password()
    {
        $user = User::factory()->create([
            'name' => 'Nickname',
            'email' => 'test_user@test.com',
            'email_verified_at' => now(),
            'password' => bcrypt('qwerty123'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
    }

    /**
     * @test
     */
    public function test_login_user_with_verified_email_redirects_to_home_page()
    {
        $user = User::factory()->create([
            'name' => 'Nickname',
            'email' => 'test_user@test.com',
            'email_verified_at' => now(),
            'password' => bcrypt('qwerty123'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'qwerty123',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/home');
    }

    /**
     * @test
     */
    public function test_unauthenticated_user_cannot_access_products_page()
    {
        $response = $this->get('/products');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function test_authenticated_user_without_verified_email_cannot_access_products_page()
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        $this->actingAs($user);

        $response = $this->get('/products');

        $response->assertStatus(302);
        $response->assertRedirect('/email/verify');
    }

    /**
     * @test
     */
    public function test_unauthenticated_user_cannot_access_home_page()
    {
        $response = $this->get('/home');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function test_authenticated_user_without_verified_email_cannot_access_home_page()
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        $this->actingAs($user);

        $response = $this->get('/home');

        $response->assertStatus(302);
        $response->assertRedirect('/email/verify');
    }
}
