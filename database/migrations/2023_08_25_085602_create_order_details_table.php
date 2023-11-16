<?php

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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id');
            $table->foreignId('product_id');
            $table->foreignId('seller_id')->nullable();
            $table->longText('variation')->nullable();
            $table->double('price', 8, 2)->nullable();
            $table->string('paypal_order_id')->nullable();
            $table->double('tax', 8,2)->default(0.00);
            $table->double('shipping_cost',8, 2)->default(0.00);
            $table->integer('quantity')->nullable();
            $table->string('payment_status')->default('upaid');
            $table->string('delivery_status')->default('pending');
            $table->string('shipping_type')->nullable();
            $table->integer('pickup_point_id')->nullable();
            $table->string('product_referral_code')->nullable();
            $table->double('earn_point', 8, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};