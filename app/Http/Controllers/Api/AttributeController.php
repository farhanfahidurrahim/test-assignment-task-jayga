<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttributeResource;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Attribute;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attributes = Attribute::latest()->get();
        if ($attributes->isEmpty()) {
            return $this->successResponse('No Attribute Found!', [], 200);
        }

        return $this->successResponse("Attribute Fetch Successfully!", AttributeResource::collection($attributes), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:attributes,name',
        ]);

        $attribute = Attribute::create($request->all());

        return $this->successResponse("Attribute Create Successfully!", new AttributeResource($attribute), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $attribute = Attribute::find($id);
        if (!$attribute) {
            return $this->errorResponse("Attribute Not Found!", 404);
        }

        return $this->successResponse("Attribute Fetch Successfully!", new AttributeResource($attribute), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $attribute = Attribute::find($id);
        if(!$attribute){
            return $this->errorResponse("Attribute Not Found!", 404);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:attributes,name',
        ]);

        $attribute->name = $request->name;
        $attribute->save();

        return $this->successResponse("Attribute Update Successfully!", new AttributeResource($attribute), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $attribute = Attribute::find($id);
        if(!$attribute){
            return $this->errorResponse("Attribute Not Found!", 404);
        }
        $attribute->delete();

        return $this->successResponse("Attribute Deleted Successfully!", null, 200);
    }
}
