<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Jobs\SendProductCreatedNotification;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = Product::query();

        if ($status === 'available') {
            $query->available();
        } elseif ($status === 'unavailable') {
            $query->where('status', 'unavailable');
        }

        $products = $query->get();

        return response()->json([
            'data' => $products
        ]);
    }

    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);
            return response()->json($product);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }

    public function store(StoreProductRequest $request)
    {
        if (config('products.role') !== 'admin' && $request->has('article')) {
            return response()->json([
                'error' => 'Permission denied: only admin can set the article field.'
            ], 403);
        }

        try {
            $validated = $request->validated();

            $product = Product::create($validated);

            SendProductCreatedNotification::dispatch($product);

            return response()->json($product, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Ошибка валидации.',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);

        if (config('products.role') !== 'admin' && $request->has('article')) {
            return response()->json([
                'error' => 'Permission denied: only admin can edit the article field.'
            ], 403);
        }

        $validated = $request->validated();

        $product->update($validated);

        return response()->json($product, 200);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

    public function restore($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();

        return response()->json(['message' => 'Product restored successfully']);
    }
}
