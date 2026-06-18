<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use App\Enums\OrderStatus;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_submit_review_for_completed_order()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create(['user_id' => $seller->id, 'category_id' => $category->id]);
        
        $order = Order::create([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'status' => OrderStatus::COMPLETED,
            'total_amount' => 100000,
            'shipping_name' => 'Test Name',
            'shipping_address' => '123',
            'shipping_phone' => '123'
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'price' => 100000
        ]);

        $response = $this->actingAs($buyer)->post(route('reviews.store', $order), [
            'rating' => 5,
            'comment' => 'Great product!'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reviews', [
            'reviewer_id' => $buyer->id,
            'reviewed_user_id' => $seller->id,
            'rating' => 5,
        ]);
    }
}
