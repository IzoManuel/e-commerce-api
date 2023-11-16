<?php

namespace App\Http\Controllers\Api\Categories;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\SlugService;
use App\Traits\JsonRespondController;
use App\Transformers\CategoryResource;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryController extends Controller
{
    use JsonRespondController;

    /**
     * Get the list of categories
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $categories = Category::latest()->paginate(12);

        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }

        return CategoryResource::collection($categories);
    }

    /**
     * Store the category
     *
     * @param Request $request
     * @return CategoryResource | \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);
        $slug = SlugService::generateUniqueSlug(Category::class, $request->name);

        try {
            $category = Category::create([
                'name' => $request->name,
                'slug' => $slug,
            ]);
        } catch (QueryExcepton $e) {
            return $this->respondNotTheRightParameters();
        }

        return new CategoryResource($category);
    }

    /**
     * Update the category
     * 
     * @param Request $request
     * @param int $categoryId
     * @return 
     */
    public function update(Request $request, $categoryId)
    {
        try {
            $category = Category::where('id',  $categoryId)
                        ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        }

        $isValid = $this->validateRequest($request);

        $slug = SlugService::generateUniqueSlug(Category::class, $request->name);
        
        try {
            $category->update([
                'name' => $request->name,
                'slug' => $slug,
            ]);
        } catch (QueryException $e) {
            return $this->respondNotTheRightParameters();
        }

        return new CategoryResource($category);
    }

    /**
     * Delete a category
     * 
     * @param int $categoryId
     * @return JsonResponse
     */
    public function destroy($categoryId)
    {
        try {
            $category = Category::where('id', $categoryId)
                        ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        }

        $category->delete();

        return $this->respondObjectDeleted($category->id);
    }

    /**
     * Validate the request for create
     * 
     * @param Request $request
     * @return boolean true | Illuminate\Http\JsonResponse
     */
    private function validateRequest(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);
        return $validated;
    }
}