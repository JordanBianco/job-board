<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

        if ($user) {
            return response()->json(['message' => 'success'], 201);
        }
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        if (Auth::attempt($validated)) {
            $user = User::where('email', $validated['email'])->first();

            return response([
                'token' => $user->createToken('app_token')->plainTextToken,
                'user' => $user
            ], 200);
        } else {
            throw ValidationException::withMessages([
                'email' => 'Le credenziali inserite non sono corrette'
            ]);
        }
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'logout'], 200);
    }
}
