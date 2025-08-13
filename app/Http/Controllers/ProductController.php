<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return Product::with(['category', 'range', 'variants'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string',
            'sku'         => 'nullable|string|unique:products,sku',
            'range_id'    => 'nullable|exists:ranges,id',
            'category_id' => 'nullable|exists:categories,id',
            'variants'    => 'nullable|array',
        ]);

        $product = Product::create($request->only(['name', 'sku', 'description', 'range_id', 'category_id']));

        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                $product->variants()->create($variant);
            }
        }

        return response()->json($product->load('variants'), 201);
    }

    public function show(Product $product)
    {
        return $product->load(['category', 'range', 'variants']);
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->only(['name', 'sku', 'description', 'range_id', 'category_id']));

        if ($request->has('variants')) {
            $product->variants()->delete();
            foreach ($request->variants as $variant) {
                $product->variants()->create($variant);
            }
        }

        return $product->load('variants');
    }

    public function destroy(Product $product)
    {
        $product->variants()->delete();
        $product->delete();
        return response()->noContent();
    }
}
