<?php

namespace App\Http\Controllers\Api\V1;

use App\Facades\Response;
use App\Http\Requests\Categories\StoreCategoryRequest;
use App\Http\Requests\Categories\UpdateCategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Models\Category;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PaginateRequest $request)
    {
        $categories = new Category();
        if ($request->q) {
            $categories = $categories->where('title', 'LIKE', '%' . $request->q . '%');
        }
        if ($request->date) {
            $categories = $categories->whereDate('created_at', $request->date);
        }

        $categories = $categories->paginate($request->per_page ?? 5);
        
        return Response::message('category.messages.category_list_found_successfully')
            ->data(new CategoryCollection($categories))
            ->send();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        Category::create($request->all());

        return Response::message('category created successfully')->send();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->all());

        return Response::message('category updated successfully')->send();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return Response::message('category deleted successfully')->send();
    }
}
