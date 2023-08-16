<?php
namespace App\Services;

use Illuminate\Support\Str;

class SlugService
{
    public static function generateUniqueSlug($model, $name, $field = 'slug')
    {
        $slug = Str::slug($name);
        $same_slug_count = $model::where($field, 'LIKE', $slug . '%')->count();
        $slug_suffix = $same_slug_count ? '-' . ($same_slug_count + 1) : '';
        return $slug . $slug_suffix;
    }
}
