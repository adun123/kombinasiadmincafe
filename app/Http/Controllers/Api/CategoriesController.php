<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    // daftar semua kategori (bersama relasi products)
    public function index()
    {
        $categories = Category::with('products')->get();
        return response()->json(['data' => $categories], 200);
    }

    // buat kategori baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'branch_id' => ['nullable', 'integer'],
        ]);

        $category = Category::create($data);

        return response()->json(['data' => $category], 201);
    }

    // tampilkan detail kategori
    public function show(Category $category)
    {
        $category->load('products');
        return response()->json(['data' => $category], 200);
    }

    // update kategori
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'branch_id' => ['nullable', 'integer'],
        ]);

        $category->update($data);

        return response()->json(['data' => $category], 200);
    }

    // hapus kategori (cek terkait product)
    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return response()->json(['message' => 'Kategori memiliki produk, tidak dapat dihapus'], 400);
        }

        $category->delete();

        return response()->json(['message' => 'Kategori dihapus'], 200);
    }
}