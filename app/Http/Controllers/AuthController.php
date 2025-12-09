<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 1. REGISTER
    public function register(Request $request) {
        // Validasi input
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        // Buat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Kembalikan token biar langsung login
        return response()->json([
            'message' => 'User registered',
            'token' => $user->createToken('auth_token')->plainTextToken
        ]);
    }

    // 2. LOGIN
    public function login(Request $request) {
        $user = User::where('email', $request->email)->first();

        // Cek password
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email atau Password salah'], 401);
        }

        return response()->json([
            'message' => 'Login success',
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user' => $user
        ]);
    }
    
    // 3. LOGOUT
    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}