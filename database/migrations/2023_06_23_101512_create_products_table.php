<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('added_by')->default('admin');
            $table->foreignId('user_id');
            $table->foreignId('category_id')->nullable;
            $table->foreignID('brand_id')->nullable();
            $table->string('photos')->nullable();
            $table->string('thumbnail_img')->nullable();
            $table->string('video_provider')->nullable();
            $table->string('video_link')->nullable();
            $table->string('tags')->nullable();
            $table->longText('description')->nullable();
            $table->double('unit_price', 8, 2);
            $table->double('purchase_price', 8, 2)->nullable();
            $table->integer('variant_product')->default(0);
            $table->string('attributes')->default('[]');
            $table->mediumText('choice_options')->nullable();
            $table->mediumText('colors')->nullable();
            $table->text('variations')->nullable();
            $table->integer('todays_deals')->default(0);
            $table->integer('published')->default(1);
            $table->tinyInteger('approved')->default(1);
            $table->string('stock_visibility_state')->default('quantity');
            $table->tinyInteger('cash_on_deliver')->default(0);
            $table->integer('featured')->default(0);
            $table->integer('seller_featured')->default(0);
            $table->integer('current_stock')->default(0);
            $table->string('unit')->nullable();
            $table->double('weight', 8, 2)->default(0.00);
            $table->integer('min_quantity')->default(1);
            $table->integer('low_stock_quantity')->nullable();
            $table->double('discount', 8, 2)->nullable();
            $table->string('discount_type')->nullable();
            $table->timestamp('discount_start_date')->nullable();
            $table->timestamp('discount_end_date')->nullable();
            $table->double('starting_bid', 8, 2)->nullable()->default(0.00);
            $table->timestamp('auction_start_date')->nullable();
            $table->timestamp('auction_end_date')->nullable();
            $table->double('tax')->nullable();
            $table->string('tax_type')->nullable();
            $table->string('shipping_type')->nullable()->default('flat_rate');
            $table->double('shipping_cost')->default(0.00);
            $table->tinyInteger('is_quantity_multiplied')->default(0);
            $table->integer('est_shipping_days')->nullable();
            $table->integer('num_of_sale')->default(0);
            $table->mediumText('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->string('meta_img')->nullable();
            $table->string('pdf')->nullable();
            $table->mediumText('slug');
            $table->double('earn_point')->default(0.00);
            $table->integer('refundable')->default(0);
            $table->double('rating')->default(0.00);
            $table->string('barcode')->nullable();
            $table->integer('digital')->default(0);
            $table->integer('auction_product')->default(0);
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
            $table->string('external_link')->nullable();
            $table->string('external_link_button')->nullable()->default('Buy Now');
            $table->string('whole_sale_product')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};