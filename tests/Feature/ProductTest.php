<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_loads_successfully()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_products_list_loads_successfully()
    {
        $response = $this->get('/san-pham');
        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_access_create_product_page()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/dang-tin');
        
        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_create_product_page()
    {
        $response = $this->get('/dang-tin');
        
        $response->assertRedirect('/login');
    }
}
