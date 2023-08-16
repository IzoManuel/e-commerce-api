<?php

namespace App\Http\Controllers\Api\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use App\Traits\JsonRespondController;
use App\Transformers\ProductResource;

class ProductController extends Controller
{
    use JsonRespondController;

    public function __construct(
        protected ProductService $productService
    ) {}

    public function index()
    {

        $products = Product::latest()->paginate(12);
        //$products = Product::latest('id')->get();
        return ProductResource::collection($products);
    }

    public function store(ProductRequest $request)
    {
        $product = $this->productService->store($request);
        return response()->json([
            'data' => new ProductResource($product),
        ]);
    }

    public function show($id)
    {
        $product = Product::find($id);

        return new ProductResource($product);
    }

    public function update(ProductRequest $request, $id)
    {
        $product = Product::find($id);

        $product = $this->productService->update($request, $product);
        return new ProductResource($product);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return $this->respond([
            'message' => 'Item deleted successfully',
        ]);
    }
}