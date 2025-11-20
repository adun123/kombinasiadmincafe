<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
     // REGISTER
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,kasir'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'Register berhasil',
            'user'    => $user,
            'token'   => $token
        ], 201);
    }

    // LOGIN
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Email atau password salah'], 401);
        }

        return response()->json([
            'message' => 'Login berhasil',
            'token'   => $token,
            'user'    => Auth::user(),
        ]);
    }

    // GET USER
    public function me()
    {
        return response()->json(Auth::user());
    }

    // LOGOUT
    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Logout berhasil']);
    }

    // REFRESH TOKEN
    public function refresh()
    {
        return response()->json([
            'token' => Auth::refresh(),
        ]);
    }
}
