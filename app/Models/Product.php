<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Traits\ProductColumnFillable;
use App\Traits\SearchAndFilterable;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, ProductColumnFillable, SearchAndFilterable;

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}