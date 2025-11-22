<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantsController extends Controller
{
    // daftar semua variant (bersama relasi product)
    public function index()
    {
        $variants = ProductVariant::with('product')->get();
        return response()->json(['data' => $variants], 200);
    }

    // buat variant baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required','integer','exists:products,id'],
            'name' => ['required','string','max:255'],
            'price' => ['required','numeric','min:0'],
        ]);

        $variant = ProductVariant::create($data);

        return response()->json(['data' => $variant], 201);
    }

    // tampilkan detail variant
    public function show(ProductVariant $productVariant)
    {
        $productVariant->load('product');
        return response()->json(['data' => $productVariant], 200);
    }

    // update variant
    public function update(Request $request, ProductVariant $productVariant)
    {
        $data = $request->validate([
            'product_id' => ['sometimes','required','integer','exists:products,id'],
            'name' => ['sometimes','required','string','max:255'],
            'price' => ['sometimes','required','numeric','min:0'],
        ]);

        $productVariant->update($data);

        return response()->json(['data' => $productVariant], 200);
    }

    // hapus variant
    public function destroy(ProductVariant $productVariant)
    {
        $productVariant->delete();
        return response()->json(['message' => 'Product variant dihapus'], 200);
    }
}