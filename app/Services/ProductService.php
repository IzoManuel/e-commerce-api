<?php

namespace App\Services;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductService
{
    public function store($data)
    {
        $collection = collect($data);
        $user_id = auth()->user()->id;
        $slug = Str::slug($data->name, '-');
        $data = $collection->merge(compact('user_id', 'slug'))->toArray();

        return Product::create([
            'name' => $data['name'],
            'category_id' => $data['category_id'],
            'user_id' => $data['user_id'],
            'unit_price' => $data['unit_price'],
            'slug' => $data['slug']
        ]);
    }
}