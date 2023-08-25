<?php

namespace App\Http\Controllers\Api\Categories;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\SlugService;
use App\Traits\JsonRespondController;
use App\Transformers\CategoryResource;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use JsonRespondController;

    public function index()
    {

        $categories = Category::latest()->paginate(12);
        return CategoryResource::collection($categories);
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);
        $slug = SlugService::generateUniqueSlug(Category::class, $request->name);

        $category = Category::create([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return new CategoryResource($category);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $validated = $this->validateRequest($request);
        $slug = SlugService::generateUniqueSlug(Category::class, $request->name);

        $category->update([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return new CategoryResource($category);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        $category->delete();

        return $this->respond([
            'message' => 'Category deleted'
        ]);
    }

    private function validateRequest(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);
        return $validated;
    }
}