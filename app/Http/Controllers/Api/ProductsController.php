<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProductsController extends Controller
{
    // GET /api/products
    
    public function index()
    {
        $products = Product::with('category')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $products
        ]);
    }
// GET /api/products/{id}
    public function show($id)
    {
        $product = Product::with('category')->find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $product
        ]);
    }

    // POST /api/products
    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $validator = Validator::make($request->all(), [
            'category_id'  => 'required|exists:categories,id',
            'branch_id'    => 'required|exists:branches,id',
            'user_id'      => 'required|exists:users,id',
            'name'         => 'required|string',
            'sku'          => 'nullable|string',
            'price'        => 'required|numeric',
            'cost_price'   => 'nullable|numeric',
            'has_variant'  => 'required|boolean',
            'stock'        => 'required_if:has_variant,0|numeric',
            'image'        => 'nullable|image|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Upload image
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'category_id' => $request->category_id,
            'branch_id'   => $request->branch_id,
            'user_id'     => $request->user_id,
            'name'        => $request->name,
            'sku'         => $request->sku,
            'price'       => $request->price,
            'cost_price'  => $request->cost_price,
            'has_variant' => $request->has_variant,
            'stock'       => $request->has_variant ? 0 : $request->stock,
            'image'       => $imagePath
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Product created successfully',
            'data' => $product
        ]);
    }
     // PUT /api/products/{id}
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'category_id'  => 'nullable|exists:categories,id',
            'branch_id'    => 'nullable|exists:branches,id',
            'name'         => 'nullable|string',
            'sku'          => 'nullable|string',
            'price'        => 'nullable|numeric',
            'cost_price'   => 'nullable|numeric',
            'has_variant'  => 'nullable|boolean',
            'stock'        => 'nullable|numeric',
            'image'        => 'nullable|image|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->update($request->except('image'));

        return response()->json([
            'status' => true,
            'message' => 'Product updated successfully',
            'data' => $product
        ]);
    }


      // DELETE /api/products/{id}
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully'
        ]);
    }


}
