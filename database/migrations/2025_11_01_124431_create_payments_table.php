<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('provider')->default('stripe');

            $table->string('provider_payment_id')->nullable(); // stripe payment_intent
            $table->string('provider_charge_id')->nullable();
            $table->string('client_secret')->nullable();

            $table->decimal('amount', 12, 2)->default(0);
            $table->string('currency', 10)->default('vnd');

            $table->string('status')->default('created'); // created | pending | succeeded | failed
            $table->json('payload')->nullable();

            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
}
