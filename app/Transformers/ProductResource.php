<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $items = ['pillow-1', 'pillow-2', 'pillow-3', 'pillow-4'];

        $randomItem = $items[array_rand($items)];

        return [
            'id' => $this->id,
            'name' => $this->name,
            'unit_price' => $this->unit_price,
            'description' => $this->description,
            'thumbnail_img' => $randomItem,
            'slug' => $this->slug,
            'min_quantity' => $this->min_quantity,
            'current_stock' => $this->current_stock,
            'categories' => $this->categories,
            'discount' => $this->discount,
            'product_images' => $this->adjustUrls($this->getMedia('products')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function adjustUrls($data)
    {
        $baseUrl = 'http://127.0.0.1:8000/';
        
        // Iterate through each media item
        $data->each(function ($mediaItem) use ($baseUrl) {
            if (is_string($mediaItem->getUrl())) {
                $mediaItem->setCustomProperty('original_url', str_replace('https://localhost/', $baseUrl, $mediaItem->getUrl()));
            }
        });
    
        return $data;
    }

}