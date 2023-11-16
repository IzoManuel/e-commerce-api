<?php

namespace App\Http\Controllers\Api\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use App\Traits\JsonRespondController;
use App\Transformers\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    use JsonRespondController;

    public function __construct(
        protected ProductService $productService
    ) {}

    /**
     * Return a list of products
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

        //$products = Product::latest()->paginate(12);
        $queryData = ['searchableColumns' => ['name'], 'search' => $request->search];
        // $filters = $this->getFilters();
        $filters = [];
        if($request->max_price) {
            $maxPrice = (float) $request->max_price;
            $filters['priceFilter'] = $maxPrice;
        }
        
        try {
            $products = Product::searchAndFilter($queryData, $filters)->latest()->paginate(50);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }

        return ProductResource::collection($products);
    }

    /**
     * Store the product
     *
     * @param Request $request
     * @return ProductResource | \Illuminate\Http\JsonResponse
     */
    public function store(ProductRequest $request)
    {
        $product = $this->productService->store($request);
        return response()->json([
            'data' => new ProductResource($product),
        ]);
    }

    /**
     * Retrieve a product
     * 
     * @param productId
     * @return ProductResource | JsonResponse
     */
    public function show($productId)
    {
        try {
            $product = Product::findOrFail($productId);
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        }
        

        return new ProductResource($product);
    }

    /**
     * Update the product
     * 
     * @param Request $request
     * @param int $productId
     * @return 
     */
    public function update(ProductRequest $request, $productId)
    {
        try {
            $product = Product::findOrFail($productId);
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        }

        try {
            $product = $this->productService->update($request, $product);
        } catch (QueryException $e) {
            return $this->respondWithError('Oops, it seems an error occured during update');
        }
        
        return new ProductResource($product);
    }

    /**
     * Delete a product
     * 
     * @param int $productId
     * @return JsonResponse
     */
    public function destroy($productId)
    {
        try {
            $product = Product::findOrFail($productId);
        } catch (ModelNotFoundException $e) {
            $this->respondNotFound();
        }

        $product->delete();

        return $this->respondObjectDeleted($product->id);
    }
}