<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('price', 15, 0); // VND, no decimals needed
            $table->boolean('is_negotiable')->default(false);
            $table->unsignedTinyInteger('condition_percent')->default(80); // 0-100%
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->foreignId('province_id')->nullable()->constrained('provinces')->nullOnDelete();
            $table->foreignId('district_id')->nullable()->constrained('districts')->nullOnDelete();
            $table->string('status', 20)->default('pending'); // pending, active, sold, hidden, rejected
            $table->string('rejection_reason')->nullable();
            $table->string('video_url')->nullable();
            $table->unsignedInteger('view_count')->default(0);
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('sold_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('category_id');
            $table->index('status');
            $table->index('price');
            $table->index('province_id');
            $table->index('created_at');
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
