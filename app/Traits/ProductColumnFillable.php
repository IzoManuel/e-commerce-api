<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;

trait ProductColumnFillable
{
    public function getFillable()
    {
        return [
            'name',
            'added_by',
            'user_id',
            'category_id',
            'brand_id',
            'photos',
            'thumbnail_img',
            'video_provider',
            'video_link',
            'tags',
            'description',
            'unit_price',
            'purchase_price',
            'variant_product',
            'attributes',
            'choice_options',
            'colors',
            'variations',
            'todays-deals',
            'published',
            'approved',
            'stock_visibilty_state',
            'cash_on_deliver',
            'featured',
            'seller_featured',
            'current_stock',
            'unit',
            'weight',
            'min_quantity',
            'low_stock_quantity',
            'discount',
            'discount_type',
            'discount_start_date',
            'discount_end_date',
            'starting_bid',
            'auction_start_date',
            'auction_end_date',
            'tax',
            'tax_type',
            'shipping_type',
            'shipping_cost',
            'is_quantity_multiplied',
            'est_shipping_days',
            'num_of_sale',
            'meta_title',
            'meta_description',
            'meta_img',
            'pdf',
            'slug',
            'earn_point',
            'refundable',
            'rating',
            'barcode',
            'digital',
            'auction_product',
            'file_name',
            'file_path',
            'external_link',
            'external_link_button',
            'whole_sale_product'    
        ];
    }
}