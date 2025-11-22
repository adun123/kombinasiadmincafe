<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    // GET ALL BRANCHES
    public function index()
    {
        $branches = Branch::all();
        return response()->json($branches, 200);
    }

    // CREATE BRANCH
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $branch = Branch::create([
            'name'    => $request->name,
            'address' => $request->address,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Branch created successfully',
            'data'    => $branch
        ], 201);
    }

    // SHOW DETAIL BRANCH
    public function show($id)
    {
        $branch = Branch::find($id);

        if (!$branch) {
            return response()->json(['message' => 'Branch not found'], 404);
        }

        return response()->json($branch, 200);
    }

    // UPDATE BRANCH
    public function update(Request $request, $id)
    {
        $branch = Branch::find($id);

        if (!$branch) {
            return response()->json(['message' => 'Branch not found'], 404);
        }

        $branch->update($request->only(['name', 'address']));

        return response()->json([
            'success' => true,
            'message' => 'Branch updated successfully',
            'data'    => $branch
        ], 200);
    }

    // DELETE BRANCH
    public function destroy($id)
    {
        $branch = Branch::find($id);

        if (!$branch) {
            return response()->json(['message' => 'Branch not found'], 404);
        }

        $branch->delete();

        return response()->json([
            'success' => true,
            'message' => 'Branch deleted successfully'
        ], 200);
    }
}
