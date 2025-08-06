<?php

use App\Enum\StockItemStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name', 255);
            $table->integer('current_price');
            $table->integer('base_price');
            $table->boolean('is_available')->default(false);
            $table->string('preview_image', 255)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('stock_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('status');
            $table->text('content');
            $table->foreignId('buyer_id')->nullable()->constrained('users');
            $table->timestamp('sold_at')->nullable(); // Когда куплен
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_items');
        Schema::dropIfExists('products');
    }
};
