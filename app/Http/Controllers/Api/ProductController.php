<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::latest()->get();
        if ($products->isEmpty()) {
            return $this->successResponse('No Product Found!', [], 200);
        }

        return $this->successResponse("Product Fetch Successfully!", ProductResource::collection($products), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->save();

        if (!empty($request->input('attributes'))) {
            $attributes = [];
            foreach ($request->input('attributes') as $attribute) {
                if (isset($attribute['id']) && isset($attribute['value'])) {
                    $attributes[$attribute['id']] = ['value' => $attribute['value']];
                }
            }

            $product->attributes()->attach($attributes);
        }

        $product->load('attributes');

        return $this->successResponse("Product Create Successfully!", new ProductResource($product), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return $this->errorResponse("Product Not Found!", 404);
        }

        return $this->successResponse("Product Fetch Successfully!", new ProductResource($product), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, $id)
    {
        $product = Product::find($id);
        if(!$product){
            return $this->errorResponse("Product Not Found!", 404);
        }

        $product->name = $request->name;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->save();

        if (!empty($request->input('attributes'))) {
            $attributes = [];
            foreach ($request->input('attributes') as $attribute) {
                if (isset($attribute['id']) && isset($attribute['value'])) {
                    $attributes[$attribute['id']] = ['value' => $attribute['value']];
                }
            }

            $product->attributes()->sync($attributes);
        }

        $product->load('attributes');

        return $this->successResponse("Product Updated Successfully!", new ProductResource($product), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if(!$product){
            return $this->errorResponse("Product Not Found!", 404);
        }
        $product->delete();

        return $this->successResponse("Product Deleted Successfully!", null, 200);
    }
}
