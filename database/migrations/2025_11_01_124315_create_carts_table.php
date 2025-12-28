<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();

            // Bắt buộc phải có user (đã đăng nhập)
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // active | ordered | abandoned
            $table->string('status')->default('active');

            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_total', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);

            $table->json('meta')->nullable(); // coupon, ghi chú tạm,...

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
}
