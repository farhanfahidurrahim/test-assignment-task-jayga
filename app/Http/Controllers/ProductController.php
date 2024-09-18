<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    // Search by using Algolia
    public function searchByAlgolia(Request $request)
    {
        $query = $request->input('query');

        if ($query) {
            $searchResults = Product::search($query)->get();
            $productIds = $searchResults->pluck('id');

            $products = Product::with('category', 'attributes')
                ->whereIn('id', $productIds)
                ->latest()
                ->get();
        } else {
            $products = Product::with('category', 'attributes')->latest()->get();
        }

        return view('admin.product.index', compact('products'));
    }


    public function index()
    {
        $products = Product::latest()->get();
        return view('admin.product.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::latest()->get();
        $attributes = Attribute::latest()->get();

        return view('admin.product.create', compact('categories', 'attributes'));
    }

    public function store(ProductRequest $request)
    {
        DB::beginTransaction();
        try {
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

            DB::commit();
            toastr()->success('Product Created Successfully!', 'Congrats');
            return redirect()->route('product.index');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Product Store Error: ' . $e->getMessage());
            toastr()->error('Product Update Failed!', 'Error');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $product = Product::with('attributes')->find($id);
        $categories = Category::latest()->get();
        $attributes = Attribute::latest()->get();
        return view('admin.product.edit', compact('product', 'categories', 'attributes'));
    }

    public function update(ProductRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);
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
            } else {
                $product->attributes()->detach();
            }

            DB::commit();
            toastr()->success('Product Updated Successfully!', 'Success');
            return redirect()->route('product.index');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Product Update Error: '.$e->getMessage());
            toastr()->error('Product Update Failed!', 'Error');
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        $product = Product::find($id);
        if (!$product) {
            toastr()->error('Product not found!', 'Error');
            return redirect()->back();
        }

        $product->delete();
        toastr()->success('Product Deleted Successfully!', 'Success');
        return redirect()->back();
    }
}
