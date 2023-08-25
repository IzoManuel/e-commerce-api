<?php

namespace App\Services;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductService
{
    public function store($request)
    {
        $collection = collect($request);
        $user_id = auth()->user()->id;

        //todo
        // $tags = array();
        // if($collection['tags'][0] != null) {
            
        // }
        
        //todo
        $discount_start_date = null;
        $discount_end_date = null;
        // if ($collection['discount_date_range' !=null ]) {
            
        // }

        if (!isset($collection['meta_title'])) {
            $collection['meta_title'] = $collection['name'];
        }

        if (!isset($collection['meta_description'])) {
            $collection['meta_description'] = strip_tags($collection['description']);
        }
        
        if (!isset($collection['meta_img'])) {
            $collection['meta_img'] = $collection['thumbnail_img'] ?? null;
        }

        $shipping_cost = 0;
        if(isset($collection['shipping_type'])) {
            if($collection['shipping_type'] == true) {
                $collection['shipping_type'] = 'free';
                $shipping_cost = 0;
            }elseif ($collection['shipping_type'] == false) {
                $collection['shipping_type'] = 'flat_rate';
                $shipping_cost = $collection['flat_shipping_cost'];
            }
        }
        unset($collection['flat_shipping_cost']);
        
        //todo
        //$slug = Str::slug($collection['name'], '-');
        $slug = SlugService::generateUniqueSlug(Product::class, $collection['name']);
        //$data = $collection->merge(compact('user_id', 'slug'))->toArray();

        //todo
        $colors = json_encode(array());
        
        //todo
        //$options = ProductUtility::get_attribute_options($collection);

        //todo
        //$combinations = Combination::makeCombinations($options);
        
        //unset($collection['colors_active']);
        
        //todo
        $choice_options = array();

        //todo
        //choice_options = json_encode($choice_options,)

        //todo
        //if(isset($collection['choice_no'])
        
        //todo
        $published = 1;
        // if($collection['button'] == 'unpublish' || $collection['button'] == 'draft') {
        //     $published = 0;
        // }
        // unset($collection['button']); 

        $data = $collection->merge(compact(
            'user_id',
            'discount_start_date',
            'discount_end_date',
            'shipping_cost',
            'slug',
            //'colors',
            //'choice_options',
            //'attributes',
            'published'
        ))->toArray();
        

        $product = Product::create($data);
        $product->categories()->attach($data['categories']);
        
        if ($images = $request->file('product_images')) {
            foreach ($images as $image) {
                $product->addMedia($image)
                ->toMediaCollection('products');
                // ->toMediaCollection('products', 'google');
            }
        }

        return $product;//->getFirstMediaUrl('products');
    }

    public function update($request, Product $product)
    {
        $collection = collect($request);

        $slug = SlugService::generateUniqueSlug(Product::class, $collection['name']);

        if(!isset($collection['is_quantity_multiplied'])){
            $collection['is_quantity_multiplied'] = 0;
        }

        if(!isset($collection['cash_on_delivery'])){
            $collection['cash_on_deliver'] = 0;
        }

        if(!isset($collection['featured'])){
            $collection['featured'] = 0;
        }

        if(!isset($collection['todays_deal'])){
            $collection['todays_deal'] = 0;
        }

        $discount_start_date = null;
        $discount_end_date   = null;

        if(!isset($collection['meta_title'])) {
            $collection['meta_title'] = $collection['name'];
        }

        if(!isset($collection['meta_description'])) {
            $collection['meta_description'] = isset($collection['description']) ? $collection['description'] : null;
        }

        if(!isset($collection['meta_img'])) {
            $collection['meta_title'] = $collection['thumbnail_img'] ?? null;
        }

        $shipping_cost = 0;
        if(isset($collection['shipping_type'])) {
            if($collection['shipping_type'] == true) {
                $collection['shipping_type'] = 'free';
                $shipping_cost = 0;
            }elseif ($collection['shipping_type'] == false) {
                $collection['shipping_type'] = 'flat_rate';
                $shipping_cost = $collection['flat_shipping_cost'];
            }
        }
        unset($collection['flat_shipping_cost']);

        $data = $collection->merge(compact(
            'discount_start_date',
            'discount_end_date',
            'shipping_cost',
            'slug',
            //'colors',
            //'choice_options',
            //'attributes',
        ))->toArray();
        
        $product->update($data);
        $product->categories()->sync($data['categories']);
        
        return $product;
    }
}