<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftsController extends Controller
{
    // daftar semua shift (opsional filter user_id / branch_id)
    public function index(Request $request)
    {
        $query = Shift::with('user')->orderByDesc('created_at');

        if ($request->has('user_id')) {
            $query->where('user_id', (int) $request->user_id);
        }

        if ($request->has('branch_id')) {
            $query->where('branch_id', (int) $request->branch_id);
        }

        $shifts = $query->get();

        return response()->json(['data' => $shifts], 200);
    }

    // buat shift baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required','integer','exists:users,id'],
            'branch_id' => ['nullable','integer'],
            'start_cash' => ['required','numeric','min:0'],
            'end_cash' => ['nullable','numeric','min:0'],
            'started_at' => ['nullable','date'],
            'ended_at' => ['nullable','date'],
        ]);

        $shift = Shift::create($data);
        $shift->load('user');

        return response()->json(['data' => $shift], 201);
    }

    // tampilkan detail shift
    public function show(Shift $shift)
    {
        $shift->load('user');
        return response()->json(['data' => $shift], 200);
    }

    // update shift
    public function update(Request $request, Shift $shift)
    {
        $data = $request->validate([
            'user_id' => ['sometimes','required','integer','exists:users,id'],
            'branch_id' => ['nullable','integer'],
            'start_cash' => ['sometimes','required','numeric','min:0'],
            'end_cash' => ['nullable','numeric','min:0'],
            'started_at' => ['nullable','date'],
            'ended_at' => ['nullable','date'],
        ]);

        $shift->update($data);
        $shift->load('user');

        return response()->json(['data' => $shift], 200);
    }

    // hapus shift
    public function destroy(Shift $shift)
    {
        $shift->delete();
        return response()->json(['message' => 'Shift dihapus'], 200);
    }
}