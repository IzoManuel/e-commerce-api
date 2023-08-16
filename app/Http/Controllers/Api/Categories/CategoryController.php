<?php

namespace App\Http\Controllers\Api\Categories;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $category = new Category;
        $category->name = $request->name;

        $category->save();
    }
}