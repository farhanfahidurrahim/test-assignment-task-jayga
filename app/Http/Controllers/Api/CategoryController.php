<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        if ($categories->isEmpty()) {
            return $this->successResponse('No Categories Found!', [], 200);
        }

        return $this->successResponse("Category Fetch Successfully!", CategoryResource::collection($categories), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->all());
        return $this->successResponse("Category Create Successfully!", new CategoryResource($category), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return $this->errorResponse("Category Not Found!", 404);
        }

        return $this->successResponse("Category View Successfully!", new CategoryResource($category), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return $this->errorResponse("Category Not Found!", 404);
        }
        $category->update($request->all());

        return $this->successResponse("Category Updated Successfully!", new CategoryResource($category), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return $this->errorResponse("Category Not Found!", 404);
        }
        $category->delete();

        return $this->successResponse("Category Deleted Successfully!", null, 200);
    }
}
