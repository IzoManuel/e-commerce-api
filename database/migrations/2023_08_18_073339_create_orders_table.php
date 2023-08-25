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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('combined_order_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->string('email')->nullable();
            $table->foreignId('guest_id')->nullable();
            $table->foreignId('seller_id')->nullable();
            $table->integer('assign_delivery_boy')->nullable();
            $table->longText('shipping_address')->nullable();
            $table->integer('pickup_point_id')->nullable()->default(0);
            $table->foreignId('carrier_id')->nullable();
            $table->string('delivery_status')->default('pending');
            $table->string('payment_type')->nullable();
            $table->integer('manual_payment')->default(0);
            $table->text('manual_payment_data')->nullable();
            $table->string('payment_status')->default('unpaid');
            $table->longText('payment_details')->nullable();
            $table->double('grand_total', 8, 2)->nullable();
            $table->double('coupon_discount', 8, 2)->default(0.00);
            $table->mediumText('code')->nullable();
            $table->string('tracking_code')->nullable();
            $table->integer('date')->nullable();
            $table->integer('viewed')->default(0);
            $table->integer('deliver_viewed')->default(1);
            $table->tinyInteger('cancel_request')->default(0);
            $table->tinyInteger('cancel_request_at')->default(0);
            $table->integer('payment_status_viewed')->default(1);
            $table->integer('commission_calculated')->default(0);
            $table->timestamp('delivery_history_date')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};