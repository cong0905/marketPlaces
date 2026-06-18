<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\Category;
class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_checkout_page()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create(['user_id' => $seller->id, 'category_id' => $category->id]);

        $response = $this->actingAs($buyer)->get(route('checkout.create', $product));

        $response->assertStatus(200);
        $response->assertSee($product->title);
    }

    public function test_user_can_checkout_with_cod()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create(['user_id' => $seller->id, 'category_id' => $category->id, 'price' => 100000]);

        $response = $this->actingAs($buyer)->post(route('checkout.store', $product), [
            'payment_method' => 'cod',
            'name' => 'Test Name',
            'address' => '123 Test Street',
            'phone' => '0123456789',
        ]);

        $response->assertRedirect(route('checkout.success', Order::first()->id));
        $this->assertDatabaseHas('orders', [
            'buyer_id' => $buyer->id,
            'total_amount' => 100000,
        ]);
        $this->assertDatabaseHas('payments', [
            'method' => 'cod',
            'status' => 'pending',
        ]);
    }
}
