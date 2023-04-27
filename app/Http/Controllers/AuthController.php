<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
        $user = User::query()->where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Sai email hoặc mật khẩu',
            ], Response::HTTP_NOT_FOUND);
        }

        $user->setTimeOnline();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'type_type' => 'Bearer',
            'access_token' => $token,
            'string' => "Bearer $token",
        ]);

        $validated = $request->validated();
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return response()->json([
            'message' => 'User created successfully',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'User logouted successfully',
        ]);
    }
}
