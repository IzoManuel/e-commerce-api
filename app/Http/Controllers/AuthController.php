<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $post_data = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'unique:users'],
            'password' => ['required', 'min:8'],
        ]);

        $user = User::create([
            'name' => $post_data['name'],
            'email' => $post_data['email'],
            'password' => Hash::make($post_data['password']),
        ]);

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function adminlogin(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Wrong password and email combination',
                'errors' => [
                    'combination' => 'Wrong password and email combination',
                ],
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        //if user is admin
        if (!$user->hasRole('admin')) {
            return response()->json([
                'message' => 'Wrong password and email combination',
                'errors' => [
                    'combination' => 'Wrong password and email combination',
                ],
            ], 401);
        }

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function adminlogout()
    {
        Auth::user()->currentAccessToken()->delete();

        return response()->json(['message' => 'logged out']);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Wrong password and email combination',
                'errors' => [
                    'combination' => 'Wrong password and email combination',
                ],
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return response()->json(['message' => 'logged out']);
    }
}