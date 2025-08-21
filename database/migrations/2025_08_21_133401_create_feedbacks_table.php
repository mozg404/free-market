<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();

            // Связи
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');

            // Оценка и коммента
            $table->boolean('is_positive');
            $table->string('comment', 255)->nullable();

            // Время
            $table->timestamps();

            // Уникальные ключи
            $table->unique(['order_item_id']);

            // Ключи для производительности
            $table->index('user_id'); // Для выборки "мои отзывы"
            $table->index('product_id'); // Для агрегации по товару
            $table->index('seller_id'); // Для агрегации по продавцу
            $table->index(['seller_id', 'is_positive']); // Для быстрого подсчета рейтинга
            $table->index(['product_id', 'is_positive']); // Для быстрого подсчета рейтинга товара
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
