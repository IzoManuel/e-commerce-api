<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Services\ProductService;

class ProductController extends Controller
{

    public function __construct(
        protected ProductService $productService
    ){}

    public function store(ProductRequest $request)
    {
        $product = $this->productService->store($request);
        
        return response()->json([
            'product' => $product
        ]);
    }
}